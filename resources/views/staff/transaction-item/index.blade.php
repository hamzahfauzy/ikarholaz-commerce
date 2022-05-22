@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ],
        [
            'label' => 'Transaction',
            'route' => route('staff.transactions.index')
        ]
    ],
    'template_title' => 'Detail '.$transactionItems[0]->transaction->customer->full_name
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
                                {{ __('Transaction Detail') }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Product</th>
										<th>Amount</th>
										<th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactionItems as $transactionItem)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>
                                            <a href="{{route('staff.products.edit',$transactionItem->product->id)}}">{{ $transactionItem->product->name }}</a>
                                            <br>
                                            @foreach($transactionItem->product->custom_fields as $cf)
                                            {{Form::label($cf->customField->field_key)}} : {{ $cf->field_value }}<br>
                                            @endforeach
                                            </td>
											<td>{{ $transactionItem->amount }}</td>
											<td>{{ $transactionItem->total_formated }}</td>
                                        </tr>
                                        @foreach($transactionItem->custom_fields as $cf)
                                        <tr>
                                            <td></td>
                                            
											<td>{{Form::label($cf->customField->field_key)}} : {{ $cf->field_value }}</td>
											<td></td>
											<td></td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                        @if($transactionItems[0]->transaction->shipping)
                                        <tr>
                                            <td></td>
                                            <td>
                                            Shipping :<br>
                                            {{$transactionItems[0]->transaction->shipping->province_name.', '.$transactionItems[0]->transaction->shipping->district_name}}<br>
                                            {{$transactionItems[0]->transaction->shipping->courir_name.' ('.$transactionItems[0]->transaction->shipping->service_name.')'}}
                                            </td>
                                            <td></td>
                                            <td>{{$transactionItems[0]->transaction->shipping->service_rates_formated}}</td>
                                        </tr>
                                        @if($transactionItems[0]->transaction->shipping->resi_number == NULL)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <form action="{{route('staff.update-shipping',$transactionItems[0]->transaction_id)}}" method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="">Update Nomor Resi :</label>
                                                        <input type="text" class="form-control" name="resi_number" required>
                                                    </div>
                                                    <button class="btn btn-success">Update</button>
                                                </form>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td></td>
                                            <td>Nomor Resi</td>
                                            <td>{{$transactionItems[0]->transaction->shipping->resi_number}}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @endif
                                        <tr>
                                            <td></td>
                                            <td>Admin Fee</td>
                                            <td></td>
                                            <td>{{$transactionItems[0]->transaction->payment->admin_fee_formated}}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td>{{$transactionItems[0]->transaction->total_formated}}</td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
