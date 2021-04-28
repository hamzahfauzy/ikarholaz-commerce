@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ],
        [
            'label' => __('Category'),
            'route' => route('staff.categories.index')
        ],
    ],
    'template_title' => __('Update Category')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Update Category')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.categories.update', $category->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('staff.category.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
