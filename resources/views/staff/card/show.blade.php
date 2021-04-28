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
    'template_title' => __('Show Card')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{__('Show Card')}}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('staff.cards.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Card Number:</strong>
                            {{ $card->card_number }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $card->name }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $card->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
