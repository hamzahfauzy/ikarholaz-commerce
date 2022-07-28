<?php

namespace App\Http\Controllers\Mobile;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Skill;
use App\Models\Alumni;
use App\Models\WaBlast;
use App\Models\Business;
use App\Models\Interest;
use App\Models\Training;
use App\Models\Broadcast;
use App\Models\Community;
use App\Models\Profession;
use App\Models\UserApprove;
use App\Models\Appreciation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                WaBlast::send($user->email, "Selamat ".$user->alumni->name.", data anda telah berhasil diverifikasi. Nomor Registrasi Anggota (NRA) IKARHOLAZ anda adalah ".$user->alumni->NRA.".

_Mohon maaf saat ini sistem belum bisa digunakan untuk login/signin hingga perbaikan selesai._");
                    // Silakan login untuk melengkapi data pendukung, juga menikmati fitur-fitur aplikasi IKARHOLAZ MBOYZ. Klik https://bit.ly/app-ika12
                    // Bila ada masalah dengan playstore gunakan versi website untuk update data keanggotaan. Klik https://bit.ly/login-ika12");

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
        
        $profile_pic = public_path().'/storage/public/'.$alumni->profile_pic;
        $type = pathinfo($profile_pic, PATHINFO_EXTENSION);
        $profile_pic = file_get_contents($profile_pic);
        $profile_pic = 'data:image/' . $type . ';base64,' . base64_encode($profile_pic);

        $html = view("mobile.kta", compact('alumni','bg','profile_pic'))->render();
        // reference the Dompdf namespace

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        // $dompdf->set_paper([0,0,500,300]);
        $dompdf->loadHtml($html);

        $dompdf->render();
        $content = $dompdf->output();
        file_put_contents('assets/kta/'.$id.'.pdf', $content);

        $imagick = new \Imagick();
        $imagick->setResolution(300, 300);
        $imagick->readImage('assets/kta/'.$id.'.pdf');
        $imagick->writeImages('assets/kta/'.$id.'.jpg', false);

        return '<img src="'.asset('assets/kta/'.$id.'.jpg').'" width="100%">';

        // return $dompdf->stream('kartu.pdf',['Attachment'=>false]);
    }

    function ktaDemo()
    {
        $alumni = Alumni::first();
        return view("mobile.kta", compact('alumni'));
    }

    function edit(Request $request)
    {
        $user = User::where('email', $request['phone'])->with(['alumni', 'alumni.skills','alumni.businesses','alumni.communities','alumni.professions','alumni.trainings','alumni.appreciations','alumni.interests'])->first();

        $new_user = $user->update([
            'name' => $request['name'],
            'email' => $request['new_phone'] ? $request['new_phone'] : $request['phone']
        ]);

        if ($new_user) {

            $alumni = $user->alumni()->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'class_name' => $request['class_name'],
                'year_in' => $request['year_in'],
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

                if ($request['businesses']) {
                    
                    foreach ($request['businesses'] as $id => $value) {
                        if (isset($value['id'])) {
                            unset($value['created_at']);
                            unset($value['updated_at']);
                            $user->alumni->businesses()->where('id', $value['id'])->update($value);
                        } else {
                            $user->alumni->businesses()->create($value);
                        }
                    }
                }

                if ($request['communities']) {

                    foreach ($request['communities'] as $id => $value) {
                        if (isset($value['id'])) {
                            unset($value['created_at']);
                            unset($value['updated_at']);
                            $user->alumni->communities()->where('id', $value['id'])->update($value);
                        } else {
                            $user->alumni->communities()->create($value);
                        }
                    }
                }

                if ($request['professions']) {

                    foreach ($request['professions'] as $id => $value) {
                        if (isset($value['id'])) {
                            unset($value['created_at']);
                            unset($value['updated_at']);
                            $user->alumni->professions()->where('id', $value['id'])->update($value);
                        } else {
                            $user->alumni->professions()->create($value);
                        }
                    }
                }

                if ($request['trainings']) {

                    foreach ($request['trainings'] as $id => $value) {
                        if (isset($value['id'])) {
                            unset($value['created_at']);
                            unset($value['updated_at']);
                            $user->alumni->trainings()->where('id', $value['id'])->update($value);
                        } else {
                            $user->alumni->trainings()->create($value);
                        }
                    }
                }

                if ($request['appreciations']) {

                    foreach ($request['appreciations'] as $id => $value) {
                        if (isset($value['id'])) {
                            unset($value['created_at']);
                            unset($value['updated_at']);
                            $user->alumni->appreciations()->where('id', $value['id'])->update($value);
                        } else {
                            $user->alumni->appreciations()->create($value);
                        }
                    }
                }

                if ($request['interests']) {

                    foreach ($request['interests'] as $id => $value) {
                        if (isset($value['id'])) {
                            unset($value['created_at']);
                            unset($value['updated_at']);
                            $user->alumni->interests()->where('id', $value['id'])->update($value);
                        } else {
                            $user->alumni->interests()->create($value);
                        }
                    }
                }

                $user = User::where('email', $request['phone'])->with(['alumni', 'alumni.skills','alumni.businesses','alumni.communities','alumni.professions','alumni.trainings','alumni.appreciations','alumni.interests'])->first();

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

    function deleteBusiness($id)
    {
        $Business = Business::find($id)->delete();

        if ($Business) {
            return response()->json(['message' => "success to delete"], 200);
        }

        return response()->json(['message' => "failed to delete"], 409);
    }

    function deleteCommunity($id)
    {
        $Community = Community::find($id)->delete();

        if ($Community) {
            return response()->json(['message' => "success to delete"], 200);
        }

        return response()->json(['message' => "failed to delete"], 409);
    }

    function deleteProfession($id)
    {
        $Profession = Profession::find($id)->delete();

        if ($Profession) {
            return response()->json(['message' => "success to delete"], 200);
        }

        return response()->json(['message' => "failed to delete"], 409);
    }

    function deleteTraining($id)
    {
        $Training = Training::find($id)->delete();

        if ($Training) {
            return response()->json(['message' => "success to delete"], 200);
        }

        return response()->json(['message' => "failed to delete"], 409);
    }

    function deleteAppreciation($id)
    {
        $Appreciation = Appreciation::find($id)->delete();

        if ($Appreciation) {
            return response()->json(['message' => "success to delete"], 200);
        }

        return response()->json(['message' => "failed to delete"], 409);
    }

    function deleteInterest($id)
    {
        $Interest = Interest::find($id)->delete();

        if ($Interest) {
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

    function alumnidpt()
    {
        if(isset($_GET['all']))
            return $this->allalumni();
        return Alumni::where('approval_status','approved')->count();
    }

    function allalumni()
    {
        return Alumni::count();
    }
    function registerWa(Request $request)
    {
        // http://gerai.ikarholaz.id/api/register-wa?name=Aji&graduation_year=2002&gender=L&address=Semarang&city=Semarang&province=Semarang&country=Semarang&date_of_birth=1997&year_in=2000&year_out=2015

        try {
            $request->validate([
                'name' => 'required',
                'graduation_year' => 'required',
                'class_name' => 'required',
                'year_in' => 'required',
                'address' => 'required',
            ]);
        } catch (\Throwable $th) {
$pesan = 
"Format tidak sesuai, silahkan coba lagi";
            
                    $data = [
                        'api_key' => env('WA_API'),
                        'sender'  => $request->sender,
                        'number'  => $request->phone,
                        'message' => $pesan
                    ];
                    
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => env('WA_URL')."/app/api/send-message",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "POST",
                      CURLOPT_POSTFIELDS => json_encode($data))
                    );
                    $response = curl_exec($curl);
                    curl_close($curl);
            return json_encode('data tidak sesuai');
        }

        DB::beginTransaction();
        try {
            if($request->phone[0] == "0"){
                $phone = '+62' . substr($request->phone,1);
            }else if($request->phone[0] == "6")
            {
                $phone = '+62' . substr($request->phone,2);
            }
            $new_user = User::create([
                'name' => $request->name,
                'email' => $phone,
                'password' => Str::random(12)
            ]);

            $tahun_lulus = substr($request->graduation_year, 2, 2);
            $nomor_kartu = substr(strtotime('now'), 2, 8);
            $NRA = $tahun_lulus . '.' . $nomor_kartu;

            $new_alumni = $new_user->alumni()->create([
                'name' => $request->name,
                'NRA' => $NRA,
                'class_name' => $request->class_name,
                'year_in' => $request->year_in,
                'address' => $request->address,
                'graduation_year' => $request->graduation_year,
            ]);

            DB::commit();
            $pesan = 
"Terima kasih telah mendaftar sebagai anggota IKARHOLAZ. Lanjutkan langkah dengan mengirim foto melalui WA ini untuk memudahkan verifikasi. 
Saat ini status masih PENDING hingga diverifikasi petugas. Ketik CEK NRA untuk mengetahui status pendaftaran anggota IKARHOLAZ.";

        $data = [
            'api_key' => env('WA_API'),
            'sender'  => $request->sender,
            'number'  => $request->phone,
            'message' => $pesan
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => env('WA_URL')."/app/api/send-message",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($data))
        );
        $response = curl_exec($curl);
        curl_close($curl);
        return json_encode('berhasil');
        }catch (\Exception $e) {
            DB::rollback();

$pesan = 
"Registrasi GAGAL. Nomer WA sudah digunakan pendaftaran sebelumnya. Ketik CEK NRA untuk mengetahui data terkait nomer WA yang digunakan.";
            
            $data = [
                'api_key' => env('WA_API'),
                'sender'  => $request->sender,
                'number'  => $request->phone,
                'message' => $pesan
            ];
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => env('WA_URL')."/app/api/send-message",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data))
            );
            $response = curl_exec($curl);
            curl_close($curl);
            return json_encode('gagal');
        }
    }
    function getNra(Request $request)
    {
        $province = array (
            0 => 
            array (
              'province_id' => '1',
              'province' => 'Bali',
            ),
            1 => 
            array (
              'province_id' => '2',
              'province' => 'Bangka Belitung',
            ),
            2 => 
            array (
              'province_id' => '3',
              'province' => 'Banten',
            ),
            3 => 
            array (
              'province_id' => '4',
              'province' => 'Bengkulu',
            ),
            4 => 
            array (
              'province_id' => '5',
              'province' => 'DI Yogyakarta',
            ),
            5 => 
            array (
              'province_id' => '6',
              'province' => 'DKI Jakarta',
            ),
            6 => 
            array (
              'province_id' => '7',
              'province' => 'Gorontalo',
            ),
            7 => 
            array (
              'province_id' => '8',
              'province' => 'Jambi',
            ),
            8 => 
            array (
              'province_id' => '9',
              'province' => 'Jawa Barat',
            ),
            9 => 
            array (
              'province_id' => '10',
              'province' => 'Jawa Tengah',
            ),
            10 => 
            array (
              'province_id' => '11',
              'province' => 'Jawa Timur',
            ),
            11 => 
            array (
              'province_id' => '12',
              'province' => 'Kalimantan Barat',
            ),
            12 => 
            array (
              'province_id' => '13',
              'province' => 'Kalimantan Selatan',
            ),
            13 => 
            array (
              'province_id' => '14',
              'province' => 'Kalimantan Tengah',
            ),
            14 => 
            array (
              'province_id' => '15',
              'province' => 'Kalimantan Timur',
            ),
            15 => 
            array (
              'province_id' => '16',
              'province' => 'Kalimantan Utara',
            ),
            16 => 
            array (
              'province_id' => '17',
              'province' => 'Kepulauan Riau',
            ),
            17 => 
            array (
              'province_id' => '18',
              'province' => 'Lampung',
            ),
            18 => 
            array (
              'province_id' => '19',
              'province' => 'Maluku',
            ),
            19 => 
            array (
              'province_id' => '20',
              'province' => 'Maluku Utara',
            ),
            20 => 
            array (
              'province_id' => '21',
              'province' => 'Nanggroe Aceh Darussalam (NAD)',
            ),
            21 => 
            array (
              'province_id' => '22',
              'province' => 'Nusa Tenggara Barat (NTB)',
            ),
            22 => 
            array (
              'province_id' => '23',
              'province' => 'Nusa Tenggara Timur (NTT)',
            ),
            23 => 
            array (
              'province_id' => '24',
              'province' => 'Papua',
            ),
            24 => 
            array (
              'province_id' => '25',
              'province' => 'Papua Barat',
            ),
            25 => 
            array (
              'province_id' => '26',
              'province' => 'Riau',
            ),
            26 => 
            array (
              'province_id' => '27',
              'province' => 'Sulawesi Barat',
            ),
            27 => 
            array (
              'province_id' => '28',
              'province' => 'Sulawesi Selatan',
            ),
            28 => 
            array (
              'province_id' => '29',
              'province' => 'Sulawesi Tengah',
            ),
            29 => 
            array (
              'province_id' => '30',
              'province' => 'Sulawesi Tenggara',
            ),
            30 => 
            array (
              'province_id' => '31',
              'province' => 'Sulawesi Utara',
            ),
            31 => 
            array (
              'province_id' => '32',
              'province' => 'Sumatera Barat',
            ),
            32 => 
            array (
              'province_id' => '33',
              'province' => 'Sumatera Selatan',
            ),
            33 => 
            array (
              'province_id' => '34',
              'province' => 'Sumatera Utara',
            ),
        );
        $sender = $request->sender; 
        $phone = $request->phone; 
        $user = User::where('email', '+'.$phone)->first();
        if(!$user)
        {
            $pesan = 
"GAGAL MENAMPILKAN DATA, disebabkan No WA anda: ".$phone." tidak terdaftar dalam sistem NRA. Anda harus menggunakan no WA terdaftar saat melakukan cek data NRA.

Hubungi mimin untuk memastikan/mengubah nomer WA yang terdaftar di sistem NRA IKARHOLAZ. 

*_Note: Permintaan ganti nomer tidak bisa diwakilkan. 1 nama alumni berlaku 1 NRA dan 1 nomer HP_*

_Jika anda belum melakukan pendaftaran anggota IKARHOLAZ/belum memiliki NRA, gunakan layanan pendaftaran anggota IKARHOLAZ melalui WA, lebih simpel dan praktis. Caranya, ketik:_

REG#nama#kelas#tahunmasuk#tahunlulus#alamat

Alternatif lain melalui:
web: https://gerai.ikarholaz.id/register";

            $data = [
                'api_key' => env('WA_API'),
                'sender'  => $sender,
                'number'  => $phone,
                'message' => $pesan
            ];
    
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => env('WA_URL')."/app/api/send-message",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data))
            );
            $response = curl_exec($curl);
            curl_close($curl);
            return json_encode($response);
        }
        $alumni = Alumni::where('user_id', $user->id)->first();
        // USAHA DAN BISNIS
        $skill = Skill::where('alumni_id', $alumni->id)->get();
        $business = Business::where('alumni_id', $alumni->id)->get();
        $profession = Profession::where('alumni_id', $alumni->id)->get();
        $comunities = Community::where('alumni_id', $alumni->id)->get();
        $trainings = Training::where('alumni_id', $alumni->id)->get();
        $apreciation = Appreciation::where('alumni_id', $alumni->id)->get();
        $interest = Interest::where('alumni_id', $alumni->id)->get();
        $dataUsaha = [$skill, $business, $profession,$comunities, $trainings, $apreciation, $interest];
        function getDataUsaha($data){
            if(count($data) >0){
                $dataValue = [];
                foreach ($data as $key => $value) {
                    $dataValue[] = $value->name;
                }
                return implode("\n", $dataValue);
            }else{
                return "_(kosong)_";
            }
        }

        function getDataMinat($data){
            if(count($data) >0){
                $dataValue = [];
                foreach ($data as $key => $value) {
                $dataValue[] = $value->bidang;
                }
                return implode("\n", $dataValue);
            }else{
                return "_(kosong)_";
            }
        }
        // USAHA DAN BISNIS
        $status = $alumni->approval_status == "approved" ? "APPROVED" : "PENDING";
        $photo = $alumni->profile_pic == NULL ? "_(kosong)_" : url('/storage/public')."/".$alumni->profile_pic;
        $ttl = $alumni->place_of_birth == NULL ? "_(kosong)_" : $alumni->place_of_birth . "/" . $alumni->date_of_birth;
        function getProvince($value, $data)
        {
           if($value != null)
           {
            if(strlen($value) <= 2)
            {
                return $data[$value-1]['province'];
            } else {
                return $value ;
            }
           }
        }
        function belumDiIsi($value)
        {
            if($value == NULL)
            {
                return "_(kosong)_";
            } else {
                return $value ;
            }
        }
        $pesan = 
"STATUS : *$status*
NRA : $alumni->NRA
Tgl Reg : $alumni->created_at
----------------------------------------
Nama : $alumni->name
Tahun Lulus : ".belumDiIsi($alumni->graduation_year)."
Tahun Masuk : ".belumDiIsi($alumni->year_in)."
Kelas : ".belumDiIsi($alumni->class_name)."
Foto Profile : $photo
----------------------------------------
Gender : ".belumDiIsi($alumni->gender)."
Tempat/Tgl Lahir: $ttl
Alamat : ".belumDiIsi($alumni->address)."
Kota : ".belumDiIsi($alumni->city)."
Provinsi : ".belumDiIsi(getProvince($alumni->province, $province))."
Negara : ".belumDiIsi($alumni->country)."
Email : ".belumDiIsi($alumni->email)."
----------------------------------------
*USAHA/BISNIS:*
".getDataUsaha($business)."
*PEKERJAAN:*
".getDataMinat($profession)."
*KOMUNITAS:*
".getDataUsaha($comunities)."
*PELATIHAN:*
".getDataUsaha($trainings)."
*PENCAPAIAN:*
".getDataUsaha($apreciation)."
*MINAT:*
".getDataMinat($interest)."
----------------------------------------
Lengkapi data profile melalui http://gerai.ikarholaz.id/login 
_Hanya alumni berstatus *approved* yang bisa login._

*NRA IKARHOLAZ SYSTEM*
_part of Sistem Informasi Rholaz (SIR) 2022_
";

        $data = [
            'api_key' => env('WA_API'),
            'sender'  => $sender,
            'number'  => $phone,
            'message' => $pesan
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('WA_URL')."/app/api/send-message",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data))
        );
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }
}