@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => __('Update Payment')
])
    <section class="">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{__('Update Payment')}}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.payments.update', $payment->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('staff.payment.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
