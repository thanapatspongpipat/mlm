<?php

namespace App\Http\Controllers;

use App\Models\AdditionalFunction;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Withdraw;
use App\Models\CompanyBankAccount;
use App\Models\CashWallet;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    //

    public function index()
    {
        $userId = Auth::user()->id;
        if($userId == null){
            return abort(404);
        }

        $bankAccount = BankAccount::with('bank')->where('user_id', $userId)->first();

        if($bankAccount == null){
            $bankAccount = new BankAccount;
            $bankAccount->user_id = $userId;
            $bankAccount->save();
        }

        $cashWallet = CashWallet::where('user_id', $userId)->first();

        if ($cashWallet == null) {
            $wallet = new CashWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return view('withdraw.index', compact('bankAccount', 'cashWallet'));
    }

    public function editBankIndex()
    {

        $userId = Auth::user()->id;
        $bankAccount = BankAccount::with('bank')->where('user_id', $userId)->first();

        $banks = Bank::where('id', '!=' , $bankAccount->bank_id)->get();
        return view('withdraw.edit-bank', compact('bankAccount', 'banks'));
    }

    public function storeBank(Request $req)
    {
        $bankId = $req->bank;
        $accountName = $req->accountName;
        $accountNo = $req->accountNo;
        $branch = $req->branch;
        // return $req->all();
        $userId = Auth::user()->id;
        if($userId == null){
            return abort(404);
        }
        // return $req->all();

        DB::beginTransaction();
        $bankAccount = BankAccount::where('user_id', $userId)->first();
        // return $bankAccount;
        if($bankAccount == null){
            $bankAccount = new BankAccount;
        }

        $bankAccount->bank_id = 1;
        $bankAccount->account_name = $accountName;
        $bankAccount->account_no = $accountNo;
        $bankAccount->branch = $branch;
        $bankAccount->save();
        DB::commit();

        return redirect()->route('withdraw.index')->with('success', 'บันทึกสำเร็จ!');
    }

    public function store(Request $req)
    {

        $bankAccountId = $req->bankAccountId;
        $amount = $req->amount;
        $userId = Auth::user()->id;

        $cashWallet = CashWallet::where('user_id', $userId)->first();
        $bankAccount = BankAccount::find($bankAccountId);
        // return $bankAccount;
        $oldBalance = $cashWallet->balance;
        // return $amount ;
        // $oldWithdraw = $cashWallet->withdraw;
        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินไม่ถูกต้อง',
                'status' => 'error',
            ];

            return $data;
        }

        if($amount > $oldBalance){
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินใน Wallet ไม่เพียงพอ',
                'status' => 'error',
            ];

            return $data;
        }
        if($bankAccount == null || $bankAccount->bank_id == null || $bankAccount->bank_account_no){
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'ไม่พบข้อมูลธนาคาร',
                'status' => 'error',
            ];

            return $data;
        }

        $wd = Withdraw::where('user_id', $userId)->where('status', 0)->first();
        if ($wd != null) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'ทำรายการถอนเงินได้ครั้งละ 1 รายการเท่านั้น',
                'status' => 'error',
            ];

            return $data;
        }

        $bankId = $bankAccount->bank_id;
        $accountName = $bankAccount->account_name;
        $accountNo = $bankAccount->account_no;

        // $newBalance = $oldBalance - $amount;

        DB::beginTransaction();

        $code = $this->getCodeForCash();

        $withdraw = new Withdraw;
        $withdraw->user_id = $userId;
        $withdraw->amount = (string) $amount;
        $withdraw->transaction_timestamp = Carbon::now();
        $withdraw->detail = 'ถอนเงินเข้าบัญชีธนาคาร';
        $withdraw->bank_id = $bankId;
        $withdraw->bank_account_name = $accountName;
        $withdraw->bank_account_no = $accountNo;
        $withdraw->status = 0;
        $withdraw->user_create_id = $userId;
        $withdraw->code = $code;
        $withdraw->save();


        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการถอนเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    public function show()
    {
        $userId = Auth::user()->id;
        return datatables()->of(
            Withdraw::query()->with('bank')->where('user_id', $userId)->orderBy('id', 'desc')
        )->toJson();
    }

    public function getBalance(){

        $userId = Auth::user()->id;

        $cashWallet = CashWallet::where('user_id', $userId)->first();

        return $cashWallet->balance;
    }
}
