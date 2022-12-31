<?php

namespace App\Http\Controllers\Staff;

use App\Models\Jolali;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JolaliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jolalis = Jolali::orderby('id','desc')->paginate();

        return view('staff.jolali.index', compact('jolalis'))
            ->with('i', (request()->input('page', 1) - 1) * $jolalis->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jolali = new Jolali();
        return view('staff.jolali.create', compact('jolali'));
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
            'text'=>'required',
            'image'=>'mimes:jpeg,jpg,png,gif|required|max:10000',
            'link'=>'required',
        ]);

        $data = $request->all();

        if($request->file('image'))
        {
            $path =  $request->file('image')->store('jolalis');
            $data['image']=$path;
        }

        $jolali = Jolali::create($data);

        return redirect()->route('staff.jolalis.index')
            ->with('success', 'jolali created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jolali = Jolali::find($id);

        return view('staff.jolali.show', compact('jolali'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jolali = Jolali::find($id);

        return view('staff.jolali.edit', compact('jolali'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  jolali $jolali
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jolali $jolali)
    {
        request()->validate([
            'text'=>'required',
            'image'=>'mimes:jpeg,jpg,png,gif|required|max:10000',
            'link'=>'required',
        ]);

        $data = $request->all();

        if($request->file('image'))
        {
            $path =  $request->file('image')->store('jolalis');
            $data['image']=$path;
        }

        $jolali->update($data);

        return redirect()->route('staff.jolalis.index')
            ->with('success', 'jolali updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $jolali = Jolali::find($id)->delete();

        return redirect()->route('staff.jolalis.index')
            ->with('success', 'jolali deleted successfully');
    }
}
