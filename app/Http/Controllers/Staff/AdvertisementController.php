<?php

namespace App\Http\Controllers\Staff;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Advertisement::orderby('id','desc')->paginate();

        return view('staff.advertisement.index', compact('model'))
            ->with('i', (request()->input('page', 1) - 1) * $model->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Advertisement();
        return view('staff.advertisement.create', compact('model'));
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
            'event'=>'required',
            'contents'=>'required',
        ]);

        $data = $request->all();

        $model = Advertisement::create($data);

        return redirect()->route('staff.advertisements.index')
            ->with('success', 'Advertisement created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Advertisement::find($id);

        return view('staff.advertisement.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Advertisement::find($id);

        return view('staff.advertisement.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  jolali $jolali
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        request()->validate([
            'event'=>'required',
            'contents'=>'required',
        ]);

        $data = $request->all();

        $advertisement->update($data);

        return redirect()->route('staff.advertisements.index')
            ->with('success', 'Advertisement updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $model = Advertisement::find($id)->delete();

        return redirect()->route('staff.advertisements.index')
            ->with('success', 'Advertisement deleted successfully');
    }
}
