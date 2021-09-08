<?php

namespace App\Http\Controllers\Upgrade;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use App\Models\UpgradeModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MLM\IndexController;

class UpgradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function memberUpgrade()
    {

        if (auth()->user()->product->level == 'S' || auth()->user()->product->level == 'M') {
            return redirect(route('orgView'))->with('modal', ' ต้องการอัพเกรด package ให้ติดต่ออัพไลน์ D หรือ SD ขึ้นไป');
        }

        return view("upgrade.upgrade");
    }

    public function productList()
    {

        $product = ProductModel::where('status', true)->orderBy('order')->get();
        $data_set = [];
        foreach ($product as $key => $value) {
            $data_set[] = [
                'image' => '<img src="//admin.happinesscorp.me/' . $value->image . '" width="70%">',
                'code' => $value->code,
                'name' => $value->name . ' ' . $value->price,
                'point' => number_format($value->point),
                'price' => number_format($value->price_num, 2),
                'amount' => ' <input class="form-control" type="text" data-id="' . $value->id . '">',
                'tool' => '<button type="submit" class="btn btn-primary w-md btn-action-slect-package btn-package-' . $value->level . '" data-id="' . $value->id . '">เลือก</button>'
            ];
        }
        $data_set = [
            "data" => $data_set
        ];

        return response()->json($data_set);
    }

    public function checkUser(Request $request)
    {
        if ($request->has('username')) {
            // $user = User::with('product')->where('username', $request->input('username'))->first();
            $user = User::with('product')->where('id', $request->input('username'))->first();
            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'คุณ ' . $user->firstname . ' ' . $user->lastname,
                    'data' => $user
                ]);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'ค้นหาไม่เจอ',
            'data' => null
        ]);
    }

    public function upgradeSave(Request $request, IndexController $indexController)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'sent_type' => 'required',
            'package_id' => 'required',
        ]);
        // check money
        if (auth()->user()->wallet) {
            $product = ProductModel::where('id', $request->input('package_id'))->first();
            if (!$product) {
                abort(404);
            }
            if ((int)auth()->user()->wallet->balance < $product->price_num) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'เงินของคุณไม่เพียงพอ กรุณาฝากเงิน',
                    'data' => null
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'กรุณาฝากเงิน',
                'data' => null
            ]);
        }

        if (!$validator->fails()) {
            $upgrade = new UpgradeModel();
            $upgrade->fill($request->all());
            // dd($upgrade);
            if ($upgrade->save()) {
                // calulat point
                $user = User::where('id', $upgrade->user_id)->first();
                $user->product_id = $request->input('package_id');
                $user->save();
                // ตัดเงิน
                // findpackage
                $proeuct = ProductModel::where('id', $user->product_id)->first();
                $price = 0;
                if($proeuct){
                    $price = $proeuct->price_num;
                }
                // ส่งไปตัดเงิน
                $indexController->withdrawCash(auth()->user()->id, $price,'อัพเกรดแพ็ค', auth()->user()->id);
                try {
                    $indexController->CreateNewUser($user->user_invite_id, $user->user_upline_id,$user->user_invite_id,$user->id);
                } catch (\Throwable $th) {

                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'อัพเกรด เสร็จสิ้น',
                    'data' => null
                ]);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'ไม่สามารถบันทึกได้',
            'data' => null
        ]);
    }
}
