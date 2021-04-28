@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Create Transaction')
])
    <section class="">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Create Transaction')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.transactions.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('staff.transaction.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
