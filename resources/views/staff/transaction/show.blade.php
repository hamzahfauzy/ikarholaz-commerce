@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Show Transaction')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{__('Show Transaction')}}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('staff.transactions.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Customer Id:</strong>
                            {{ $transaction->customer_id }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $transaction->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
