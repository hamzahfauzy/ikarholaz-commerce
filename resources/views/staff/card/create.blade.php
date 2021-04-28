@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ],
        [
            'label' => __('Card'),
            'route' => route('staff.cards.index')
        ]
    ],
    'template_title' => __('Create Card')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Create Card')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.cards.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('staff.card.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
