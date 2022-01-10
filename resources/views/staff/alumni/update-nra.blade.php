@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
'breadcrumbs' => [],
'template_title' => __('Update NRA')
])
<section class="">
    <div class="">
        <div class="col-md-12">

            @includeif('partials.errors')

            <div class="card card-default">
                <div class="card-header">
                    <span class="card-title">{{__('Update NRA')}} - {{$alumni->name}}</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.alumnis.update-nra', $alumni->id) }}" role="form" enctype="multipart/form-data">
                        @csrf
                        <div class="box box-info padding-1">
                            <div class="box-body">
                                <div class="form-group">
                                    {{ Form::label('NRA') }}
                                    {{ Form::text('NRA', $alumni->NRA, ['class' => 'form-control' . ($errors->has('NRA') ? ' is-invalid' : ''), 'placeholder' => 'NRA']) }}
                                    {!! $errors->first('NRA', '<p class="invalid-feedback">:message</p>') !!}
                                </div>
                            </div>
                            <div class="box-footer mt20">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection