<?php

namespace App\Http\Controllers\Staff;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CardController
 * @package App\Http\Controllers
 */
class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::paginate();

        return view('staff.card.index', compact('cards'))
            ->with('i', (request()->input('page', 1) - 1) * $cards->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $card = new Card();
        return view('staff.card.create', compact('card'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Card::$rules);

        $card = Card::create($request->all());

        return redirect()->route('staff.cards.index')
            ->with('success', 'Card created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::find($id);

        return view('staff.card.show', compact('card'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $card = Card::find($id);

        return view('staff.card.edit', compact('card'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Card $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        request()->validate(Card::$rules);

        $card->update($request->all());

        return redirect()->route('staff.cards.index')
            ->with('success', 'Card updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $card = Card::find($id)->delete();

        return redirect()->route('staff.cards.index')
            ->with('success', 'Card deleted successfully');
    }
}
