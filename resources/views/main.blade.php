<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>K STOCK </title>
    <link rel="shortcut icon" href="/uploads/icon.png"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ URL::asset('/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ion icons -->
    <link rel="stylesheet" href="{{ URL::asset('/bower_components/Ionicons/css/ionicons.min.css')}}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ URL::asset('/bower_components/select2/dist/css/select2.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ URL::asset('/dist/css/skins/_all-skins.min.css')}}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ URL::asset('/bower_components/morris.js/morris.css')}}">
    <!-- jvector map -->
    <link rel="stylesheet" href="{{ URL::asset('/bower_components/jvectormap/jquery-jvectormap.css')}}">
    <!-- Date Picker -->
    <link rel="stylesheet"
          href="{{ URL::asset('/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <!-- Date range picker -->
    <link rel="stylesheet" href="{{ URL::asset('/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
    <!-- bootstrap wysi html5 - text editor -->
    <link rel="stylesheet" href="{{ URL::asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">

    <link rel="stylesheet" href="{{ URL::asset('/css/utils.css')}}">


</head>

<body class="hold-transition fixed skin-blue-light sidebar-mini">
<div class="wrapper">

    <header class="main-header">
    @php
        $user_id = Auth::user()->user_id;

        $store_name = DB::table('tec_users as u')
            ->select('s.*')
            ->join('tec_stores as s', 'u.store_id', '=', 's.id')
            ->where('u.id', $user_id)
            ->get();

        $permissions = DB::table('tec_permission')
            ->where('user_id', $user_id)
            ->first();

        $dashboard = $permissions->dashboard == '1' ? 'block' : 'none';
        $category = $permissions->category == '1' ? 'block' : 'none';
        $product = $permissions->product == '1' ? 'block' : 'none';
        $permission = $permissions->permission == '1' ? 'block' : 'none';
        $import = $permissions->import == '1' ? 'block' : 'none';
        $export = $permissions->export == '1' ? 'block' : 'none';
        $sale = $permissions->sale == '1' ? 'block' : 'none';
        $user = $permissions->user == '1' ? 'block' : 'none';
        $setting = $permissions->setting == '1' ? 'block' : 'none';
        $report = $permissions->report == '1' ? 'block' : 'none';
        $pos = $permissions->pos == '1' ? 'block' : 'none';
        $imp = $permissions->imp == '1' ? 'block' : 'none';
        $ex = $permissions->ex == '1' ? 'block' : 'none';

    @endphp

    <!-- Logo -->
        <a href="{{ url('/dashboard') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>K</b>S4</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">{{ $store_name[0]->name }} </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                    <li class="dropdown messages-menu">
                        <a href="{{ route('dashboard') }}" class="dropdown-toggle">
                            <i class="fa fa-dashboard"></i>
                            {{--                            <span class="label label-success">4</span> --}}
                        </a>
                        {{--                        <ul class="dropdown-menu"> --}}
                        {{--                            <li class="header">You have 4 messages</li> --}}
                        {{--                            <li> --}}
                        {{--                                <!-- inner menu: contains the actual data --> --}}
                        {{--                                <ul class="menu"> --}}
                        {{--                                    <li><!-- start message --> --}}
                        {{--                                        <a href="#"> --}}
                        {{--                                            <div class="pull-left"> --}}
                        {{--                                                <img src="dist/img/user2-160x160.jpg" class="img-circle" --}}
                        {{--                                                     alt="User Image"> --}}
                        {{--                                            </div> --}}
                        {{--                                            <h4> --}}
                        {{--                                                Support Team --}}
                        {{--                                                <small><i class="fa fa-clock-o"></i> 5 mins</small> --}}
                        {{--                                            </h4> --}}
                        {{--                                            <p>Why not buy a new awesome theme?</p> --}}
                        {{--                                        </a> --}}
                        {{--                                    </li> --}}
                        {{--                                    <!-- end message --> --}}
                        {{--                                    <li> --}}
                        {{--                                        <a href="#"> --}}
                        {{--                                            <div class="pull-left"> --}}
                        {{--                                                <img src="dist/img/user3-128x128.jpg" class="img-circle" --}}
                        {{--                                                     alt="User Image"> --}}
                        {{--                                            </div> --}}
                        {{--                                            <h4> --}}
                        {{--                                                AdminLTE Design Team --}}
                        {{--                                                <small><i class="fa fa-clock-o"></i> 2 hours</small> --}}
                        {{--                                            </h4> --}}
                        {{--                                            <p>Why not buy a new awesome theme?</p> --}}
                        {{--                                        </a> --}}
                        {{--                                    </li> --}}
                        {{--                                    <li> --}}
                        {{--                                        <a href="#"> --}}
                        {{--                                            <div class="pull-left"> --}}
                        {{--                                                <img src="dist/img/user4-128x128.jpg" class="img-circle" --}}
                        {{--                                                     alt="User Image"> --}}
                        {{--                                            </div> --}}
                        {{--                                            <h4> --}}
                        {{--                                                Developers --}}
                        {{--                                                <small><i class="fa fa-clock-o"></i> Today</small> --}}
                        {{--                                            </h4> --}}
                        {{--                                            <p>Why not buy a new awesome theme?</p> --}}
                        {{--                                        </a> --}}
                        {{--                                    </li> --}}
                        {{--                                    <li> --}}
                        {{--                                        <a href="#"> --}}
                        {{--                                            <div class="pull-left"> --}}
                        {{--                                                <img src="dist/img/user3-128x128.jpg" class="img-circle" --}}
                        {{--                                                     alt="User Image"> --}}
                        {{--                                            </div> --}}
                        {{--                                            <h4> --}}
                        {{--                                                Sales Department --}}
                        {{--                                                <small><i class="fa fa-clock-o"></i> Yesterday</small> --}}
                        {{--                                            </h4> --}}
                        {{--                                            <p>Why not buy a new awesome theme?</p> --}}
                        {{--                                        </a> --}}
                        {{--                                    </li> --}}
                        {{--                                    <li> --}}
                        {{--                                        <a href="#"> --}}
                        {{--                                            <div class="pull-left"> --}}
                        {{--                                                <img src="dist/img/user4-128x128.jpg" class="img-circle" --}}
                        {{--                                                     alt="User Image"> --}}
                        {{--                                            </div> --}}
                        {{--                                            <h4> --}}
                        {{--                                                Reviewers --}}
                        {{--                                                <small><i class="fa fa-clock-o"></i> 2 days</small> --}}
                        {{--                                            </h4> --}}
                        {{--                                            <p>Why not buy a new awesome theme?</p> --}}
                        {{--                                        </a> --}}
                        {{--                                    </li> --}}
                        {{--                                </ul> --}}
                        {{--                            </li> --}}
                        {{--                            <li class="footer"><a href="#">See All Messages</a></li> --}}
                        {{--                        </ul> --}}
                    </li>
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            {{--                            <span class="label label-warning">10</span> --}}
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 10 notifications</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                {{--                                <ul class="menu"> --}}
                                {{--                                    <li> --}}
                                {{--                                        <a href="#"> --}}
                                {{--                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today --}}
                                {{--                                        </a> --}}
                                {{--                                    </li> --}}
                                {{--                                    <li> --}}
                                {{--                                        <a href="#"> --}}
                                {{--                                            <i class="fa fa-warning text-yellow"></i> Very long description here that --}}
                                {{--                                            may not fit into the --}}
                                {{--                                            page and may cause design problems --}}
                                {{--                                        </a> --}}
                                {{--                                    </li> --}}
                                {{--                                    <li> --}}
                                {{--                                        <a href="#"> --}}
                                {{--                                            <i class="fa fa-users text-red"></i> 5 new members joined --}}
                                {{--                                        </a> --}}
                                {{--                                    </li> --}}
                                {{--                                    <li> --}}
                                {{--                                        <a href="#"> --}}
                                {{--                                            <i class="fa fa-shopping-cart text-green"></i> 25 sales made --}}
                                {{--                                        </a> --}}
                                {{--                                    </li> --}}
                                {{--                                    <li> --}}
                                {{--                                        <a href="#"> --}}
                                {{--                                            <i class="fa fa-user text-red"></i> You changed your username --}}
                                {{--                                        </a> --}}
                                {{--                                    </li> --}}
                                {{--                                </ul> --}}
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>
                    <!-- Tasks: style can be found in dropdown.less -->
                    <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Language</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li>
                                        <!-- Task item -->
                                        <a href="/lang/en">
                                            <h3>
                                                English

                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                     role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                    <li>
                                        <!-- Task item -->
                                        <a href="/lang/kh">
                                            <h3>
                                                Khmer

                                            </h3>
                                            <div class="progress xs">
                                                <div class="progress-bar progress-bar-green" style="width: 40%"
                                                     role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->

                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('uploads/photo/male.png') }}" class="user-image" alt="User Image">
                            <span class="hidden-xs"> {{ Auth::user()->name }} </span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ asset('uploads/photo/male.png') }}" class="img-circle" alt="User Image">

                                <p>
                                    {{ Auth::user()->name }} - Seller
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Body -->

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{route('profile')}}"
                                       class="btn btn-default btn-flat">{{ __('language.profile') }}</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('post_logout') }}"
                                       class="btn btn-default btn-flat">{{ __('language.sign_out') }}</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                {{--                <li class="header">MAIN NAVIGATION</li> --}}

                <li style="display: {{ $dashboard }}" class="active" id="dashboard"><a
                        href="{{ url('dashboard') }}"><i class="fa fa-dashboard"></i>
                        <span>{{ __('language.dashboard') }}</span></a></li>

                <li style="display: {{ $pos }}" class=""><a href="{{ url('pos') }}"><i
                            class="fa fa-desktop"></i>
                        <span>{{ __('language.pos') }}</span></a></li>

                <li style="display: {{ $pos }}" class=""><a href="{{ url('sale_qr_code') }}"><i
                            class="fa fa-qrcode"></i>
                        <span>ស្កេន{{ __('language.pos') }}</span></a></li>

                <li class="treeview" id="categories" style="display: {{ $category }}">
                    <a href="#">
                        <i class="fa fa-folder-open-o"></i> <span>{{ __('language.category') }}</span>
                        <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('categories') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.list_category') }}</a></li>
                        <li><a href="{{ url('brands') }}"><i class="fa fa-circle-o"></i>
                                {{ lang('brand') }}</a>
                        </li>
                    </ul>
                </li>


                <li class="treeview" id="products" style="display: {{ $product }}">
                    <a href="#">
                        <i class="fa  fa-cubes"></i> <span>{{ __('language.product') }}</span>
                        <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('products') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.list_product') }}</a></li>
                        <li><a href="{{ url('list_products') }}"><i class="fa fa-circle-o"></i>
                                {{ lang('price')}}{{ lang('product') }}</a></li>
                    </ul>
                </li>


                <li class="treeview" id="warehouse" style="display: {{ $permission }}">
                    <a href="#">
                        <i class="fa  fa-home"></i> <span>{{ __('language.warehouse') }}</span>
                        <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('warehouse') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.adjustment_stock') }}</a></li>
                        <li><a href="{{ url('adjustment') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.adjustment_report') }}</a></li>
                    </ul>
                </li>


                <li class="treeview" id="import">
                    <a href="#">
                        <i class="fa fa-cubes"></i> <span>{{ __('language.import') }}</span>
                        <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li style="display: {{ $import }}"><a href="{{ url('import') }}"><i
                                    class="fa fa-circle-o"></i> {{ __('language.list_import') }}
                            </a></li>
                        <li style="display: {{ $imp }}"><a href="{{ url('list_import') }}"><i
                                    class="fa fa-circle-o"></i> {{ __('language.list_all_import') }}</a>
                        </li>

                        <li style="display: {{ $import }}"><a href="{{ url('ui/add_stock') }}"><i
                                    class="fa fa-circle-o"></i> ស្កេនទំនិញចូលតាមសាខា
                            </a></li>
                    </ul>
                </li>


                <li class="treeview" id="export">
                    <a href="#">
                        <i class="fa fa-exchange"></i> <span>{{ __('language.export') }} </span>
                        <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li style="display: {{ $ex }}"><a href="{{ url('list_export') }}"><i
                                    class="fa fa-circle-o"></i> {{ __('language.list_export') }}</a>
                        </li>
                    </ul>
                </li>

                <li class="treeview" id="sale">
                    <a href="#">
                        <i class="fa fa-sitemap"></i> <span>{{ __('language.sales') }} </span>
                        <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('list_sale') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.list_sale') }}
                            </a></li>
                        <li><a href="{{ url('sale_record') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.sale_record') }}</a>
                        </li>
                        <li><a href="{{ url('sale_report') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.sale_report') }}</a>
                        </li>
                        <li><a href="{{ url('stock_report') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.stock_report') }}</a>
                        </li>
                        <li style="display: {{ $permission }}"><a href="{{ url('chart_report') }}"><i
                                    class="fa fa-circle-o"></i> {{ __('language.chart_report') }}</a>
                        </li>
                    </ul>
                </li>

                <li class="treeview" id="users" style="display: {{ $user }}">
                    <a href="#">
                        <i class="fa fa-users"></i> <span>{{ __('language.users') }} </span>
                        <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('users') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.list_users') }}
                            </a></li>

                        <li><a href="{{ url('customers') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.list_customer') }}</a></li>


                    </ul>
                </li>

                <li class="treeview" id="setting" style="display: {{ $setting }}">
                    <a href="#">
                        <i class="fa fa-cog"></i> <span>{{ __('language.setting') }} </span>
                        <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('stores') }}"><i class="fa fa-circle-o"></i>
                                {{ __('language.list_store') }}
                            </a></li>

                    </ul>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <p>
                {{lang('dashboard')}}
                <small></small>
            </p>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> {{lang('home')}}</a></li>
                <li class="active">{{lang('dashboard')}}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content thumbnail-component">

            @yield('content')

            {{--             <div id="app"> --}}
            {{--                <example-component /> --}}
            {{--                 <router-link></router-link> --}}
            {{--             </div> --}}

        </section>
        <!-- /.content -->
    </div>


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark" style="display: none;">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-user bg-yellow"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                <p>New phone +1(800)555-1234</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                <p>nora@example.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-file-code-o bg-green"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                <p>Execution time 5 seconds</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Update Resume
                                <span class="label label-success pull-right">95%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Laravel Integration
                                <span class="label label-warning pull-right">50%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Back End Framework
                                <span class="label label-primary pull-right">68%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Allow mail redirect
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Other sets of options are available
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Expose author name in posts
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Allow the user to show his name in blog posts
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <h3 class="control-sidebar-heading">Chat Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Show me as online
                            <input type="checkbox" class="pull-right" checked>
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Turn off notifications
                            <input type="checkbox" class="pull-right">
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Delete chat history
                            <a href="javascript:void(0)" class="text-red pull-right"><i
                                    class="fa fa-trash-o"></i></a>
                        </label>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>

<div class="modal fade" id="modal-avatar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <img src="{{ asset('uploads/no.jpg') }}" style="width: 100%"/>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ URL::asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ URL::asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{ URL::asset('bower_components/raphael/raphael.min.js')}}"></script>
<script src="{{ URL::asset('bower_components/morris.js/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ URL::asset('bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ URL::asset('bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ URL::asset('bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{ URL::asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{ URL::asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ URL::asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{ URL::asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{ URL::asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ URL::asset('dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{-- <script src="dist/js/pages/dashboard.js"></script> --}}
<!-- AdminLTE for demo purposes -->
{{-- <script src="dist/js/demo.js"></script> --}}

<script src="{{ URL::asset('bower_components/ckeditor/ckeditor.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->

<!-- DataTables -->
<script src="{{ URL::asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ URL::asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>

<script src="{{ URL::asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- FastClick -->

<!-- Select2 -->
<script src="{{ URL::asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js">
</script>

<script src="{{ URL::asset('js/utils.js')}}"></script>

{{-- <script src="{{ mix('/js/appj.ts') }}"></script> --}}

@stack('scripts')
</body>

</html>
