@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
'breadcrumbs' => [],
'template_title' => __('Create Alumni')
])
<section class="">
    <div class="row">
        <div class="col-md-12">

            @includeif('partials.errors')

            <div class="card card-default">
                <div class="card-header">
                    <span class="card-title">{{__('Create Alumni')}}</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.alumnis.store') }}" role="form" enctype="multipart/form-data">
                        @csrf

                        @include('staff.alumni.form')

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection