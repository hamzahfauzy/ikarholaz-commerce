@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Show Payment')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{__('Show Payment')}}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('staff.payments.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Transaction Id:</strong>
                            {{ $payment->transaction_id }}
                        </div>
                        <div class="form-group">
                            <strong>Total:</strong>
                            {{ $payment->total }}
                        </div>
                        <div class="form-group">
                            <strong>Admin Fee:</strong>
                            {{ $payment->admin_fee }}
                        </div>
                        <div class="form-group">
                            <strong>Checkout Url:</strong>
                            {{ $payment->checkout_url }}
                        </div>
                        <div class="form-group">
                            <strong>Payment Type:</strong>
                            {{ $payment->payment_type }}
                        </div>
                        <div class="form-group">
                            <strong>Merchant Ref:</strong>
                            {{ $payment->merchant_ref }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $payment->status }}
                        </div>
                        <div class="form-group">
                            <strong>Payment Reference:</strong>
                            {{ $payment->payment_reference }}
                        </div>
                        <div class="form-group">
                            <strong>Payment Code:</strong>
                            {{ $payment->payment_code }}
                        </div>
                        <div class="form-group">
                            <strong>Expired Time:</strong>
                            {{ $payment->expired_time }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
