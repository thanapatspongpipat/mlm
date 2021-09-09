<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\MLM\RollUpController;
use App\Http\Controllers\MLM\BasicController;
use App\Models\CoupleLogs;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

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
        foreach($UserData as $User){
            $userId = $User->id;
            $totalResult = $this->insertCouple($userId);
            if($totalResult === null) continue;
            $totalBalance = $totalResult["totalBalance"];
            $totalCouple = $totalResult["totalCouple"];

            if($totalCouple <= 0) continue;
            //dd($userId, $totalBalance);
            $this->extractBalance($userId, $totalBalance, "ค่าครบคู่ ({$totalCouple} คู่)", "DEPOSIT_COUPLE");
        }
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
    private $typeCouple = "BONUS_COUPLE";
    public function generateLogsCouple($userId, $balance){
        return [
            "user_id"=>$userId,
            "balance"=>$balance,
            "detail"=>$this->detailCouple,
            "type"=>"DEPOSIT_COUPLE",
        ];
    }

    private function insertTransactionLoop($id, $balance, $count){
        DB::beginTransaction();
        for($i=0;$i<$count;$i++){
            $coupleLogs = new CoupleLogs();
            $coupleLogs->user_id = $id;
            $coupleLogs->amount = $balance;
            $coupleLogs->fk_id = 0;
            $coupleLogs->save();
        }
        DB::commit();
    }

    public function insertTransactionById($id){
        $increment = 0;
        $MyPoint = $this->getBalance($id);
        $result = $this->getCoupleValue($id);
        if($result === false) return false;
        $alreadyInsert = CoupleLogs::where('user_id', $id)
                                    ->select('id')
                                    ->get();
        $alreadyInsertCount = count($alreadyInsert);
        $ToInsertCount = $result["max"][0] + $result["min"][0];
        $userLevel = $this->getUserLevel($id);
        $RangeCouple = $this->convertMaxCouple($userLevel);
        if($ToInsertCount >= $alreadyInsertCount){
            $ToInsertCount -= $alreadyInsertCount;
        }
        $toInsertMin = 0;
        $toInsertMax = 0;
        $countCoupleMin = $RangeCouple["phrase1"]["countCouple"];
        $countCoupleMax = $RangeCouple["phrase2"]["countCouple"];

        if($ToInsertCount >= $countCoupleMin){
            $toInsertMin = $countCoupleMin;
            $ToInsertCount -= $countCoupleMin;
            $toInsertMax = $ToInsertCount;
        } else {
            $toInsertMin = $ToInsertCount;
            $ToInsertCount = 0;
        }
        //dd($toInsertMin, $toInsertMax);
        $totalBalance = 0;
        $totalCouple = 0;
        if ($toInsertMin > 0) {
            $this->insertTransactionLoop($id, $result["min"][1], $toInsertMin);
            $totalCouple += $toInsertMin;
            $totalBalance += $result["min"][1] * $toInsertMin;
        }
        if ($toInsertMax > 0) {
            $this->insertTransactionLoop($id, $result["max"][1], $toInsertMax);
            $totalCouple += $toInsertMax;
            $totalBalance += $result["max"][1] * $toInsertMax;
        }
        return array(
            "totalCouple"=>$totalCouple,
            "totalBalance"=>$totalBalance
        );
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

    // use in case insert key and upgrade user level
    public function insertKey($id, $pairId){
        $keyValue = $this->getKeyCost($id, $pairId);
        $keyResult = $keyValue['cost'];
        $type = "DEPOSIT_KEY";
        $keyDuplicate = Transaction::where([
            ['user_id', '=', $id],
            ['fk_id', '=', $pairId],
            ['type', '=', $type]
        ])->get();

        // in case user upgrade level
        $keyValueInTransaction = 0;
        if(count($keyDuplicate) > 0){
            foreach($keyDuplicate as $keyData){
                $keyValueInTransaction += $keyData->amount;
                if($keyResult == $keyValueInTransaction) return false;
            }
            $keyResult -= $keyValueInTransaction;
        }

        $keyDetail = "ค่าลงทะเบียน {$pairId}";
        $this->extractBalance($id, $keyResult, $keyDetail, $type, $pairId);
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
