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
                    <h3 class="m-t-20">{{__('List Alumni')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="category-filter">
                                    <select id="categoryFilter" class="form-control select2">
                                        <option value="">Show All</option>
                                        @for($y = date('Y')-5; $y >= 1900; $y--)
                                        <option value="{{$y}}">{{$y}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <table class="table table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>Tahun Lulus</th>
                                            <th>Tanggal Register</th>
                                            <th>Gambar</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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

    var ajaxUrl = "{{url()->current()}}"
    $('.datatable').dataTable({
        processing: true,
        search: {
            return: true
        },
        pageLength: 50,
        serverSide: true,
        ajax: ajaxUrl
    })

    var table = $('.datatable').DataTable();
    $("#categoryFilter").change(function (e) {
        table.ajax.url(ajaxUrl + '?year=' + $('#categoryFilter').val()).load()
        table.draw();
    });
    table.draw();
</script>
@endsection