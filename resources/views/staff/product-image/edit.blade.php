@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Update Product Image')
])
    <section class="">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Update Product Image')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.product-images.update', $productImage->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('staff.product-image.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
