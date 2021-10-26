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
    'template_title' => __('Show Broadcast')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Broadcast</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('broadcasts.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $broadcast->title }}
                        </div>
                        <div class="form-group">
                            <strong>Message:</strong>
                            {{ $broadcast->message }}
                        </div>
                        <div class="form-group">
                            <strong>Url:</strong>
                            {{ $broadcast->url }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
