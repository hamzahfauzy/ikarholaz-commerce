<?php

namespace App\Http\Controllers\Staff;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::orderby('id','desc')->paginate();

        return view('staff.event.index', compact('events'))
            ->with('i', (request()->input('page', 1) - 1) * $events->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $event = new Event();
        return view('staff.event.create', compact('event'));
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
            'name'=>'required',
            'image'=>'mimes:jpeg,jpg,png,gif|required|max:10000'
        ]);

        $data = $request->all();

        if($request->file('image'))
        {
            $path =  $request->file('image')->store('events');
            $data['image']=$path;
        }

        $event = Event::create($data);

        return redirect()->route('staff.events.index')
            ->with('success', 'Event created successfully.');
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

        return view('staff.event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);

        $stime = \DateTime::createFromFormat('Y-m-d H:i:s', $event->start_time);
        $ltime = \DateTime::createFromFormat('Y-m-d H:i:s', $event->end_time);
        $event->start_time = $stime->format('Y-m-d\TH:i');
        $event->end_time = $ltime->format('Y-m-d\TH:i');

        return view('staff.event.edit', compact('event'));
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
        request()->validate([
            'name'=>'required',
            'image'=>'mimes:jpeg,jpg,png,gif|required|max:10000'
        ]);

        $data = $request->all();

        if($request->file('image'))
        {
            $path =  $request->file('image')->store('events');
            $data['image']=$path;
        }

        $event->update($data);

        return redirect()->route('staff.events.index')
            ->with('success', 'Event updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $event = Event::find($id)->delete();

        return redirect()->route('staff.events.index')
            ->with('success', 'Event deleted successfully');
    }
}
