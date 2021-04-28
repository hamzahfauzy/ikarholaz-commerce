@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Update Custom Field')
])
    <section class="">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Update Custom Field')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('custom-fields.update', $customField->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('staff.custom-field.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
