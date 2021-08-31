<?php

namespace App\Http\Controllers;

use App\Models\CashWallet;
use App\Models\CoinWallet;
use App\Models\Transaction;
use App\Models\CashTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class CashWalletController extends Controller
{

    public function index()
    {

        $userId = Auth::user()->id;

        $wallet = CashWallet::where('user_id', $userId)->first();

        if ($wallet == null) {
            $wallet = new CashWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return view('wallet.cash-wallet.index', compact('wallet') );
    }

    public function search(Request $req)
    {

        // return $req->all();
        $from = $req->from != null ? date('Y-m-d', strtotime($req->from)) : null;
        $to = $req->to != null ? date('Y-m-d', strtotime($req->to)) : null;
        $type = $req->type;

        // return $from;

        $userId = Auth::user()->id;
        $wallet = CashWallet::where('user_id', $userId)->first();

        if ($type == 'all') {
            if ($from != null && $to != null) {
                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->orderBy('transaction_timestamp', 'desc')->get();
                // return $data;

            } else if ($from != null && $to == null) {

                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->orderBy('transaction_timestamp', 'desc')->get();
            } else if ($from == null && $to != null) {

                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->orderBy('transaction_timestamp', 'desc')->get();
            } else {

                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    ->orderBy('transaction_timestamp', 'desc')->get();
            }
        } else if ($type == 'in') {
            if ($from != null && $to != null) {
                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->where('type',  'like', '%DEPOSIT%')
                    ->orderBy('transaction_timestamp', 'desc')->get();
            } else if ($from != null && $to == null) {
                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->where('type', 'like', '%DEPOSIT%')
                    ->orderBy('transaction_timestamp', 'desc')->get();
            } else if ($from == null && $to != null) {
                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    ->where('type', 'like', '%DEPOSIT%')
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->orderBy('transaction_timestamp', 'desc')->get();
            } else {
                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    ->where('type', 'like', '%DEPOSIT%')
                    ->orderBy('transaction_timestamp', 'desc')->get();
            }
        } else if ($type == 'out') {
            if ($from != null && $to != null) {
                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    // ->orWhere('to_user_id', $userId)
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->where('type', 'WITHDRAW')
                    ->orderBy('transaction_timestamp', 'desc')->get();
            } else if ($from != null && $to == null) {
                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    // ->orWhere('to_user_id', $userId)
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->where('type', 'WITHDRAW')
                    ->orderBy('transaction_timestamp', 'desc')->get();
            } else if ($from == null && $to != null) {
                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    // ->orWhere('to_user_id', $userId)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->where('type', 'WITHDRAW')
                    ->orderBy('transaction_timestamp', 'desc')->get();
            } else {
                $data = Transaction::with('user')
                    ->where('user_id', $userId)
                    // ->orWhere('to_user_id', $userId)
                    ->where('type', 'WITHDRAW')
                    ->orderBy('transaction_timestamp', 'desc')->get();
            }
        } else {
        }



        $i = 1;

        $view = View::make('wallet.view-make.transaction-table', compact('data', 'i'))->render();
        return response()->json([
            'html' => $view,
        ]);
    }



    // public function search(Request $req)
    // {

    //     // return $req->all();
    //     $from = $req->from != null ? date('Y-m-d', strtotime($req->from)) : null ;
    //     $to = $req->to != null ? date('Y-m-d', strtotime($req->to)) : null;
    //     $type = $req->type;

    //     // return $from;

    //     $userId = Auth::user()->id;
    //     $wallet = CashWallet::where('user_id', $userId)->first();



    //     if($type == 'all'){
    //         if($from != null && $to != null){
    //             $obj1 = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('from_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '>=', $from)
    //                 ->whereDate('transaction_timestamp', '<=', $to)
    //                 ->orderBy('id', 'desc')->get()->toArray();

    //             $obj2 = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('to_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '>=', $from)
    //                 ->whereDate('transaction_timestamp', '<=', $to)
    //                 ->orderBy('id', 'desc')->get()->toArray();

    //             $data =  array_merge($obj1, $obj2);
    //             // return $data;

    //         }else if($from != null && $to == null){
    //             $obj1 = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('from_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '>=', $from)
    //                 ->orderBy('id', 'desc')->get()->toArray();

    //             $obj2 = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('to_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '>=', $from)
    //                 ->orderBy('id', 'desc')->get()->toArray();

    //             $data =  array_merge($obj1, $obj2);

    //         }else if($from == null && $to != null){
    //             $obj1 = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('from_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '<=', $to)
    //                 ->orderBy('id', 'desc')->get()->toArray();

    //             $obj2 = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('to_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '<=', $to)
    //                 ->orderBy('id', 'desc')->get()->toArray();

    //             $data =  array_merge($obj1, $obj2);

    //         }else{
    //             $data = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('from_user_id', $userId)
    //                 ->orWhere('to_user_id', $userId)
    //                 ->orderBy('id', 'desc')->get();
    //         }

    //     }else if($type == 'in'){
    //         if ($from != null && $to != null) {
    //             $data = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('to_user_id', $userId)
    //                 // ->orWhere('to_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '>=', $from)
    //                 ->whereDate('transaction_timestamp', '<=', $to)
    //                 ->where('type', 'deposit')
    //                 ->orderBy('id', 'desc')->get();
    //         } else if ($from != null && $to == null) {
    //             $data = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('to_user_id', $userId)
    //                 // ->orWhere('to_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '>=', $from)
    //                 ->where('type' , 'deposit')
    //                 ->orderBy('id', 'desc')->get();
    //         } else if ($from == null && $to != null) {
    //             $data = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('to_user_id', $userId)
    //                 // ->orWhere('to_user_id', $userId)
    //                 ->where('type', 'deposit')
    //                 ->whereDate('transaction_timestamp', '<=', $to)
    //                 ->orderBy('id', 'desc')->get();
    //         } else {
    //             $data = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('to_user_id', $userId)
    //                 // ->orWhere('to_user_id', $userId)
    //                 ->where('type', 'deposit')
    //                 ->orderBy('id', 'desc')->get();
    //         }

    //     }else if($type == 'out'){
    //         if ($from != null && $to != null) {
    //             $data = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('from_user_id', $userId)
    //                 // ->orWhere('to_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '>=', $from)
    //                 ->whereDate('transaction_timestamp', '<=', $to)
    //                 ->where('type', 'withdraw')
    //                 ->orderBy('id', 'desc')->get();
    //         } else if ($from != null && $to == null) {
    //             $data = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('from_user_id', $userId)
    //                 // ->orWhere('to_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '>=', $from)
    //                 ->where('type', 'withdraw')
    //                 ->orderBy('id', 'desc')->get();
    //         } else if ($from == null && $to != null) {
    //             $data = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('from_user_id', $userId)
    //                 // ->orWhere('to_user_id', $userId)
    //                 ->whereDate('transaction_timestamp', '<=', $to)
    //                 ->where('type', 'withdraw')
    //                 ->orderBy('id', 'desc')->get();
    //         } else {
    //             $data = CashTransaction::with('fromUser', 'toUser', 'fromCashWallet', 'toCashWallet')
    //                 ->where('from_user_id', $userId)
    //                 // ->orWhere('to_user_id', $userId)
    //                 ->where('type', 'withdraw')
    //                 ->orderBy('id', 'desc')->get();
    //         }

    //     }else{

    //     }



    //     $i = 1;

    //     $view = View::make('wallet.view-make.history-table', compact('data', 'i'))->render();
    //     return response()->json([
    //         'html' => $view,
    //     ]);
    // }



}
