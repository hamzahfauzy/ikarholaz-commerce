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
                    <h3 class="m-t-20">{{__('List NRA')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                {{--
                                <div class="category-filter">
                                    <select id="categoryFilter" class="form-control">
                                        <option value="">Show All</option>
                                        @for($y = date('Y')-5; $y >= 1900; $y--)
                                        <option>{{$y}}</option>
                                        @endfor
                                    </select>
                                </div>
                                --}}
                                <table class="table table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                            <th>NRA</th>
                                            <th>Tahun Lulus</th>
                                            <th>Kota</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="mb-4"></div>
                            <h4>Catatan : </h4>
                            <p style="text-align:justify">Data alumni terverifikasi adalah alumni yang melakukan pendaftaran anggota IKARHOLAZ sejak program KTA diluncurkan, triwulan pertama 2021, hingga sekarang. Data diatas hasil pendaftaran alumni melalui applikasi ( <a href="https://bit.ly/app-ika12">https://bit.ly/app-ika12</a> ) dan web ( <a href="https://bit.ly/daftar-ika12">https://bit.ly/daftar-ika12</a> ) secara mandiri. Petugas tidak menerima permintaan bantuan pendaftaran. Jika saat mendaftar muncul pesan error "Gagal membuat data" biasanya disebabkan anda melakukan pendaftaran berulang. Cek data anda di laman <a href="http://gerai.ikarholaz.id/pending">http://gerai.ikarholaz.id/pending</a> , jika nama anda muncul berarti data sudah masuk. Tunggu verifikasi petugas. Untuk mendapatkan NRA, anda cukup mendaftar/signup/register saja tanpa perlu login. Login hanya untuk melengkapi data pendukung (bukan syarat wajib).</p>
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
    top:20px;
}
/* .custom-filter-select {
    position:absolute;
    top:0;
} */
</style>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
    $('.datatable').dataTable({
        processing: true,
        search: {
            return: true
        },
        pageLength: 50,
        serverSide: true,
        ajax: "{{route('nra')}}"
    })

    // var table = $('.datatable').DataTable();
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

    // $("#categoryFilter").change(function (e) {
    //     table.draw();
    // });
    // table.draw();
</script>
@endsection