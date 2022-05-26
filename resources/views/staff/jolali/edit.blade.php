@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ],
        [
            'label' => __('Jolali'),
            'route' => route('staff.jolalis.index')
        ],
    ],
    'template_title' => __('Edit Jolali')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Edit Jolali')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.jolalis.update', $jolali->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('staff.jolali.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
