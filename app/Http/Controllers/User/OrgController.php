<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrgController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        return view("user.org");
    }

    public function indexUserList(Request $request){
        $users = new User();
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

    public function create(Request $request){
        return view("user.create");
     }

     public function createUser(Request $request){

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users']
        ]);

        if (!$validator->fails()) {
            $user = new User();
            $user = $user->fill($request->all());
            if($user->save()){
                return redirect()->route('memberView');
            }
        }

        return back()
            ->withErrors($validator)
            ->withInput();
    }
}
