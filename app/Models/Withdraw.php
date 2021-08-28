<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function createUser()
    {
        return $this->belongsTo(User::class, 'user_create_id', 'id');
    }

    public function approveUser()
    {
        return $this->belongsTo(User::class, 'user_approve_id', 'id');
    }

    public function cancleUser()
    {
        return $this->belongsTo(User::class, 'user_cancle_id', 'id');
    }


}
