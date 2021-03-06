@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ]
    ],
    'template_title' => __('Transaction')
])
    <div class="">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif
                    
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Transaction') }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Customer</th>
										<th>Produk</th>
										<th>Total</th>
										<th>Status</th>
										<th>Date</th>
										<th>Nomor Resi</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $transaction->customer->full_name }}</td>
                                            @if(count($transaction->transactionItems) && $transaction->transactionItems[0]->product)
											<td>{{ $transaction->transactionItems[0]->product->name }}</td>
                                            @else
                                            <td></td>
                                            @endif
											<td>{{ $transaction->total_formated }}</td>
											<td>{{ $transaction->status }}</td>
											<td>{{ $transaction->created_at->format('d/m/Y') }}</td>
											<td>
                                            {{ $transaction->shipping ? ($transaction->shipping->resi_number != NULL ? $transaction->shipping->resi_number : 'Belum ada nomor resi') : '-' }}
                                            </td>

                                            <td>
                                                <form action="{{ route('staff.transactions.destroy',$transaction->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to delete this item ?')}}')){ return true }else{ return false }">
                                                    @if($transaction->status == 'checkout')
                                                    <a class="btn btn-sm btn-success " href="{{ route('staff.transactions.approve',$transaction->id) }}" onclick="if(confirm('Apakah anda yakin akan menyetujui transaksi ini ?')){ return true }else{ return false }"><i class="fa fa-fw fa-check"></i> Approve</a>
                                                    <a class="btn btn-sm btn-warning " href="{{ route('staff.transactions.cancel',$transaction->id) }}" onclick="if(confirm('Apakah anda yakin akan membatalkan transaksi ini ?')){ return true }else{ return false }"><i class="fa fa-fw fa-times"></i> Cancel</a>
                                                    @else
                                                    <a class="btn btn-sm btn-secondary " href="{{ route('staff.transactions.resend',$transaction->id) }}" onclick="if(confirm('Apakah anda yakin akan mengirim ulang notifikasi pada transaksi ini ?')){ return true }else{ return false }">Resend</a>
                                                    @endif
                                                    <a class="btn btn-sm btn-primary " href="{{ route('staff.transaction-items.index',['transaction_id'=>$transaction->id]) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{__('Delete')}}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $transactions->links('vendor.pagination.bootstrap-4') !!}
            </div>
        </div>
    </div>
@endsection
