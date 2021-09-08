<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MLM\IndexController;
use App\Models\BankModel;
use App\Models\ProductModel;
use App\Models\User;
use App\Thaibulksms\SMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(SMS $sms)
    {
        $this->sms = $sms;
        $this->middleware('auth');
    }

    public function test()
    {
        // $this->sentSms('0861415526', 'usernamedsafdsa', 'passwordddddd');

        return redirect()->route('memberView')->with('modal', '<h4>ชื่อผู้ใช้: ffffff </h4><h4>สมัครสมบูรณ์ ดูรหัสใน SMS</h4>');
    }



    public function index()
    {
        return view("user.index");
    }

    public function indexUserList(Request $request)
    {
        // $users = new User();
        // // where filter
        // if ($request->input('username') !== '' && $request->input('username') !== null) {
        //     $username = $request->input('username');
        //     // $users = $users->orWhere('username', 'like', "%{$username}%");
        //     $users = $users->orWhere('id', $username);
        // }
        // if ($request->input('display_name') !== '' && $request->input('display_name') !== null) {
        //     $display_name = $request->input('display_name');
        //     $users = $users->orWhere('firstname', 'like', "%{$display_name}%");
        //     $users = $users->orWhere('lastname', 'like', "%{$display_name}%");
        // }
        // $start = $request->input('start_date');
        // $end = $request->input('end_date');
        // if ($start !== '' && $start !== null) {
        //     $users = $users->whereDate('created_at', '>=', date("Y-m-d", strtotime($start)));
        // }
        // if ($end !== '' && $end !== null) {
        //     $users = $users->whereDate('created_at', '<=', date("Y-m-d", strtotime($end)));
        // }

        // $users = $users->orderBy('created_at', 'desc');
        // $users = $users->paginate($request->input('length'), ['*'], 'page', ($request->input('start') / $request->input('length')) + 1);
        // // dd($request->input('length'), ['*'], 'page', ($request->input('start')/$request->input('length'))+1);

        // user id list under
        $user_id_list_model = User::with('childrenUpline')->where('id', auth()->user()->id)->get();
        $user_id_list = $this->listIdChile($user_id_list_model);

        $users = new User();
        if(count($user_id_list)>0){
            $users = $users->whereIn('id', $user_id_list);
        }
        $users = $users->where(function ($users) use($request) {
            if ($request->input('username') !== '' && $request->input('username') !== null) {
                $username = $request->input('username');
                $users = $users->orWhere('id', $username);
            }
            if ($request->input('display_name') !== '' && $request->input('display_name') !== null) {
                $display_name = $request->input('display_name');
                $users = $users->orWhere('firstname', 'like', "%{$display_name}%");
                $users = $users->orWhere('lastname', 'like', "%{$display_name}%");
            }
        });

        $users = $users->with('childrenUpline')->first();
        if(!$users){
            return response()->json([
                "draw" => $request->input('draw'),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
        }
        // $list_user_id = [];
        $list_id = $this->listIdChile([$users]);

        if(count($list_id)>0){
            $users = new User();
            $users = $users->whereIn('id', $list_id);
            $users = $users->orderBy('created_at', 'desc');
            $users = $users->paginate($request->input('length'), ['*'], 'page', ($request->input('start') / $request->input('length')) + 1);
        }
        // dd($users);
        // return $users;
        $data = [];
        foreach ($users->items() as $key => $value) {
            $text_thai_space = '-';
            if($value->position_space == 'left'){
                $text_thai_space = 'ซ้าย';
            }
            if($value->position_space == 'right'){
                $text_thai_space = 'ขวา';
            }
            $data[] = [
                "date" => date('d-m-Y', strtotime($value->created_at)),
                "username" => $value->username,
                "id" => $value->id,
                "name" => $value->firstname . " " . $value->lastname,
                "invite" => $value->user_invite_id,
                "upline" => $value->user_upline_id,
                "position" => $text_thai_space,
                "phone_no" => $value->phone_number,
                "email" => $value->email,
                "line" => $value->line,
                "invite_count" => ($value->inviteCount() ?? 0) . ' ท่าน'
            ];
        }
        // dd($request->all());

        if(($request->input('username') == '' || $request->input('username') == null)  && ($request->input('display_name') == '' || $request->input('display_name') == null)){
            return response()->json([
                "draw" => $request->input('draw'),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
        }
        return response()->json([
            "draw" => $request->input('draw'),
            "recordsTotal" => $users->total(),
            "recordsFiltered" => $users->total(),
            "data" => $data
        ]);
    }

    public function listIdChile($data){
        $data_set = [];
        foreach ($data as $value) {
            $data_set[] = $value->id;
            if($value->childrenUpline){
                $a = $this->listIdChile($value->childrenUpline);
                foreach ($a as $v) {
                    $data_set[] = $v;
                }
            }
        }
        return $data_set;
    }

    public function create($product_id, $upline_id, $position)
    {
        $product = ProductModel::where('id', $product_id)->first();
        if (!$product) {
            abort(404);
        }
        if (auth()->user()->wallet) {
            if ((int)auth()->user()->wallet->balance < $product->price_num) {
                return redirect(route('itemView', ['position' => $position, 'upline_id' => $upline_id]))->with('modal', 'เงินของคุณไม่เพียง พอ กรุณาฝาก');
            }
        } else {
            // ไม่เคยฝากเงินเลย
            return redirect(route('itemView', ['position' => $position, 'upline_id' => $upline_id]))->with('modal', 'กรุณาฝากเงิน');
        }
        $user_upline = User::findOrFail($upline_id);
        $banks = BankModel::where('active', true)->orderBy('order', 'asc')->get();
        return view("user.create", compact('banks', 'product_id', 'user_upline', 'position'));
    }

    public function createUserFindInvite(Request $request)
    {
        // return response()->json();
        $user = User::where('id', $request->input('invite_id'))->first();
        if ($user) {
            return $user->firstname . ' ' . $user->lastname;
        }
        return 'ไม่พบข้อมูล';
    }

    public function createUser(Request $request, IndexController $indexController)
    {
        $validator = Validator::make($request->all(), [
            // 'phone_number' => ['required', 'string', 'max:255', 'unique:users'],
            // 'phone_number' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['min:6', 'required_with:password_confirmation', 'same:password_confirmation'],
            'password_confirmation' => ['min:6'],
            'user_invite_id' => ['required', 'string', 'max:255']
        ]);

        if (!$validator->fails()) {
            $user = new User();
            $user = $user->fill($request->all());
            $user->password = Hash::make($request->input('password'));
            $user->username = $request->input('phone_number');
            $user->send_email = $request->input('email');
            $user->send_phone_number = $request->input('phone_number');
            $user->avatar = '/assets/images/brands/slack.png';
            $user->first_time_login = 'true';
            // $user->product_id = $product_id;
            if ($user->save()) {
                // ตัดเงิน
                // findpackage
                $proeuct = ProductModel::where('id', $user->product_id)->first();
                $price = 0;
                if ($proeuct) {
                    $price = $proeuct->price_num;
                }
                // ส่งไปตัดเงิน
                $this->createCashWallet($user->id);
                $this->createCoinWallet($user->id);
                $indexController->withdrawCash(auth()->user()->id, $price, 'สมัครสมาชิก', auth()->user()->id);
                // คิดคะแนน
                $indexController->CreateNewUser($user->user_upline_id, auth()->user()->id, $user->id);


                // sent sms
                $this->sentSms($request->input('phone_number'), $user->id, $request->input('password'));
                // return redirect()->route('memberView')->with('modal', ['username'=>$user->username]);
                return redirect()->route('memberView')->with('modal', '<h4>ชื่อผู้ใช้: ' . $user->username . ' </h4><h4>สมัครสมบูรณ์ ดูรหัสใน SMS</h4>');
            }
        }

        return back()
            ->withErrors($validator)
            ->withInput();
    }

    public function listItem($upline_id, $position)
    {

        if (auth()->user()->product->level == 'S' || auth()->user()->product->level == 'M') {
            return redirect(route('orgView'))->with('modal', ' ต้องการอัพเกรด package ให้ติดต่ออัพไลน์ D หรือ SD ขึ้นไป');
        }

        $user_upline = User::findOrFail($upline_id);
        // check left , right
        if ($position == 'left' || $position == 'right') {
        } else {
            abort(404);
        }
        $products = ProductModel::where('status', true)->orderBy('order', 'asc')->get();
        return view('user.items', compact('products', 'upline_id', 'position'));
    }


    private function sentSms($phone_number, $username, $password)
    {
        $sms = new SMS('23b000d1c193cf62d5411f85c4741f54', '0317b8afaec3f4543ffca994b30c6526');
        $body = [
            'msisdn' => $phone_number,
            'message' => 'สำหรับ ใช้งาน www.happinesscorp.me  username: ' . $username . ' รหัส: ' . $password,
        ];
        $res = $sms->sendSMS($body);
        return $res;
    }
}
