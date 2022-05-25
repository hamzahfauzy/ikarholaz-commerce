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
                    <h3 class="m-t-20">{{__('List Event')}}</h3>
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
                                            <th>Nama</th>
                                            <th>Venue</th>
                                            <th>Waktu</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($events as $key => $event)
                                        @php($customFields = App\Models\CustomField::where('class_target','App\Models\EventProduct')->get())
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td><a href="{{route('shop.product-detail',$event->slug)}}">{{$event->name}}</a></td>
                                            <td>{{$customFields[1]->get_value($event->id)->field_value}}</td>
                                            <td>{{date('d-m-Y H:i', strtotime(str_replace('T','',$customFields[0]->get_value($event->id)->field_value)))}}</td>
                                            <td>
                                                <a href="{{route('events.show',$event->id)}}" class="btn btn-primary">Lihat Peserta</a>
                                            </td>
                                        </tr>
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