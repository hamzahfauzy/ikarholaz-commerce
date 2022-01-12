<?php

namespace App\Http\Controllers\Staff;

use App\Models\User;
use App\Models\Alumni;
use App\Models\WaBlast;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
        $alumnis = Alumni::orderby('id', 'desc')->paginate();

        return view('staff.alumni.index', compact('alumnis'))
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
        $Alumni = Alumni::find($id);

        return view('staff.alumni.edit', compact('Alumni'));
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
    public function update(Request $request, Alumni $Alumni)
    {
        request()->validate(Alumni::$rules);

        $Alumni->update($request->all());

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

        WaBlast::send($alumni->user->email, "Selamat $alumni->name, data anda telah berhasil diverifikasi. Nomor Registrasi Anggota (NRA) anda adalah $alumni->NRA. Silakan login untuk melengkapi data pendukung, juga menikmati fitur-fitur aplikasi IKARHOLAZ MBOYZ. Klik https://bit.ly/app-ika12");

        return redirect()->route('staff.alumnis.index')
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
        $user = $Alumni->user;
        $Alumni->delete();
        $user->delete();

        return redirect()->route('staff.alumnis.index')
            ->with('success', 'Alumni deleted successfully');
    }
}
