@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Show Transaction Item')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{__('Show Transaction Item')}}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('staff.transaction_items.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Transaction Id:</strong>
                            {{ $transactionItem->transaction_id }}
                        </div>
                        <div class="form-group">
                            <strong>Product Id:</strong>
                            {{ $transactionItem->product_id }}
                        </div>
                        <div class="form-group">
                            <strong>Amount:</strong>
                            {{ $transactionItem->amount }}
                        </div>
                        <div class="form-group">
                            <strong>Total:</strong>
                            {{ $transactionItem->total }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
