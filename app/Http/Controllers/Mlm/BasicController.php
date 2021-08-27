<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\MLM\BaseMLM;
use App\Models\Transactions;

class BasicController extends RollUpController
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
    
    public function insertFee($id){
        $presentArray = array();
        $type = 'DEPOSIT_FEE';
        $this->computeReferral($id, $presentArray);
        //dd($presentArray);
        $finishedCount = 0;
        foreach($presentArray as $present){
            foreach($present['total'] as $index){
                $action = "ค่าแนะนำสมาชิก";
                    if (count(Transactions::where('user_id', $present['id'])->get()) <= 0  || count(Transactions::where('fk_id', $index['invitedUserId'])->get()) <= 0){
                        Transactions::insert(array(
                            'user_id' => $present['id'],
                            'type' => $type,
                            'fk_id' => $index['invitedUserId'],
                            'balance' => $index['total'],
                            'detail' => $action,
                            'amount' => 0,
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
        //dd($presentArray);
        foreach($presentArray as $index){
            $userId = $index["userId"];
            $action = "ค่า RollUp";
            // SELECT * FROM transactions WHERE user_id = $userId AND fk_id = $dealerId AND type = 'DEPOSIT_ROLLUP' AND user_id != 0;
            $condition = Transactions::where('user_id', $userId)
                                        ->where('fk_id', $index['dealerId'])
                                        ->where('type', $type);
            $selfCondition = Transactions::where('user_id',$index['dealerId'])
                                        ->where('type', $type)
                                        ->where('fk_id', $index['userId']);
            /*echo $condition->toSql();
            dd($userId,$index['dealerId'],$type,$index['dealerId']);*/
            #dd($userId);
            if (count($condition->get()) <= 0 and count($selfCondition->get()) <= 0){
                Transactions::insert(array(
                    'user_id' => $index['dealerId'],
                    'fk_id' => $index['userId'], 
                    'type' => $type,
                    'detail' => $action,
                    'balance' => $index['total'],
                    'amount' => 0,
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
    //private function computeRollup

}
