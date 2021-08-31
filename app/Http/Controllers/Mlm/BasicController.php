<?php

namespace App\Http\Controllers\MLM;

use App\Models\Transaction;

class BasicController extends RollUpController
{
    private function percentage($level){
        $percentage = array(
            'S' => 20,
            'M' => 25,
            'D' => 30,
            'SD' => 30
        );
        return intval($percentage[$level])/100;
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

    public function insertFee($id){
        $presentArray = array();
        $type = 'DEPOSIT_FEE';
        $this->computeReferral($id, $presentArray);
        //dd($presentArray);
        $finishedCount = 0;
        foreach($presentArray as $present){
            foreach($present['total'] as $index){
                $action = "ค่าแนะนำสมาชิก {$index['invitedUserId']}";
                    if (count(Transaction::where('user_id', $present['id'])->get()) <= 0  || count(Transaction::where('fk_id', $index['invitedUserId'])->get()) <= 0){
                        Transaction::insert(array(
                            'user_id' => $present['id'],
                            'type' => $type,
                            'fk_id' => $index['invitedUserId'],
                            'amount' => $index['total'],
                            'detail' => $action,
                            'balance' => 0,
                            'user_approve_id' => 0,
                            'user_create_id' => 0
                        ));
                        $finishedCount++;
                    }
                }
            }
            return $finishedCount > 0;
        }

    private function computeReferral($id, &$presentArray){
        $userData = $this->getLeftRight($id);
        $userLeft = $userData['left'];
        $userRight = $userData['right'];
        $total = $this->finalCompute($userData['userId']);
        $presentArray[] = array(
            'id' => $userData['userId'],
            'total' => $total,
        );
        if($userLeft !== null) $this->computeReferral($userLeft['userId'], $presentArray);
        if($userRight !== null) $this->computeReferral($userRight['userId'], $presentArray);
    }

    public function insertRollup($id){
        $presentArray = array();
        $type = "DEPOSIT_ROLLUP";
        $this->computeRollup($id, $presentArray);
        $finishedCount = 0;
        foreach($presentArray as $index){
            $userId = $index["userId"];
            $action = "ค่า RollUp {$index['userId']}";
            $condition = Transaction::where('user_id', $userId)
                                        ->where('fk_id', $index['dealerId'])
                                        ->where('type', $type);
            $selfCondition = Transaction::where('user_id',$index['dealerId'])
                                        ->where('type', $type)
                                        ->where('fk_id', $index['userId']);
            if (count($condition->get()) <= 0 and count($selfCondition->get()) <= 0){
                Transaction::insert(array(
                    'user_id' => $index['dealerId'],
                    'fk_id' => $index['userId'],
                    'type' => $type,
                    'detail' => $action,
                    'amount' => $index['total'],
                    'balance' => 0,
                    'user_approve_id' => 0,
                    'user_create_id' => 0
                ));
                $finishedCount++;
            }
        }
        return $finishedCount > 0;
    }


    private function computeRollup($id, &$presentArray){
        $userData = $this->getLeftRight($id);
        $userLeft = $userData['left'];
        $userRight = $userData['right'];
        $rollup = $this->getLogRollUp($userData['userId']);
        foreach($rollup as $index){
            $presentArray[] = array(
                'userId' => $index['userId'],
                'dealerId' =>$index['dealerId'],
                'total' => $index['rollUpResult']
            );
        }
        if($userLeft !== null) $this->getLogRollUp($userLeft['userId'], $presentArray);
        if($userRight !== null) $this->getLogRollUp($userRight['userId'], $presentArray);
    }

}
