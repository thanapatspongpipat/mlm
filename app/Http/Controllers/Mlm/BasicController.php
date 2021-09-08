<?php

namespace App\Http\Controllers\MLM;

use App\Models\Transaction;
use App\Models\ProductModel;


class BasicController extends RollUpController
{
    private function percentage($level){
        $percentage = array(
            's' => 20,
            'm' => 25,
            'd' => 30,
            'sd' => 30
        );
        $lowerLevel = strtolower($level);
        if(!isset($percentage[$lowerLevel])) return 0;
        return intval($percentage[$lowerLevel])/100;
    }

    private function finalCompute($id){
        $inviteUsersLevel = $this->getUserInviter($id);
        $usersLevel = $this->getUserLevel($id);
        $result = array();
        foreach($inviteUsersLevel as $inviteUser){
            $userInviteLevel = $this->getLevelByProductId($inviteUser->product_id);
            $value = $this->getLevelCost($userInviteLevel);
            $percent = $this->percentage($usersLevel);
            $total = $value * $percent;
            $result[] = array(
                'invitedUserLevel' => $userInviteLevel,
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
        $finishedCount = 0;
        foreach($presentArray as $present){
            foreach($present['total'] as $index){
                $action = "ค่าแนะนำสมาชิก {$index['invitedUserId']}";
                $transactionById = $this->getTransactionByUserId($present['id'], $type);
                $transactionByFk = $this->getTransactionFieldKeyById($index['invitedUserId'], $type);
                if (count($transactionById) <= 0  || count($transactionByFk) <= 0){
                    $presentId = $present['id'];
                    $amount = $index['total'];
                    $fkId = $index['invitedUserId'];
                    $this->extractBalance($presentId, $amount, $action, $type, $fkId);
                    $finishedCount++;
                }
            }
        }
        return $finishedCount > 0;
    }

    private function computeReferral($id, &$presentArray){
        $userData = $this->getLeftRight($id);
        if(!isset($userData) || $userData === null) return;
        $userLeft =  (isset($userData['left'])) ? $userData['left'] : null;
        $userRight = (isset($userData['right'])) ? $userData['right'] : null;
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
                $dealerId = $index['dealerId'];
                $amount = $index['total'];
                $fkId = $index['userId'];
                //$this->depositCash($dealerId, $amount, $action, 0, $type, $fkId);
                $this->extractBalance($dealerId, $amount, $action, $type, $fkId);
                $finishedCount++;
            }
        }
        return $finishedCount > 0;
    }


    private function computeRollup($id, &$presentArray){
        $userData = $this->getLeftRight($id);
        if(!isset($userData) || $userData === null) return;
        $userLeft =  (isset($userData['left'])) ? $userData['left'] : null;
        $userRight = (isset($userData['right'])) ? $userData['right'] : null;
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

    public function upgradeUser($id){
        $details = "ค่าแนะนำอัพเกรดสมาชิก {$id}";
        $type = "DEPOSIT_UPGRADE_FEE";
        $upline_id = $this->getUserById($id)->user_upline_id;
        $upline_productId = $this->getUserById($upline_id)->product_id;
        $user_productId = $this->getUserById($id)->product_id;
        $user_product = ProductModel::where('id', $user_productId)->get()->first();
        $upline_product = ProductModel::where('id', $upline_productId)->get()->first();
        $user_product_point = $user_product->point;
        $percent_upline = $this->percentage($upline_product->level);
        $amount = $percent_upline * $user_product_point;
        $checkTransaction = Transaction::where('user_id', $upline_id)
                                            ->where('fk_id', $id)
                                            ->where('amount', $amount * 0.75)->get();
        if(count($checkTransaction) != 0 ) return false;
        $this->extractBalance($upline_id, $amount, $details, $type, $id);
        return true;
    }
}
