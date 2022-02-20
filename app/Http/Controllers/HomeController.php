<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Alumni;
use App\Models\Product;
use App\Models\Category;
use App\Models\Ref\District;
use App\Models\Ref\Province;
use Illuminate\Http\Request;
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
        $products = Product::where('is_dynamic',NULL)->doesntHave('parent')->whereHas('categories',function($query){
            return $query->where('categories.slug','!=','nra');
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

        return view('edit-profile',compact('provincies','alumni'));
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

    function pending()
    {
        if(isset($_GET['draw']))
        {

            $alumnis = (new Alumni)->select('id','name','graduation_year','created_at');
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
            ];

            if(isset($_GET['year']) && !empty($_GET['year']))
                $alumnis = $alumnis->where('graduation_year',$_GET['year']);
    
            if(!empty($search))
            {
                $alumnis = $alumnis->where('name','LIKE','%'.$search.'%');
                $alumnis = $alumnis->orwhere('graduation_year','LIKE','%'.$search.'%');
                $alumnis = $alumnis->orwhere('created_at','LIKE','%'.$search.'%');
            }

            $alumnis = $alumnis->whereNull('approval_status');
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
}
