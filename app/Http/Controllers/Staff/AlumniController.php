<?php

namespace App\Http\Controllers\Staff;

use App\Models\User;
use App\Models\Alumni;
use App\Models\WaBlast;
use Illuminate\Support\Str;
use App\Models\Ref\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * Class AlumniController
 * @package App\Http\Controllers
 */
class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alumnis = Alumni::join('users','users.id','=','alumnis.user_id');
        if(isset($_GET['keyword']) && !empty($_GET['keyword']))
        {
            $keyword = $_GET['keyword'];
            $alumnis = $alumnis->where('alumnis.name','LIKE', '%'.$keyword.'%');
            $alumnis = $alumnis->orwhere('alumnis.NRA','LIKE', '%'.$keyword.'%');
            $alumnis = $alumnis->orwhere('users.email','LIKE', '%'.$keyword.'%');
        }

        if(isset($_GET['filter']))
        {
            if(isset($_GET['filter']['graduation_year']) && !empty($_GET['filter']['graduation_year']))
            {
                $alumnis = $alumnis->where('alumnis.graduation_year','LIKE', '%'.$_GET['filter']['graduation_year'].'%');
            }

            if(isset($_GET['filter']['approval_status']) && !empty($_GET['filter']['approval_status']) && $_GET['filter']['approval_status'] != 'semua')
            {
                $alumnis = $alumnis->where('alumnis.approval_status','LIKE', '%'.$_GET['filter']['approval_status'].'%');
            }
        }
        $alumnis = $alumnis->select('alumnis.*','users.email')->orderby('alumnis.id', 'desc')->paginate();
        $filter = $_GET['filter'] ?? ['graduation_year'=>'','approval_status' => ''];

        return view('staff.alumni.index', compact('alumnis','filter'))
            ->with('i', (request()->input('page', 1) - 1) * $alumnis->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $alumni = new Alumni();
        return view('staff.alumni.create', compact('alumni'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Alumni::$rules);

        $user = new User();

        $new_user = $user->create([
            'name' => $request['name'],
            'email' => $request['phone'],
            'password' => Str::random(12)
        ]);

        if ($new_user) {
            $alumni = $new_user->alumni()->create($request->except('phone'));

            $new_user->email_verified_at = date("Y-m-d H:i:s");

            if ($alumni && $new_user->update()) {

                return redirect()->route('staff.alumnis.index')
                    ->with('success', 'Alumni created successfully.');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alumni = Alumni::find($id);

        return view('staff.alumni.show', compact('alumni'));
    }

    public function import(Request $request)
    {
        if ($request->isMethod("POST")) {
            $file = $request->file('import');
            $extension = $file->extension();
            if ($extension == 'xlsx') {
                $inputFileType = 'Xlsx';
            } else {
                $inputFileType = 'Xls';
            }
            $reader     = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

            $spreadsheet = $reader->load($file->getPathName());
            $worksheet   = $spreadsheet->getActiveSheet();
            $highestRow  = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            $status = [
                'success' => 'Berhasil import data akun'
            ];

            DB::beginTransaction();
            try {
                for ($row = 2; $row <= $highestRow; $row++) {
                    $name = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $graduation_year = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $NRA = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $email = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $phone = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $address = $worksheet->getCellByColumnAndRow(12, $row)->getValue();

                    $phone = "+".$phone;

                    if ($name == '' || $graduation_year == '' || $phone == '') break;

                    $user = User::where('email', $phone)->first();

                    if ($user) {
                        $user->alumni()->updateOrCreate([
                            'name' => $name,
                            'graduation_year' => $graduation_year,
                            'email' => $email,
                            'NRA' => $NRA,
                            'address' => $address,
                        ]);
                    } else {
                        $user = new User();

                        $new_user = $user->create([
                            'name' => $name,
                            'email' => $phone,
                            'password' => Str::random(12)
                        ]);

                        $new_user->alumni()->updateOrCreate([
                            'name' => $name,
                            'graduation_year' => $graduation_year,
                            'email' => $email,
                            'NRA' => $NRA,
                            'address' => $address,
                        ]);

                        $new_user->email_verified_at = date("Y-m-d H:i:s");

                        $new_user->update();
                    }
                }

                $status = [
                    'success' => 'Sukses import data akun'
                ];

                DB::commit();
            } catch (\Throwable $th) {
                throw $th;
                $status = [
                    'fail' => 'Gagal import data akun'
                ];
                DB::rollback();
            }

            return redirect()->route('staff.alumnis.import')->with($status);
        }

        return view('staff.alumni.import');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $alumni = Alumni::find($id);
        $provincies = Province::get();

        return view('staff.alumni.edit', compact('alumni','provincies'));
    }

    public function updateNra(Request $request, Alumni $alumni)
    {
        if ($request->isMethod("POST")) {
            $alumni->update(['NRA'=>$request->NRA]);
            return redirect()->route('staff.alumnis.index')
            ->with('success', 'NRA updated successfully');
        }
        return view('staff.alumni.update-nra', compact('alumni'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Alumni $Alumni
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alumni $alumni)
    {   
        if($request["phone"][0] == "0"){
            $request["phone"] = '+62' . substr($request['phone'],1);
        }

        $user = $alumni->user();

        $new_user = $user->update([
            'name' => $request['name'],
            'email' => $request['phone']
        ]);

        if ($new_user) {

            $new_alumni = $alumni->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'gender' => $request['gender'],
                'graduation_year' => $request['graduation_year'],
                'place_of_birth' => $request['place_of_birth'],
                'date_of_birth' => $request['date_of_birth'],
                'address' => $request['address'],
                'city' => $request['city'],
                'province' => $request['province'],
                'country' => $request['country'],
                'private_email' => $request['private_email'] == 'on' ? true : false,
                'private_phone' => $request['private_phone']  == 'on' ? true : false,
                'private_domisili' => $request['private_domisili']  == 'on' ? true : false,
            ]);

            if ($new_alumni) {

                if ($request['skills']) {
                    foreach ($request['skills'] as $value) {
                        if (isset($value['id'])) {
                            $alumni->skills()->where('id', $value['id'])->update(['name' => $value['name']]);
                        } else {
                            $alumni->skills()->create($value);
                        }
                    }
                }

                if ($request->file('profile')) {

                    $profile = $request->file('profile')->store('profiles');

                    if ($profile) {

                        $oldPic = $alumni->profile_pic;

                        if ($oldPic) {
                            Storage::delete($oldPic);
                        }

                        $uploaded = $alumni->update([
                            'profile_pic' => $profile
                        ]);
                    }
                }

                return redirect()->route('staff.alumnis.show',$alumni->id)->with('success', 'Alumni updated successfully');
            }

        }

        return redirect()->route('staff.alumnis.index')
            ->with('success', 'Alumni updated successfully');
    }

    public function approve(Request $request, Alumni $alumni)
    {
        $alumni->update([
            'approval_status' => 'approved',
            'approval_by' => 'admin',
        ]);

        $alumni->user->email_verified_at = date('Y-m-d H:i:s');
        $alumni->user->update();

        WaBlast::send($alumni->user->email, "Selamat $alumni->name, data anda telah berhasil diverifikasi. Nomor Registrasi Anggota (NRA) IKARHOLAZ anda adalah $alumni->NRA. 

_Mohon maaf saat ini sistem belum bisa digunakan untuk login/signin hingga perbaikan selesai._");
            // Silakan login untuk melengkapi data pendukung, juga menikmati fitur-fitur aplikasi IKARHOLAZ MBOYZ. Klik https://bit.ly/app-ika12
            // Bila ada masalah dengan playstore gunakan versi website untuk update data keanggotaan. Klik https://bit.ly/login-ika12");

        return redirect()->back() // ('staff.alumnis.index')
            ->with('success', 'Alumni updated successfully');
    }

    public function unapprove(Request $request, Alumni $alumni)
    {
        $alumni->update([
            'approval_status' => NULL,
            'approval_by' => NULL,
        ]);

        $alumni->user->email_verified_at = NULL;
        $alumni->user->update();

        return redirect()->back() // ('staff.alumnis.index')
            ->with('success', 'Alumni updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $Alumni = Alumni::find($id);
        $user = User::find($Alumni->user_id);
        $Alumni->delete();
        $user->delete();

        return redirect()->route('staff.alumnis.index')
            ->with('success', 'Alumni deleted successfully');
    }
}
