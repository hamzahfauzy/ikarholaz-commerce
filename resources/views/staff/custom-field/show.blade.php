@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Show Custom Field')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{__('Show Custom Field')}}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('custom-fields.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Field Key:</strong>
                            {{ $customField->field_key }}
                        </div>
                        <div class="form-group">
                            <strong>Field Type:</strong>
                            {{ $customField->field_type }}
                        </div>
                        <div class="form-group">
                            <strong>Class Target:</strong>
                            {{ $customField->class_target }}
                        </div>
                        <div class="form-group">
                            <strong>Query Condition:</strong>
                            {{ $customField->query_condition }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
