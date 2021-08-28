<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CashWallet;
use App\Models\CoinWallet;

class WalletController extends Controller
{
    //

    public function index()
    {

        $userId = Auth::user()->id;

        $cashWallet = CashWallet::where('user_id', $userId)->first();
        $coinWallet = CoinWallet::where('user_id', $userId)->first();

        return view('wallet.index', compact('cashWallet', 'coinWallet'));
    }

    public function createCashWallet()
    {

        $userId = Auth::user()->id;

        $cashWallet = CashWallet::where('user_id', $userId)->first();
        if($cashWallet == null){
            $cashWallet = new CashWallet;
        }
        $cashWallet->user_id = $userId;
        $cashWallet->balance = 0;
        $cashWallet->deposit = 0;
        $cashWallet->withdraw = 0;
        $cashWallet->save();


        return redirect()->route('wallet.index');
    }

    public function createCoinWallet()
    {

        $userId = Auth::user()->id;

        $cashWallet = CoinWallet::where('user_id', $userId)->first();
        if ($cashWallet == null) {
            $cashWallet = new CoinWallet;
        }
        $cashWallet->user_id = $userId;
        $cashWallet->balance = 0;
        $cashWallet->deposit = 0;
        $cashWallet->withdraw = 0;
        $cashWallet->save();


        return redirect()->route('wallet.index');
    }
}
