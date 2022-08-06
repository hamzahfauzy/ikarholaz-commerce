@extends('staff.layouts.app')

@section('content')
@include('staff.partials.breadcrumbs',[
    'breadcrumbs' => [],
    'template_title' => 'Dashboard'
])

<div class="row mb-3">

    <div class="col-xl-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-30">Statistik Alumni</h4>

            <div class="row">
                <div class="col-xl-2 col-lg-4 col-sm-6">
                    <div class="card-box widget-box-one">
                        <i class="mdi mdi-account-multiple widget-one-icon"></i>
                        <div class="wigdet-one-content">
                            <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="Total Alumni">Total Alumni</p>
                            <h2>{{$alumnis}}</h2>
                        </div>
                    </div>
                </div><!-- end col -->
                <div class="col-xl-2 col-lg-4 col-sm-6">
                    <div class="card-box widget-box-one">
                        <i class="mdi mdi-account-multiple widget-one-icon"></i>
                        <div class="wigdet-one-content">
                            <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="Approved">Approved</p>
                            <h2>{{$alumnis_approved}}</h2>
                        </div>
                    </div>
                </div><!-- end col -->
                <div class="col-xl-2 col-lg-4 col-sm-6">
                    <div class="card-box widget-box-one">
                        <i class="mdi mdi-account-multiple widget-one-icon"></i>
                        <div class="wigdet-one-content">
                            <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="Pending">Pending</p>
                            <h2>{{$alumnis_pending}}</h2>
                        </div>
                    </div>
                </div><!-- end col -->

                <div class="col-xl-2 col-lg-4 col-sm-6">
                    <div class="card-box widget-box-one">
                        <i class="mdi mdi-account-multiple widget-one-icon"></i>
                        <div class="wigdet-one-content">
                            <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="Denied">Denied</p>
                            <h2>{{$alumnis_denied}}</h2>
                        </div>
                    </div>
                </div><!-- end col -->

                <div class="col-xl-2 col-lg-4 col-sm-6">
                    <div class="card-box widget-box-one">
                        <i class="mdi mdi-account-multiple widget-one-icon"></i>
                        <div class="wigdet-one-content">
                            <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="Died">Died</p>
                            <h2>{{$alumnis_died}}</h2>
                        </div>
                    </div>
                </div><!-- end col -->

                
            </div>
            <!-- end row -->

        </div> <!-- end card -->
    </div>
    <!-- end col -->

</div>
<!-- end row -->


<div class="row mb-3">
    <div class="col-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">Statistik Angkatan</h4>
            <div id="alumnis_angkatan" class="text-center morris-charts" style="height: 300px;"></div>
        </div>
    </div><!-- end col -->
</div>
<!-- end row -->

<div class="row mb-3">
    <div class="col-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-30">Statistik Event</h4>

            <div class="row">
                @foreach($events as $event)
                <div class="col-xl-2 col-lg-4 col-sm-6">
                    <div class="card-box widget-box-one">
                        <i class="mdi mdi-calendar widget-one-icon"></i>
                        <div class="wigdet-one-content">
                            <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="{{$event->name}}">{{$event->name}}</p>
                            <h2>{{$event->amount}}</h2>
                            <p class="text-muted m-0"><b>Total:</b> Rp.{{number_format($event->total)}}</p>
                        </div>
                    </div>
                </div><!-- end col -->
                @endforeach
                
            </div>
            <!-- end row -->

        </div> <!-- end card -->
    </div>
    <!-- end col -->

</div>
<!-- end row -->

<div class="row mb-3">
    <div class="col-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">Statistik Transaksi</h4>
            <div id="transactions" class="text-center morris-charts" style="height: 400px;"></div>
        </div>
    </div><!-- end col -->
</div>
<!-- end row -->


{{--
<div class="row mb-3">
    <div class="col-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-30">Statistik Transaksi</h4>

            <div class="row">
                @foreach($transactions as $transaction)
                <div class="col-xl-2 col-lg-4 col-sm-6">
                    <div class="card-box widget-box-one">
                        <i class="mdi mdi-cart widget-one-icon"></i>
                        <div class="wigdet-one-content">
                            <p class="m-0 text-uppercase font-600 font-secondary text-overflow" title="{{$transaction->name}}">{{$transaction->name}}</p>
                            <h2>{{$transaction->amount}}</h2>
                            <p class="text-muted m-0"><b>Total:</b> Rp.{{number_format($transaction->total)}}</p>
                        </div>
                    </div>
                </div><!-- end col -->
                @endforeach
                
            </div>
            <!-- end row -->

        </div> <!-- end card -->
    </div>
    <!-- end col -->

</div>
<!-- end row -->

--}}


@endsection

@section('script')
<script>
    var alumnis_angkatan  = {!! json_encode($alumnis_angkatan->toArray()) !!}
    Morris.Line({
        element: 'alumnis_angkatan',
        data: alumnis_angkatan,
        xkey: 'x',
        ykeys: ['y'],
        labels:  ['Total'],
        hideHover: 'auto',
    });

    var transactions  = {!! json_encode($transactions->toArray()) !!}
    Morris.Bar({
        element: 'transactions',
        data: transactions,
        xkey: 'name',
        ykeys: ['total'],
        labels:  ['Total'],
        hideHover: 'auto',
        preUnits:'Rp.'
    });
</script>
@endsection
