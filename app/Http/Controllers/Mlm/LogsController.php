<?php

namespace App\Http\Controllers\Mlm;

use App\Http\Controllers\Mlm\RollUpController;
use App\Http\Controllers\Mlm\BasicController;
use App\Models\User;
use App\Models\Transaction;

class LogsController extends RollUpController
{
    public function insertCouple($id){
        $result = $this->insertTransactionById($id);
        return $result;
    }

    public function index($id, $playerId, $type){
        if($type == "couple"){
            $result = $this->getCoupleValue($id);
            return $result;
        }
        if($type == "allLogs"){
            // date
            $result = $this->getLogs($id);
            return $result;
        }
        if($type == "setlog"){
            $result = $this->InsertData();
        }
    }

    // format yyyy-mm-dd
    public function getKeyLogDate($id, $date = null){
        if($date === null){
            $date = date('Y-m-d');
        }
        $result = Transaction::where('user_id', $id)
        ->where('type', "DEPOSIT_KEY")
        ->whereDate("transaction_timestamp", $date);
        return $result->get();
    }

    public function getLogs($date = null){
        if($date === null){
            $date = date('Y-m-d');
        }
        $result = Transaction::whereDate("transaction_timestamp", $date);
        return $result->get();
    }

    public function InsertData(){
        $UserData = User::select("id")->get();
        $AllLogs = array();
        $BasicController = new BasicController();
        foreach($UserData as $User){
            $userId = $User->id;
            $logs = $this->getCoupleValue($userId);
            $this->insertTransactionById($userId);
            foreach($logs as $log){
                for($i=0;$i<$log[0];$i++){
                    $couple = $this->generateLogsCouple($userId, $log[1]);
                    if($couple != false){
                        $AllLogs[] = $couple;
                    }
                }
            }
            $KeyLogDate = $this->getKeyLogDate($userId);
            foreach($KeyLogDate as $key){
                $AllLogs[] = $this->formatKeyLog($key);
            }
            // have to add log
            $BasicController->insertFee($userId);
            $BasicController->insertRollup($userId);
        }
        return $AllLogs;
    }

    public function formatKeyLog($data){
        return  [
            "user_id"=>$data->user_id,
            "balance"=>$data->balance,
            "detail"=>$data->detail,
            "type"=>"DEPOSIT_KEY"
        ];
    }

    private $detailCouple = "ค่าครบคู่";
    public function generateLogsCouple($userId, $balance){
        return [
            "user_id"=>$userId,
            "balance"=>$balance,
            "detail"=>$this->detailCouple,
            "type"=>"DEPOSIT_COUPLE",
        ];
    }

    private function insertTransactionLoop($id, $balance, $count){
        for($i=0;$i<$count;$i++){
            /*Transaction::insert([
                "user_id"=>$id,
                "balance"=>0,
                "amount"=>$balance,
                "type"=>"DEPOSIT_COUPLE",
                "detail"=>$this->detailCouple,
                "user_approve_id"=>Auth::user()->id ,
                "user_create_id"=>0,
                "transaction_timestamp"=>Carbon::now()
            ]);*/
            $type = "DEPOSIT_COUPLE";
            $this->extractBalance($id, $balance, $this->detailCouple, $type, 0);
        }
    }

    public function insertTransactionById($id){
        $increment = 0;
        $MyPoint = $this->getBalance($id);
        $result = $this->getCoupleValue($id);
        if($result === false) return false;
        $amountMin = $this->floorp($result["min"][1] * 0.75, 2);
        $amountMax = $this->floorp($result["max"][1] * 0.75, 2);
        $minTransaction = Transaction::where([
            ["user_id", "=", $id],
            ["amount", "=", $amountMin]
        ])->select("user_id", "balance")->get();
        $maxTransaction = Transaction::where([
            ["user_id", "=", $id],
            ["amount", "=", $amountMax]
        ])->select("user_id", "balance")->get();
        $toInsertMin = 0;
        $toInsertMax = 0;
        if(count($minTransaction) < $result["min"][0] && $MyPoint > 0){
            $toInsertMin = $result["min"][0] - count($minTransaction);
            $increment++;
        }
        if(count($maxTransaction) < $result["max"][0] && $MyPoint > 0){
            $toInsertMax = $result["max"][0] - count($maxTransaction);
            $increment++;
        }
        if($toInsertMax + $toInsertMin >= 99){
            // insert 99 couple per day
            $toInsertMax = 99 - $toInsertMin;
        }
        if ($toInsertMin > 0) $this->insertTransactionLoop($id, $result["min"][1], $toInsertMin);
        if ($toInsertMax > 0) $this->insertTransactionLoop($id, $result["max"][1], $toInsertMax);
        return $increment > 0;
    }

    public function getCoupleValue($id){
        $id = (int)$id;
        $userLevel = $this->getUserLevel($id);
        $RangeCouple = $this->convertMaxCouple($userLevel);
        if(!isset($RangeCouple)) return false;
        $MyPoint = $this->getBalance($id);
        $result = $this->reverseCoupleValue($MyPoint["point"], $RangeCouple);
        return $result;
    }

    public function insertKey($id, $pairId){
        $keyValue = $this->getKeyCost($id, $pairId);
        $type = "DEPOSIT_KEY";
        $keyDuplicate = Transaction::where([
            ['user_id', '=', $id],
            ['fk_id', '=', $pairId],
            ['type', '=', $type]
        ])->get();
        if(count($keyDuplicate) > 0) return false;

        $keyDetail = "ค่าลงทะเบียน {$pairId}";
        $this->extractBalance($id, $keyValue["cost"], $keyDetail, $type, $pairId);

        return true;
    }

    public function reverseCoupleValue($MyPoint, $RangeCouple){
        $MinCouple = $RangeCouple["phrase1"]["countCouple"];
        $PriceMin = $RangeCouple["phrase1"]["price"];
        $MaxCouple = $RangeCouple["phrase2"]["countCouple"];
        $PriceMax = $RangeCouple["phrase2"]["price"];
        if($MyPoint <= $MinCouple * $PriceMin){
            return ["min"=> [$MyPoint / $PriceMin, $PriceMin] , "max" => [0, $PriceMax]];
        }
        $MaxPrice = 0;
        $MyPoint -= $MinCouple * $PriceMin;
        if($MaxCouple == 0){
            $MaxPrice = $MyPoint / $PriceMax;
        } else {
            if($MyPoint <= ($MaxCouple - $MinCouple) * $PriceMax){
                $MaxPrice = $MyPoint / $PriceMax ;
            } else {
                $MaxPrice = $MaxCouple - $MinCouple;
            }
        }
        // [8, 255], [4, 75]
        return ["min" => [$MinCouple, $PriceMin], "max" => [$MaxPrice, $PriceMax]];
    }
}
