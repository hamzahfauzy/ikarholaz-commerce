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
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function previewTicket()
    {
        $transaction = \App\Models\Transaction::find(17);
        $items    = $transaction->transactionItems;
        $payment  = $transaction->payment;
        $product  = $items[0]->product;
        $customer = $transaction->customer;

        $custom_fields = \App\Models\CustomField::where('class_target','App\Models\EventProduct')->get();
        $cf = [];
        foreach($custom_fields as $key => $value)
        {
            $cf[$value->field_key] = $value->get_value($product->id)->field_value;
        }

        $participant_custom_fields = \App\Models\CustomField::where('class_target','App\Models\Event')->get();
        $participants = [];
        foreach($participant_custom_fields as $key => $value)
        {
            $cf_values = $value->customFieldValues()->where('pk_id',$items[0]->id)->get();
            foreach($cf_values as $cf_value)
            {
                $participants[$key][] = $cf_value->field_value;
            }
        }

        $flip = array_map(null, ...$participants);
        $part = "";
        $no = 1;
        foreach($flip as $ps)
        {
            $part .= $no.'. '.$ps[0];
            $part .= "\n";
            $no++;
        }

        $path = public_path('/assets/images/e-tiket-bg.jpeg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $bg   = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $barcode = file_get_contents("http://www.barcode-generator.org/phpqrcode/getCode.php?cht=qr&chl=samplepdf&chs=180x180&choe=UTF-8&chld=L|0");
        $qrcode = 'data:image/png;base64,' . base64_encode($barcode);


        $content = view('pdf.ticket',compact('transaction','items','payment','product','customer','cf','part','bg','qrcode'))->render();

        return PDF::loadHTML($content)->setOptions(['defaultFont' => 'Courier'])->setPaper([0,0,440,580])->stream('download.pdf');
        $pdf = PDF::loadHTML($content)->stream();
        $filename = md5(md5($customer->id.".".$transaction->id.".".$transaction->created_at));
        $file_to_save = 'pdf/'.$filename.'.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdf->output());

        return $file_to_save;
    }
}
