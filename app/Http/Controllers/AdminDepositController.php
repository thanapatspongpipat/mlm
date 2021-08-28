<?php

namespace App\Http\Controllers;

use App\Models\CashWallet;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AdminDepositController extends Controller
{

    public function index()
    {
        return view('admin-deposit.index');
    }


    public function show()
    {
        return datatables()->of(
            Deposit::query()->with('user', 'approveUser', 'cancleUser')->orderBy('created_at', 'desc')
        )->toJson();
    }

    public function store(Request $req)
    {
        $status = $req->status;
        $depositId = $req->id;

        $deposit = Deposit::find($depositId);

        if($deposit == null || $deposit->status != 0){
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'มีบางอย่างผิดพลาด กรุณาติดต่อ Admin',
                'status' => 'error',
            ];
            return $data;
        }
        DB::beginTransaction();

        if($status == 1){
                $wallet = CashWallet::where('user_id', $deposit->user_id)->first();
                $oldBalance = $wallet->balance;
                $oldDeposit= $wallet->deposit;
                $newBalance = $oldBalance + $deposit->amount;

                $ts = new Transaction;
                $ts->user_id = $deposit->user_id;
                $ts->amount = (string) $deposit->amount;
                $ts->balance = (string) $newBalance;
                $ts->type = 'DEPOSIT';
                $ts->transaction_timestamp = Carbon::now();
                $ts->detail = $deposit->detail ? $deposit->detail : 'เติมเงินเข้า CASH-WALLET';
                $ts->user_create_id = $deposit->user_create_id;
                $ts->user_approve_id = Auth::user()->id;
                $ts->save();

                $newDeposit = $oldDeposit + $deposit->amount;
                $wallet->balance = (string) $newBalance;
                $wallet->deposit = $newDeposit;
                $wallet->save();

                $deposit->status = 1;
                $deposit->user_approve_id = Auth::user()->id;
                $deposit->save();


        }else{
                $deposit->status = 2;
                $deposit->user_cancle_id = Auth::user()->id;
                $deposit->save();
        }


        DB::commit();


        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'เติมเงินสำเร็จ',
            'status' => 'success',
        ];


        return $data;
    }

}
