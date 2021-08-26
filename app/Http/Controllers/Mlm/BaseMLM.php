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

    protected function getAllUser(){
        return User::all();
    }
}
