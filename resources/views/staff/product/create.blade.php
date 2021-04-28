@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ],
        [
            'label' => __('Product'),
            'route' => route('staff.products.index')
        ],
    ],
    'template_title' => __('Create Product')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                @includeif('partials.errors')
            </div>
        </div>
        <form method="POST" action="{{ route('staff.products.store') }}"  role="form" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Product Description')}}</span>
                    </div>
                    <div class="card-body">
                        @include('staff.product.form')
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                @include('staff.product.attribute')    
                <button type="submit" class="btn btn-primary btn-block">{{__('Save')}}</button>
            </div>
        </div>
        </form>
    </section>
@endsection
