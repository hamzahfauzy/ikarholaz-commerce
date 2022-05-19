@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [
        [
            'label' => 'Dashboard',
            'route' => route('staff.index')
        ],
        [
            'label' => __('Kegiatan'),
            'route' => route('staff.events.index')
        ],
    ],
    'template_title' => __('Edit Kegiatan')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Edit Kegiatan')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.events.update', $event->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('staff.event.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
