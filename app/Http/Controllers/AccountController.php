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
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
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

    /*
    public function UserEdit(Request $request){

        $UserCurrentID = Auth::user()->user_id;
        
        $MemberData = Member::where('user_id',$UserCurrentID)->first();

        if ($MemberData == null){

            return redirect()->route('ActivateMemberCode');
        }
      

        if($request->isMethod('put')){
            $birthday_convert = str_replace('/', '-', $request->birthday);
            $birthday_to_use = date('Y-m-d', strtotime($birthday_convert));
            $birthday_to_sql = Carbon::parse($birthday_to_use)->subYears(543)->format('Y-m-d');

            $form_member = array(
                'nickname' => $request->nickname,
                'generation' => $request->generation,
                'year_of_study' => $request->year_of_study,
                'birthday' => $birthday_to_sql,
                'phone_number' => $request->phone_number,
                'home_phone_number' => $request->home_phone_number,
                'email' => $request->email,
                'line_id' => $request->line_id,
                'house_no' => $request->house_no,
                'address' => $request->address,
                'district' => $request->district,
                'province' => $request->province,
                'country' => $request->country,
                'zipcode' => $request->zipcode,
                'status_id' => $request->status,
                'contact_status_id' => $request->contact_status,
                'update_by' => null,
            );

            $form_office = array(
                'office_name' => $request->office_name,
                'department' => $request->department,
                'office_no' => $request->office_no,
                'address' => $request->office_address,
                'district' => $request->office_district,
                'province' => $request->office_province,
                'country' => $request->office_country,
                'zipcode' => $request->office_zipcode,
                'office_phone' => $request->office_phone,
            );

            $form_name = array(
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'title_name' => $request->title_name,
            
            );

            $form_name_current = array(
                'firstname' => $request->firstname_current,
                'lastname' => $request->lastname_current,
                'title_name' => $request->title_name_current,
            
            );

            $form_member_updateStatus = Member::where('member_id',$MemberData->member_id)->update($form_member);
            OfficeAddress::where('member_id',$MemberData->member_id)->update($form_office);
            NameData::where('member_id',$MemberData->member_id)->where('name_status_id',1)->update($form_name);
            NameData::where('member_id',$MemberData->member_id)->where('name_status_id',2)->update($form_name_current); 
          
            if($form_member_updateStatus == true ){
                alert()->success(' ', 'บันทึกข้อมูลสำเร็จ');
                return redirect()->route('profile');
                
            }
            else{
                alert()->warning('บันทึกข้อมูลไม่สำเร็จ', 'เกิดปัญหาในการบันทึกข้อมูล !');
            }
            
        }
     
        return view('user.edit',['MemberData'=> $MemberData]);
        
    }
    */
}
