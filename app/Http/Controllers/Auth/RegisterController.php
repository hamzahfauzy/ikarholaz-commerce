<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Alumni;
use App\Models\WaBlast;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Notifications\UserNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = "/register";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users,email'],
            'graduation_year' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'image'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);

        DB::beginTransaction();

        try {

            if($data["phone"][0] == "0"){
                $data["phone"] = '+62' . substr($data['phone'],1);
            }

            $new_user = User::create([
                'name' => $data['name'],
                'email' => $data['phone'],
                'password' => Str::random(12)
            ]);

            $tahun_lulus = substr($data['graduation_year'], 2, 2);
            $nomor_kartu = substr(strtotime('now'), 2, 8);
            $NRA = $tahun_lulus . '.' . $nomor_kartu;

            $new_alumni = $new_user->alumni()->create([
                'name' => $data['name'],
                'NRA' => $NRA,
                'graduation_year' => $data['graduation_year'],
            ]);

            if ($data['photo']) {

                $profile = $data['photo']->store('profiles');

                if ($profile) {

                    $oldPic = $new_user->alumni->profile_pic;

                    if ($oldPic) {
                        Storage::delete($oldPic);
                    }

                    $alumnis = Alumni::where('graduation_year',$new_alumni->graduation_year)->where('id','!=',$new_alumni->id)->inRandomOrder()->limit(5)->get();

                    if(empty($alumnis)){

                        $new_user->update([
                            "email_verified_at" => date("Y-m-d H:i:s")
                        ]);

                        $uploaded = $new_user->alumni()->update([
                            'profile_pic' => $profile
                        ]);

                    }else{

                        $uploaded = $new_user->alumni()->update([
                            'profile_pic' => $profile
                        ]);

                        
                        $notifUser = User::find($new_user->id);
                        
                        $message = "Teman atas nama $new_user->name, tahun lulus $data[graduation_year] mendaftar anggota IKARHOLAZ. Benarkah dia seangkatan dengan Anda? Bantu admin memverifikasi nya dengan membalas WA ini: *YA/TIDAK/RAGU-RAGU.*"; //membuka aplikasi IKARHOLAZ MBOYZ. Klik untuk install https://bit.ly/app-ika12";
                        foreach($alumnis as $alumni){
                            $alumni->user->notify(new UserNotification($notifUser));
                            WaBlast::send($alumni->user->email, $message);
                        }
                    }
                }
            }

            DB::commit();

            Session::flash('success',"Pendaftaran diterima. Sebuah notif terkirim ke nomer anda melalui WA mengenai status pendaftaran Anda. (Mohon maaf saat ini sistem hanya bisa mengirim notifikasi ke nomer WA. Hubungi admin jika tak kunjung diaprove dalam 36 jam)");
            $message = "$new_user->name, tahun lulus $data[graduation_year] mendaftar anggota IKARHOLAZ. Saat ini menunggu persetujuan Anda.";
            $admin_number = env('WA_ADMIN_NUMBER',0);
            if($admin_number)
                WaBlast::send($admin_number, $message);

            $message = "Terima kasih $new_user->name, tahun lulus $data[graduation_year], telah mendaftar sebagai anggota IKARHOLAZ. Status masih PENDING hingga diverifikasi petugas. Hubungi petugas atau reply nomer ini jika tak kunjung diaprove dalam 36 jam.";
            WaBlast::send($data["phone"], $message);

            // return \redirect()->route('register')->with('success',"Success to Register!");

            return redirect('/register');

        }catch (\Exception $e) {
            DB::rollback();

            Session::flash('failed',"Data Anda Sudah Terdaftar. Terima kasih atas partisipasinya");

            // return \redirect()->route('register')->with('failed',"Failed to Register!");

            return redirect('/register');
        }
    }
}
