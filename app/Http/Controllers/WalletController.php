<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CashWallet;
use App\Models\CoinWallet;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\Withdraw;
use Carbon\Carbon;

class WalletController extends Controller
{
    //

    public function index()
    {

        // return $this->getCode();
        // $this->depositCash(1, 10000, 'ทดสอบฝาก', 0, 'DEPOSIT', $fkID = 0);
        // $this->withdrawCash(1, 100, 'ทดสอบถอน', 0);

        $userId = Auth::user()->id;
        if ($userId == null) {
            return abort(404);
        }
        $cashWallet = CashWallet::where('user_id', $userId)->first();
        $coinWallet = CoinWallet::where('user_id', $userId)->first();

        return view('wallet.index', compact('cashWallet', 'coinWallet'));
    }

    public function getCode()
    {
        $now_at = Carbon::now();

        $month = $now_at->month;

        $day = $now_at->day;

        if (strlen($month) == 1) {
            $month = '0' . $month;
        }

        if (strlen($day) == 1) {
            $day = '0' . $day;
        }

        $year = substr($now_at->year, -2);

        $search_code =  'A' . $year . $month . $day;

        // return $search_code;

        $lastest_code1 = Deposit::where('code', 'LIKE', $search_code . '%')->orderBy('code', 'desc')->first();
        $lastest_code2 = Withdraw::where('code', 'LIKE', $search_code . '%')->orderBy('code', 'desc')->first();
        // dd($lastest_code1, $lastest_code2);
        $rand = rand(1, 9);
        if ($lastest_code1 == null && $lastest_code2 == null) {
            $current_code = $search_code . '0001' . $rand;

            return $current_code;
        }


        if($lastest_code1 != null && $lastest_code2 != null){
            $code1 = substr($lastest_code1->code, 0, -1);;
            $num1 = (int) substr($code1, -3);

            $code2 = substr($lastest_code2->code, 0, -1);;
            $num2 = (int) substr($code2, -3);

            if($num1 > $num2){
                $code = $lastest_code1->code;
            }else{
                $code = $lastest_code2->code;
            }
        }else if($lastest_code1 != null && $lastest_code2 == null){
            $code1 = substr($lastest_code1->code, 0, -1);;
            $num1 = (int) substr($code1, -3);
            $code = $lastest_code1->code;

        }else if($lastest_code1 == null && $lastest_code2 != null){
            $code2 = substr($lastest_code2->code, 0, -1);;
            $num1 = (int) substr($code2, -3);
            $code = $lastest_code2->code;

        }else{

            $current_code = $search_code . '0001' . $rand;

            return $current_code;
        }

        // $code = $lastest_code->code;
        //return $code;
        $code = substr($code, 0, -1);;
        // return $code;

        $num = (int) substr($code, -3);
        $code = $num + 1;
        $count = 4 - strlen($code);

        for ($i = 0; $i < $count; $i++) {
            $code = '0' . $code;
        }

        $current_code = $search_code . $code . $rand;

        return $current_code;
    }





}
