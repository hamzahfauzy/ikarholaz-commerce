<?php

namespace App\Http\Controllers\Staff;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

/**
 * Class ProductVariantController
 * @package App\Http\Controllers
 */
class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productVariants = ProductVariant::paginate();

        return view('staff.product-variant.index', compact('productVariants'))
            ->with('i', (request()->input('page', 1) - 1) * $productVariants->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productVariant = new ProductVariant();
        return view('staff.product-variant.create', compact('productVariant'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            //code...
            $product = Product::create([
                'name' => $request->name,
                'slug' => '',
                'description' => '',
                'base_price' => $request->price,
                'stock' => $request->stock,
            ]);

            $parent_product = Product::find($request->parent_id);
            $parent_product->variants()->attach($product);

            if($request->file('image'))
            {
                $path = $request->file('image')->store('products');

                ProductImage::create([
                    'product_id' => $product->id,
                    'file_url' => $path
                ]); 
            }

            DB::commit();
            return redirect()->route('staff.products.edit',$request->parent_id)
                ->with('success', 'Variant created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('staff.products.edit',$request->parent_id)
                ->with('success', 'Failed to create variant.')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productVariant = ProductVariant::find($id);

        return view('staff.product-variant.show', compact('productVariant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productVariant = ProductVariant::find($id);

        return view('staff.product-variant.edit', compact('productVariant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ProductVariant $productVariant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $productVariant)
    {
        DB::beginTransaction();

        try {
            //code...
            $product = $productVariant;
            $product->update([
                'name' => $request->name,
                'slug' => '',
                'description' => '',
                'base_price' => $request->base_price,
                'stock' => $request->stock,
            ]);

            if($request->file('image'))
            {
                $path = $request->file('image')->store('products');

                ProductImage::create([
                    'product_id' => $product->id,
                    'file_url' => $path
                ]); 
            }

            DB::commit();
            return redirect()->route('staff.products.edit',$product->parent->parent_id)
                ->with('success', 'Variant updated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('staff.products.edit',$product->parent->parent_id)
                ->with('error', 'Failed to update variant.')->withInput();
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $productVariant = ProductVariant::find($id)->delete();

        return redirect()->route('staff.product-variants.index')
            ->with('success', 'ProductVariant deleted successfully');
    }
}
