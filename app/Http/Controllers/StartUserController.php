<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StartUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function startChangePassIndex(){
        
        return view ('profile.first-change-pass');
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
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200);
        } else {
            $user = User::find($currentUserId);
            $user->password = Hash::make($request->get('password'));
            $user->first_time_login = "false";
            $user->update();
            if ($user) {
                
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); 
                
            } else {
                
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200);
            }
        }
    }
}
