<?php

namespace App\Http\Controllers\Staff;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Libraries\NotifAction;
use App\Http\Controllers\Controller;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::orderby('id','desc')->paginate();

        return view('staff.transaction.index', compact('transactions'))
            ->with('i', (request()->input('page', 1) - 1) * $transactions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaction = new Transaction();
        return view('staff.transaction.create', compact('transaction'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Transaction::$rules);

        $transaction = Transaction::create($request->all());

        return redirect()->route('staff.transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);

        return view('staff.transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = Transaction::find($id);

        return view('staff.transaction.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        request()->validate(Transaction::$rules);

        $transaction->update($request->all());

        return redirect()->route('staff.transactions.index')
            ->with('success', 'Transaction updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id)->delete();

        return redirect()->route('staff.transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }

    public function patchStatus(Transaction $transaction, $status)
    {
        $transaction->update([
            'status' => $status
        ]);
    }

    public function resend(Transaction $transaction)
    {
        $product  = $transaction->transactionItems[0]->product;
        $customer = $transaction->customer;
        $payment  = $transaction->payment;

        $notifAction = new NotifAction;
        $notifAction->paymentSuccess($product, $customer, $transaction, $payment);

        return redirect()->route('staff.transactions.index')
            ->with('success', 'Notification resend successfully');
    }

    public function approve(Transaction $transaction)
    {
        $this->patchStatus($transaction, 'PAID');

        $product  = $transaction->transactionItems[0]->product;
        $customer = $transaction->customer;
        if($transaction->payment)
        {
            $payment  = $transaction->payment;
    
            $payment->update([
                'status' => 'PAID'
            ]);
            
            $notifAction = new NotifAction;
            $notifAction->paymentSuccess($product, $customer, $transaction, $payment);
        }


        return redirect()->route('staff.transactions.index')
            ->with('success', 'Transaction approved successfully');
    }

    public function cancel(Transaction $transaction)
    {
        $this->patchStatus($transaction, 'CANCELED');

        foreach($transaction->transactionItems as $item)
        {
            $product = $item->product;
            if($product->is_dynamic) continue;

            $product->update([
                'stock' => $product->stock + $item->amount
            ]);
        }

        return redirect()->route('staff.transactions.index')
            ->with('success', 'Transaction canceled successfully');
    }
}
