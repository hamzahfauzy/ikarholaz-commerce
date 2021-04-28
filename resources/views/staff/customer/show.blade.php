@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Show Customer')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{__('Show Customer')}}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('staff.customers.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $customer->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>First Name:</strong>
                            {{ $customer->first_name }}
                        </div>
                        <div class="form-group">
                            <strong>Last Name:</strong>
                            {{ $customer->last_name }}
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            {{ $customer->email }}
                        </div>
                        <div class="form-group">
                            <strong>Province Id:</strong>
                            {{ $customer->province_id }}
                        </div>
                        <div class="form-group">
                            <strong>District Id:</strong>
                            {{ $customer->district_id }}
                        </div>
                        <div class="form-group">
                            <strong>Subdistrict Id:</strong>
                            {{ $customer->subdistrict_id }}
                        </div>
                        <div class="form-group">
                            <strong>Address:</strong>
                            {{ $customer->address }}
                        </div>
                        <div class="form-group">
                            <strong>Postal Code:</strong>
                            {{ $customer->postal_code }}
                        </div>
                        <div class="form-group">
                            <strong>Phone Number:</strong>
                            {{ $customer->phone_number }}
                        </div>
                        <div class="form-group">
                            <strong>Province Name:</strong>
                            {{ $customer->province_name }}
                        </div>
                        <div class="form-group">
                            <strong>District Name:</strong>
                            {{ $customer->district_name }}
                        </div>
                        <div class="form-group">
                            <strong>Subdistrict Name:</strong>
                            {{ $customer->subdistrict_name }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
