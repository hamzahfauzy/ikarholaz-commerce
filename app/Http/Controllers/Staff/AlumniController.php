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
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
                if($_GET['filter']['approval_status'] == "approved"){

                    $alumnis = $alumnis->where('alumnis.approval_status','LIKE', '%'.$_GET['filter']['approval_status'].'%');
                }else{
                    $alumnis = $alumnis->where('alumnis.approval_status', null);

                }
            }
        }
        
        if(isset($_GET['export'])){
            $data = $alumnis->select('alumnis.name','alumnis.NRA','users.email','alumnis.graduation_year','alumnis.approval_status','alumnis.created_at')->orderby('alumnis.id', 'desc')->get()->toArray();
            $keys = array_keys($data[0]);
            $this->export([$keys,...$data]);
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

    public function export($data){
        try {
           $spreadSheet = new Spreadsheet();
           $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
           $spreadSheet->getActiveSheet()->fromArray($data);
           $Excel_writer = new Xls($spreadSheet);
           header('Content-Type: application/vnd.ms-excel');
           header('Content-Disposition: attachment;filename="ExportDataAlumni.xls"');
           header('Cache-Control: max-age=0');
           ob_end_clean();
           $Excel_writer->save('php://output');
           exit();
       } catch (Exception $e) {
           return;
       }
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
        $sektors = json_decode(file_get_contents('sektors.json'));
        $communities = json_decode(file_get_contents('communities.json'));
        $professions = json_decode(file_get_contents('professions.json'));
        $badan_hukums = json_decode(file_get_contents('badan_hukums.json'));
        $ijin_usahas = json_decode(file_get_contents('ijin_usahas.json'));

        return view('staff.alumni.edit', compact('alumni','provincies','sektors','communities','professions','badan_hukums','ijin_usahas'));
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
                'class_name' => $request['class_name'],
                'year_in' => $request['year_in'],
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
                    foreach ($request['skills'] as $id => $value) {
                        if (isset($value['id'])) {
                            $alumni->skills()->where('id', $value['id'])->update(['name' => $value['name']]);
                        } else {
                            $alumni->skills()->create($value);
                        }
                    }
                }

                if ($request['businesses']) {

                    foreach ($request['businesses'] as $id => $value) {
                        if (isset($value['id'])) {
                            $alumni->businesses()->where('id', $value['id'])->update($value);
                        } else {
                            $alumni->businesses()->create($value);
                        }
                    }
                }

                if ($request['communities']) {

                    foreach ($request['communities'] as $id => $value) {
                        if (isset($value['id'])) {
                            $alumni->communities()->where('id', $value['id'])->update($value);
                        } else {
                            $alumni->communities()->create($value);
                        }
                    }
                }

                if ($request['professions']) {

                    foreach ($request['professions'] as $id => $value) {
                        if (isset($value['id'])) {
                            $alumni->professions()->where('id', $value['id'])->update($value);
                        } else {
                            $alumni->professions()->create($value);
                        }
                    }
                }

                if ($request['trainings']) {

                    foreach ($request['trainings'] as $id => $value) {
                        if (isset($value['id'])) {
                            $alumni->trainings()->where('id', $value['id'])->update($value);
                        } else {
                            $alumni->trainings()->create($value);
                        }
                    }
                }

                if ($request['appreciations']) {

                    foreach ($request['appreciations'] as $id => $value) {
                        if (isset($value['id'])) {
                            $alumni->appreciations()->where('id', $value['id'])->update($value);
                        } else {
                            $alumni->appreciations()->create($value);
                        }
                    }
                }

                if ($request['interests']) {

                    foreach ($request['interests'] as $id => $value) {
                        if (isset($value['id'])) {
                            $alumni->interests()->where('id', $value['id'])->update($value);
                        } else {
                            $alumni->interests()->create($value);
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

        WaBlast::send($alumni->user->email, "Selamat $alumni->name, data anda telah berhasil kami verifikasi. Nomor Registrasi Anggota (NRA) IKARHOLAZ anda adalah $alumni->NRA. 

        Pendaftaran cukup sampai tahap ini. Jika ingin melengkapi data profile silakan login dan edit profile melalui http://gerai.ikarholaz.id/login ");
            // Silakan login untuk melengkapi data pendukung, juga menikmati fitur-fitur aplikasi IKARHOLAZ MBOYZ. Klik https://bit.ly/app-ika12
            // Bila ada masalah dengan playstore gunakan versi website untuk update data keanggotaan. Klik https://bit.ly/login-ika12");

        return redirect()->back() // ('staff.alumnis.index')
            ->with('success', 'Alumni updated successfully');
    }

    public function updateStatus(Request $request, Alumni $alumni)
    {
        $alumni->update([
            'approval_status' => $request->status,
            'approval_by' => 'admin',
        ]);

        if($request->notes)
        {
            $alumni->update([
                'notes' => $request->notes
            ]);
        }

        if($request->status == "approved"){
            $alumni->user->email_verified_at = date('Y-m-d H:i:s');
            $alumni->user->update();
            WaBlast::send($alumni->user->email, "Selamat $alumni->name, data anda telah berhasil kami verifikasi. Nomor Registrasi Anggota (NRA) IKARHOLAZ anda adalah $alumni->NRA. 
    
            Pendaftaran cukup sampai tahap ini. Jika ingin melengkapi data profile silakan login dan edit profile melalui http://gerai.ikarholaz.id/login ");
                // Silakan login untuk melengkapi data pendukung, juga menikmati fitur-fitur aplikasi IKARHOLAZ MBOYZ. Klik https://bit.ly/app-ika12
                // Bila ada masalah dengan playstore gunakan versi website untuk update data keanggotaan. Klik https://bit.ly/login-ika12");
        }

        return redirect()->back() // ('staff.alumnis.index')
            ->with('success', 'Alumni updated successfully');
    }

    public function unapprove(Request $request, Alumni $alumni)
    {
        $alumni->update([
            'approval_status' => NULL,
            'approval_by' => NULL,
            'notes' => NULL,
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
