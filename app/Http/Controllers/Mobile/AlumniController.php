<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniController extends Controller
{
    function edit(Request $request)
    {
        $user = User::where('email', $request['phone'])->with(['alumni', 'alumni.skills'])->first();

        $new_user = $user->update([
            'name' => $request['name'],
            'email' => $request['new_phone'] ? $request['new_phone'] : $request['phone']
        ]);

        if ($new_user) {

            $alumni = $user->alumni()->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'graduation_year' => $request['graduation_year'],
                'date_of_birth' => $request['date_of_birth'],
                'address' => $request['address'],
                'city' => $request['city'],
                'province' => $request['province'],
                'country' => $request['country'],
                'private_email' => $request['private_email'],
                'private_phone' => $request['private_phone'],
            ]);

            if ($alumni) {

                if ($request['skills']) {
                    foreach ($request['skills'] as $value) {
                        if (isset($value['id'])) {
                            $user->alumni->skills()->where('id', $value['id'])->update(['name' => $value['name']]);
                        } else {
                            $user->alumni->skills()->create($value);
                        }
                    }
                }

                $user = User::where('email', $request['phone'])->with(['alumni', 'alumni.skills'])->first();

                return response()->json(['message' => 'success to update', 'data' => $user], 200);
            }
        }

        return response()->json(['message' => 'failed to update'], 409);
    }

    function deleteSkill($id)
    {
        $skill = Skill::find($id)->delete();

        if ($skill) {
            return response()->json(['message' => "success to delete"], 200);
        }

        return response()->json(['message' => "failed to delete"], 409);
    }

    function uploadProfile(Request $request)
    {
        $user = User::where('email', $request['phone'])->with(['alumni', 'alumni.skills'])->first();

        if ($request->file('profile')) {

            $profile = $request->file('profile')->store('profiles');

            if ($profile) {

                $oldPic = $user->alumni->profile_pic;

                if ($oldPic) {
                    Storage::delete($oldPic);
                }

                $uploaded = $user->alumni()->update([
                    'profile_pic' => $profile
                ]);

                if ($uploaded) {

                    $user = User::where('email', $request['phone'])->with(['alumni', 'alumni.skills'])->first();

                    return response()->json(['message' => "success to upload file", "data" => $user], 200);
                }
            }
        }

        return response()->json(['message' => 'failed to update'], 409);
    }
}
