<?php

namespace App\Http\Controllers\Staff;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('is_dynamic',NULL)->doesntHave('parent')->paginate();

        return view('staff.product.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * $products->perPage());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vouchers()
    {
        $products = Product::where('is_dynamic',NULL)->doesntHave('parent')->whereHas('categories', function ($query) {
            return $query->where('category_id', config('reference.voucher_kategori'));
        })->paginate();

        return view('staff.product.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * $products->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product();
        $categories = Category::pluck('name','id');
        return view('staff.product.create', compact('product','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Product::$rules);

        DB::beginTransaction();

        try {
            //code...
            $product = Product::create($request->all());
    
            $product->categories()->attach($request->category_id);

            if($request->file('image'))
            {
                $path = $request->file('image')->store('products');

                ProductImage::create([
                    'product_id' => $product->id,
                    'file_url' => $path
                ]); 
            }

            if($request->hidden_image)
            {
                ProductImage::create([
                    'product_id' => $product->id,
                    'file_url' => $request->hidden_image
                ]); 
            }

            DB::commit();
            return redirect()->route('staff.products.edit',$product->id)
                ->with('success', 'Product created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('staff.products.create')
                ->with('danger', 'Product failed to created.')->withInput();

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return view('staff.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::pluck('name','id');
        return view('staff.product.edit', compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $rules = Category::$rules;
        $rules['slug'] = $rules['slug'] . ',id,' . $product->id;
        request()->validate($rules);
        DB::beginTransaction();

        try {
            //code...
            $product->update($request->all());
    
            $product->categories()->sync($request->category_id);

            if($request->file('image'))
            {
                $path = $request->file('image')->store('products');
    
                if(count($product->images) > 0)
                    $product->images()->update([
                        'file_url' => $path
                    ]);
                else
                    ProductImage::create([
                        'product_id' => $product->id,
                        'file_url' => $path
                    ]); 
            }

            if($request->custom_fields)
            {
                $product->set_custom_fields((array) $request->custom_fields, $request->custom_field_target);
            }


            DB::commit();
            return redirect()->route('staff.products.edit',$product->id)
                ->with('success', 'Product updated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('staff.products.edit',$product->id)
                ->with('danger', 'Product failed to created.')->withInput();

    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $product = Product::find($id)->delete();

        return redirect()->route('staff.products.index')
            ->with('success', 'Product deleted successfully');
    }
}
