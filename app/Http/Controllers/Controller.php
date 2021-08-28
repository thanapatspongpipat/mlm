<?php

namespace App\Http\Controllers;

use App\Models\CoinTransaction;
use App\Models\BankAccount;
use App\Models\CashWallet;
use App\Models\CoinWallet;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\Models\CoinWithdraw;
use App\Models\CoinDeposit;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function icon()
    {
        return view('icons-boxicons');
    }

    public function uiGeneral()
    {
        return view('ui-general');
    }

    // public function depositCash()
    public function depositCash($userId, $amount, $detail, $userCreateId)
    {
        // $userId = Auth::user()->id;
        // $amount = 100000;
        // $detail = 'ทดสอบ';
        // $userCreateId = Auth::user()->id;

        if ($userId == null || $amount == null || $detail == null) {

            $data = [
                'title' => 'ผิดพลาด!',
                'msg' => 'กรุณาใส่ข้อมูลที่จำเป็น',
                'status' => 'warning',
            ];

            return $data;
        }

        if (!User::find($userId)) {

            $data = [
                'title' => 'ผิดพลาด!',
                'msg' => 'ไม่พบผู้ใช้นี้',
                'status' => 'error',
            ];

            return $data;
        }

        $wallet = CashWallet::where('user_id', $userId,)->first();

        DB::beginTransaction();

        if ($wallet == null) {
            $wallet = new CashWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        $deposit = new Deposit;
        $deposit->user_id = $userId;
        $deposit->amount = (string) $amount < 0 ? $amount * (-1) : $amount;
        $deposit->transaction_timestamp = Carbon::now();
        $deposit->company_bank_account_id = null;
        $deposit->slip_img = null;
        $deposit->detail = $detail ? $detail : 'เติมเงินเข้า CASH - WALLET';
        $deposit->status = 1;
        $deposit->user_create_id = $userCreateId ? $userCreateId : Auth::user()->id;
        $deposit->user_approve_id = 1;
        $deposit->save();

        $tmpAmount = $amount < 0 ? $amount * (-1) : $amount;
        $oldBalance = $wallet->balance;
        $oldDeposit = $wallet->deposit;
        $newBalance = $oldBalance + $tmpAmount;

        $ts = new Transaction;
        $ts->user_id = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = 'DEPOSIT';
        $ts->transaction_timestamp = Carbon::now();
        $ts->detail = $detail ? $detail : 'เติมเงินเข้า CASH-WALLET';
        $ts->user_create_id = $userCreateId;
        $ts->user_approve_id = 1;
        $ts->save();

        $newDeposit = $oldDeposit + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->deposit = (string) $newDeposit;
        $wallet->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการเติมเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    // public function depositCashWithValidation()
    public function depositCashWithValidation($userId, $amount, $detail, $userCreateId)
    {
        // $userId = Auth::user()->id;
        // $amount = 100000;
        // $detail = 'ทดสอบ';
        // $userCreateId = Auth::user()->id;

        if($userId == null || $amount == null || $detail == null){

            $data = [
                'title' => 'ผิดพลาด!',
                'msg' => 'กรุณาใส่ข้อมูลที่จำเป็น',
                'status' => 'warning',
            ];

            return $data;
        }

        if(!User::find($userId)){

            $data = [
                'title' => 'ผิดพลาด!',
                'msg' => 'ไม่พบผู้ใช้นี้',
                'status' => 'warning',
            ];

            return $data;
        }

        DB::beginTransaction();

        $deposit = new Deposit;
        $deposit->user_id = $userId;
        $deposit->amount = (string) $amount < 0 ? $amount*(-1) : $amount;
        $deposit->transaction_timestamp = Carbon::now();
        $deposit->company_bank_account_id = null;
        $deposit->slip_img = null;
        $deposit->detail = $detail ? $detail : 'เติมเงินเข้า CASH - WALLET';
        $deposit->status = 0;
        $deposit->user_create_id = $userCreateId ? $userCreateId : Auth::user()->id;
        $deposit->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการเติมเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    public function withdrawCash()
    // public function withdrawCash($userId, $amount, $detail, $userCreateId)
    {

        $userId = Auth::user()->id;
        $amount = 150;
        $detail = 'ทดสอบตัดเงิน';
        $userCreateId = Auth::user()->id;

        $wallet = CashWallet::where('user_id', $userId)->first();
        $oldBalance = $wallet->balance;
        if($wallet == null){
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'ไม่พบ wallet',
                'status' => 'error',
            ];

            return $data;
        }

        if ($amount > $oldBalance) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินใน Wallet ไม่เพียงพอ',
                'status' => 'error',
            ];

            return $data;
        }


        DB::beginTransaction();

        $withdraw = new Withdraw;
        $withdraw->user_id = $userId;
        $withdraw->amount = (string) $amount;
        $withdraw->transaction_timestamp = Carbon::now();
        $withdraw->bank_id = null;
        $withdraw->bank_account_name = null;
        $withdraw->bank_account_no = null;
        $withdraw->status = 1;
        $withdraw->detail = $detail;
        $withdraw->user_create_id = $userCreateId;
        $withdraw->save();

        $oldBalance = $wallet->balance;
        $oldWithdraw = $wallet->withdraw;
        $newBalance = $oldBalance - $amount;

        $ts = new Transaction;
        $ts->user_id = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = 'WITHDRAW';
        $ts->transaction_timestamp = Carbon::now();
        $ts->detail = $detail;
        $ts->user_create_id = $userCreateId;
        $ts->user_approve_id = 1;
        $ts->save();

        $newWithdraw = $oldWithdraw + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->withdraw = (string) $newWithdraw;
        $wallet->save();

        $withdraw->status = 1;
        $withdraw->user_approve_id = Auth::user()->id;
        $withdraw->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการถอนเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;

    }

    // public function depositCash()
    public function depositCoin($userId, $amount, $detail, $userCreateId)
    {
        // $userId = Auth::user()->id;
        // $amount = 100000;
        // $detail = 'ทดสอบ';
        // $userCreateId = Auth::user()->id;

        if ($userId == null || $amount == null || $detail == null) {

            $data = [
                'title' => 'ผิดพลาด!',
                'msg' => 'กรุณาใส่ข้อมูลที่จำเป็น',
                'status' => 'warning',
            ];

            return $data;
        }

        if (!User::find($userId)) {

            $data = [
                'title' => 'ผิดพลาด!',
                'msg' => 'ไม่พบผู้ใช้นี้',
                'status' => 'error',
            ];

            return $data;
        }

        $wallet = CoinWallet::where('user_id', $userId,)->first();

        DB::beginTransaction();

        if ($wallet == null) {
            $wallet = new CoinWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        $deposit = new CoinDeposit;
        $deposit->user_id = $userId;
        $deposit->amount = (string) $amount < 0 ? $amount * (-1) : $amount;
        $deposit->transaction_timestamp = Carbon::now();
        $deposit->detail = $detail ? $detail : 'เติมเงินเข้า COIN - WALLET';
        $deposit->status = 1;
        $deposit->user_create_id = $userCreateId ? $userCreateId : Auth::user()->id;
        $deposit->user_approve_id = 1;
        $deposit->save();

        $tmpAmount = $amount < 0 ? $amount * (-1) : $amount;
        $oldBalance = $wallet->balance;
        $oldDeposit = $wallet->deposit;
        $newBalance = $oldBalance + $tmpAmount;

        $ts = new CoinTransaction;
        $ts->user_id = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = 'DEPOSIT';
        $ts->transaction_timestamp = Carbon::now();
        $ts->detail = $detail ? $detail : 'เติมเงินเข้า CASH-WALLET';
        $ts->user_create_id = $userCreateId;
        $ts->user_approve_id = 1;
        $ts->save();

        $newDeposit = $oldDeposit + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->deposit = $newDeposit;
        $wallet->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการเติม Coin สำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    public function withdrawCoin($userId, $amount, $detail, $userCreateId)
    {
        $wallet = CoinWallet::where('user_id', $userId)->first();
        $oldBalance = $wallet->balance;
        if ($wallet == null) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'ไม่พบ wallet',
                'status' => 'error',
            ];

            return $data;
        }

        if ($amount > $oldBalance) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวน Coin ใน Wallet ไม่เพียงพอ',
                'status' => 'error',
            ];

            return $data;
        }


        DB::beginTransaction();

        $withdraw = new CoinWithdraw;
        $withdraw->user_id = $userId;
        $withdraw->amount = (string) $amount;
        $withdraw->transaction_timestamp = Carbon::now();
        $withdraw->bank_id = null;
        $withdraw->bank_account_name = null;
        $withdraw->bank_account_no = null;
        $withdraw->status = 1;
        $withdraw->detail = $detail;
        $withdraw->user_create_id = $userCreateId;
        $withdraw->save();

        $oldBalance = $wallet->balance;
        $oldWithdraw = $wallet->withdraw;
        $newBalance = $oldBalance - $amount;

        $ts = new CoinTransaction;
        $ts->user_id = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = 'WITHDRAW';
        $ts->transaction_timestamp = Carbon::now();
        $ts->detail = $detail;
        $ts->user_create_id = $userCreateId;
        $ts->user_approve_id = 1;
        $ts->save();

        $newWithdraw = $oldWithdraw + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->withdraw = (string) $newWithdraw;
        $wallet->save();

        $withdraw->status = 1;
        $withdraw->user_approve_id = 1;
        $withdraw->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการถอน Coin สำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    public function getWalletBalance($userId)
    {

        $cashWallet = CashWallet::where('user_id', $userId)->first();
        $coinWallet = CoinWallet::where('user_id', $userId)->first();

        $data = [
            'cash_wallet' => $cashWallet,
            'coin_wallet' => $coinWallet,
        ];

        return $data;
    }

    public function getCashWalletBalance($userId)
    {

        $wallet = CashWallet::where('user_id', $userId)->first();

        $data = [
            'balance' => $wallet->balance,
            'deposit' => $wallet->deposit,
            'withdraw' => $wallet->withdraw,
        ];

        return $data;

    }

    public function getCoinWalletBalance($userId)
    {

        $wallet = CoinWallet::where('user_id', $userId)->first();

        $data = [
            'balance' => $wallet->balance,
            'deposit' => $wallet->deposit,
            'withdraw' => $wallet->withdraw,
        ];

        return $data;

    }


}
