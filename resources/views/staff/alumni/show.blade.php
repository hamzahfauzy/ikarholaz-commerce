@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
'breadcrumbs' => [],
'template_title' => __('Show Alumni')
])
<section class="">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <span class="card-title">{{__('Show Alumni')}}</span>
                    </div>
                    <div class="float-right">
                        <a class="btn btn-primary" href="{{ route('staff.alumnis.index') }}"> Back</a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <strong>Name:</strong>
                        {{ $alumni->name ?? '-' }}
                    </div>
                    <div class="form-group">
                        <strong>Graduation Year:</strong>
                        {{ $alumni->graduation_year ?? '-' }}
                    </div>
                    <div class="form-group">
                        <strong>Gender:</strong>
                        {{ $alumni->gender ?? '-' }}
                    </div>
                    <div class="form-group">
                        <strong>Address:</strong>
                        {{ $alumni->address ?? '-' }}
                    </div>
                    <div class="form-group">
                        <strong>City:</strong>
                        {{ $alumni->city ?? '-' }}
                    </div>
                    <div class="form-group">
                        <strong>Province:</strong>
                        {{ $alumni->province ?? '-' }}
                    </div>
                    <div class="form-group">
                        <strong>Country:</strong>
                        {{ $alumni->country ?? '-' }}
                    </div>
                    <div class="form-group">
                        <strong>Date of birth:</strong>
                        {{ $alumni->date_of_birth ?? '-' }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection