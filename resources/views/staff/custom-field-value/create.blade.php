@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Create Custom Field Value')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Create Custom Field Value')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('custom-field-values.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('staff.custom-field-value.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
