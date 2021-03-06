<?php

namespace App\Http\Controllers\Mobile;

use App\Models\User;
use App\Models\Alumni;
use App\Models\WaBlast;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    function getAlumni()
    {
        $alumni = (new Alumni)->with('user');
        if(isset($_GET['nras']))
        {
            $nras = explode(',',$_GET['nras']);
            $alumni = $alumni->whereNotIn('NRA',$nras);
        }

        if(isset($_GET['id']))
        {
            $alumni = $alumni->whereIn('id',$_GET['id']);
        }

        $alumni = $alumni->where('approval_status','approved')->get();

        return response()->json(['data' => $alumni], 200);
    }

    function getDetailAlumni($id)
    {
        $alumni = Alumni::with(['skills', 'user','user.user_approves.user.alumni'])->find($id);

        return response()->json(['data' => $alumni], 200);
    }

    function searchAlumni($key)
    {
        $alumni = Alumni::where('name', 'like', "%$key%")->get();

        return response()->json(['data' => $alumni], 200);
    }

    function approveAlumni($id)
    {
        $alumni = Alumni::find($id);

        $new_alumni = $alumni->update([
            "approval_status" => "approved",
            "approval_by" => "admin"
        ]);

        $alumni->user->email_verified_at = date('Y-m-d H:i:s');

        if ($new_alumni && $alumni->user->update()) {
            WaBlast::send($alumni->user->email, "Selamat $alumni->name, data anda telah berhasil diverifikasi. Nomor Registrasi Anggota (NRA) IKARHOLAZ anda adalah $alumni->NRA. Silakan login untuk melengkapi data pendukung, juga menikmati fitur-fitur aplikasi IKARHOLAZ MBOYZ. Klik https://bit.ly/app-ika12");
            return response()->json(['message' => "success to approve alumni"], 200);
        }

        return response()->json(['message' => "failed to approve alumni"], 409);
    }

    function deleteAlumni($id)
    {
        $alumni = Alumni::find($id);
        $user = User::find($alumni->user_id);
        $alumni->delete();
        $user->delete();

        if ($alumni) {
            return response()->json(['message' => "success to delete"], 200);
        }

        return response()->json(['message' => "failed to delete"], 409);
    }
}
