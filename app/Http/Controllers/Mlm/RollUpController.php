<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\MLM\BaseMLM;

class RollUpController extends BaseMLM
{
    public function index($type,$id){
        // Get Key Topic 3
        // MyUserID , Percent
        if($type == "key"){
            return $this->getKeyCost($id, 5);
        }

        // GetLogRollUp
        // MyUserID
        if($type == "log"){
            return $this->getLogRollUp($id);
        }

        if($type == "all"){
            return $this->getAllLog();
        }
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
    public function getAllLog(){
        /*$AllUserData = $this->getAllUser();
        $AllUserDataFormat = array();
        $userInviteId = array();
        foreach($AllUserData as $UserData){
            $userInviteId[] = intval($UserData->user_invite_id);
            $AllUserDataFormat[] = $this->formatUserData($UserData);
        }*/
        $result = $this->getLeftRight(1);
        dd($result);
        return $result;
        /*$FinalUserData = array();
        foreach($AllUserDataFormat as $UserData){
            $UserId = $UserData["id"];
            $BottomUserData = $this->getUserInviter($UserId);
            $UserData["isMatch"] = (count($BottomUserData) == 2)?TRUE:FALSE;
            $BottomDataResult = array();
            foreach($BottomUserData as $data){
                $BottomDataResult[] = $this->formatUserData($data);
            }
            $UserData["MyBottomUser"] = $BottomDataResult;
            $FinalUserData[] = $UserData;
        }
        dd($FinalUserData);*/
        //$countUserInviteId = array_count_values($userInviteId);
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
        foreach($ReferralData as $user){
            $UserID = $user->id;
            $UserLevel  = $user->level;
            $PercentRollUp = $this->getPercentRollUp($UserLevel);
            $PriceLevel = $this->getLevelCost($UserLevel);
            $RollUpResult = ($PercentRollUp / 100) * $PriceLevel;
            $CloserDealerLevel = $this->CloserDealer($UserLevel);
            $DealerID = $this->getDealer($id, $CloserDealerLevel);
            $result[] = array(
                "dealerId"=>$DealerID,
                "rollUpResult"=>$RollUpResult,
                "percentRollUp"=>$PercentRollUp,
                "userId"=>$UserID
            );
        }
        return $result;
    }

    private function getKeyCost($userId, $Percent){
        $MyLevel = $this->getUserLevel($userId);
        $KeyFee = $Percent / 100;
        return array(
            "cost" => $this->getLevelCost($MyLevel) * $KeyFee
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

    private function getDealer($id, $Level){
        $MyData = $this->getUserById($id);
        $HeaderID = $MyData->user_invite_id;
        if(isset($HeaderID)){
            $HeaderLevel = $this->getUserLevel($HeaderID);
            if($HeaderLevel == $Level) return $HeaderID;
            return $this->getDealer($HeaderID, $Level);
        }
        return 0;
    }
}
