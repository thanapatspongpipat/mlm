<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\MLM\BaseMLM;
use App\Models\User;

class RollUpController extends BaseMLM
{

    public function index($id, $pairId, $type){
        // Get Key Topic 3
        // MyUserID , Percent
        if($type == "key"){
            $headerId = 1;
            // user, percent, dealerId or super dealer id
            return $this->getKeyCost($headerId, $id);
        }

        // GetLogRollUp Topic 2
        // MyUserID
        if($type == "log"){
            // my userId
            return $this->getLogRollUp($id);
        }

        if($type == "all"){
            return $this->getAllLog($id);
        }

        // Topic 4
        if($type == "balance"){
            return $this->getBalance($id);
        }
    }

    public function getBalance($id){
        $AllData = $this->getAllLog($id);
        $CombineValue = $this->combine($AllData);
        $MyLevel = $this->getUserLevel($id);
        $RangeCouple = $this->convertMaxCouple($MyLevel);
        $LeftValue = $CombineValue["left"];
        $RightValue = $CombineValue["right"];
        $numCouple = min($LeftValue, $RightValue);
        $result = $this->calculateResultCouple($RangeCouple, $numCouple);
        return ["point"=>$result];
    }

    public function getValueLeftRight($id){
        $AllData = $this->getAllLog($id);
        $CombineValue = $this->combine($AllData);
        $MyLevel = $this->getUserLevel($id);
        $RangeCouple = $this->convertMaxCouple($MyLevel);
        $LeftValue = $CombineValue["left"];
        $RightValue = $CombineValue["right"];
        return array(
            "valueLeft"=>$LeftValue,
            "valueRight"=>$RightValue
        );
    }

    public function compute($data, &$total = 0){
        if($data !== null && $data["level"] !== null && $data['userId'] !== null) {
            $userId = $data['userId'];
            $levelLogs = $this->getLevelLogs($userId);
            foreach($levelLogs as $log){
                //dd($log->product->id);
                $level = $log->product->id;
                $total += $this->convertLevelPrice($level);
            }
            //$total += $this->convertLevelPrice($data["level"]);
        }
        if($data !== null && $data["left"] !== null ) $this->compute($data["left"], $total);
        if($data !== null && $data["right"] !== null) $this->compute($data["right"], $total);
    }

    public function combine($data){
        $totalLeft = 0;
        $totalRight = 0;
        $this->compute($data["left"], $totalLeft);
        $this->compute($data["right"], $totalRight);
        return array(
            "left"=>$totalLeft,
            "right"=>$totalRight
        );
    }

    public function getAllLog($UserId){
        $result = $this->getLeftRight($UserId);
        return $result;
    }

    private function calculateResultCouple($RangeCouple, $numCouple){
        $result = 0;
        if(!isset($RangeCouple) || $RangeCouple === null) return;
        $min = (isset($RangeCouple["phrase1"])) ? $RangeCouple["phrase1"] : 0;
        $max = (isset($RangeCouple["phrase2"])) ? $RangeCouple["phrase2"] : 0;
        if($numCouple >= $min["countCouple"]){
            $result += $min["price"] * $min["countCouple"];
            $numCouple -= $min["countCouple"];
        } else {
            $result += $min["price"] * $numCouple;
            $numCouple = 0;
        }
        // unlimit for D and SD
        if($max["countCouple"] == 0){
            $result += $max["price"] * $numCouple;
        } else {
            if($numCouple >= $max["countCouple"] - $min["countCouple"]){
                $result += $max["price"] * ($max["countCouple"] - $min["countCouple"]);
            } else {
                $result += $max["price"] * $numCouple;
            }
        }
        return $result;
    }

    private function formatLeftRight($UserId){
        $UserInviter = $this->getUserInviter($UserId);
        $result = array(
            "left"=>null,
            "right"=>null
        );
        foreach($UserInviter as $user){
            if($user->position_space == "left"){
                $result["left"] = $user->id;
            } else {
                $result["right"] = $user->id;
            }
        }
        return $result;
    }

    public function getLeftRight($UserId){
        if($UserId == 0) return null;
        $LeftRight = $this->formatLeftRight($UserId);
        $result = array(
            "userId"=>$UserId,
            "level"=>$this->getUserLevel($UserId),
            "left"=>$this->getLeftRight(($LeftRight["left"] !== null)?$LeftRight["left"]:0),
            "right"=>$this->getLeftRight($LeftRight["right"] !== null?$LeftRight["right"]:0),
        );
        return $result;
    }

    public function getLogRollUp($id){
        $ReferralData = $this->getUserInviter($id);
        $result = array();
        $UserLevel = $this->getUserLevel($id);
        $PercentRollUp = $this->getPercentRollUp($UserLevel);
        foreach($ReferralData as $user){
            $UserID = $user->id;
            $UserLevel  = $this->getLevelByProductId($user->product_id);
            $PriceLevel = $this->getLevelCost($user->product_id);
            $RollUpResult = ($PercentRollUp / 100) * $PriceLevel;
            $DealerID = $this->getDealer($id);
            $result[] = array(
                "dealerId"=>$DealerID,
                "rollUpResult"=>$RollUpResult,
                "percentRollUp"=>$PercentRollUp,
                "userId"=>$UserID
            );
        }
        return $result;
    }

    public function getKeyCost($headerId, $userId, $Percent = 5){
        $User = $this->getUserById($userId);
        $KeyFee = $Percent / 100;
        return array(
            "cost" => $this->getLevelCost($User->product_id) * $KeyFee,
            "userId"=> $userId,
            "headerId"=>$headerId, // D or SD
        );
    }

    private function getPercentRollUp($Level){
        $levels = array(
            "S"=>10,
            "M"=>5,
            "D"=>0,
            "SD"=>0
        );
        return (isset($levels[$Level]))?$levels[$Level]:null;
    }

    private function getDealer($id){
        $MyData = $this->getUserById($id);
        $HeaderID = isset($MyData) && isset($MyData->user_upline_id) ? $MyData->user_upline_id : null;
        if(isset($HeaderID) && $HeaderID !== null){
            $HeaderLevel = $this->getUserLevel($HeaderID);
            if($HeaderLevel == "D" || $HeaderLevel == "SD") return $HeaderID;
            return $this->getDealer($HeaderID);
        }
        return 0;
    }

}
