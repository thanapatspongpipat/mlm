<?php

namespace App\Http\Controllers;

use App\Models\CashWallet;
use App\Models\CoinWallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function summaryInOut()
    {

        return view('report.summary-in-out.index');
    }

    public function showSummaryInOut(Request $req)
    {
        $from = $req->from != null ? date('Y-m-d', strtotime($req->from)) : null;
        $to = $req->to != null ? date('Y-m-d', strtotime($req->to)) : null;
        $type = $req->type;


        // return $req->all();

        $i = 1;
        $data = [];

        $users = User::get();

        $sumBalance = 0;
        $sumDeposit = 0;
        $sumWithdraw= 0;

        if($type == null){
            if($from == null && $to == null){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'DEPOSIT')
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'WITHDRAW')
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }else if($from == null && $to != null){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'DEPOSIT')
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'WITHDRAW')
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }else if($from != null && $to == null){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'DEPOSIT')
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'WITHDRAW')
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }else{
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'DEPOSIT')
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'WITHDRAW')
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }
        }else{
            if($type == 'd'){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'DEPOSIT')
                        ->whereDate('transaction_timestamp', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'WITHDRAW')
                        ->whereDate('transaction_timestamp', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];
                    
                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }else if($type == 'm'){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'DEPOSIT')
                        ->whereMonth('transaction_timestamp', '=', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'WITHDRAW')
                        ->whereMonth('transaction_timestamp', '=', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }else if($type == 'y'){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'DEPOSIT')
                        ->whereYear('transaction_timestamp', '=', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'WITHDRAW')
                        ->whereYear('transaction_timestamp', '=', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }

        }


        $header = [
            'sumBalance' => $sumBalance,
            'sumDeposit' => $sumDeposit,
            'sumWithdraw' => $sumWithdraw,
        ];


        // return $header;
        $view = View::make('report.summary-in-out.datatable', compact('header', 'data', 'i'))->render();
        return response()->json([
            'html' => $view,
        ]);

    }
}
