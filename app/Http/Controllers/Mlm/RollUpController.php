<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\MLM\BaseMLM;

class RollUpController extends BaseMLM
{
    public function index($type,$id){
        // Get Key Topic 3
        // MyUserID , Percent
        if($type == "key"){
            $headerId = 1;
            return $this->getKeyCost($id, 5, $headerId);
        }

        // GetLogRollUp
        // MyUserID
        if($type == "log"){
            return $this->getLogRollUp($id);
        }

        if($type == "all"){
            return $this->getAllLog($id);
        }

        if($type == "balance"){
            $AllData = $this->getAllLog($id);
            $CombineValue = $this->combine($AllData);
            $LeftValue = $CombineValue["left"];
            $RightValue = $CombineValue["right"];
            $result = min($LeftValue, $RightValue);
            return ["point"=>$result * 255];
        }
    }

    public function compute($data, &$total = 0){
        $total += $this->convertLevelPrice($data["level"]);
        if($data["left"] !== null) $this->compute($data["left"], $total);
        if($data["right"] !== null) $this->compute($data["right"], $total);
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

    private function formatUserData($UserData){
        $result = array(
            "id"=>$UserData->id,
            "user_invite_id"=>$UserData->user_invite_id,
            "position_space"=>$UserData->position_space,
            "level"=>$UserData->level,
        );
        return $result;
    }

    public function getAllLog($UserId){
        $result = $this->getLeftRight($UserId);
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
            $UserLevel  = $user->level;
            $PriceLevel = $this->getLevelCost($UserLevel);
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

    private function getKeyCost($userId, $Percent, $headerId){
        $MyLevel = $this->getUserLevel($userId);
        $KeyFee = $Percent / 100;
        return array(
            "cost" => $this->getLevelCost($MyLevel) * $KeyFee,
            "userId"=> $userId,
            "headerId"=>$headerId,
        );
    }

    private function CloserDealer($Level){
        $levels = array(
            "S"=>"D",
            "M"=>"D",
            "D"=>"SD",
            "SD"=>"SD"
        );
        return $levels[$Level];
    }

    private function getPercentRollUp($Level){
        $levels = array(
            "S"=>10,
            "M"=>5,
            "D"=>0,
            "SD"=>0
        );
        return $levels[$Level];
    }

    private function getDealer($id){
        $MyData = $this->getUserById($id);
        $HeaderID = $MyData->user_invite_id;
        if(isset($HeaderID)){
            $HeaderLevel = $this->getUserLevel($HeaderID);
            if($HeaderLevel == "D" || $HeaderLevel == "SD") return $HeaderID;
            return $this->getDealer($HeaderID);
        }
        return 0;
    }
}
