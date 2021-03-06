<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Aplikasi Sistem Keanggotaan IKA SMPN 12 Surabaya.">
        <meta name="author" content="HTD">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
        <!-- App title -->
        <title>Ikarholaz - Register</title>

        <!-- App css -->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/icons.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}" />
        <link rel="stylesheet" href="{{asset('plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}">


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

                                    <form class="form-horizontal" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">

                                            <label for="">Nama</label>
                                            
                                            <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}" type="text" required="" placeholder="Nama">

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="">Kelas</label>
                                            <input type="text" name="class_name" value="{{old('class_name')}}" class="form-control" data-role="tagsinput">
                                            @error('class_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tahun Masuk</label>
                                            <select name="year_in" class="form-control @error('year_in') is-invalid @enderror" id="">
                                                <option value="">- Pilih Tahun -</option>
                                                @for($y=date('Y')-2;$y>=1971;$y--)
                                                <option {{old('year_in') == $y ? 'selected' : ''}}>{{$y}}</option>
                                                @endfor
                                            </select>
                                            @error('year_in')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">

                                            <label for="">Tahun Lulus</label>

                                            <select name="graduation_year" class="form-control @error('graduation_year') is-invalid @enderror" id="">
                                                <option value="">- Pilih Tahun -</option>
                                                @for($y=date('Y');$y>=1974;$y--)
                                                @if($y==1978)
                                                @continue
                                                @endif
                                                <option {{old('graduation_year') == $y ? 'selected' : ''}}>{{$y}}</option>
                                                @endfor
                                            </select>
                                            
                                            @error('graduation_year')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">

                                            <label for="">No WA (081234xxxxxxxx)</label>
                                            
                                            <input class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{old('phone')}}" type="number" required="" placeholder="No HP">

                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">

                                            <label for="">Photo (Max. 2MB)</label>
                                            
                                            <input class="form-control @error('photo') is-invalid @enderror" name="photo" value="{{old('photo')}}" type="file" required="" placeholder="Photo">

                                            @error('photo')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group account-btn text-center m-t-10">
                                            <div class="col-12">
                                                <button class="btn w-md btn-bordered btn-danger waves-effect waves-light" type="submit">Register</button>
                                            </div>
                                        </div>

                                    </form>

                                    <div class="clearfix"></div>

                                </div>
                            </div>
                            <!-- end card-box-->


                            <div class="row m-t-50">
                                <div class="col-sm-12 text-center">
                                    <p class="text-muted">Have an account? <a href="{{route('login')}}" class="text-primary m-l-5"><b>Sign In</b></a></p>
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
        <script src="{{asset('plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('assets/js/jquery.core.js')}}"></script>
        <script src="{{asset('assets/js/jquery.app.js')}}"></script>

    </body>
</html>