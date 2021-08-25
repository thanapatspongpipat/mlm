<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\MLM\BaseMLM;

class BasicController extends BaseMLM
{
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
        $inviteUsersLevel = $this->getUserInviter($id);
        $usersLevel = $this->getUserLevel($id);
        $result = array();
        foreach($inviteUsersLevel as $inviteUser){
            $userInviteLevel = $inviteUser->level;
            $value = $this->getLevelCost($userInviteLevel);
            $percent = $this->percentage($usersLevel);
            $total = $value * $percent;
            $result[] = array(
                'invitedUserLevel' => $inviteUser->level,
                'invitedUserId' => $inviteUser->id,
                'total' => $total
            );
        }
        return $result;
    }

    public function computeFee($id){
        $result = $this->finalCompute($id);
        return $result;
    }
}
