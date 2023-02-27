<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Cart;
use App\Models\Alumni;
use App\Models\Product;
use App\Models\Category;
use App\Models\BlacklistNra;
use App\Models\Ref\District;
use App\Models\Ref\Province;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::where('status','Publish')->where('is_dynamic',NULL)->doesntHave('parent')->whereHas('categories',function($query){
            return $query->where('categories.slug','!=','nra')->where('category_id','!=',config('reference.voucher_kategori'));
        })->orderby('created_at','desc')->paginate(8);
        $category = Category::find(getenv('DESAIN_KARTU_KATEGORI',1));
        $desain_products = $category ? $category->products : [];

        $nra_cantiks = Product::whereHas('categories',function($query){
            return $query->where('categories.slug','nra');
        })->where('stock','>',0)->get();

        return view('home',compact('products','desain_products','nra_cantiks'));
    }

    public function profile()
    {
        return view('profile');
    }

    public function editProfile(Request $request)
    {
        if($request->isMethod("post")){
            if($request["phone"][0] == "0"){
                $request["phone"] = '+62' . substr($request['phone'],1);
            }

            $user = auth()->user();

            $new_user = $user->update([
                'name' => $request['name'],
                'email' => $request['phone']
            ]);

            if ($new_user) {

                $alumni = $user->alumni()->update([
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
                                $user->alumni->businesses()->where('id', $value['id'])->update($value);
                            } else {
                                $user->alumni->businesses()->create($value);
                            }
                        }
                    }

                    if ($request['communities']) {

                        foreach ($request['communities'] as $id => $value) {
                            if (isset($value['id'])) {
                                $user->alumni->communities()->where('id', $value['id'])->update($value);
                            } else {
                                $user->alumni->communities()->create($value);
                            }
                        }
                    }

                    if ($request['professions']) {

                        foreach ($request['professions'] as $id => $value) {
                            if (isset($value['id'])) {
                                $user->alumni->professions()->where('id', $value['id'])->update($value);
                            } else {
                                $user->alumni->professions()->create($value);
                            }
                        }
                    }

                    if ($request['trainings']) {

                        foreach ($request['trainings'] as $id => $value) {
                            if (isset($value['id'])) {
                                $user->alumni->trainings()->where('id', $value['id'])->update($value);
                            } else {
                                $user->alumni->trainings()->create($value);
                            }
                        }
                    }

                    if ($request['appreciations']) {

                        foreach ($request['appreciations'] as $id => $value) {
                            if (isset($value['id'])) {
                                $user->alumni->appreciations()->where('id', $value['id'])->update($value);
                            } else {
                                $user->alumni->appreciations()->create($value);
                            }
                        }
                    }

                    if ($request['interests']) {

                        foreach ($request['interests'] as $id => $value) {
                            if (isset($value['id'])) {
                                $user->alumni->interests()->where('id', $value['id'])->update($value);
                            } else {
                                $user->alumni->interests()->create($value);
                            }
                        }
                    }

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
                        }
                    }

                    return redirect()->back()->with('success', 'success to update data');
                }

            }
        }

        $provincies = Province::get();
        $alumni = auth()->user()->alumni;
        $sektors = json_decode(file_get_contents('sektors.json'));
        $communities = json_decode(file_get_contents('communities.json'));
        $professions = json_decode(file_get_contents('professions.json'));
        $badan_hukums = json_decode(file_get_contents('badan_hukums.json'));
        $ijin_usahas = json_decode(file_get_contents('ijin_usahas.json'));

        return view('edit-profile',compact('alumni','provincies','sektors','communities','professions','badan_hukums','ijin_usahas'));
    }

    function nra()
    {
        if(isset($_GET['draw']))
        {

            $alumnis = (new Alumni)->select('id','name','NRA','graduation_year','city')->where('approval_status','approved');
            $draw   = $_GET['draw'];
            $start  = $_GET['start'];
            $length = $_GET['length'];
            $search = $_GET['search']['value'];
            $order  = $_GET['order'];
    
            $columns = [
                'id',
                'name',
                'NRA',
                'graduation_year',
                'city',
            ];

            if(isset($_GET['year']) && !empty($_GET['year']))
                $alumnis = $alumnis->where('graduation_year',$_GET['year']);
    
            if(!empty($search))
            {
                $alumnis = $alumnis->where('name','LIKE','%'.$search.'%');
                $alumnis = $alumnis->orwhere('NRA','LIKE','%'.$search.'%');
                $alumnis = $alumnis->orwhere('city','LIKE','%'.$search.'%');
            }
            
            
            $total = $alumnis->count();
            $alumnis = $alumnis->orderby($columns[$order[0]['column']], $order[0]['dir']);
            $alumnis = $alumnis->skip($start)->take($length);
            $alumnis = $alumnis->get();
    
            $results  = [];
            foreach($alumnis as $key => $alumni)
            {
                $results[$key][] = $key+1;
                $results[$key][] = $alumni->name;
                $results[$key][] = $alumni->NRA;
                $results[$key][] = $alumni->graduation_year;
                $results[$key][] = $alumni->city;
            }
    
            return [
                "draw" => $draw,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
                "data" => $results
            ];
        }

        return view('nra');
    }

    function nraBuy()
    {
        if(isset($_GET['draw']))
        {

            $alumnis = (new Card)->select('id','name','card_number')->where('status','Checkout');
            $draw   = $_GET['draw'];
            $start  = $_GET['start'];
            $length = $_GET['length'];
            $search = $_GET['search']['value'];
            $order  = $_GET['order'];
    
            $columns = [
                'id',
                'name',
                'card_number'
            ];
    
            if(!empty($search))
            {
                $alumnis = $alumnis->where('name','LIKE','%'.$search.'%');
                $alumnis = $alumnis->orwhere('card_number','LIKE','%'.$search.'%');
            }
            
            
            $total = $alumnis->count();
            $alumnis = $alumnis->orderby($columns[$order[0]['column']], $order[0]['dir']);
            $alumnis = $alumnis->skip($start)->take($length);
            $alumnis = $alumnis->get();
    
            $results  = [];
            foreach($alumnis as $key => $alumni)
            {
                $results[$key][] = $key+1;
                $results[$key][] = $alumni->name;
                $results[$key][] = $alumni->card_number;
            }
    
            return [
                "draw" => $draw,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
                "data" => $results
            ];
        }

        return view('nra-buy');
    }

    function nraBlacklist()
    {
        if(isset($_GET['draw']))
        {

            $alumnis = (new BlacklistNra)->select('id','nomor');
            $draw   = $_GET['draw'];
            $start  = $_GET['start'];
            $length = $_GET['length'];
            $search = $_GET['search']['value'];
            $order  = $_GET['order'];
    
            $columns = [
                'id',
                'nomor',
            ];
    
            if(!empty($search))
            {
                $alumnis = $alumnis->where('nomor','LIKE','%'.$search.'%');
            }
            
            
            $total = $alumnis->count();
            $alumnis = $alumnis->orderby($columns[$order[0]['column']], $order[0]['dir']);
            $alumnis = $alumnis->skip($start)->take($length);
            $alumnis = $alumnis->get();
    
            $results  = [];
            foreach($alumnis as $key => $alumni)
            {
                $results[$key][] = $key+1;
                $results[$key][] = $alumni->nomor;
            }
    
            return [
                "draw" => $draw,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
                "data" => $results
            ];
        }

        return view('nra-blacklist');
    }

    function listAlumni($status)
    {
        if(isset($_GET['draw']))
        {

            $alumnis = (new Alumni)->select('id','name','graduation_year','created_at');
            if($status == 'pending')
            {
                $alumnis = $alumnis->whereNull('approval_status');
            }
            else
            {
                $alumnis = $alumnis->where('approval_status',$status);
            }
            $draw   = $_GET['draw'];
            $start  = $_GET['start'];
            $length = $_GET['length'];
            $search = $_GET['search']['value'];
            $order  = $_GET['order'];
    
            $columns = [
                'id',
                'name',
                'graduation_year',
                'created_at',
                'notes'
            ];

            if(isset($_GET['year']) && !empty($_GET['year']))
                $alumnis = $alumnis->where('graduation_year',$_GET['year']);
    
            if(!empty($search))
            {
                $alumnis = $alumnis->where('name','LIKE','%'.$search.'%');
                $alumnis = $alumnis->orwhere('graduation_year','LIKE','%'.$search.'%');
                $alumnis = $alumnis->orwhere('created_at','LIKE','%'.$search.'%');
            }

            // $alumnis = $alumnis->whereNull('approval_status');
            $total   = $alumnis->count();
            $alumnis = $alumnis->orderby($columns[$order[0]['column']], $order[0]['dir']);
            $alumnis = $alumnis->skip($start)->take($length);
            $alumnis = $alumnis->get();
    
            $results  = [];
            foreach($alumnis as $key => $alumni)
            {
                $results[$key][] = $key+1;
                $results[$key][] = $alumni->name;
                $results[$key][] = $alumni->graduation_year;
                $results[$key][] = $alumni->tanggal;
                $results[$key][] = $alumni->profile_pic ? "<a href='".Storage::url($alumni->profile_pic)."'>Lihat</a>" : '<i>Tidak ada gambar</i>';
                $results[$key][] = $alumni->notes;
            }
    
            return [
                "draw" => $draw,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
                "data" => $results
            ];
        }

        return view('nra-pending');
    }

    public function previewTicket()
    {
        $transaction_id = 17;
        $pdf_url = (new \App\Libraries\PdfAction)->ticketUrl($transaction_id);

        return redirect()->to($pdf_url);
    }
}
