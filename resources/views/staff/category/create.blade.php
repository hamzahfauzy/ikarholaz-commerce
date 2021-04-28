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
    'template_title' => __('Create Category')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Create Category')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.categories.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('staff.category.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
