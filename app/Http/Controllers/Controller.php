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
use App\Models\CompanyWithdraw;
use App\Models\CompanyDeposit;
use App\Models\CompanyWallet;
use App\Models\CompanyTransaction;
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
    public function depositCash($userId, $amount, $detail, $userCreateId, $typeDeposit, $fkID=0)
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
        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินไม่ถูกต้อง',
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

        $code = $this->getCodeForCash();

        $deposit = new Deposit;
        $deposit->user_id = $userId;
        $deposit->amount = (string) $amount < 0 ? $amount * (-1) : $amount;
        $deposit->transaction_timestamp = Carbon::now();
        $deposit->company_bank_account_id = null;
        $deposit->slip_img = null;
        $deposit->detail = $detail ? $detail : 'ฝากเงินเข้า CASH - WALLET';
        $deposit->status = 1;
        $deposit->user_create_id = $userCreateId ? $userCreateId : Auth::user()->id;
        $deposit->user_approve_id = 1;
        $deposit->approved_at = Carbon::now();
        $deposit->code = $code;
        $deposit->save();

        $tmpAmount = $amount < 0 ? $amount * (-1) : $amount;
        $oldBalance = $wallet->balance;
        $oldDeposit = $wallet->deposit;
        $newBalance = $oldBalance + $tmpAmount;

        $ts = new Transaction;
        $ts->user_id = $userId;
        $ts->code = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = $typeDeposit ? $typeDeposit : 'DEPOSIT';
        $ts->transaction_timestamp = Carbon::now();
        $ts->detail = $detail ? $detail : 'ฝากเงินเข้า CASH-WALLET';
        $ts->user_create_id = $userCreateId;
        $ts->user_approve_id = 1;
        $ts->code = $code;
        $ts->deposit_id = $deposit->id;
        $ts->fk_id = (isset($fkID)) ? $fkID : 0;
        $ts->save();

        $newDeposit = $oldDeposit + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->deposit = (string) $newDeposit;
        $wallet->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการฝากเงินสำเร็จ',
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
                'status' => 'warning',
            ];

            return $data;
        }

        $code = $this->getCodeForCash();

        DB::beginTransaction();

        $deposit = new Deposit;
        $deposit->user_id = $userId;
        $deposit->amount = (string) $amount < 0 ? $amount * (-1) : $amount;
        $deposit->transaction_timestamp = Carbon::now();
        $deposit->company_bank_account_id = null;
        $deposit->slip_img = null;
        $deposit->detail = $detail ? $detail : 'ฝากเงินเข้า CASH - WALLET';
        $deposit->status = 0;
        $deposit->code = $code;
        $deposit->user_create_id = $userCreateId ? $userCreateId : Auth::user()->id;
        $deposit->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการฝากเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    // public function withdrawCash()
    public function withdrawCash($userId, $amount, $detail, $userCreateId)
    {

        // $userId = Auth::user()->id;
        // $amount = 150;
        // $detail = 'ทดสอบตัดเงิน';
        // $userCreateId = Auth::user()->id;

        $wallet = CashWallet::where('user_id', $userId)->first();
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
                'msg' => 'จำนวนเงินใน Wallet ไม่เพียงพอ',
                'status' => 'error',
            ];

            return $data;
        }

        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินไม่ถูกต้อง',
                'status' => 'error',
            ];

            return $data;
        }


        DB::beginTransaction();

        $code = $this->getCodeForCash();

        $withdraw = new Withdraw;
        $withdraw->user_id = $userId;
        $withdraw->amount = (string) $amount;
        $withdraw->tax = 0;
        $withdraw->amount = (string) $amount;
        $withdraw->transaction_timestamp = Carbon::now();
        $withdraw->bank_id = null;
        $withdraw->bank_account_name = null;
        $withdraw->bank_account_no = null;
        $withdraw->status = 1;
        $withdraw->detail = $detail;
        $withdraw->user_create_id = $userCreateId;
        $withdraw->code = $code;
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
        $ts->code = $code;
        $ts->withdraw_id = $withdraw->id;
        $ts->save();

        $newWithdraw = $oldWithdraw + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->withdraw = (string) $newWithdraw;
        $wallet->save();

        $withdraw->status = 1;
        $withdraw->user_approve_id = Auth::user()->id;
        $withdraw->approved_at = Carbon::now();
        $withdraw->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการถอนเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    // public function withdrawCash()
    public function withdrawCashWithType($userId, $amount, $detail, $userCreateId, $typeWithdraw)
    {

        // $userId = Auth::user()->id;
        // $amount = 150;
        // $detail = 'ทดสอบตัดเงิน';
        // $userCreateId = Auth::user()->id;

        $wallet = CashWallet::where('user_id', $userId)->first();
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
                'msg' => 'จำนวนเงินใน Wallet ไม่เพียงพอ',
                'status' => 'error',
            ];

            return $data;
        }

        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินไม่ถูกต้อง',
                'status' => 'error',
            ];

            return $data;
        }


        DB::beginTransaction();

        $code = $this->getCodeForCash();

        $withdraw = new Withdraw;
        $withdraw->user_id = $userId;
        $withdraw->amount = (string) $amount;
        $withdraw->tax = 0;
        $withdraw->amount = (string) $amount;
        $withdraw->transaction_timestamp = Carbon::now();
        $withdraw->bank_id = null;
        $withdraw->bank_account_name = null;
        $withdraw->bank_account_no = null;
        $withdraw->status = 1;
        $withdraw->detail = $detail;
        $withdraw->user_create_id = $userCreateId;
        $withdraw->code = $code;
        $withdraw->save();

        $oldBalance = $wallet->balance;
        $oldWithdraw = $wallet->withdraw;
        $newBalance = $oldBalance - $amount;

        $ts = new Transaction;
        $ts->user_id = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = $typeWithdraw ? $typeWithdraw : 'WITHDRAW';
        $ts->transaction_timestamp = Carbon::now();
        $ts->detail = $detail;
        $ts->user_create_id = $userCreateId;
        $ts->user_approve_id = 1;
        $ts->code = $code;
        $ts->withdraw_id = $withdraw->id;
        $ts->save();

        $newWithdraw = $oldWithdraw + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->withdraw = (string) $newWithdraw;
        $wallet->save();

        $withdraw->status = 1;
        $withdraw->user_approve_id = Auth::user()->id;
        $withdraw->approved_at = Carbon::now();
        $withdraw->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการถอนเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    // public function withdrawCashWithTax()
    public function withdrawCashWithTax($userId, $amount, $detail, $userCreateId, $tax)
    {

        // $userId = Auth::user()->id;
        // $amount = 150;
        // $detail = 'ทดสอบตัดเงิน';
        // $userCreateId = Auth::user()->id;

        $wallet = CashWallet::where('user_id', $userId)->first();
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
                'msg' => 'จำนวนเงินใน Wallet ไม่เพียงพอ',
                'status' => 'error',
            ];

            return $data;
        }

        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินไม่ถูกต้อง',
                'status' => 'error',
            ];

            return $data;
        }


        DB::beginTransaction();

        $code = $this->getCodeForCash();

        $withdraw = new Withdraw;
        $withdraw->user_id = $userId;
        $withdraw->amount = (string) $amount;
        $withdraw->tax = $tax;
        $withdraw->amount = (string) ($amount - $tax);
        $withdraw->transaction_timestamp = Carbon::now();
        $withdraw->bank_id = null;
        $withdraw->bank_account_name = null;
        $withdraw->bank_account_no = null;
        $withdraw->status = 1;
        $withdraw->detail = $detail;
        $withdraw->user_create_id = $userCreateId;
        $withdraw->code = $code;
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
        $ts->code = $code;
        $ts->withdraw_id = $withdraw->id;
        $ts->save();

        $newWithdraw = $oldWithdraw + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->withdraw = (string) $newWithdraw;
        $wallet->save();

        $withdraw->status = 1;
        $withdraw->user_approve_id = Auth::user()->id;
        $withdraw->approved_at = Carbon::now();
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
    public function depositCoin($userId, $amount, $detail, $userCreateId, $typeDeposit)
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

        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวน Coin ไม่ถูกต้อง',
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
        $code = $this->getCodeForCoin();

        $deposit = new CoinDeposit;
        $deposit->user_id = $userId;
        $deposit->amount = (string) $amount < 0 ? $amount * (-1) : $amount;
        $deposit->transaction_timestamp = Carbon::now();
        $deposit->detail = $detail ? $detail : 'ฝากเงินเข้า COIN - WALLET';
        $deposit->status = 1;
        $deposit->user_create_id = $userCreateId ? $userCreateId : Auth::user()->id;
        $deposit->user_approve_id = 1;
        $deposit->code = $code;
        $deposit->save();

        $tmpAmount = $amount < 0 ? $amount * (-1) : $amount;
        $oldBalance = $wallet->balance;
        $oldDeposit = $wallet->deposit;
        $newBalance = $oldBalance + $tmpAmount;

        $ts = new CoinTransaction;
        $ts->user_id = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = $typeDeposit ? $typeDeposit : 'DEPOSIT';
        $ts->transaction_timestamp = Carbon::now();
        $ts->detail = $detail ? $detail : 'ฝากเงินเข้า CASH-WALLET';
        $ts->user_create_id = $userCreateId;
        $ts->code = $code;
        $ts->user_approve_id = 1;
        $ts->deposit_id = $deposit->id;
        $ts->save();

        $newDeposit = $oldDeposit + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->deposit = $newDeposit;
        $wallet->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการฝาก Coin สำเร็จ',
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

        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวน Coin ไม่ถูกต้อง',
                'status' => 'error',
            ];

            return $data;
        }

        DB::beginTransaction();

        $code = $this->getCodeForCoin();

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
        $withdraw->code = $code;
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
        $ts->code = $code;
        $ts->withdraw_id = $withdraw->id;
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

    public function getRevenue($userId)
    {
        $toDayRevenue = Transaction::with('user')
            ->where('user_id', $userId)
            ->whereDate('transaction_timestamp', Carbon::now())
            ->where('type', '!=', 'WITHDRAW')
            ->sum('amount');


        $data = [
            'revenue' => $toDayRevenue,
        ];

        return $data;
    }

    public function getCoinRevenue($userId)
    {
        $toDayRevenue = CoinTransaction::with('user')
            ->where('user_id', $userId)
            ->whereDate('transaction_timestamp', Carbon::now())
            ->where('type', '!=', 'WITHDRAW')
            ->sum('amount');


        $data = [
            'revenue' => $toDayRevenue,
        ];

        return $data;
    }

    public function depositCompanyWallaet($userId, $amount, $detail)
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
        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินไม่ถูกต้อง',
                'status' => 'error',
            ];

            return $data;
        }

        $wallet = CompanyWallet::where('user_id', $userId,)->first();

        DB::beginTransaction();

        if ($wallet == null) {
            $wallet = new CompanyWallet;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        $deposit = new CompanyDeposit;
        $deposit->user_id = $userId;
        $deposit->amount = (string) $amount < 0 ? $amount * (-1) : $amount;
        $deposit->transaction_timestamp = Carbon::now();
        $deposit->detail = $detail ? $detail : 'ฝากเงินเข้า COMPANY - WALLET';
        $deposit->save();

        $tmpAmount = $amount < 0 ? $amount * (-1) : $amount;
        $oldBalance = $wallet->balance;
        $oldDeposit = $wallet->deposit;
        $newBalance = $oldBalance + $tmpAmount;

        $ts = new CompanyTransaction;
        $ts->user_id = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = 'DEPOSIT';
        $ts->transaction_timestamp = Carbon::now();
        $ts->detail = $detail ? $detail : 'ฝากเงินเข้า COMPANY-WALLET';
        $ts->save();

        $newDeposit = $oldDeposit + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->deposit = (string) $newDeposit;
        $wallet->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการฝากเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    public function createCashWallet($userId)
    {
        $cashWallet = CashWallet::where('user_id', $userId)->first();

        if ($cashWallet == null) {
            $wallet = new CashWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return true;
    }

    public function createCoinWallet($userId)
    {

        $wallet = CoinWallet::where('user_id', $userId)->first();

        if ($wallet == null) {
            $wallet = new CoinWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return true;
    }

    public function getCodeForCash()
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

        $rand = rand(1, 9);
        if ($lastest_code1 == null && $lastest_code2 == null) {
            $current_code = $search_code . '0001' . $rand;

            return $current_code;
        }

        if ($lastest_code1 != null && $lastest_code2 != null) {
            $code1 = substr($lastest_code1, 0, -1);;
            $num1 = (int) substr($code1, -3);

            $code2 = substr($lastest_code2, 0, -1);;
            $num2 = (int) substr($code2, -3);

            if ($num1 > $num2) {
                $code = $lastest_code1->code;
            } else {
                $code = $lastest_code2->code;
            }
        } else if ($lastest_code1 != null && $lastest_code2 == null) {
            $code1 = substr($lastest_code1, 0, -1);;
            $num1 = (int) substr($code1, -3);
            $code = $lastest_code1->code;
        } else if ($lastest_code1 == null && $lastest_code2 != null) {
            $code2 = substr($lastest_code2, 0, -1);;
            $num1 = (int) substr($code2, -3);
            $code = $lastest_code2->code;
        } else {

            $current_code = $search_code . '0001' . $rand;

            return $current_code;
        }

        // $code = $lastest_code->code;

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

    public function getCodeForCoin()
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

        $search_code =  'B' . $year . $month . $day;

        // return $search_code;

        $lastest_code1 = CoinDeposit::where('code', 'LIKE', $search_code . '%')->orderBy('code', 'desc')->first();
        $lastest_code2 = CoinWithdraw::where('code', 'LIKE', $search_code . '%')->orderBy('code', 'desc')->first();

        $rand = rand(1, 9);
        if ($lastest_code1 == null && $lastest_code2 == null) {
            $current_code = $search_code . '0001' . $rand;

            return $current_code;
        }

        if ($lastest_code1 != null && $lastest_code2 != null) {
            $code1 = substr($lastest_code1, 0, -1);;
            $num1 = (int) substr($code1, -3);

            $code2 = substr($lastest_code2, 0, -1);;
            $num2 = (int) substr($code2, -3);

            if ($num1 > $num2) {
                $code = $lastest_code1->code;
            } else {
                $code = $lastest_code2->code;
            }

        } else if ($lastest_code1 != null && $lastest_code2 == null) {
            $code1 = substr($lastest_code1, 0, -1);;
            $num1 = (int) substr($code1, -3);
            $code = $lastest_code1->code;
        } else if ($lastest_code1 == null && $lastest_code2 != null) {
            $code2 = substr($lastest_code2, 0, -1);;
            $num1 = (int) substr($code2, -3);
            $code = $lastest_code2->code;
        } else {

            $current_code = $search_code . '0001' . $rand;

            return $current_code;
        }

        // $code = $lastest_code->code;

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

    public function saveLevelState($userId){

    }
}
