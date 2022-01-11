<?php

namespace App\Http\Controllers\Mobile;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Skill;
use App\Models\Alumni;
use App\Models\WaBlast;
use App\Models\Broadcast;
use App\Models\UserApprove;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AlumniController extends Controller
{

    function getNotifications($id){
        $user = User::find($id);

        return response()->json(['data' => $user->unreadNotifications], 200);
    }

    function getBroadcasts($id){
        $user = User::find($id);
        
        $broadcastsNotIn = Broadcast::whereNotIn('id',$user->broadcasts->pluck('broadcast_id'))->get();

        foreach($broadcastsNotIn as $bc){
            $bc->broadcastUser()->create(['user_id'=>$user->id]);
        }

        return response()->json(['message'=>"success","data"=>$broadcastsNotIn],200);
    }
    
    function markAsRead(Request $request){

        $user = User::find($request->user_id);

        $newUserApprove = $user->user_approves()->create([
            'friend_id'=>$request->friend_id,
            'status'=>$request->status
        ]);

        if($newUserApprove){

            $userApproves = $user->user_approves()->where('status','Diterima')->get();

            $num_of_alumni = Alumni::where('graduation_year',$user->alumni->graduation_year)->where('id','!=',$user->alumni->id)->limit(5)->count();

            if($userApproves->count() == $num_of_alumni){
                WaBlast::send($user->email, "Selamat ".$user->alumni->name.", data anda telah berhasil diverifikasi. NRA anda : ".$user->alumni->NRA.". Silakan login untuk melengkapi data Anda atau menikmati fitur-fitur aplikasi IKARHOLAZ MBOYZ.");

                $user->alumni->update([
                    "approval_status" => "approved",
                    "approval_by" => "friend"
                ]);

                $user->email_verified_at = date("Y-m-d H:i:s");

                $user->update();
            }

            DB::table('notifications')->where('id',$request->id)->update(['read_at'=>Carbon::now()]);
    
            return response()->json(['message' => $request->status], 200);

        }


        return response()->json(['message' => 'failed'], 400);
    }

    function kta($id)
    {
        $alumni = Alumni::find($id);
        $bg_name = '80.jpg';
        if((int) $alumni->graduation_year <= 1990)
            $bg_name = '90.jpg';
        elseif((int) $alumni->graduation_year <= 2000)
            $bg_name = '2000.jpg';
        else
            $bg_name = '2001.jpg';

        $bg = public_path().'/assets/v-card/'.$bg_name;
        $type = pathinfo($bg, PATHINFO_EXTENSION);
        $bg = file_get_contents($bg);
        $bg = 'data:image/' . $type . ';base64,' . base64_encode($bg);

        $html = view("mobile.kta", compact('alumni','bg'))->render();

        // reference the Dompdf namespace

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->render();
        $content = $dompdf->output();
        file_put_contents('assets/kta/'.$id.'.pdf', $content);

        $imagick = new \Imagick();
        $imagick->setResolution(300, 300);
        $imagick->readImage('assets/kta/'.$id.'.pdf');
        $imagick->writeImages('assets/kta/'.$id.'.jpg', false);

        return '<img src="'.asset('assets/kta/'.$id.'.jpg').'">';

        // return $dompdf->stream('kartu.pdf',['Attachment'=>false]);
    }

    function ktaDemo()
    {
        $alumni = Alumni::first();
        return view("mobile.kta", compact('alumni'));
    }

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
                'private_domisili' => $request['private_domisili'],
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
