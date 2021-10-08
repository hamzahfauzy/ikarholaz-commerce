<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function getAlumni()
    {
        $alumni = Alumni::with('user')->get();

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
            return response()->json(['message' => "success to approve alumni"], 200);
        }

        return response()->json(['message' => "failed to approve alumni"], 409);
    }

    function deleteAlumni($id)
    {
        $alumni = Alumni::find($id)->delete();

        if ($alumni) {
            return response()->json(['message' => "success to delete"], 200);
        }

        return response()->json(['message' => "failed to delete"], 409);
    }
}
