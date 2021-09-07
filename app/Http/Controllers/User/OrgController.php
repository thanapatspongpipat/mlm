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
        $user = User::with('product')->where('id', $request->input('id'))->first();
        if($user){
            return response()->json($user);
        }else{
            return response()->json([], 400);
        }
    }

    public function uplineListInfoarray2(Request $request){
        $user = User::with('product', 'childrenUpline')->where('id', $request->input('id'))->get();
        $d = $this->tree2Array($user->toArray());
        // dd($user);
        if($d){
            return response()->json($d);
        }else{
            return response()->json([], 400);
        }
    }
    public function uplineListInfoarray(Request $request){
        // $username = $request->input('username');
        $username = $request->input('id');
        $start = $request->input('start');
        $end = $request->input('end');
        $users = User::with('product','childrenUpline')
            // ->where('username', $username);
            ->where('id', $username);

        if($start !== '' && $start !== null){
            $users = $users->whereDate('created_at', '>=', date("Y-m-d",strtotime($start)));
        }

        if($end !== '' && $end !== null){
            $users = $users->whereDate('created_at', '<=', date("Y-m-d",strtotime($end)));
        }
        $users = $users->orderBy('position_space','ASC')->get();

        $data = $this->responsiteData($users, true);
        if(!$data){
            $data = [];
        }else{
            $data = [$data];
        }
        $d = $this->tree2Array2($data);
        return response()->json($d);
    }

    private function tree2Array2($data, $parent_id = ''){
        // dd($data);
        $dataSet = [];
        foreach ($data as $value) {
            // $value['parentId'] = '0-'.$parent_id;
            $value['parentId'] = $parent_id;
            $a = $this->tree2Array2($value['children'] ?? [], $value['id']);
            unset($value['children']);
            // // $value['id'] = '0-'.$value['id'];
            $dataSet[] = $value;
            foreach ($a as $v_a) {
                $dataSet[] = $v_a;
            }
        }
        return $dataSet;
    }

    private function tree2Array($data, $parent_id = ''){
        $dataSet = [];
        foreach ($data as $value) {
            // $value['parentId'] = '0-'.$parent_id;
            $value['parentId'] = $parent_id;
            $a = $this->tree2Array($value['children_upline'], $value['id']);
            unset($value['children_upline']);
            // $value['id'] = '0-'.$value['id'];
            $dataSet[] = $value;
            foreach ($a as $v_a) {
                $dataSet[] = $v_a;
            }
        }
        return $dataSet;
    }


    public function uplineList(Request $request){
        $username = $request->input('username');
        $start = $request->input('start');
        $end = $request->input('end');
        $users = User::with('childrenUpline')
            // ->where('username', $username);
            ->where('id', $username);

        if($start !== '' && $start !== null){
            $users = $users->whereDate('created_at', '>=', date("Y-m-d",strtotime($start)));
        }

        if($end !== '' && $end !== null){
            $users = $users->whereDate('created_at', '<=', date("Y-m-d",strtotime($end)));
        }
        $users = $users->orderBy('position_space','ASC')->get();

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
                "level_space"=> $value->product->level ?? '-',
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
                    "level_space"=> '',
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
                    "level_space"=> '',
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
                    "level_space"=> '',
                    "username"=> '',
                    "position" => $posi,
                    "empty"=> true,
                ];
            }
        }else{
            if(count($data_set)>0){
                $data_set = $data_set[0];
            }
        }
        return $data_set;
    }
}
