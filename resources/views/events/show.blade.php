@extends('layouts.app')

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row text-center">
                <div class="col-sm-12">
                    <h3 class="m-t-20">{{__('List Peserta')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Peserta</th>
                                            <th>Tahun Lulus</th>
                                            <th>Kode Booking</th>
                                            <th>Tanggal Order</th>
                                            <th>Biaya Tiket</th>
                                            <th>Nominal Donasi</th>
                                            <th>Nama Pemesan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($no=1)
                                        @foreach($transactionItems as $key => $item)
                                        @foreach($item->participants as $participant)
                                        <tr>
                                            <td>{{$no++}}</td>
                                            <td>{{$participant[0]}}</td>
                                            <td>{{$participant[1]}}</td>
                                            <td>{{$item->transaction->id}}</td>
                                            <td>{{$item->transaction->created_at->format('d-m-Y H:i')}}</td>
                                            <td>{{$item->total_formated}}</td>
                                            <td>{{$item->transaction->transactionItems[1]->total_formated}}</td>
                                            <td>{{$item->transaction->customer->full_name}}</td>
                                        </tr>
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mb-4"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->


        </div> <!-- container -->

    </div> <!-- content -->

</div>


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
@endsection

@section('script')
<link rel="stylesheet" href="{{asset('plugins/datatables/jquery.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css')}}">
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
$('.datatable').dataTable()
</script>
@endsection