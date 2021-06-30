@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
'breadcrumbs' => [
[
'label' => 'Dashboard',
'route' => route('staff.index')
]
],
'template_title' => __('Import Alumni')
])
<div class="">

    <div class="row">
        <div class="col-sm-12">

            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
            @endif

            @if ($message = Session::get('fail'))
            <div class="alert alert-danger">
                {{ $message }}
            </div>
            @endif

            <form method="POST" action="{{ route('staff.alumnis.import') }}" role="form" enctype="multipart/form-data">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Import Alumni')}}</span>
                    </div>
                    <div class="card-body">
                        @csrf

                        <div class="custom-file">
                            <input type="file" name="import" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>

                    </div>
                </div>

                <div class="box-footer mt20">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection