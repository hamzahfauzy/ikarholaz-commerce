<?php

namespace App\Http\Controllers\Staff;

use App\Models\Broadcast;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

/**
 * Class BroadcastController
 * @package App\Http\Controllers
 */
class BroadcastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $broadcasts = Broadcast::paginate();

        return view('staff.broadcast.index', compact('broadcasts'))
            ->with('i', (request()->input('page', 1) - 1) * $broadcasts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $broadcast = new Broadcast();
        return view('staff.broadcast.create', compact('broadcast'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Broadcast::$rules);

        $factory = (new Factory)->withServiceAccount('ika-mboyz-firebase-adminsdk-v1dqb-ba0905467e.json');

        $messaging = $factory->createMessaging();
        $message = CloudMessage::fromArray([
            'topic' => 'bc_notif',
            'notification' => [
                'title'=>$request->title,
                'body' =>$request->message
            ],
            'data' => [
                'url' => $request->url
            ]
        ]);
            
        $messaging->send($message);

        $broadcast = Broadcast::create($request->all());

        return redirect()->route('staff.broadcasts.index')
            ->with('success', 'Broadcast created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $broadcast = Broadcast::find($id);

        return view('staff.broadcast.show', compact('broadcast'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $broadcast = Broadcast::find($id);

        return view('staff.broadcast.edit', compact('broadcast'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Broadcast $broadcast
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Broadcast $broadcast)
    {
        request()->validate(Broadcast::$rules);

        $broadcast->update($request->all());

        return redirect()->route('staff.broadcasts.index')
            ->with('success', 'Broadcast updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $broadcast = Broadcast::find($id)->delete();

        return redirect()->route('staff.broadcasts.index')
            ->with('success', 'Broadcast deleted successfully');
    }
}
