<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Alumni;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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

            $new_user = User::create([
                'name' => $data['name'],
                'email' => $data['phone'],
                'password' => Str::random(12)
            ]);

            $new_alumni = $new_user->alumni()->create([
                'name' => $data['name'],
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

                        foreach($alumnis as $alumni){
                            $alumni->user->notify(new UserNotification($notifUser));
                        }
                    }
                }
            }

            DB::commit();

            Session::flash('success',"Success to Register!");

            // return \redirect()->route('register')->with('success',"Success to Register!");

            return redirect('/register');

        }catch (\Exception $e) {
            DB::rollback();

            Session::flash('failed',"Failed to Register!");

            // return \redirect()->route('register')->with('failed',"Failed to Register!");

            return redirect('/register');
        }
    }
}
