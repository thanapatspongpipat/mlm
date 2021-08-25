<?php

namespace App\Http\Controllers\Mlm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RollUpController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index($id){
        // Get Key Topic 3
        // MyUserID , Percent
        //$KeyPriceResult = $this->getKeyResult($id, 5);
        //dd($KeyPriceResult);

        // GetLogRollUp
        // MyUserID
        $logRollUp = $this->getLogRollUp($id);
        dd($logRollUp);

    }
    public function getLogRollUp($id){
        $ReferralData = User::where('user_invite_id', $id)->select('level', 'id')->get();
        $result = array();
        foreach($ReferralData as $user){
            $UserID = $user->id;
            $UserLevel  = $user->level;
            $PercentRollUp = $this->getPercentRollUp($UserLevel);
            $PriceLevel = $this->getPriceLevel($UserLevel);
            $RollUpResult = ($PercentRollUp / 100) * $PriceLevel;
            $DealerCloserLevel = $this->DealerCloser($UserLevel);
            $DealerID = $this->getDealer($id, $DealerCloserLevel);
            array_push($result, array(
                "DealerID"=>$DealerID,
                "RollUpResult"=>$RollUpResult,
                "PercentRollUp"=>$PercentRollUp,
                "UserID"=>$UserID
            ));
        }
        dd($result);
    }

    private function getKeyResult($UserID, $Percent){
        $MyData = User::where('id', $UserID)->select('level')->get();
        $MyLevel = $MyData[0]->level;
        $KeyFee = $Percent / 100;
        return $this->getPriceLevel($MyLevel) * $KeyFee;
    }
    private function DealerCloser($Level){
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
    private function getPriceLevel($Level){
        $levels = array(
            "S"=>1500,
            "M"=>15000,
            "D"=>45000,
            "SD"=>150000
        );
        return $levels[$Level];
    }
    private function getDealer($id, $Level){
        $MyData = User::where('id', $id)->select('user_invite_id', 'level')->get();
        $MyLevel = $MyData[0]->id;
        $HeaderID = $MyData[0]->user_invite_id;
        if(isset($HeaderID)){
            $HeaderData = User::where('id', $HeaderID)->select('level')->get();
            $HeaderLevel  = $HeaderData[0]->level;
            if($HeaderLevel == $Level){
                return $HeaderID;
            }  else {
                return $this->getDealer($HeaderID, $Level);
            }
        } else {
            return 0;
        }
    }
}
