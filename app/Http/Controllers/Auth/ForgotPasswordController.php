<?php

namespace App\Http\Controllers\Auth;

use App\Models\SmsOTP;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Exception\ClientException;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    use SendsPasswordResetEmails;

    public function forgotPasswordIndex(){

        return view("auth.passwords.recovery");
    }

    public function generateOTP(Request $req){

        $phoneNumber = $req->phoneNumber;
        // $userData = User::where('phone_number',$phoneNumber)->first();
        $userData = User::where('id',$phoneNumber)->first();

        if ($userData != null) {

                $client = new Client();

                $res = $client->request('POST', 'https://otp.thaibulksms.com/v1/otp/request', [
                    'form_params' => [
                        'key' => '1709486729410897',
                        'secret' => 'b45ad47a774aa51e81797cad21e60bf8',
                        'msisdn' => $userData->phone_number
                    ]
                ]);

                $resBody = json_decode($res->getBody());
                if($res->getStatusCode() && $resBody->data->status == 'success'){

                    return response()->json([
                        'isSuccess' => true,
                        'status'=>200,
                        'message'=> 'success',
                        'otp_token' => $resBody->data->token
                    ]);

                }else{

                    return response()->json([
                        'isSuccess' => false,
                        'status'=>500,
                        'errors'=> 'SMS Service error please try again.'
                    ]);
                }


        }else{
            return response()->json([
                'isSuccess' => false,
                'status'=>404,
                'phoneNumberErr'=> 'ไม่พบหมายเลขโทรศัพท์มือถือนี้.'
            ]);
        }

    }

    public function verifyOTP(Request $req){

        //$phoneNumber = $req->phoneNumber;
        //$userData = User::where('phone_number',$phoneNumber)->first();

        $client = new Client();

        try {
            $res = $client -> request('POST', 'https://otp.thaibulksms.com/v1/otp/verify', [
                'form_params' => [
                    'key' => '1709486729410897',
                    'secret' => 'b45ad47a774aa51e81797cad21e60bf8',
                    'token' => $req->otp_token,
                    'pin' => $req->otp_pin,
                ],
                //'http_errors' => false,
            ]);
        } catch (ClientException $e) {
            echo $e->getRequest();
            echo $e->getResponse();
        }

        $resBody = json_decode($res->getBody());

        if($resBody->data->status == 'success' && $resBody->data->message == 'Code is correct.'){

            return response()->json([
                'isSuccess' => true,
                'status'=>200,
                'message'=> 'Verify success.',
            ]);

        }else{

            return response()->json([
                'isSuccess' => false,
                'status'=>500,
                'errors'=> 'Somethings worng.'
            ]);

       }
    }

    public function changePassword(Request $request) {

        $phoneNumber = $request->get('phone_number');
        // $userData = User::where('phone_number', $phoneNumber)->first();
        $userData = User::where('id', $phoneNumber)->first();

        $request -> validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $userData->password = Hash::make($request->get('password'));
        $userData->update();
        if($userData) {
            return response()->json([
                'isSuccess' => true,
                'message' => "เปลี่ยนรหัสผ่านเรียบร้อย"
            ], 200);

        } else {
            return response()->json([
                'isSuccess' => true,
                'message' => "Something went wrong!"
            ], 500);
        }
    }


}

