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
    'template_title' => __('Lihat Jolali')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Lihat Jolali')}}</span>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('staff.jolalis.edit',$jolali) }}"> Edit</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <img src="{{asset('storage/public/'.$jolali->image)}}" height="100" alt="Image">
                        </div>
                        <div class="form-group">
                            <strong>Text:</strong>
                            {{ $jolali->text }}
                        </div>
                        
                        <div class="form-group">
                            <strong>Link:</strong>
                            {{ $jolali->link }}
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

