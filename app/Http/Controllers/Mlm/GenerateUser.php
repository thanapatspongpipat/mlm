<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LevelLogs;
class GenerateUser extends Controller
{
    public function generateUser(){
        $count = 1;
        //return $this->generateUserBy(0, $count, 'left');
        $this->generateTriangleUser(0, true, $count);
    }

    private function getMaxCurrentUserId(){
        $userMaxData = (User::select('id')->orderBy('id', 'desc') ->limit(1)->get());
        $userId = (count($userMaxData) > 0)?$userMaxData[0]->id:1;
        return $userId;
    }

    private function generateTriangleUser($userInviteId, $headPosition, &$count){
        $level = ["", "SD", "D", "M", "S"];
        for($i=0;$i<3;$i++){
            if($headPosition === null && $i == 0){
                continue;
            }
            $index = rand(1, 4);
            $userLevel = $level[$index];
            $productId = $index;
            $password = "dsadasdsadads";
            $avatar = "/images/1629883023.png";
            $username = "test{$index}";
            $email = "test{$index}@gmail.com";
            $position = 'left';
            if($i == 1){
                $userInviteId = $this->getMaxCurrentUserId();
            }
            if($i == 0){ // if true mean top user
                $position = ($headPosition)?'left':$headPosition;
            } else if($i == 1){
                $position = 'left';
            } else if($i == 2){
                $position = 'right';
            }
            if($count <= 300){
                $count += 1;
                User::insert([
                    "username"=>$username,
                    "email"=>$email,
                    "user_upline_id"=>$userInviteId,
                    "position_space"=>$position,
                    "level"=>$userLevel,
                    "product_id"=>$productId,
                    "password"=>$password,
                    "avatar"=>$avatar
                ]);
                LevelLogs::insert([
                    "user_id"=>$this->getMaxCurrentUserId(),
                    "product_id"=>$productId
                ]);
            } else {
                return true;
            }
            if($i == 2){
                $userInviteId = $this->getMaxCurrentUserId();
                $this->generateTriangleUser($userInviteId, $position, $count);
            }
        }
    }

    private function generateUserBy($userInviteId, &$count, $position){
        $userMaxData = (User::select('id')->orderBy('id', 'desc') ->limit(1)->get());
        $userId = 1;
        if(count($userMaxData) != 0){
            $userId = $userMaxData[0]['id'] + 1;
        }
        $level = ["", "SD", "D", "M", "S"];
        $index = rand(1, 4);
        $userLevel = $level[$index];
        $productId = $index;
        $password = "dsadasdsadads";
        $avatar = "/images/1629883023.png";
        $username = "test{$userId}";
        $email = "test{$userId}@gmail.com";
        if($count <= 300){
            User::insert([
                "id"=>$userId,
                "username"=>$username,
                "email"=>$email,
                "user_upline_id"=>$userInviteId,
                "position_space"=>$position,
                "level"=>$userLevel,
                "product_id"=>$productId,
                "password"=>$password,
                "avatar"=>$avatar
            ]);
            $count += 1;
            $result = [$this->generateUserBy($userId, $count, "left"), $this->generateUserBy($userId, $count, "right")];
        } else {
            return true;
        }
    }
}
/*





*/
