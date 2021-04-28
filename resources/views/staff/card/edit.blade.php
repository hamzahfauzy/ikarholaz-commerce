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
    'template_title' => __('Update Card')
])
    <section class="">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Update Card')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.cards.update', $card->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('staff.card.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
