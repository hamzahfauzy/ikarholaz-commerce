<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
        <!-- App title -->
        <title>Ikarholaz - Login</title>

        <!-- App css -->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/icons.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}" />


        <script src="{{asset('assets/js/modernizr.min.js')}}"></script>

    </head>


    <body class="bg-transparent">

        <!-- HOME -->
        <section>
            <div class="container-alt">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="wrapper-page">

                            <div class="m-t-40 account-pages">
                                <div class="text-center account-logo-box">
                                    <div class="m-t-10 m-b-10">
                                        <a href="{{route('home')}}" class="text-success">
                                            <span><img src="{{asset('assets/images/logo_me.png')}}" alt="" height="36"></span>
                                        </a>
                                    </div>
                                    <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                                </div>
                                <div class="account-content">

                                    @if (\Session::has('success'))
                                        <div class="alert alert-success">{!! \Session::get('success') !!}</div>
                                    @endif

                                    @if (\Session::has('failed'))
                                        <div class="alert alert-danger">{!! \Session::get('failed') !!}</div>
                                    @endif

                                    <form class="form-horizontal" method="POST" action="{{ route('otp') }}" onsubmit="return false">
                                        @csrf
                                        <div class="form-group">

                                            <label for="">OTP</label>
                                            
                                            <input class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{old('otp')}}" type="text" required="" placeholder="OTP">

                                            @error('otp')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- <div class="form-group row">
                                            <div class="col-12">
                                                <div class="checkbox checkbox-success pl-1">
                                                    <input id="checkbox-signup" type="checkbox" {{ old('remember') ? 'checked' : '' }} name="remember">
                                                    <label for="checkbox-signup">
                                                        Remember me
                                                    </label>
                                                </div>

                                            </div>
                                        </div> -->

                                        <div class="form-group account-btn text-center m-t-10">
                                            <div class="col-12">
                                                <button class="btn w-md btn-bordered btn-danger waves-effect waves-light btn-otp" type="submit" onclick="handleOtp()">Submit</button>
                                            </div>
                                        </div>

                                    </form>

                                    <div class="clearfix"></div>

                                </div>
                            </div>
                            <!-- end card-box-->


                            <div class="row m-t-50">
                                <div class="col-sm-12 text-center">
                                    <p class="text-muted">Don't have an account? <a href="{{route('register')}}" class="text-primary m-l-5"><b>Sign Up</b></a></p>
                                </div>
                            </div>

                        </div>
                        <!-- end wrapper -->

                    </div>
                </div>
            </div>
          </section>
          <!-- END HOME -->

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/js/detect.js')}}"></script>
        <script src="{{asset('assets/js/fastclick.js')}}"></script>
        <script src="{{asset('assets/js/jquery.blockUI.js')}}"></script>
        <script src="{{asset('assets/js/waves.js')}}"></script>
        <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>
        <script src="{{asset('assets/js/jquery.scrollTo.min.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('assets/js/jquery.core.js')}}"></script>
        <script src="{{asset('assets/js/jquery.app.js')}}"></script>
        <script src="{{asset('js/firebase.js')}}"></script>
        <script>
            var postedData = JSON.parse(localStorage.getItem("postData"))
            function handleOtp()
            {
                var btn_otp = document.querySelector('.btn-otp')
                btn_otp.innerHTML = "Memverifikasi OTP..."
                var otp = document.querySelector('input[name=otp]').value
                if (otp) {
                    postedData.confirmationResult.confirm(otp).then( async () => {
                        var formData = new FormData
                        formData.append('phone',postedData.phone)
                        formData.append('otp',otp)
                        formData.append('token_data',postedData.token_data)
                        fetch('{{ route('login') }}',{
                            method:'POST',
                            headers: {
                                "X-CSRF-Token": document.querySelector('input[name="_token"]').value
                            },
                            body:formData
                        })
                        .then(res => res.json())
                        .then(res => {
                            if(res.status == 'success')
                            {
                                window.location = '{{url()->to('/')}}'
                            }
                            else
                            {
                                alert("OTP Tidak Valid")
                                btn_otp.innerHTML = "Submit"
                            }
                        })
                    }).catch((error) => {
                        console.error(error)
                        btn_otp.innerHTML = "Submit"
                    });

                } else {
                    alert("Lengkapi otp terlebih dahulu");
                }
            }
        </script>

    </body>
</html>