<?php

namespace App\Http\Controllers\Staff;

use App\Models\CustomFieldValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CustomFieldValueController
 * @package App\Http\Controllers
 */
class CustomFieldValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customFieldValues = CustomFieldValue::paginate();

        return view('staff.custom-field-value.index', compact('customFieldValues'))
            ->with('i', (request()->input('page', 1) - 1) * $customFieldValues->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customFieldValue = new CustomFieldValue();
        return view('staff.custom-field-value.create', compact('customFieldValue'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(CustomFieldValue::$rules);

        $customFieldValue = CustomFieldValue::create($request->all());

        return redirect()->route('custom-field-values.index')
            ->with('success', 'CustomFieldValue created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customFieldValue = CustomFieldValue::find($id);

        return view('staff.custom-field-value.show', compact('customFieldValue'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customFieldValue = CustomFieldValue::find($id);

        return view('staff.custom-field-value.edit', compact('customFieldValue'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CustomFieldValue $customFieldValue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomFieldValue $customFieldValue)
    {
        request()->validate(CustomFieldValue::$rules);

        $customFieldValue->update($request->all());

        return redirect()->route('custom-field-values.index')
            ->with('success', 'CustomFieldValue updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $customFieldValue = CustomFieldValue::find($id)->delete();

        return redirect()->route('custom-field-values.index')
            ->with('success', 'CustomFieldValue deleted successfully');
    }
}
