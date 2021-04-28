@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Create Product Variant')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Create Product Variant')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.product-variants.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('staff.product-variant.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
