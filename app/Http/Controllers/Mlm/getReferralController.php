<?php

namespace App\Http\Controllers\Mlm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class getReferralController extends Controller
{
    private function getLevel($id){
        $userData = User::where([
            "id" => $id
        ])->first();
        $level = $userData->level;
        return $level;
       
    }

    private function valueLevel($level){
        $value = array(
            'S' => 1500, 
            'M' => 15000, 
            'D' => 45000, 
            'SD' => 150000,
        ); 
        return $value[$level];
    }

    private function percentage($level){
        $percentage = array(
            'S' => 20/100,
            'M' => 25/100,
            'D' => 30/100,
            'SD' => 30/100
        );
        return $percentage[$level];
    }

    private function finalCompute($id){
        $inviteUsersLevel = User::where("user_invite_id", $id)->select('level', 'id')->get();
        $usersLevel = User::where("id", $id)->select('level')->get();
        $result = [];
        for($i = 0; $i < count($inviteUsersLevel); $i++){
            $userInviteLevel = $inviteUsersLevel[$i]->level;
            $userLevel = $usersLevel->first()->level;
            $value = $this->valueLevel($userInviteLevel);
            $percent = $this->percentage($userLevel);
            $total = $value * $percent;
            $result[] = array('levelUser' => $usersLevel->first()->level, 'levelInviteUser' => $inviteUsersLevel[$i]->level, 'idInviteUser' => $inviteUsersLevel[$i]->id, 'total' => $total);
        }
        dd($result);
        return $result;
    }
    
    public function computeFee($id){ 
        $result = $this->finalCompute($id);
    }
}
