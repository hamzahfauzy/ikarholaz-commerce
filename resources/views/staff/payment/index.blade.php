@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ]
    ],
    'template_title' => __('Payment')
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
                                {{ __('Payment') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('staff.payments.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Transaction</th>
										<th>Total</th>
										<th>Admin Fee</th>
										<th>Payment Type</th>
										<th>Status</th>
										<th>Date</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>
                                                <a href="{{route('staff.transaction-items.index',['transaction_id'=>$payment->transaction_id])}}">{{ $payment->transaction_id }}</a>
                                            </td>
											<td>{{ $payment->total_formated }}</td>
											<td>{{ $payment->admin_fee_formated }}</td>
											<td>{{ $payment->payment_type }}</td>
											<td>{{ $payment->status }}</td>
											<td>{{ $payment->created_at->format('Y-m-d') }}</td>

                                            <td>
                                                <form action="{{ route('staff.payments.destroy',$payment->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to delete this item ?')}}')){ return true }else{ return false }">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('staff.payments.show',$payment->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
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
                {!! $payments->links('vendor.pagination.bootstrap-4') !!}
            </div>
        </div>
    </div>
@endsection
