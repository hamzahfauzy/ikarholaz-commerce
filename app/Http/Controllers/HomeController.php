<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use App\Models\Ref\District;
use App\Models\Ref\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $products = Product::where('is_dynamic',NULL)->doesntHave('parent')->orderby('created_at','desc')->paginate(8);
        $category = Category::find(getenv('DESAIN_KARTU_KATEGORI',1));
        $desain_products = $category ? $category->products : [];
        return view('home',compact('products','desain_products'));
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
                    'email' => $request['email'],
                    'graduation_year' => $request['graduation_year'],
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

                    return redirect()->back()->with('success', 'success to update data');
                }

            }

            dd($request->all());
        }

        $provincies = Province::get();
        $alumni = auth()->user()->alumni;

        return view('edit-profile',compact('provincies','alumni'));
    }
}
