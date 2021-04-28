@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Show Custom Field Value')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{__('Show Custom Field Value')}}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('custom-field-values.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Custom Field Id:</strong>
                            {{ $customFieldValue->custom_field_id }}
                        </div>
                        <div class="form-group">
                            <strong>Pk Id:</strong>
                            {{ $customFieldValue->pk_id }}
                        </div>
                        <div class="form-group">
                            <strong>Field Value:</strong>
                            {{ $customFieldValue->field_value }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
