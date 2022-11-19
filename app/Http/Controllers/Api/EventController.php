<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $events = Event::orderby('id','desc');
        
        if($request->clauses)
        {
            $events = $events->where($request->clauses);
        }

        $events = $events->paginate();

        return response()->json([
            'status'=>'success',
            'data' => $events
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'image'=>'mimes:jpeg,jpg,png,gif|required|max:10000'
        ]);

        if ($validator->fails()) {    
            return response()->json($validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->all();

        if($request->file('image'))
        {
            $path =  $request->file('image')->store('events');
            $data['image']=$path;
        }

        $event = Event::create($data);

        return response()->json([
            'status'=>'success',
            'data' => $event
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id);

        return response()->json([
            'status'=>'success',
            'data' => $event
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'image'=>'nullable|mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        if ($validator->fails()) {    
            return response()->json($validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->all();

        if($request->file('image'))
        {
            $path =  $request->file('image')->store('events');
            $data['image']=$path;
        }

        $event->update($data);

        return response()->json([
            'status'=>'success',
            'data' => $event
        ]);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $event = Event::find($id)->delete();

        return response()->json([
            'status'=>'success'
        ]);
    }
}

