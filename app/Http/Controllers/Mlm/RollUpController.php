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
