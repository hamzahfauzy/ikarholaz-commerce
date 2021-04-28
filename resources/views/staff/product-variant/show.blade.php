@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Show Product Variant')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{__('Show Product Variant')}}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('staff.product-variants.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Product Id:</strong>
                            {{ $productVariant->product_id }}
                        </div>
                        <div class="form-group">
                            <strong>Parent Id:</strong>
                            {{ $productVariant->parent_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
