<?php

namespace App\Http\Controllers\Mobile;

use App\Models\User;
use App\Models\Staff;
use App\Models\Alumni;
use App\Models\WaBlast;
use App\Notifications\UserNotification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;

class AuthController extends Controller
{

    function register(Request $request)
    {
        $user = new User();

        $new_user = $user->create([
            'name' => $request['name'],
            'email' => $request['phone'],
            'password' => Str::random(12)
        ]);

        if ($new_user) {

            $new_alumni = $new_user->alumni()->create([
                'name' => $request['name'],
                'graduation_year' => $request['graduation_year'],
            ]);

            if ($new_alumni) {

                if ($request->file('profile')) {

                    $profile = $request->file('profile')->store('profiles');

                    if ($profile) {

                        $oldPic = $new_user->alumni->profile_pic;

                        if ($oldPic) {
                            Storage::delete($oldPic);
                        }

                        $alumnis = Alumni::where('graduation_year',$new_alumni->graduation_year)->inRandomOrder()->limit(5)->get();

                        if(empty($alumnis)){
                            $new_user->update([
                                "email_verified_at" => date("Y-m-d H:i:s")
                            ]);

                            $uploaded = $new_user->alumni()->update([
                                'profile_pic' => $profile
                            ]);

                            if ($uploaded) {
                                return response()->json(['message' => 'success to create'], 200);
                            }
                        }else{

                            $uploaded = $new_user->alumni()->update([
                                'profile_pic' => $profile
                            ]);

                            
                            if ($uploaded) {
                                $notifUser = User::find($new_user->id);

                                foreach($alumnis as $alumni){
                                    $alumni->user->notify(new UserNotification($notifUser));
                                }
                                
                                return response()->json(['message' => 'success to create'], 200);
                            }
                        }
                    }
                }

                return response()->json(['message' => 'success to create'], 200);
            }
        }

        return response()->json(['message' => 'failed to create'], 409);
    }

    function login(Request $request)
    {
        if ($request['login'] == "user") {
            $user = User::where('email', $request['phone'])->first();
        } else {
            $user = Staff::where('email', $request['phone'])->first();
        }

        if ($user) {

            if($user->email_verified_at == NULL){
                return response()->json(['message' => 'user belum terkonfirmasi','error'=>true], 200);
            }

            $otp = mt_rand(1111,9999);
            $message = "Kode OTP Anda adalah $otp";

            // WaBlast::send($request['phone'], $message);
            $this->sendOTP($request['phone']);  

            $updatedUser = $user->update([
                'password' => $otp
            ]);

            if ($updatedUser) {
                return response()->json(['message' => 'success to update data'], 200);
            }

        }

        return response()->json(['message' => 'data not found'], 403);
    }

    function otp(Request $request)
    {
        $phone = $request['phone'];
        if($phone[0] == "0")
            $phone = substr($phone,0);
        elseif($phone[0] == "+")
            $phone = substr($phone,3);
        if ($request['login'] == "user") {
            $user = User::where('email', 'LIKE', '%'.$phone.'%')->with(['alumni', 'alumni.skills'])->first();
        } else {
            $user = Staff::where('email', 'LIKE', '%'.$phone.'%')->first();
        }

        if ($user) {
            $validate = $this->verifyOTP($request['phone'],$request['otp']);

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

            return response()->json(['message' => 'success to retrieve data', 'data' => $user], 200);
        }

        return response()->json(['message' => 'data not found'], 403);
    }

    private function sendMessage($recipient, $message)
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($recipient, 
                ['from' => $twilio_number, 'body' => $message] );
    }

    private function sendOTP($recipient)
    {
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($recipient,"sms");
    }

    private function verifyOTP($recipient, $code)
    {
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($code, ["to" => $recipient]);

        return $verification;
    }
}
