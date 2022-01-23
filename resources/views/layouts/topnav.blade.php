<!-- Navigation Bar-->
<header id="topnav">
    <div class="topbar-main navbar p-0">
        <div class="container-fluid">

            <!-- Logo container-->
            <div class="topbar-left">
                <!-- Text Logo -->
                <!--<a href="index.html" class="logo">-->
                    <!--Zircos-->
                <!--</a>-->
                <!-- Image Logo -->
                <a href="{{route('home')}}" class="logo">
                    <img src="{{asset('assets/images/logo_me.png')}}" alt="" height="30">
                </a>

            </div>
            <!-- End Logo container-->


            <div class="menu-extras">

                <ul class="nav navbar-right float-right">
                    <li class="navbar-c-items">
                        <form role="search" class="navbar-left app-search float-left d-none d-sm-inline-block">
                                <input type="text" placeholder="Search..." class="form-control">
                                <a href=""><i class="fa fa-search"></i></a>
                        </form>
                    </li>

                    <li class="dropdown navbar-c-items">
                        <a href="" class="right-menu-item dropdown-toggle" data-toggle="dropdown">
                            <i class="mdi mdi-cart"></i>
                            <span class="badge up badge-danger badge-pill">{{cart()->count()}}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right dropdown-lg user-list notify-list">
                            <li class="text-center">
                                <h5>{{__('Cart')}}</h5>
                            </li>
                            @foreach(cart()->lists() as $cart)
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="avatar">
                                        <img src="{{$cart->thumbnail}}" alt="" height="30px" style="object-fit:cover;">
                                    </div>
                                    <div class="user-desc">
                                        <span class="name"><b>{{$cart->parent?$cart->parent->parent->name.' - ':''}}{{$cart->name}}</b></span>
                                        <span class="desc">Qty : {{cart()->get($cart->id)}}</span>
                                        <span class="time" onclick="location='{{route('shop.cart-remove',$cart->id)}}'"><i class="fa fa-trash"></i></span>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                            <li class="all-msgs text-center">
                                <p class="m-0"><a href="{{route('shop.cart')}}">{{__('See all Cart')}}</a></p>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown navbar-c-items">
                        <a href="" class="dropdown-toggle waves-effect waves-light profile" data-toggle="dropdown" aria-expanded="true">
                            <img src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="user-img" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                            @if(auth()->guard('web')->check())
                            <li class="text-center">
                                <h5>Hi, {{auth()->user()->name}}</h5>
                            </li>
                            <li><a href="{{route('profile')}}" class="dropdown-item"><i class="ti-user m-r-5"></i> Profile</a></li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <i class="ti-power-off m-r-5"></i> Logout
                                </a>
                            </li>
                            @else
                            <li class="text-center">
                                <h5>Hi, Guest</h5>
                            </li>
                            <li><a href="{{route('login')}}" class="dropdown-item"><i class="ti-login m-r-5"></i> Login</a></li>
                            @endif

                        </ul>

                    </li>
                </ul>
                <div class="menu-item">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </div>
            </div>
            <!-- end menu-extras -->

        </div> <!-- end container-fluid -->
    </div>
    <!-- end topbar-main -->

    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">

                    <li>
                        <a href="{{route('home')}}"><i class="mdi mdi-view-dashboard"></i>{{__('Home')}}</a>
                    </li>

                    <li>
                        <a href="{{route('shop.index')}}"><i class="mdi mdi-shopping"></i>{{__('Shop')}}</a>
                    </li>
                    
                    <li>
                        <a href="{{route('nra')}}"><i class="mdi mdi-view-list"></i>{{__('List NRA')}}</a>
                    </li>

                    <li>
                        <a href="{{route('pending')}}"><i class="mdi mdi-view-list"></i>{{__('Pending Alumni')}}</a>
                    </li>

                    <li>
                        <a href="#"><i class="mdi mdi-view-list"></i>{{__('How To')}}</a>
                    </li>
                </ul>
                <!-- End navigation menu -->
            </div> <!-- end #navigation -->
        </div> <!-- end container-fluid -->
    </div> <!-- end navbar-custom -->
</header>
<!-- End Navigation Bar-->