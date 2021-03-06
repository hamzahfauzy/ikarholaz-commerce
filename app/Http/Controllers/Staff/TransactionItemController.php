<?php

namespace App\Http\Controllers\Staff;

use App\Models\WaBlast;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use App\Http\Controllers\Controller;

/**
 * Class TransactionItemController
 * @package App\Http\Controllers
 */
class TransactionItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactionItems = TransactionItem::where($_GET)->paginate();

        return view('staff.transaction-item.index', compact('transactionItems'))
            ->with('i', (request()->input('page', 1) - 1) * $transactionItems->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transactionItem = new TransactionItem();
        return view('staff.transaction-item.create', compact('transactionItem'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(TransactionItem::$rules);

        $transactionItem = TransactionItem::create($request->all());

        return redirect()->route('staff.transaction_items.index')
            ->with('success', 'TransactionItem created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transactionItem = TransactionItem::find($id);

        return view('staff.transaction-item.show', compact('transactionItem'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transactionItem = TransactionItem::find($id);

        return view('staff.transaction-item.edit', compact('transactionItem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  TransactionItem $transactionItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionItem $transactionItem)
    {
        request()->validate(TransactionItem::$rules);

        $transactionItem->update($request->all());

        return redirect()->route('staff.transaction_items.index')
            ->with('success', 'TransactionItem updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $transactionItem = TransactionItem::find($id)->delete();

        return redirect()->route('staff.transaction_items.index')
            ->with('success', 'TransactionItem deleted successfully');
    }

    public function updateShipping(Request $request, Transaction $transaction)
    {
        $transaction->shipping->update([
            'resi_number' => $request->resi_number
        ]);

        $message = "Hai Kak ".$transaction->customer->full_name.". Barang pesanan kakak telah dikirim ke alamat ".$transaction->shipping->address." via ".$transaction->shipping->courir_name.".
No resinya ".$request->resi_number.".
Pakai no resi ini untuk melacak pengiriman melalui web ".$transaction->shipping->courir_name;
        WaBlast::send($transaction->customer->phone_number,$message);

        return redirect()->route('staff.transaction-items.index',['transaction_id'=>$transaction->id])
            ->with('success', 'Resi Number updated successfully');
    }
}
