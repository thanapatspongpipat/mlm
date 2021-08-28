<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    use HasFactory;

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    public function fromCoinWallet()
    {
        return $this->belongsTo(CashWallet::class, 'from_coin_wallet_id', 'id');
    }

    public function toCoinWallet()
    {
        return $this->belongsTo(CashWallet::class, 'form_coin_wallet_id', 'id');
    }
}
