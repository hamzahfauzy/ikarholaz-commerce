@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ],
        [
            'label' => __('Blacklist'),
            'route' => route('staff.blacklist-nra.index')
        ],
    ],
    'template_title' => __('Buat Blacklist')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Buat Blacklist')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.blacklist-nra.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('staff.blacklist-nra.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

