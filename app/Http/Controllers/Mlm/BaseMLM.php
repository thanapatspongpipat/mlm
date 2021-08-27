<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\User;

class BaseMLM extends Controller
{
    private $UserCache = array();
    protected function getUserById($userId){
        if(isset($this->UserCache["u{$userId}"])) return $this->UserCache["u{$userId}"];
        $User = User::where('id', $userId)->get()->first();
        $this->UserCache["u{$userId}"] = $User;
        return $this->UserCache["u{$userId}"];
    }

    protected function getUserLevel($userId){
        $User = $this->getUserById($userId);
        return (isset($User)) ? $User->level : null;
    }

    private $InviterCache = array();
    protected function getUserInviter($userId){
        if(isset($this->InviterCache["u{$userId}"])) return $this->InviterCache["u{$userId}"];
        $User = User::where('user_invite_id', $userId)->get();
        $this->InviterCache["u{$userId}"] = $User;
        return $this->InviterCache["u{$userId}"];
    }

    protected function getLevelCost($Level){
        $levels = array(
            "s"=>1500,
            "m"=>15000,
            "d"=>45000,
            "sd"=>150000
        );
        $lowerLevel = strtolower($Level);
        return (isset($levels[$lowerLevel])) ? $levels[$lowerLevel] : 0;
    }

    private $UsersCache = array();
    protected function getAllUser(){
        if(isset($this->UsersCache)) return $this->UsersCache;
        $Users = User::all();
        $this->UsersCache = $Users;
        return $this->UsersCache;
    }

    protected function computeValueOfRank(&$array, $raw, $side = "left"){
        $targetSide = $raw[$side];
        $otherSide = $raw[ (($side === "left") ? "right" : "left") ];
        if($targetSide !== null){
            # ถ้าอีกข้างมีค่า (มีทั้งสองข้าง) ให้หยุดทำงาน
            if($otherSide !== null && count($array) > 0) return;
            $array[] = array(
                "userId"=> $targetSide["id"],
                "level" => $targetSide["level"]
            );
            $this->computeValueOfRank($array, $targetSide, $side);
        }
    }

    protected function sumValueOfRank($structure){
        $results_left = array();
        $results_right = array();
        $this->computeValueOfRank($results_left, $structure, "left");
        $this->computeValueOfRank($results_right, $structure, "right");
        $levelKey = "level";
        return array(
            "userId"=>$structure["id"],
            "leftValue" => array_sum(array_column($results_left, $levelKey)),
            "rightValue" => array_sum(array_column($results_right, $levelKey))
        );
    }

    protected function convertLevelPrice($Level){
        $result = array(
            "S"=>1,
            "M"=>10,
            "D"=>30,
            "SD"=>100,
        );
        return $result[$Level];
    }

    protected function convertMaxCouple($level){
        $result = array(
            "S" => [
                "phrase1" => [
                    "countCouple" => 8,
                    "price" => 225],
                "phrase2" => [
                    "countCouple" => 56,
                    "price" => 75]
            ],
            "M" => [
                "phrase1" => [
                    "countCouple" => 16,
                    "price" => 225],
                "phrase2" => [
                    "countCouple" => 416,
                    "price" => 112]
            ],
            "D" => [
                "phrase1" => [
                    "countCouple" => 32,
                    "price" => 225
                ],
                "phrase2" => [
                    "countCouple" => 0,
                    "price" => 112
                ]
            ],
            "SD" => [
                "phrase1" => [
                    "countCouple" => 70,
                    "price" => 225
                ],
                "phrase2" => [
                    "countCouple" => 0,
                    "price" => 112
                ]
            ]
        );

        return (isset($result[$level]))?$result[$level]:null;
    }
}
