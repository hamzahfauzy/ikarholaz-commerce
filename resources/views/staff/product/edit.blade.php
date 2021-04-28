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
        ]
    ],
    'template_title' => __('Update Product')
])
    <section class="">
        <form method="POST" action="{{ $product->parent ? route('staff.product-variants.update',$product->id) : route('staff.products.update', $product->id) }}"  role="form" enctype="multipart/form-data">
        {{ method_field('PATCH') }}
        @csrf
        <div class="row">
            <div class="col-12">
                @includeif('partials.errors')
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @endif
            </div>
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
        @if(!$product->parent)
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Product Variant')}}</span>
                    </div>
                    <div class="card-body">
                        @include('staff.product.form-variation')
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
@endsection
