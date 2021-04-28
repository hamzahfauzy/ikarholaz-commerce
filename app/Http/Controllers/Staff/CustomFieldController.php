<?php

namespace App\Http\Controllers\Staff;

use App\Models\CustomField;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CustomFieldController
 * @package App\Http\Controllers
 */
class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customFields = CustomField::paginate();

        return view('staff.custom-field.index', compact('customFields'))
            ->with('i', (request()->input('page', 1) - 1) * $customFields->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customField = new CustomField();
        return view('staff.custom-field.create', compact('customField'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(CustomField::$rules);

        $customField = CustomField::create($request->all());

        return redirect()->route('custom-fields.index')
            ->with('success', 'CustomField created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customField = CustomField::find($id);

        return view('staff.custom-field.show', compact('customField'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customField = CustomField::find($id);

        return view('staff.custom-field.edit', compact('customField'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CustomField $customField
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomField $customField)
    {
        request()->validate(CustomField::$rules);

        $customField->update($request->all());

        return redirect()->route('custom-fields.index')
            ->with('success', 'CustomField updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $customField = CustomField::find($id)->delete();

        return redirect()->route('custom-fields.index')
            ->with('success', 'CustomField deleted successfully');
    }
}
