@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Create Transaction Item')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Create Transaction Item')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.transaction_items.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('staff.transaction-item.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
