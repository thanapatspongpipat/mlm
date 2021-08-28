<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
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

    public function fromCashWallet()
    {
        return $this->belongsTo(CashWallet::class, 'from_cash_wallet_id', 'id');
    }


    public function toCashWallet()
    {
        return $this->belongsTo(CashWallet::class, 'form_cash_wallet_id', 'id');
    }


}
