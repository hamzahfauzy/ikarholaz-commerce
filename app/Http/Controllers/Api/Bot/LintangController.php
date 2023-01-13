<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\User;
use App\Models\WaBlast;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LintangController extends Controller
{
    //
    public function getNra(Request $request)
    {
        $phone = str_replace('+','',$request->phone);
        $user  = User::where('email','LIKE', '%'.$phone.'%')->first();
        if(empty($user))
        {
            return response()->json([
                'status' => 'fail',
                'message' => "Maaf, Nomor WA tidak ditemukan."
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $user->alumni->NRA
        ]);
    }

    public function sendOtp(Request $request)
    {
        $phone = str_replace('+','',$request->phone);
        $user  = User::where('email','LIKE', '%'.$phone.'%')->first();
        if(empty($user))
        {
            return response()->json([
                'status' => 'fail',
                'message' => "Maaf, Nomor WA tidak ditemukan."
            ], 400);
        }

        if(!$user->alumni)
        {
            return response()->json([
                'status' => 'fail',
                'message' => "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut."
            ], 400);
        }
        
        $otp     = mt_rand(111111,999999);
        $message = 'Berikut adalah kode OTP anda '.$otp;
        WaBlast::webisnisSendNoSender($phone, $message);
        return response()->json([
            'status' => 'success',
            'data'   => [
                'phone' => $request->phone,
                'otp'   => $otp,
                'nra'   => $user->alumni->NRA
            ]
        ]);
    }
}
