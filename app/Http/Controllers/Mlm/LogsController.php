<?php

namespace App\Http\Controllers\Mlm;

use App\Http\Controllers\Mlm\RollUpController;
use App\Http\Controllers\Mlm\BasicController;
use App\Models\User;
use App\Models\Transaction;
class LogsController extends RollUpController
{
    public function index($id, $playerId, $type){
        if($type == "key"){
            $result = $this->getKeyLogs($id, $playerId);
            return $result;
        }
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

    public function generateLogsCouple($userId, $balance){
        return [
            "user_id"=>$userId,
            "balance"=>$balance,
            "detail"=>"COUPLE",
            "type"=>"DEPOSIT",
        ];
    }

    public function getCoupleValue($id){
        $id = (int)$id;
        $userLevel = $this->getUserLevel($id);
        $RangeCouple = $this->convertMaxCouple($userLevel);
        if(!isset($RangeCouple)){ return ["status"=>false]; }
        $MyPoint = $this->getBalance($id);
        $result = $this->reverseCoupleValue($MyPoint["point"], $RangeCouple);
        $minTransaction = Transaction::where([
            ["user_id", "=", $id],
            ["balance", "=", $result["min"][1]]
        ])->select("user_id", "balance")->get();
        $maxTransaction = Transaction::where([
            ["user_id", "=", $id],
            ["balance", "=", $result["max"][1]]
        ])->select("user_id", "balance")->get();
        if(count($minTransaction) < $result["min"][0] && $MyPoint > 0){
            $toInsert = $result["min"][0] - count($minTransaction);
            for($i=0;$i<$toInsert;$i++){
                Transaction::insert([
                    "user_id"=>$id,
                    "amount"=>0,
                    "balance"=>$result["min"][1],
                    "type"=>"DEPOSIT_COUPLE",
                    "detail"=>"COUPLE",
                    "user_approve_id"=>0,
                    "user_create_id"=>0

                ]);
            }
        }
        if(count($maxTransaction) < $result["max"][0] && $MyPoint > 0){
            $toInsert = $result["max"][0] - count($maxTransaction);
            for($i=0;$i<$toInsert;$i++){
                Transaction::insert([
                    "user_id"=>$id,
                    "type"=>"DEPOSIT_COUPLE",
                    "amount"=>0,
                    "detail"=>"COUPLE",
                    "balance"=>$result["max"][1],
                    "user_approve_id"=>0,
                    "user_create_id"=>0
                ]);
            }
        }
        return $result;
    }

    public function getKeyLogs($id, $pairId){
        $keyValue = $this->getKeyCost($id, $pairId);
        $keyDuplicate = Transaction::where([
            ['user_id', '=', $id],
            ['fk_id', '=', $pairId],
            ['type', '=', "DEPOSIT_KEY"]
        ])->get();
        if(count($keyDuplicate) > 0) return ["status"=>false];
        Transaction::insert([
            "user_id"=>$id,
            "detail"=>"KEY",
            "balance"=>$keyValue["cost"],
            "amount"=>0,
            "fk_id"=>$pairId,
            "type"=>"DEPOSIT_KEY",
            "user_approve_id"=>0,
            "user_create_id"=>0
        ]);
        $keyValue["status"] = true;
        return $keyValue;
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
