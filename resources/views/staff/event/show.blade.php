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
    'template_title' => __('Lihat Kegiatan')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Lihat Kegiatan')}}</span>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('staff.events.edit',$event) }}"> Edit</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <img src="{{asset('storage/public/'.$event->image)}}" height="100" alt="Image">
                        </div>
                        <div class="form-group">
                            <strong>Nama:</strong>
                            {{ $event->name }}
                        </div>
                        <div class="form-group">
                            <strong>Kategori:</strong>
                            {{ $event->category }}
                        </div>
                        <div class="form-group">
                            <strong>Deskripsi:</strong>
                            {{ $event->description }}
                        </div>
                        <div class="form-group">
                            <strong>Waktu Mulai:</strong>
                            {{ $event->start_time }}
                        </div>
                        <div class="form-group">
                            <strong>Waktu Selesai:</strong>
                            {{ $event->end_time }}
                        </div>
                        <div class="form-group">
                            <strong>Tempat:</strong>
                            {{ $event->place }}
                        </div>
                        <div class="form-group">
                            <strong>Lokasi:</strong>
                            {{ $event->location }}
                        </div>
                        <div class="form-group">
                            <strong>PIC:</strong>
                            {{ $event->PIC }}
                        </div>
                        <div class="form-group">
                            <strong>Tag:</strong>
                            {{ $event->tag }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

