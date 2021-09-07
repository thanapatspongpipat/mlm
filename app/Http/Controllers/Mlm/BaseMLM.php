<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\LevelLogs;
use App\Models\User;
use App\Models\Transaction;
use App\Models\ProductModel;

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
        return (isset($User)) ? $this->getLevelByProductId($User->product_id) : null;
    }

    protected function getLevelByProductId($productId){
        $Product = $this->getProduct($productId);
        return (isset($Product)) ? $Product->level : null;
    }

    private $InviterCache = array();
    protected function getUserInviter($userId){
        if(isset($this->InviterCache["u{$userId}"])) return $this->InviterCache["u{$userId}"];
        $User = User::where('user_upline_id', $userId)->get();
        $this->InviterCache["u{$userId}"] = $User;
        return $this->InviterCache["u{$userId}"];
    }

    private $LevelCache = null;
    protected function getProductAll(){
        if($this->LevelCache == null || count($this->LevelCache) <= 0) $this->LevelCache = ProductModel::all();
        return $this->LevelCache;
    }

    protected function getProduct($productId){
        foreach($this->getProductAll() as $product){
            if($product->id === $productId) return $product;
        }
        return null;
    }

    protected function getLevelCost($Level){
        foreach($this->getProductAll() as $LevelData){
            if(strtolower($LevelData->level) == strtolower($Level)) return intval($LevelData->price_num);
        }
        return 0;
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
        foreach($this->getProductAll() as $LevelData){
            if(strtolower($LevelData->level) == strtolower($Level)) return intval($LevelData->level_value);
        }
        return 0;
    }

    protected function getTransactionFieldKeyById($id, $type){
        return Transaction::where('fk_id', $id)->where('type', $type)->get();
    }

    protected function getTransactionByUserId($id, $type){
        return Transaction::where('user_id', $id)->where('type', $type)->get();
    }

    protected function convertMaxCouple($level){
        $result = array(
            "S" => [
                "phrase1" => [
                    "countCouple" => 8,
                    "price" => 255],
                "phrase2" => [
                    "countCouple" => 56,
                    "price" => 75]
            ],
            "M" => [
                "phrase1" => [
                    "countCouple" => 16,
                    "price" => 255],
                "phrase2" => [
                    "countCouple" => 416,
                    "price" => 112]
            ],
            "D" => [
                "phrase1" => [
                    "countCouple" => 32,
                    "price" => 255
                ],
                "phrase2" => [
                    "countCouple" => 0,
                    "price" => 112
                ]
            ],
            "SD" => [
                "phrase1" => [
                    "countCouple" => 70,
                    "price" => 255
                ],
                "phrase2"=> [
                    "countCouple" => 0,
                    "price"=>112
                ]
            ]
        );

        return (isset($result[$level]))?$result[$level]:null;
    }

    protected function floorp($val, $precision)
    {
        $mult = pow(10, $precision); // Can be cached in lookup table
        return floor($val * $mult) / $mult;
    }

    protected function extractBalance($userId, $totalBalance, $detail, $type, $fkId = 0){
        $eWalletBalance = $totalBalance * 0.75;
        $pointBalance = $totalBalance * 0.2;
        $remainVatFee = $totalBalance - ($eWalletBalance + $pointBalance);

        $this->depositCash($userId, $this->floorp($eWalletBalance, 2), $detail, 0, $type, $fkId);

        $this->depositCoin($userId, $this->floorp($pointBalance, 2), $detail, 0, $type);

        $this->depositCompanyWallaet($userId, $this->floorp($remainVatFee, 2), $detail);
    }

    protected function getLevelLogs($userId){
        return LevelLogs::where(array('user_id', $userId))->distinct()->get();
    }

}
