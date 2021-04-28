<!-- Top Bar Start -->
<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <!-- Image logo -->
        <a href="{{route('staff.index')}}" class="logo">
            <span>
                <img src="{{asset('assets/images/logo_me.png')}}" alt="" height="30">
            </span>
            <i>
                <img src="{{asset('assets/images/logo_me.png')}}" alt="" height="28">
            </i>
        </a>
    </div>

    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">

            <div class="clearfix">
                <!-- Navbar-left -->
                <ul class="nav navbar-left">
                    <li>
                        <button class="button-menu-mobile open-left waves-effect">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>
                    <li class="d-none d-sm-inline-block">
                        <form role="search" class="app-search">
                            <input type="text" placeholder="Search..."
                                class="form-control">
                            <a href=""><i class="fa fa-search"></i></a>
                        </form>
                    </li>
                    <li class="d-none d-lg-inline-block">
                        <a href="#" class="menu-item">New</a>
                    </li>
                    <li class="dropdown d-none d-lg-inline-block">
                        <a class="dropdown-toggle menu-item" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            English
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" class="dropdown-item">German</a></li>
                            <li><a href="javascript:void(0)" class="dropdown-item">French</a></li>
                            <li><a href="javascript:void(0)" class="dropdown-item">Italian</a></li>
                            <li><a href="javascript:void(0)" class="dropdown-item">Spanish</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Right(Notification) -->
                <ul class="nav navbar-right">
                    <li>
                        <a href="#" class="right-menu-item dropdown-toggle" data-toggle="dropdown">
                            <i class="mdi mdi-bell"></i>
                            <span class="badge up badge-success badge-pill">4</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right dropdown-lg user-list notify-list">
                            <li>
                                <h5>Notifications</h5>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="icon bg-info">
                                        <i class="mdi mdi-account"></i>
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">New Signup</span>
                                        <span class="time">5 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="icon bg-danger">
                                        <i class="mdi mdi-comment"></i>
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">New Message received</span>
                                        <span class="time">1 day ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="icon bg-warning">
                                        <i class="mdi mdi-settings"></i>
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">Settings</span>
                                        <span class="time">1 day ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="all-msgs text-center">
                                <p class="m-0"><a href="#">See all Notification</a></p>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#" class="right-menu-item dropdown-toggle" data-toggle="dropdown">
                            <i class="mdi mdi-email"></i>
                            <span class="badge up badge-danger badge-pill">8</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right dropdown-lg user-list notify-list">
                            <li>
                                <h5>Messages</h5>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="avatar">
                                        <img src="assets/images/users/avatar-2.jpg" alt="">
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">Patricia Beach</span>
                                        <span class="desc">There are new settings available</span>
                                        <span class="time">2 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="avatar">
                                        <img src="assets/images/users/avatar-3.jpg" alt="">
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">Connie Lucas</span>
                                        <span class="desc">There are new settings available</span>
                                        <span class="time">2 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="avatar">
                                        <img src="assets/images/users/avatar-4.jpg" alt="">
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">Margaret Becker</span>
                                        <span class="desc">There are new settings available</span>
                                        <span class="time">2 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="all-msgs text-center">
                                <p class="m-0"><a href="#">See all Messages</a></p>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="right-bar-toggle right-menu-item">
                            <i class="mdi mdi-settings"></i>
                        </a>
                    </li>

                    <li class="dropdown user-box">
                        <a href="" class="dropdown-toggle waves-effect user-link" data-toggle="dropdown" aria-expanded="true">
                            <img src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="user-img" class="rounded-circle user-img">
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                            <li>
                                <h5>Hi, John</h5>
                            </li>
                            <li><a href="javascript:void(0)" class="dropdown-item"><i class="ti-user m-r-5"></i> Profile</a></li>
                            <li><a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings m-r-5"></i> Settings</a></li>
                            <li><a href="javascript:void(0)" class="dropdown-item"><i class="ti-lock m-r-5"></i> Lock screen</a></li>
                            <li>
                                        <form id="logout-form" action="{{ route('staff-logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                        <a href="{{ route('staff-logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            <i class="ti-power-off m-r-5"></i> Logout
                                        </a>
                                    </li>
                        </ul>
                    </li>

                </ul> <!-- end navbar-right -->
            </div>

        </div><!-- end container -->
    </div><!-- end navbar -->
</div>
<!-- Top Bar End -->