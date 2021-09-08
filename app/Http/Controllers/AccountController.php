<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateIndex(){
        
        $userId = Auth::user()->id;
        $userData = User::where('id', $userId)->first();
      
        return view("profile.update", ['userData' => $userData]);
    }

    public function accountProfileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
    
            'send_zip_code'=>'numeric|max:99999',
            'email'=>'email|max:191',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'isSuccess' => false,
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }
        $currentUserId = Auth::user()->id;
        $userData = User::find($currentUserId);
        
        if($userData){
            $form_user = array(
                'nationality' => $request->nationality,
                'sex' => $request->sex,
                'line' => $request->line,
                'fb' => $request->fb,
                'ig' => $request->ig,
                'prefix_name'=> $request->prefix_name,
                'send_address' => $request-> send_address,
                'send_province' => $request->send_province,
                'send_sub_district' => $request->send_sub_district,
                'send_district' => $request->send_district,
                'send_zip_code' => $request->send_zip_code,
                'send_email' => $request->send_email,
                'send_phone_number' => $request->send_phone_number,
            );
            
            
            if($userData->update($form_user)){
                return response()->json([
                    'isSuccess' => true,
                    'status'=>200,
                    'Message'=>'แก้ไขข้อมูลเรียบร้อย'
                ]);
            }else{
                return response()->json([
                    'isSuccess' => false,
                    'status'=>500,
                    'Message'=>'เกิดข้อผิดพลาดโปรดลองใหม่อีกครั้ง'
                ]);
            }

        }else
        {
            return response()->json([
                'isSuccess' => false,
                'status'=>404,
                'Message'=>'Not Found.'
            ]);
        }
        
        
        return view("profile.update", ['userData' => $userData]);
    }

    public function changePassIndex(){

      
        return view ('profile.changepass');
    }

    public function changePassword(Request $request){
        
        $currentUserId = Auth::user()->id;
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "รหัสผ่านปัจจุบันไม่ถูกต้อง"
            ], 200); // Status code
        } else {
            $user = User::find($currentUserId);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "เปลี่ยนรหัสผ่านเรียบร้อย"
                ], 200); 
                
            } else {
                
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "เกิดข้อผิดพลาด!"
                ], 200);
            }
        }
    }

}
