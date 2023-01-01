<?php

namespace App\Http\Controllers\Staff;

use App\Models\BlacklistNra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlacklistNraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = BlacklistNra::orderby('id','desc')->paginate();

        return view('staff.blacklist-nra.index', compact('model'))
            ->with('i', (request()->input('page', 1) - 1) * $model->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new BlacklistNra();
        return view('staff.blacklist-nra.create', compact('model'));
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
            'nomor'=>'required|unique:blacklist_nras',
        ]);

        $data = $request->all();

        $model = BlacklistNra::create($data);

        return redirect()->route('staff.blacklist-nra.index')
            ->with('success', 'BlacklistNra created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = BlacklistNra::find($id);

        return view('staff.blacklist-nra.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = BlacklistNra::find($id);

        return view('staff.blacklist-nra.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  jolali $jolali
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlacklistNra $BlacklistNra)
    {
        request()->validate([
            'nomor'=>'required|unique:blacklist_nras,nomor,'.$BlacklistNra->id,
        ]);

        $data = $request->all();

        $BlacklistNra->update($data);

        return redirect()->route('staff.blacklist-nra.index')
            ->with('success', 'BlacklistNra updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $model = BlacklistNra::find($id)->delete();

        return redirect()->route('staff.blacklist-nra.index')
            ->with('success', 'BlacklistNra deleted successfully');
    }
}
