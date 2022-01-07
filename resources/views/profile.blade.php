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
                    <h3 class="m-t-20">{{__('Profile')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12 col-md-3">
                    <img src="{{Storage::url(auth()->user()->alumni->profile_pic)}}" alt="" width="100%">
                </div>
                <div class="col-12 col-md-9">
                    <?php $alumni = auth()->user()->alumni ?>
                    <table class="table table-bordered">
                        <tr>
                            <td>NRA</td>
                            <td>:</td>
                            <td>{{$alumni->NRA}}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{auth()->user()->name}}</td>
                        </tr>
                        <tr>
                            <td>Tahun Lulus</td>
                            <td>:</td>
                            <td>{{$alumni->graduation_year}}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>{{$alumni->gender}}</td>
                        </tr>
                        <tr>
                            <td>Tempat / Tanggal Lahir</td>
                            <td>:</td>
                            <td>{{$alumni->place_of_birth .' / '. $alumni->date_of_birth}}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{$alumni->address . ', ' . $alumni->city . ', ' . $alumni->province . ', ' . $alumni->country}}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>{{ucwords($alumni->approval_status)}} Oleh {{$alumni->approval_by}}</td>
                        </tr>
                    </table>
                    <a href="{{route('edit-profile')}}" class="btn btn-primary"><i class="ti-pencil"></i> Edit Profile</a>
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