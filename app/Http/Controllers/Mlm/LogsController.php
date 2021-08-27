<?php

namespace App\Http\Controllers\Mlm;

use App\Http\Controllers\Mlm\RollUpController;
use Illuminate\Http\Request;
use App\Models\transaction;
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
    }

    public function getCoupleValue($id){
        $id = (int)$id;
        $userLevel = $this->getUserLevel($id);
        $RangeCouple = $this->convertMaxCouple($userLevel);
        if(!isset($RangeCouple)){ return ["status"=>false]; }
        $MyPoint = $this->getBalance($id);
        $result = $this->reverseCoupleValue($MyPoint["point"], $RangeCouple);
        $minTransaction = transaction::where([
            ["user_id", "=", $id],
            ["balance", "=", $result["min"][1]]
        ])->select("user_id", "balance")->get();
        $maxTransaction = transaction::where([
            ["user_id", "=", $id],
            ["balance", "=", $result["max"][1]]
        ])->select("user_id", "balance")->get();
        if(count($minTransaction) < $result["min"][0]){
            $toInsert = $result["min"][0] - count($minTransaction);
            for($i=0;$i<$toInsert;$i++){
                transaction::insert([
                    "user_id"=>$id,
                    "amount"=>0,
                    "balance"=>$result["min"][1],
                    "type"=>"DEPOSIT",
                    "detail"=>"couple",
                    "user_approve_id"=>0,
                    "user_create_id"=>0

                ]);
            }
        }
        if(count($maxTransaction) < $result["max"][0]){
            $toInsert = $result["max"][0] - count($maxTransaction);
            for($i=0;$i<$toInsert;$i++){
                transaction::insert([
                    "user_id"=>$id,
                    "type"=>"DEPOSIT",
                    "amount"=>0,
                    "detail"=>"couple",
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
        /*$keyDuplicate = transaction::where([
            ['user_id', '=', $id, ""]
        ])->get();
        if(count($keyDuplicate) > 0) return ["status"=>false];*/
        transaction::insert([
            "user_id"=>$id,
            "detail"=>"key from userId {$pairId}",
            "balance"=>$keyValue["cost"],
            "amount"=>0,
            "type"=>"DEPOSIT",
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
        if($MyPoint <= $MinCouple){
            return ["min"=> [$MyPoint, $PriceMin] , "max" => [0, $PriceMax]];
        }
        $MaxPrice = 0;
        $MyPoint -= $MinCouple;
        if($MaxCouple == 0){
            $MaxPrice = $MyPoint;
        } else {
            if($MyPoint <= $MaxCouple){
                $MaxPrice = $MyPoint;
            } else {
                $MaxPrice = $MaxCouple;
            }
        }
        return ["min" => [$MinCouple, $PriceMin], "max" => [$MaxPrice, $PriceMax]];
    }
}
