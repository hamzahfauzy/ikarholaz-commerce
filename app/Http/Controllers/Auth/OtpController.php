<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mobile\AuthController;

class OtpController extends Controller
{

    function otp(Request $request)
    {
        if($request->method() == "POST"){
            $phone = $request['phone'];
            if($phone[0] == "0")
                $phone = substr($phone,0);
            elseif($phone[0] == "+")
                $phone = substr($phone,3);
                $user = User::where('email', 'LIKE', '%'.$phone.'%')->first();

            if ($user) {
                $authCtrlr = new AuthController();

                $validate = $authCtrlr->verifyOTP($request['phone'],$request['otp']);
    
                // if (Hash::check($request['otp'], $user->password)) {
                //     $user->update([
                //         'password' => strtotime('now')
                //     ]);
                // }
    
                if($validate->valid){
                    $user->update([
                        'password' => strtotime('now')
                    ]);
                }

                if(Auth::guard()->login($user)){
                    return redirect("/");
                }
    
            }
    
            return redirect()->back()->with("failed","data not found");
        }else{
            return view("auth.otp");
        }
    }
}
