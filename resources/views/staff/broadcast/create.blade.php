@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ],
        [
            'label' => __('Broadcast'),
            'route' => route('staff.broadcasts.index')
        ]
    ],
    'template_title' => __('Create Broadcast')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create Broadcast</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.broadcasts.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('staff.broadcast.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
