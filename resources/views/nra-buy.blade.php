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
                    <h3 class="m-t-20">{{__('NRA Sudah dibeli')}}</h3>
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
                                            <th>NRA</th>
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
<style>
.custom-filter {
    position: absolute;
    top:10px;
}
.custom-filter-select {
    position:absolute;
    top:0;
}
</style>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
    var ajaxUrl = "{{route('nra.buy')}}"
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
    // $(".datatable.dataTables_filter").append($("#categoryFilter"));
    // var categoryIndex = 0;
    // $(".datatable th").each(function (i) {
    //     if ($($(this)).html() == "Tahun Lulus") {
    //         categoryIndex = i; return false;
    //     }
    // });
    // $.fn.dataTable.ext.search.push(
    //     function (settings, data, dataIndex) {
    //         var selectedItem = $('#categoryFilter').val()
    //         var category = data[categoryIndex];
    //         if (selectedItem === "" || category.includes(selectedItem)) {
    //             return true;
    //         }
    //         return false;
    //     }
    // );

    table.draw();
</script>
@endsection