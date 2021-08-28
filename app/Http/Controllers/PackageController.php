<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    //

    public function index()
    {
        return view('package.index');
    }

    public function show()
    {
        // return Package::query()->orderBy('updated_at', 'desc')->get()->toJson();

        return datatables()->of(
            Package::query()->orderBy('updated_at', 'desc')
            ->orderBy('updated_at', 'desc')
        )->toJson();
    }

    public function store(Request $req)
    {
        // return $req->all();

        $id = $req->id;
        $name = $req->name;
        $price = $req->price;
        $base64_image = $req->imgbase64;

        if($id != null && Package::find($id) != null && Package::where('name', $name)->first()){

            $data = [
                'title' => 'Please try again!',
                'msg' => 'Package Name does not exist',
                'status' => 'warning',
            ];
            return $data;
        }

        DB::beginTransaction();

        if($id == null && Package::find($id) == null){
            if(Package::where('name', $name)->first()){
                $data = [
                    'title' => 'Please try again!',
                    'msg' => 'Package Name does not exist',
                    'status' => 'warning',
                ];
                return $data;
            }
            $package = new Package;
        }else{
            $package = Package::find($id);
        }

        $path = '/imgs/packages/';

        if ($base64_image != null && preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $base64_decode = base64_decode($data);
            $extension = explode('/', explode(':', substr($base64_image, 0, strpos($base64_image, ';')))[1])[1];
            $filename = strtotime(Carbon::now()) . rand(1, 100) . '.' . $extension;
            Storage::put('public'.$path . $filename, $base64_decode);
        } else {
            dd('Base64 not match');
        }


        $package->name = $name;
        $package->price = $price;
        $package->image = 'storage' . $path . $filename;
        $package->save();

        DB::commit();

        $data = [
            'title' => 'Save success!',
            'msg' => 'Save package success',
            'status' => 'success',
        ];
        return $data;

    }

    public function delete(Request $req)
    {

        $id = $req->id;

        DB::beginTransaction();

        Package::findOrFail($id)->delete();

        DB::commit();

        $data = [
            'title' => 'Remove success!',
            'msg' => 'Remove package success',
            'status' => 'success',
        ];
        return $data;
    }

}
