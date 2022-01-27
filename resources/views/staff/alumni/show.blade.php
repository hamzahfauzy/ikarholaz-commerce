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
                        @if ($alumni->approval_status == '' && $alumni->NRA)
                        <form action="{{ route('staff.alumnis.approve',$alumni->id) }}" method="POST" onsubmit="if(confirm('{{__('Are you sure to approve this item ?')}}')){ return true }else{ return false }">
                            @csrf
                            <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-check"></i> {{__('Approve')}}</button>
                            <a class="btn btn-primary" href="{{ route('staff.alumnis.index') }}"> Back</a>
                        </form>
                        @endif
                    </div>
                </div>

                
                <div class="card-body">
                    
                    <img src="{{asset('storage/public/'.$alumni->profile_pic)}}" alt="" height="200px" class="mb-2">

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