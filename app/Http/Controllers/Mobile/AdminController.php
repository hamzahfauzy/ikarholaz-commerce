<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function getAlumni()
    {
        $alumni = Alumni::get();

        return response()->json(['data' => $alumni], 200);
    }

    function getDetailAlumni($id)
    {
        $alumni = Alumni::with(['skills', 'user'])->find($id);

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

        if ($new_alumni) {
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
