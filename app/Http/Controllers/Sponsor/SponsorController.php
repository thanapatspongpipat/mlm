<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SponsorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(){
        $levels = [
                [
                    "value"=> 0,
                    "text" => 'ทุกชั้น'
                ],
                [
                    "value"=> 1,
                    "text" => '1'
                ],
                [
                    "value"=> 2,
                    "text" => '2'
                ],
                [
                    "value"=> 3,
                    "text" => '4'
                ],
                [
                    "value"=> 5,
                    "text" => '6'
                ]
            ];
        return view("sponsor.index", compact('levels'));
    }
    public function getList(Request $request){
        $member = $request->input('member');
        $level = $request->input('level');

        $users = new User();
        $users = $users->with('childrenUpline')
        ->where('username', $member)->get();

        $data = $this->setDataRemember($users,0);
        $sum = count($data);
        $c = collect($data);
        $c = $c->sortBy('level');
        if($level !== '0'){
            $c = $c->where('level', $level);
        }
        $data = [];
        foreach($c as $d){
            $data[] = $d;
        }
        return response()->json([
            "draw"=> $request->input('draw'),
            "recordsTotal"=> $sum,
            "recordsFiltered"=> count($data),
            "data"=> $data
        ]);
    }

    private function setDataRemember($data, $level){
        $data_set = [];
        foreach ($data as $key => $value) {
            $name = $value->firstname." ".$value->lastname.' ('.$value->username.')';
            $data_set[] = [
                "date"=> date('d-m-Y', strtotime($value->created_at)),
                "member"=> '<div class="d-flex flex-row bd-highlight"><i class="bx bx-home-circle icon-table-row"></i><div class="row"><h5>'.$name.'</h5><span>'.$value->username.'</span></div></div>',
                "level"=> $level
            ];
            if($value->getAttribute('childrenUpline')){
                $a = $this->setDataRemember($value->getAttribute('childrenUpline'), $level+1);
                foreach ($a as $key_a => $value_a) {
                    $data_set[] = $value_a;
                }
            }
        }
        return $data_set;
    }


}
