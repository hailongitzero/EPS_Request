<?php

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>EPS-Tiếp nhận xử lý yêu cầu</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <!--base css styles-->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.min.css">

    <!--page specific css styles-->
    <link rel="stylesheet" href="../assets/data-tables/bootstrap3/dataTables.bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="../assets/chosen-bootstrap/chosen.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/datepicker/bootstrap-datepicker.css" />
    <link rel="stylesheet" type="text/css" href="../assets/bootstrap-switch/static/stylesheets/bootstrap-switch.css" />

    <!--flaty css styles-->
    <link rel="stylesheet" href="../css/eps.css">
    <link rel="stylesheet" href="../css/eps-responsive.css">

    <link rel="shortcut icon" href="../img/favicon.ico">
</head>

<body>

    <!-- BEGIN Navbar -->
    <div id="navbar" class="navbar">
        <button type="button" class="navbar-toggle navbar-btn collapsed" data-toggle="collapse" data-target="#sidebar">
            <span class="fa fa-bars"></span>
        </button>
        <a class="navbar-brand" href="{{url('')}}">
            <small>
                <i class="fa fa-desktop"></i>
                EPS-Tiếp nhận xử lý yêu cầu
            </small>
        </a>

        <!-- BEGIN Navbar Buttons -->
        <ul class="nav flaty-nav pull-right">
            <!-- BEGIN Button Notifications -->
            <li class="hidden-xs">
                @if(\Illuminate\Support\Facades\Auth::user())
                <a data-toggle="dropdown" class="dropdown-toggle" href="{{url(\Illuminate\Support\Facades\Auth::user()->role == 1 ? 'request-handle' : (\Illuminate\Support\Facades\Auth::user()->role == 2 ? 'request-manage' : ''))}}">
                    <i class="fa fa-bell"></i>
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 1)
                        <span class="badge badge-important">{{ $masterData['totalAssignRequest'] }}</span>
                    @elseif(\Illuminate\Support\Facades\Auth::user()->role == 2)
                        <span class="badge badge-important">{{ $masterData['totalNewRequest'] + $masterData['pendingRequest'] }}</span>
                    @endif
                </a>

                <!-- BEGIN Notifications Dropdown -->
                <ul class="dropdown-navbar dropdown-menu">
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 1)
                    <li class="nav-header">
                        <i class="fa fa-warning"></i>
                        {{ $masterData['totalAssignRequest'] }} Thông báo mới
                    </li>
                    @elseif(\Illuminate\Support\Facades\Auth::user()->role == 2)
                        <li class="nav-header">
                            <i class="fa fa-warning"></i>
                            {{ $masterData['totalNewRequest'] + $masterData['pendingRequest'] }} Thông báo mới
                        </li>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->role == 1)
                    <li class="notify">
                        <a href="{{ url('request-handle') }}">
                            <i class="fa fa-asterisk blue"></i>
                            <p>Chờ Xử Lý</p>
                            <span class="badge badge-info">{{ $masterData['totalAssignRequest'] }}</span>
                        </a>
                    </li>
                    @elseif(\Illuminate\Support\Facades\Auth::user()->role == 2)
                        <li class="notify">
                            <a href="{{ url('request-manage') }}">
                                <i class="fa fa-asterisk blue"></i>
                                <p>Yêu cầu mới</p>
                                <span class="badge badge-info">{{ $masterData['totalNewRequest'] }}</span>
                            </a>
                        </li>
                        <li class="notify">
                            <a href="{{ url('request-pending-manage') }}">
                                <i class="fa fa-asterisk blue"></i>
                                <p>Đang Xử Lý</p>
                                <span class="badge badge-info">{{ $masterData['pendingRequest'] }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
                @endif
                <!-- END Notifications Dropdown -->
            </li>
            <!-- END Button Notifications -->

            <!-- BEGIN Button User -->
            <li class="user-profile">
                <a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                    {{--<img class="nav-user-photo" src="../img/demo/avatar/avatar1.jpg" alt="Penny's Photo" />--}}
                    <i class="fa fa-user"></i>
                    <span class="hhh" id="user_info">
                        {{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->name : ""}}
                    </span>
                    <i class="fa fa-caret-down"></i>
                </a>

                <!-- BEGIN User Dropdown -->
                <ul class="dropdown-menu dropdown-navbar" id="user_menu">
                    <li>
                        @if(\Illuminate\Support\Facades\Auth::user())
                            <a href="{{ url('logout') }}">
                                <i class="fa fa-off"></i>
                                Logout
                            </a>
                        @else
                            <a href="{{ url('login') }}">
                                <i class="fa fa-off"></i>
                                Login
                            </a>
                        @endif
                    </li>
                </ul>
                <!-- BEGIN User Dropdown -->
            </li>
            <!-- END Button User -->
        </ul>
        <!-- END Navbar Buttons -->
    </div>
    <!-- END Navbar -->

    <!-- BEGIN Container -->
    <div class="container" id="main-container">
        <!-- BEGIN Sidebar -->
        <div id="sidebar" class="navbar-collapse collapse">
            <!-- BEGIN Navlist -->
            <ul class="nav nav-list">
                <li class="{{ $masterData['activeMenu'] == 1 ? "active" : "" }}">
                    <a href="{{url('')}}">
                        <i class="fa fa-dashboard"></i>
                        <span>Trang Chủ</span>
                    </a>
                </li>
                <li class="{{ $masterData['activeMenu'] == 2 ? "active" : "" }}">
                    <a href="{{url('request')}}">
                        <i class="fa fa-edit"></i>
                        <span>Tạo Yêu Cầu</span>
                    </a>
                </li>
                @if(\Illuminate\Support\Facades\Auth::user())
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 2)
                        <li class="{{ $masterData['activeMenu'] == 3 || $masterData['activeMenu'] == 4 || $masterData['activeMenu'] == 5 ? "active" : "" }}">
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-list"></i>
                                <span>Quản Lý Yêu Cầu</span>
                                <b class="arrow fa fa-angle-right"></b>
                            </a>

                            <!-- BEGIN Submenu -->
                            <ul class="submenu">
                                <li class="{{ $masterData['activeMenu'] == 3 ? "active" : "" }}">
                                    <a href="{{url('request-manage')}}">Yêu Cầu Mới
                                        @if($masterData['totalNewRequest'] > 0)
                                            <small class="badge badge-info">{{ $masterData['totalNewRequest'] }}</small>
                                        @endif
                                    </a>
                                </li>
                                <li class="{{ $masterData['activeMenu'] == 4 ? "active" : "" }}">
                                    <a href="{{url('request-pending-manage')}}">Đang Xử Lý
                                        @if($masterData['pendingRequest'] > 0)
                                            <small class="badge label-magenta">{{ $masterData['pendingRequest'] }}</small>
                                        @endif
                                    </a>
                                </li>
                                <li class="{{ $masterData['activeMenu'] == 5 ? "active" : "" }}">
                                    <a href="{{url('request-complete-manage')}}">Hoàn Thành
                                        @if($masterData['totalCompleteRequest'] > 0)
                                            <small class="badge badge-success">{{ $masterData['totalCompleteRequest'] }}</small>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                            <!-- END Submenu -->
                        </li>
                    @endif
                @endif
                @if(\Illuminate\Support\Facades\Auth::user())
                    <li class="{{ $masterData['activeMenu'] == 6 || $masterData['activeMenu'] == 7 || $masterData['activeMenu'] == 8 ? "active" : "" }}">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-list"></i>
                            <span>Cá Nhân</span>
                            <b class="arrow fa fa-angle-right"></b>
                        </a>
                        <!-- BEGIN Submenu -->
                        <ul class="submenu">
                            @if(\Illuminate\Support\Facades\Auth::user()->role == 1 || \Illuminate\Support\Facades\Auth::user()->role == 2)
                                <li class="{{ $masterData['activeMenu'] == 6 ? "active" : "" }}">
                                    <a href="{{url('request-handle')}}">Được Giao
                                        @if( $masterData['totalAssignRequest'] > 0 )
                                            <small class="badge badge-warning">{{ $masterData['totalAssignRequest'] }}</small>
                                        @endif
                                    </a>
                                </li>
                                <li class="{{ $masterData['activeMenu'] == 7 ? "active" : "" }}">
                                    <a href="{{url('request-handle-complete')}}">Hoàn Thành
                                        @if($masterData['totalMyCompleteRequest'] > 0)
                                            <small class="badge badge-info">{{ $masterData['totalMyCompleteRequest'] }}</small>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            <li class="{{ $masterData['activeMenu'] == 8 ? "active" : "" }}">
                                <a href="{{url('my-request')}}">Yêu cầu của tôi
                                    @if($masterData['totalMyRequest'] > 0)
                                        <small class="badge badge-success">{{ $masterData['totalMyRequest'] }}</small>
                                    @endif
                                </a>
                            </li>
                        </ul>
                        <!-- END Submenu -->
                    </li>
                @endif
                <li class="{{ $masterData['activeMenu'] == 9 ? "active" : "" }}">
                    <a href="{{url('manual-document')}}">
                        <i class="fa fa-file"></i>
                        <span>Hướng dẫn sử dụng</span>
                    </a>
                </li>
            </ul>
            <!-- END Navlist -->

            <!-- BEGIN Sidebar Collapse Button -->
            <div id="sidebar-collapse" class="visible-lg">
                <i class="fa fa-angle-double-left"></i>
            </div>
            <!-- END Sidebar Collapse Button -->
        </div>
        <!-- END Sidebar -->

        <!-- BEGIN Content -->
        <div id="main-content">
            <!-- BEGIN Page Title -->
            <div class="page-title">
                <div>
                    @section('page-title')
                    <h1><i class="fa fa-file-o"></i> Dashboard</h1>
                    <h4>Overview, stats, chat and more</h4>
                    @show
                </div>
            </div>
            <!-- END Page Title -->

            <!-- BEGIN Breadcrumb -->
            <div id="breadcrumbs">
                @section('breadcrumb')
                <ul class="breadcrumb">
                    <li class="active"><i class="fa fa-home"></i> @yield('breakcrum-name')</li>
                </ul>
                @show
            </div>
            <!-- END Breadcrumb -->
            @yield('content')
            <!-- body content -->

            <footer>
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <h4 class="text-center">CÔNG TY DỊCH VỤ SỮA CHỮA CÁC NHÀ MÁY ĐIỆN EVNGENCO 3</h4>
                        <P class="text-center">332 Độc Lập (QL51, P.Phú Mỹ, Tx.Phú Mỹ, T.Bà Rịa Vũng Tàu</P>
                        <p class="text-center"><i class="fa fa-phone"></i>Tel: 0254 392 4436 <i class="fa fa-fax"></i>Fax: 0254 392 4437</p>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <h4 class="text-left">LIÊN HỆ</h4>
                        <p class="text-left">Đỗ Hữu Lợi</p>
                        <p class="text-left"><i class="fa fa-envelope"></i> loidh@eps.genco3.vn <i class="fa fa-phone"></i> 64704</p>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <h4 class="text-left">GÓP Ý</h4>
                        <p class="text-left">Trần Trung Thiên</p>
                        <p class="text-left"><i class="fa fa-envelope"></i> thientt@eps.genco3.vn <i class="fa fa-phone"></i> 64704</p>
                    </div>
                </div>
            </footer>

            <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="fa fa-chevron-up"></i></a>
        </div>
        <!-- END Content -->
    </div>
    <!-- END Container -->


    <!--basic scripts-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="../assets/jquery/jquery-2.1.1.min.js"><\/script>')
    </script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="../assets/jquery-cookie/jquery.cookie.js"></script>

    <!--page specific plugin scripts-->
    <script src="../assets/flot/jquery.flot.js"></script>
    <script src="../assets/flot/jquery.flot.resize.js"></script>
    <script src="../assets/flot/jquery.flot.pie.js"></script>
    <script src="../assets/flot/jquery.flot.stack.js"></script>
    <script src="../assets/flot/jquery.flot.crosshair.js"></script>
    <script src="../assets/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../assets/sparkline/jquery.sparkline.min.js"></script>
    <script type="text/javascript" src="../assets/data-tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="../assets/data-tables/bootstrap3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="../assets/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="../assets/chosen-bootstrap/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="../assets/datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="../assets/bootstrap-switch/static/js/bootstrap-switch.js"></script>

    <!--flaty scripts-->
    <script src="../js/eps.js"></script>
    <script src="../js/eps-demo-codes.js"></script>

</body>

</html> 