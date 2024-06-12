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

                                    <form class="form-horizontal login-form" method="POST" action="{{ route('login-email') }}" style="width:282px;margin:auto">
                                        @csrf

                                        <div class="form-group">

                                            <label for="">Email</label>
                                            
                                            <input class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" type="email" required="" placeholder="Email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        
                                        <div class="form-group">

                                            <label for="">Password</label>
                                            
                                            <input class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}" type="password" required="" placeholder="Password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group account-btn text-center m-t-10">
                                            <div class="col-12">
                                                <button class="btn w-md btn-bordered btn-danger waves-effect waves-light" type="submit">Login</button>
                                            </div>
                                        </div>

                                    </form>

                                </div>
                            </div>
                            <!-- end card-box-->

                            <div class="row m-t-50">
                                <div class="col-sm-12 text-center">
                                    <p class="text-muted">Login with <a href="{{route('login')}}" class="text-primary m-l-5"><b>Phone Number</b></a></p>
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
    </body>
</html>