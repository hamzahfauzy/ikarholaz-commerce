<?php

namespace App\Http\Controllers\Staff;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Merchant::orderby('id','desc')->paginate();

        return view('staff.merchant.index', compact('model'))
            ->with('i', (request()->input('page', 1) - 1) * $model->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Merchant();
        return view('staff.merchant.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name'=>'required|unique:merchants',
            'phone'=>'required',
        ]);

        $data = $request->all();

        $model = Merchant::create($data);

        return redirect()->route('staff.merchants.index')
            ->with('success', 'Merchant created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Merchant::find($id);

        return view('staff.merchant.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Merchant::find($id);

        return view('staff.merchant.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  jolali $jolali
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Merchant $Merchant)
    {
        request()->validate([
            'name'=>'required|unique:merchants,name,'.$Merchant->id,
            'phone'=>'required',
        ]);

        $data = $request->all();

        $Merchant->update($data);

        return redirect()->route('staff.merchants.index')
            ->with('success', 'Merchant updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $model = Merchant::find($id)->delete();

        return redirect()->route('staff.merchants.index')
            ->with('success', 'Merchant deleted successfully');
    }
}
