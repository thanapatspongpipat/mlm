<?php

namespace App\Http\Controllers;


use App\Models\CashWallet;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;


class AdminWithdrawController extends Controller
{
    public function index()
    {
        return view('admin-withdraw.index');
    }

    public function show()
    {
        return datatables()->of(
            Withdraw::query()->with('user', 'bank')->orderBy('id', 'desc')
        )->toJson();
    }

    public function store(Request $req)
    {
        $status = $req->status;
        $withdrawId = $req->id;

        $withdraw = Withdraw::find($withdrawId);

        if ($withdraw == null || $withdraw->status != 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'มีบางอย่างผิดพลาด กรุณาติดต่อ Admin',
                'status' => 'error',
            ];
            return $data;
        }
        DB::beginTransaction();

        if ($status == 1) {
            $wallet = CashWallet::where('user_id', $withdraw->user_id)->first();
            $oldBalance = $wallet->balance;
            $oldWithdraw = $wallet->withdraw;
            $newBalance = $oldBalance - $withdraw->amount;

            $ts = new Transaction;
            $ts->user_id = $withdraw->user_id;
            $ts->amount = (string) $withdraw->amount;
            $ts->balance = (string) $newBalance;
            $ts->type = 'WITHDRAW';
            $ts->transaction_timestamp = Carbon::now();
            $ts->detail = 'ถอนเงินออกจาก CASH-WALLET';
            $ts->user_create_id = $withdraw->user_create_id;
            $ts->user_approve_id = Auth::user()->id;
            $ts->save();

            $newWithdraw = $oldWithdraw + $withdraw->amount;
            $wallet->balance = (string) $newBalance;
            $wallet->withdraw = $newWithdraw;
            $wallet->save();

            $withdraw->status = 1;
            $withdraw->user_approve_id = Auth::user()->id;
            $withdraw->save();
        } else {
            $withdraw->status = 2;
            $withdraw->user_cancle_id = Auth::user()->id;
            $withdraw->save();
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
