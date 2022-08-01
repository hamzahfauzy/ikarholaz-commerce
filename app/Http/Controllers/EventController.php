<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\TransactionItem;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $event_category_id = config('reference.event_kategori');
        $events = Product::whereHas('categories', function($q) use ($event_category_id){
            $q->where('categories.id', $event_category_id);
        })->get();

        return view('events.index',compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $variants = ProductVariant::where('parent_id',$id)->pluck('product_id');
        $variants[] = $id;
        $transactionItems = TransactionItem::whereIn('product_id',$variants)->whereHas('transaction',function($q){
            $q->where('status','PAID');
        })->get();

        foreach($transactionItems as $item)
        {
            $participant_custom_fields = \App\Models\CustomField::where('class_target','App\Models\Event')->get();
            $participants = [];
            foreach($participant_custom_fields as $key => $value)
            {
                $cf_values = $value->customFieldValues()->where('pk_id',$item->id)->get();
                foreach($cf_values as $cf_value)
                {
                    $participants[$key][] = $cf_value->field_value;
                }
            }

            $flip = array_map(null, ...$participants);
            $item->participants = $flip;
        }


        return view('events.show',compact('transactionItems'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
