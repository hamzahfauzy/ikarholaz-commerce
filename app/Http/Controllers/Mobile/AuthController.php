<?php

namespace App\Http\Controllers\Mobile;

use App\Models\User;
use App\Models\Staff;
use App\Models\Alumni;
use App\Models\WaBlast;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

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
            $otp = mt_rand(1111,9999);
            $message = "Kode OTP Anda adalah $otp";

            WaBlast::send($request['phone'], $message);

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
        if ($request['login'] == "user") {
            $user = User::where('email', $request['phone'])->with(['alumni', 'alumni.skills'])->first();
        } else {
            $user = Staff::where('email', $request['phone'])->first();
        }

        if ($user) {
            if (Hash::check($request['otp'], $user->password)) {
                $user->update([
                    'password' => strtotime('now')
                ]);
                return response()->json(['message' => 'success to retrieve data', 'data' => $user], 200);
            }
        }

        return response()->json(['message' => 'data not found'], 403);
    }
}
