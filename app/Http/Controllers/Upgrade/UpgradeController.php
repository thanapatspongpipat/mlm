<?php

namespace App\Http\Controllers\Upgrade;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpgradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function memberUpgrade(){

        return view("upgrade.upgrade");
    }

    public function productList(){

        $product = ProductModel::where('status', true)->orderBy('order')->get();
        $data_set = [];
        foreach ($product as $key => $value) {
            $data_set[] = [
                'image'=> '<img src="'.asset($value->image).'">',
                'code'=> $value->code,
                'name'=> $value->name.' '.$value->price,
                'point'=> number_format($value->point),
                'price'=> number_format($value->price_num, 2),
                'amount'=> ' <input class="form-control" type="text" data-id="'.$value->id.'">',
                'tool'=> '<button type="submit" class="btn btn-primary w-md">เลือก</button>'
            ];
        }
        $data_set = [
            "data"=> $data_set
        ];

        return response()->json($data_set);
    }

}
