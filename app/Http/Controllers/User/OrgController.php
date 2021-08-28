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

    public function uplineListInfo(Request $request){
        $user = User::where('id', $request->input('id'))->first();
        if($user){
            return response()->json($user);
        }else{
            return response()->json([], 400);
        }
    }

    public function uplineList(Request $request){
        // dd($request->all());
        $username = $request->input('username');
        $start = $request->input('start');
        $end = $request->input('end');
        $users = User::with('childrenUpline')
            ->where('username', $username);
        if($start !== '' && $start !== null){
            $users = $users->whereDate('created_at', '>=', date("Y-m-d",strtotime($start)));
        }

        if($end !== '' && $end !== null){
            $users = $users->whereDate('created_at', '<=', date("Y-m-d",strtotime($end)));
        }


        $users = $users->orderBy('position_space','ASC')->get();
        // return $users;
        $data = $this->responsiteData($users, true);
        return response()->json($data);
    }

    private function responsiteData($users, $first=false, $parent_id=0){
        $data_set = [];
        foreach ($users as $key => $value) {
            $data_set[] = [
                "id" => $value->id,
                "parent_id" => $parent_id,
                "name"=> $value->firstname.' '.$value->lastname,
                "title"=> $value->firstname.' '.$value->lastname,
                "avatar"=> $value->avatar,
                "level"=> $value->username,
                "level"=> $value->level,
                "username"=> $value->username,
                "position" => $value->position_space,
                "empty"=> false,
                "children" => $this->responsiteData($value->getAttribute('childrenUpline'), false, $value->id)
            ];
        }
        if(!$first){
            if(count($data_set) == 0){
                $data_set[] = [
                    "id" => null,
                    "parent_id" => $parent_id,
                    "name"=> '',
                    "title"=> '<button type="button" class="btn btn-primary">Add</button>',
                    "avatar"=> '',
                    "level"=> '',
                    "level"=> '',
                    "username"=> '',
                    "position" => 'left',
                    "empty"=> true,
                ];
                $data_set[] = [
                    "id" => null,
                    "parent_id" => $parent_id,
                    "name"=> '',
                    "title"=> '<button type="button" class="btn btn-primary">Add</button>',
                    "avatar"=> '',
                    "level"=> '',
                    "level"=> '',
                    "username"=> '',
                    "position" => 'right',
                    "empty"=> true,
                ];
            }
            if(count($data_set) == 1){
                $posi = 'left';
                if($data_set[0]['position'] == 'left'){
                    $posi = 'right';
                }
                $data_set[] = [
                    "id" => null,
                    "parent_id" => $parent_id,
                    "name"=> '',
                    "title"=> '<button type="button" class="btn btn-primary">Add</button>',
                    "avatar"=> '',
                    "level"=> '',
                    "level"=> '',
                    "username"=> '',
                    "position" => $posi,
                    "empty"=> true,
                ];
                // $data_set[] = [];
            }
        }else{
            if(count($data_set)>0){
                $data_set = $data_set[0];
            }
        }
        return $data_set;
    }
}
