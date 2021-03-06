<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Mobile\AuthController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = "/";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if($request["phone"][0] == "0"){
            $request["phone"] = '+62' . substr($request['phone'],1);
        }

        $user = User::where('email', $request['phone'])->first();

        if ($user) {

            if($user->email_verified_at == NULL){
                // return redirect()->route('login')->with('failed','user belum terkonfirmasi');
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'user belum terkonfirmasi'
                ]);
            }
                
            $otp = mt_rand(111111,999999);

            if (strpos($user->name, 'ika_demo_user') !== false) 
                $otp = 1234;

            // $message = "Kode OTP Anda adalah $otp";
            // WaBlast::send($request['phone'], $message);

            // $authCtrlr = new AuthController();

            // $authCtrlr->sendOTP($request['phone']);
            
            $password = bcrypt($otp);

            $updatedUser = $user->update([
                'password' => $password
            ]);

            if ($updatedUser) {
                // if ($this->attemptLogin($request)) {
                //     return $this->sendLoginResponse($request);
                // }

                return response()->json([
                    'status'  => 'success',
                    'token_data' => $password,
                    'message' => 'user valid'
                ]);
                // return redirect()->route('otp')->with(['phone'=>$request['phone']]);
            }

        }

        return response()->json([
            'status'  => 'fail',
            'message' => 'user tidak valid'
        ]);

        // return redirect()->route('login')->with('failed','data not found');
    }
}
