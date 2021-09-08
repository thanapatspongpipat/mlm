<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\CashWallet;
use App\Models\Deposit;
use App\Models\CompanyBankAccount;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{

    public function index()
    {
        $userId = Auth::user()->id;
        if ($userId == null) {
            return abort(404);
        }
        $comBank = CompanyBankAccount::where('is_active', 1)->orderBy('id', 'desc')->first();
        $cashWallet = CashWallet::where('user_id', $userId)->first();

        if ($cashWallet == null) {
            $wallet = new CashWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return view('deposit.index', compact('comBank', 'cashWallet'));
    }


    public function store(Request $req)
    {

        $comBankId = $req->comBankAccount;
        $amount = $req->amount;
        $detail = $req->detail;
        $base64_image = $req->imgbase64;
        $date = $req->date;
        $time = $req->time;
        $userId = Auth::user()->id;

        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินไม่ถูกต้อง',
                'status' => 'error',
            ];

            return $data;
        }

        $dateTime = date_format(date_create($date . ' ' . $time), "Y-m-d H:i:s");

        // $storagePath  = Storage::getDriver()->getAdapter()->getPathPrefix();

        $fullpath = url('/') . '/storage/app/public/imgs/deposit/' . $userId . '/';

        $path = '/imgs/deposit/' . $userId . '/';

        DB::beginTransaction();

        if ($base64_image != null && preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $base64_decode = base64_decode($data);
            $extension = explode('/', explode(':', substr($base64_image, 0, strpos($base64_image, ';')))[1])[1];
            $filename = strtotime(Carbon::now()) . rand(1, 100) . '.' . $extension;
            Storage::put('public' . $path . $filename, $base64_decode);
        } else {
            dd('Base64 not match');
        }
        $code = $this->getCodeForCash();

        $deposit = new Deposit;
        $deposit->user_id = $userId;
        $deposit->amount = (string) $amount;
        $deposit->transaction_timestamp = Carbon::now();
        $deposit->company_bank_account_id = $comBankId;
        $deposit->slip_img =  $fullpath . $filename;
        $deposit->detail = $detail ? $detail : 'ฝากเงินเข้า CASH - WALLET';
        $deposit->status = 0;
        $deposit->user_create_id = Auth::user()->id;
        $deposit->code = $code;
        $deposit->deposit_at = $dateTime;
        $deposit->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการฝากเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    public function show()
    {
        $userId = Auth::user()->id;
        return datatables()->of(
            Deposit::query()->where('user_id', $userId)->orderBy('id', 'desc')
        )->toJson();
    }

    public function getBalance()
    {

        $userId = Auth::user()->id;

        $cashWallet = CashWallet::where('user_id', $userId)->first();

        return $cashWallet->balance;
    }

    public function approve(Request $req)
    {

        $bankAccountId = $req->bankAccountId;
        $amount = $req->amount;
        $userId = Auth::user()->id;

        $cashWallet = CashWallet::where('user_id', $userId)->first();
        $bankAccount = BankAccount::find($bankAccountId);
        // return $bankAccount;
        $oldBalance = $cashWallet->balance;
        $oldDeposit = $cashWallet->deposit;

        $bankId = $bankAccount->bank_id;
        $accountName = $bankAccount->account_name;
        $accountNo = $bankAccount->account_no;

        $newBalance = $oldBalance - $amount;

        DB::beginTransaction();

        $ts = new Transaction;
        $ts->user_id = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = 'DEPOSIT';
        $ts->transaction_timestamp = Carbon::now();
        $ts->bank_id = $bankId;
        $ts->bank_account = $accountName;
        $ts->bank_no = $accountNo;
        $ts->detail = 'ถอนเงินจาก CASH-WALLET';
        $ts->save();

        $newDeposit = $oldDeposit + $amount;
        $cashWallet->balance = (string) $newBalance;
        $cashWallet->deposit = (string) $newDeposit;
        $cashWallet->withdraw = 0;
        $cashWallet->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการถอนเงินสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }




}
