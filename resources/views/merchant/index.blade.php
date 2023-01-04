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
                    <h3 class="m-t-20">{{__('Merchant Section')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-3 m-auto">
                    @if($errors->any())
                    <div class="alert alert-danger">{{$errors->first()}}</div>
                    @endif

                    @if($otp_status)
                    <form action="{{route('merchant.verify-otp')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="">OTP</label>
                            <input type="tel" class="form-control" name="otp" placeholder="Masukkan OTP">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary">Verify OTP</button>
                        </div>
                    </form>
                    @else
                    <form action="{{route('merchant.send-otp')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="">No WA</label>
                            <input type="tel" class="form-control" name="phone" placeholder="Masukkan No WA Merchant">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary">Kirim OTP</button>
                        </div>
                    </form>
                    @endif
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