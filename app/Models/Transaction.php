<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userApprove()
    {
        return $this->belongsTo(User::class, 'user_approve_id', 'id');
    }


    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    public function cashWallet()
    {
        return $this->belongsTo(CashWallet::class, 'cash_wallet_id', 'id');
    }

    public function coinWallet()
    {
        return $this->belongsTo(CoinWallet::class, 'coin_wallet_id', 'id');
    }
}
