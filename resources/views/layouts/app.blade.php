<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('front/images/favicon.ico')}}">
        <!-- App title -->
        <title>@yield('title',config('app.name', 'Laravel'))</title>

        @yield('css')

        <!-- App css -->
        <link rel="stylesheet" type="text/css" href="{{asset('front/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('front/css/icons.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('front/css/style.css')}}" />

        <script src="{{asset('front/js/modernizr.min.js')}}"></script>

    </head>

    <body>

        @include('layouts.topnav')

        <div class="wrapper">
            @yield('content')
            <!-- End container-fluid -->

            <!-- Footer -->
            <footer class="footer text-right">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            2021 © {{config('app.name', 'Laravel')}}
                        </div>
                    </div>
                </div>
            </footer>
            <!-- End Footer -->
            
        </div>
        <!-- End wrapper -->

        <!-- jQuery  -->
        <script src="{{asset('front/js/jquery.min.js')}}"></script>
        <script src="{{asset('front/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('front/js/detect.js')}}"></script>
        <script src="{{asset('front/js/jquery.blockUI.js')}}"></script>
        <script src="{{asset('front/js/waves.js')}}"></script>
        <script src="{{asset('front/js/jquery.slimscroll.js')}}"></script>
        <script src="{{asset('front/js/jquery.scrollTo.min.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('front/js/jquery.core.js')}}"></script>
        <script src="{{asset('front/js/jquery.app.js')}}"></script>
        @yield('script')
    </body>
</html>