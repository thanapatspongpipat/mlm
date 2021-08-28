<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BankModel;
use App\Models\ProductModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        return view("user.index");
    }

    public function indexUserList(Request $request){
        $users = new User();
        // dd($request->all());
        // where filter
        if($request->input('username') !== '' && $request->input('username') !== null){
            $username = $request->input('username');
            $users = $users->orWhere('username', 'like', "%{$username}%");
        }
        if($request->input('display_name') !== '' && $request->input('display_name') !== null){
            $display_name = $request->input('display_name');
            $users = $users->orWhere('firstname', 'like', "%{$display_name}%");
            $users = $users->orWhere('lastname', 'like', "%{$display_name}%");
        }
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        if($start !== '' && $start !== null){
            $users = $users->whereDate('created_at', '>=', date("Y-m-d",strtotime($start)));
        }
        if($end !== '' && $end !== null){
            $users = $users->whereDate('created_at', '<=', date("Y-m-d",strtotime($end)));
        }

        $users = $users->orderBy('created_at', 'desc');
        $users = $users->paginate($request->input('length'), ['*'], 'page', ($request->input('start')/$request->input('length'))+1 );
        // dd($request->input('length'), ['*'], 'page', ($request->input('start')/$request->input('length'))+1);

        $data = [];
        foreach ($users->items() as $key => $value) {
            $data[] = [
                "date"=> date('d-m-Y', strtotime($value->created_at)),
                "username"=> $value->username,
                "name"=> $value->firstname." ".$value->lastname,
                "invite"=> $value->user_invite_id,
                "upline"=> $value->user_upline_id,
                "position"=> $value->position_space,
                "phone_no"=> $value->phone_number,
                "email"=> $value->email,
                "line"=> $value->line,
            ];
        }
        return response()->json([
            "draw"=> $request->input('draw'),
            "recordsTotal"=> $users->total(),
            "recordsFiltered"=> $users->total(),
            "data"=> $data
        ]);
    }

    public function create($product_id, $upline_id, $position){
        $user_upline = User::findOrFail($upline_id);
        $banks = BankModel::where('active', true)->orderBy('order', 'asc')->get();
        return view("user.create", compact('banks', 'product_id', 'user_upline', 'position'));
     }

    public function createUserFindInvite(Request $request){
        // return response()->json();
        $user = User::where('id', $request->input('invite_id'))->first();
        if($user){
            return $user->firstname.' '.$user->lastname;
        }
        return 'ไม่พบข้อมูล';
    }

    public function createUser(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['min:6', 'required_with:password_confirmation', 'same:password_confirmation'],
            'password_confirmation' => ['min:6']
        ]);

        if (!$validator->fails()) {
            $user = new User();
            $user = $user->fill($request->all());
            $user->password = Hash::make($request->input('password'));
            // $user->product_id = $product_id;
            if($user->save()){
                return redirect()->route('memberView');
            }
        }

        return back()
            ->withErrors($validator)
            ->withInput();
    }

    public function listItem($upline_id, $position){
        $user_upline = User::findOrFail($upline_id);
        // check left , right
        if($position == 'left' || $position == 'right'){

        }else{
            abort(404);
        }
        $products = ProductModel::where('status', true)->orderBy('order', 'asc')->get();
        return view('user.items', compact('products','upline_id', 'position'));
    }
}
