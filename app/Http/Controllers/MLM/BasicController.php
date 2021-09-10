<?php
namespace App\Http\Controllers\MLM;

use App\Models\Transaction;

class BasicController extends RollUpController
{
    private function percentage($level){
        $percentage = array(
            'sd' => 30,
            'd' => 30,
            'm' => 25,
            's' => 20
        );
        $lowerLevel = strtolower($level);
        if(!isset($percentage[$lowerLevel])) return 0;
        return intval($percentage[$lowerLevel])/100;
    }

    private function finalCompute($id){
        $inviteUsersLevel = $this->getUserInvite($id);
        $usersLevel = $this->getUserLevel($id);
        $result = array();
        foreach($inviteUsersLevel as $inviteUser){
            $userInviteLevel = $this->getLevelByProductId($inviteUser->product_id);
            $value = $this->getLevelCost($inviteUser->product_id);
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


    public function insertFee($userId){
        $id = intval($userId);
        $presentArray = array();
        $type = 'DEPOSIT_FEE';
        $this->computeReferral($id, $presentArray);
        $finishedCount = 0;
        foreach($presentArray as $present){
            foreach($present['total'] as $index){
                $action = "ค่าแนะนำสมาชิก {$index['invitedUserId']}";
                if (!$this->isInsertFeeTransaction($present['id'], $index['invitedUserId'], $type)){
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
        //dd($presentArray);
        foreach($presentArray as $index){
            $userId = $index["userId"];
            $dealerId = $index['dealerId'];
            $action = "ค่า RollUp {$index['userId']}";
            $selfCondition = Transaction::where([
                ['user_id', '=',$dealerId ],
                ['fk_id', '=', $userId],
                ['type', '=', $type]
            ])->get();
            if (count($selfCondition) <= 0){
                $dealerId = $index['dealerId'];
                $amount = $index['total'];
                $fkId = $index['userId'];
                // in first case add money to dealerId
                // $this->extractBalance($dealerId, $amount, $action, $type, $fkId);

                // in second case
                if($dealerId == 0){
                    // add this amount to company without extractBalance
                    $this->depositCompanyWallaet($dealerId, $this->floorp($amount, 2), $action);
                } else {
                    // dealerId > 0
                    // add money to this delerId
                    $this->extractBalance($dealerId, $amount, $action, $type, $fkId);
                }
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
        $invite_id = $this->getUserById($id)->user_invite_id;
        $invite_productId = $this->getUserById($invite_id)->product_id;
        $user_productId = $this->getUserById($id)->product_id;
        $user_product = ProductModel::where('id', $user_productId)->get()->first();
        $invite_product = ProductModel::where('id', $invite_productId)->get()->first();
        $user_product_point = $user_product->point;
        $percent_invite = $this->percentage($invite_product->level);
        $amount = $percent_upline * $user_product_point;
        $checkTransaction = Transaction::where('user_id', $invite_id)
                                            ->where('fk_id', $id)
                                            ->where('amount', $amount * 0.75)->get();
        if(count($checkTransaction) != 0 ) return false;
        //$this->insertFee($invite_id);
        $this->extractBalance($invite_id, $amount, $details, $type, $id);
        return true;
    }

}
