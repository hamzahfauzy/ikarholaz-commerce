<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Mobile\AuthController;

class OtpController extends Controller
{

    function otp(Request $request)
    {
        if($request->method() == "POST"){
            $phone = $request['phone'];
            if($phone[0] == "0")
                $phone = substr($phone,1);
            elseif($phone[0] == "+")
                $phone = substr($phone,3);

            $user = User::where('email', 'LIKE', '%'.$phone.'%')->first();

            if ($user) {
                
                // $authCtrlr = new AuthController();

                // $validate = $authCtrlr->verifyOTP($request['phone'],$request['otp']);
    
                if (Hash::check($request['token_data'], $user->password)) {
                    $user->update([
                        'password' => strtotime('now')
                    ]);

                    Auth::guard()->login($user);

                    return response()->json([
                        'status' => 'success'
                    ]);
                }

            }

            return response()->json([
                'status' => 'fail'
            ]);

                // if($validate->valid){
                //     $user->update([
                //         'password' => strtotime('now')
                //     ]);
                // }
                // else
                // {
                //     return redirect()->back()->with(["failed"=>"OTP Not Valid",'phone'=>$request['phone']]);
                // }

                // Auth::guard()->login($user);
                // return redirect("/");
    
            // }
    
            // return redirect()->back()->with(["failed"=>"data not found",'phone'=>$request['phone']]);
        }else{
            return view("auth.otp");
        }
    }
}
