<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>POS</title>
    <!-- Tell the browser to be responsive to screen width -->
    {{--    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> --}}
    {{--    <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->

    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">

    <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nokora&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nokora', 'auto';
            font-size: 14px;
        }

        .qty {
            height: 21px;
        }

        .mailbox-attachments li {
            width: 120px;
        }

        .mailbox-attachment-icon.has-img>img {
            height: 110px;
            border: 4px solid #fff;
        }

        .mailbox-attachment-info {
            text-align: center;
            height: 30px;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 2px;
            font-size: 12px;

        }

        .clearfix li {
            cursor: pointer;
        }

        .badge-right {
            float: right;
        }

        .content-wrapper {
            background-image: url("{{ asset('uploads/well.jpg') }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .well {
            background-color: #fff;
        }

        .close {
            font-size: 27px;
        }

        .quick-cash {
            margin: 10px 0;
        }
    </style>
</head>

<body class="hold-transition fixed skin-blue-light sidebar-collapse sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><i class="fa fa-home"></i></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Admin</b>LTE</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                {{--            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"> --}}
                {{--                <span class="sr-only">Toggle navigation</span> --}}
                {{--                <span class="icon-bar"></span> --}}
                {{--                <span class="icon-bar"></span> --}}
                {{--                <span class="icon-bar"></span> --}}
                {{--            </a> --}}


                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu">
                            <a href="/dashboard" class="dropdown-toggle">
                                {{--                            <i class="fa fa-envelope-o"></i> --}}
                                {{--                            <span class="label label-success">4</span> --}}
                            </a>
                            {{--                        <ul class="dropdown-menu"> --}}
                            {{--                            <li class="header">You have 4 messages</li> --}}
                            {{--                            <li> --}}
                            {{--                                <!-- inner menu: contains the actual data --> --}}
                            {{--                                <ul class="menu"> --}}
                            {{--                                    <li> --}}
                            {{--                                        <!-- start message --> --}}
                            {{--                                        <a href="#"> --}}
                            {{--                                            <div class="pull-left"> --}}
                            {{--                                                <img src="../../dist/img/user2-160x160.jpg" class="img-circle" --}}
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
                            {{--                                </ul> --}}
                            {{--                            </li> --}}
                            {{--                            <li class="footer"><a href="#">See All Messages</a></li> --}}
                            {{--                        </ul> --}}
                        </li>
                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                {{--                            <i class="fa fa-bell-o"></i> --}}
                                {{--                            <span class="label label-warning">10</span> --}}
                            </a>
                            {{--                        <ul class="dropdown-menu"> --}}
                            {{--                            <li class="header">You have 10 notifications</li> --}}
                            {{--                            <li> --}}
                            {{--                                <!-- inner menu: contains the actual data --> --}}
                            {{--                                <ul class="menu"> --}}
                            {{--                                    <li> --}}
                            {{--                                        <a href="#"> --}}
                            {{--                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today --}}
                            {{--                                        </a> --}}
                            {{--                                    </li> --}}
                            {{--                                </ul> --}}
                            {{--                            </li> --}}
                            {{--                            <li class="footer"><a href="#">View all</a></li> --}}
                            {{--                        </ul> --}}
                        </li>
                        <!-- Tasks: style can be found in dropdown.less -->
                        <li class="dropdown tasks-menu">
                            <a href="/dashboard" class="dropdown-toggle">
                                <i class="fa fa-dashboard"></i>
                                {{--                            <span class="label label-danger">9</span> --}}
                            </a>
                            {{--                        <ul class="dropdown-menu"> --}}
                            {{--                            <li class="header">You have 9 tasks</li> --}}
                            {{--                            <li> --}}
                            {{--                                <!-- inner menu: contains the actual data --> --}}
                            {{--                                <ul class="menu"> --}}
                            {{--                                    <li> --}}
                            {{--                                        <!-- Task item --> --}}
                            {{--                                        <a href="#"> --}}
                            {{--                                            <h3> --}}
                            {{--                                                Design some buttons --}}
                            {{--                                                <small class="pull-right">20%</small> --}}
                            {{--                                            </h3> --}}
                            {{--                                            <div class="progress xs"> --}}
                            {{--                                                <div class="progress-bar progress-bar-aqua" style="width: 20%" --}}
                            {{--                                                     role="progressbar" aria-valuenow="20" aria-valuemin="0" --}}
                            {{--                                                     aria-valuemax="100"> --}}
                            {{--                                                    <span class="sr-only">20% Complete</span> --}}
                            {{--                                                </div> --}}
                            {{--                                            </div> --}}
                            {{--                                        </a> --}}
                            {{--                                    </li> --}}
                            {{--                                    <!-- end task item --> --}}
                            {{--                                </ul> --}}
                            {{--                            </li> --}}
                            {{--                            <li class="footer"> --}}
                            {{--                                <a href="#">View all tasks</a> --}}
                            {{--                            </li> --}}
                            {{--                        </ul> --}}
                        </li>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ asset('uploads/photo/male.png') }}" class="user-image" alt="User Image">
                                <span class="hidden-xs">{{ Auth::user()->name }} </span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{{ asset('uploads/photo/male.png') }}" class="img-circle"
                                        alt="User Image">

                                    <p>
                                        {{ Auth::user()->name }} - Saler
                                        <small>Member since Nov. 2012</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                {{--                            <li class="user-body"> --}}
                                {{--                                <div class="row"> --}}
                                {{--                                    <div class="col-xs-4 text-center"> --}}
                                {{--                                        <a href="#">Followers</a> --}}
                                {{--                                    </div> --}}
                                {{--                                    <div class="col-xs-4 text-center"> --}}
                                {{--                                        <a href="#">Sales</a> --}}
                                {{--                                    </div> --}}
                                {{--                                    <div class="col-xs-4 text-center"> --}}
                                {{--                                        <a href="#">Friends</a> --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}
                                {{--                                <!-- /.row --> --}}
                                {{--                            </li> --}}
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#"
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

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->

                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree" style="display: none">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
                        </ul>
                    </li>
                    <li class="treeview active">
                        <a href="#">
                            <i class="fa fa-files-o"></i>
                            <span>Layout Options</span>
                            <span class="pull-right-container">
                                <span class="label label-primary pull-right">4</span>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Boxed</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Fixed</a></li>
                            <li class="active"><a href="collapsed-sidebar.html"><i class="fa fa-circle-o"></i>
                                    Collapsed Sidebar</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/">
                            <i class="fa fa-th"></i> <span>Widgets</span>
                            <span class="pull-right-container">
                                <small class="label pull-right bg-green">new</small>
                            </span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-pie-chart"></i>
                            <span>Charts</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/"><i class="fa fa-circle-o"></i> ChartJS</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Morris</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Flot</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Inline charts</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-laptop"></i>
                            <span>UI Elements</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/"><i class="fa fa-circle-o"></i> General</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Icons</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Buttons</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Sliders</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Timeline</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Modals</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="/">
                            <i class="fa fa-edit"></i> <span>Forms</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/"><i class="fa fa-circle-o"></i> General Elements</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Editors</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-table"></i> <span>Tables</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="/"><i class="fa fa-circle-o"></i> Simple tables</a></li>
                            <li><a href="/"><i class="fa fa-circle-o"></i> Data tables</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/">
                            <i class="fa fa-calendar"></i> <span>Calendar</span>
                            <span class="pull-right-container">
                                <small class="label pull-right bg-red">3</small>
                                <small class="label pull-right bg-blue">17</small>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="/">
                            <i class="fa fa-envelope"></i> <span>Mailbox</span>
                            <span class="pull-right-container">
                                <small class="label pull-right bg-yellow">12</small>
                                <small class="label pull-right bg-green">16</small>
                                <small class="label pull-right bg-red">5</small>
                            </span>
                        </a>
                    </li>


                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div style="display: flex; margin: 10px;" id="table-1">
                <div class="well well-sm" style="min-width: 450px; height: 100%">
                    <div class="form-group" style="margin-bottom:5px;">
                        <input type="text" name="scan_phone" id="search_customers_phone"
                            class="form-control ui-autocomplete-input" placeholder="{{ __('language.scan_phone') }}"
                            autocomplete="off">
                    </div>

                    <div class="form-group" style="margin-bottom:5px;">
                        <div class="input-group">
                            <select name="customer_id" id="customer_id" data-placeholder="Select Customer"
                                required="required" class="form-control select2 " style="width:100%;">

                                @foreach ($customer as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach

                            </select>
                            <div class="input-group-addon no-print" style="padding: 2px 5px;">
                                <a href="#" id="add-customer" class="external" data-toggle="modal"
                                    data-target="#modal-customer"><i class="fa fa-2x fa-plus-circle"
                                        id="addIcon"></i></a>
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>

                    <div class="form-group" style="margin-bottom:5px;">
                        <input type="text" name="hold_ref" value="" id="hold_ref"
                            class="form-control kb-text" placeholder="{{ __('language.reference_note') }}">
                    </div>


                    <div class="form-group" style="margin-bottom:5px;">
                        <input type="text" name="code" id="add_item"
                            class="form-control ui-autocomplete-input" placeholder="{{ __('language.scan_code') }}"
                            autocomplete="off">
                    </div>


                    <div id="print" class="fixed-table-container">
                        <div class="slimScrollDiv"
                            style="position: relative; overflow: hidden; width: auto; height: 250px;">
                            <div id="list-table-div" style="overflow: hidden; width: auto; height: 491px;">

                                <table id="posTable"
                                    class="table table-striped table-condensed table-hover list-table"
                                    style="margin:0px;" data-height="100">
                                    <thead>
                                        <tr class="info">
                                            <th>{{ __('language.product') }}</th>
                                            <th style="width: 15%;text-align:center;">{{ __('language.price') }}</th>
                                            <th style="width: 15%;text-align:center;">{{ __('language.qty') }}</th>
                                            <th style="width: 20%;text-align:center;">{{ __('language.amount') }}</th>
                                            <th style="width: 20px;" class=""><i class="fa fa-trash-o"></i>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-item" style="min-height: 200px;overflow-x:scroll ; ">

                                    </tbody>
                                </table>
                            </div>
                            <div class="slimScrollBar"
                                style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 491px;">
                            </div>
                            <div class="slimScrollRail"
                                style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;">
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                        <div id="totaldiv">
                            <table id="totaltbl" class="table table-condensed totals" style="margin-bottom:10px;">
                                <tbody>
                                    <tr class="info">
                                        <td width="25%"> {{ __('language.total_items') }}</td>
                                        <td class="text-right" style="padding-right:10px;"><span id="count">0
                                                (0)</span></td>
                                        <td width="25%">{{ __('language.total') }}</td>
                                        <td class="text-right" colspan="2"><span id="total">$0.00</span></td>
                                    </tr>
                                    <tr class="info">
                                        <td width="25%"><a href="#"
                                                id="add_discount">{{ __('language.discount') }}</a></td>
                                        <td class="text-right" style="padding-right:10px;"><span
                                                id="ds_con">($0.00) $0.00</span></td>
                                        <td width="25%"><a href="#"
                                                id="add_tax">{{ __('language.tax') }}</a></td>
                                        <td class="text-right"><span id="ts_con">$0.00</span></td>
                                    </tr>
                                    <tr class="info">
                                        <td colspan="2" style="font-weight:bold;">
                                            {{ __('language.total_payable') }} ​ <a role="button"
                                                data-toggle="modal" data-target="#noteModal">
                                                <i class="fa fa-comment"></i>
                                            </a>
                                        </td>
                                        <td class="text-right" colspan="2" style="font-weight:bold;"><span
                                                id="total-payable">$0.00</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="botbuttons" class="col-xs-12 text-center">
                        <div class="row">
                            <div class="col-xs-4" style="padding: 0;">
                                <div class="btn-group-vertical btn-block">
                                    <button type="button" class="btn btn-warning btn-block btn-flat"
                                        id="suspend">{{ __('language.hold') }}
                                    </button>
                                    <button type="button" class="btn btn-danger btn-block btn-flat"
                                        id="reset">{{ __('language.cancel') }}
                                    </button>
                                </div>

                            </div>
                            <div class="col-xs-4" style="padding: 0 5px;">
                                <div class="btn-group-vertical btn-block">
                                    <button type="button" class="btn bg-purple btn-block btn-flat"
                                        id="print_order">{{ __('language.print_order') }}
                                    </button>

                                    <button type="button" class="btn bg-navy btn-block btn-flat"
                                        id="print_bill">{{ __('language.print_bill') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-4" style="padding: 0;">
                                <button type="button" class="btn btn-success btn-block btn-flat" id="payment"
                                    style="height:67px;">{{ __('language.payment') }}
                                </button>
                            </div>
                        </div>

                    </div>


                    <div class="modal fade" id="modal-customer">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">បន្ថែមអតិថិជន</h4>
                                </div>
                                <form id="form-customer">
                                    <div class="modal-body">
                                        <div id="c-alert" class="alert alert-danger" style="display:none;"></div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="code">
                                                        ឈ្មោះ </label>
                                                    <input type="text" name="name" value=""
                                                        class="form-control input-sm kb-text" id="name"​​
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label for="status">ស្ថានភាព</label> <select name="status"
                                                        class="form-control tip select2 select2-hidden-accessible"
                                                        style="width:100%;" id="status" required="required"
                                                        tabindex="-1" aria-hidden="true">
                                                        <option value="enable">បើក</option>
                                                        <option value="disable">បិទ</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="email">
                                                        អ៊ីម៉ែល </label>
                                                    <input type="text" name="email" value="thary@gmail.com"
                                                        class="form-control input-sm kb-text" id="email">
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label class="control-label" for="phone">
                                                        លេខទូរសព្ទ </label>
                                                    <input type="text" name="phone" value="+855 77772655"
                                                        class="form-control input-sm kb-pad" id="phone" required>
                                                </div>
                                            </div>
                                        </div>

                                        {{ csrf_field() }}

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left"
                                            data-dismiss="modal">បិត
                                        </button>
                                        <button type="submit" class="btn btn-primary">បន្ថែមអតិថិជន
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->


                </div>
                <div class="wells well-sms" style="margin-left: 15px;">


                    <div class="modal modal-success fade" id="modal-payment" data-easein="flipXIn">
                        <div class="modal-dialog modal-lg" style="width: 750px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 27px">&times;</span></button>
                                    <h4 class="modal-title">ការទូទាត់</h4>
                                </div>
                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-md-9">

                                            <div class="col-md-12">
                                                <table class="table table-bordered">

                                                    <tbody>
                                                        <tr>
                                                            <th scope="col" width="25%">ចំនួនសរុប</th>
                                                            <th scope="col" width="25%" id="md-qty"
                                                                class="text-right">0 (0)
                                                            </th>
                                                            <th scope="col" width="25%">ចំណាយសរុប</th>
                                                            <th scope="col" width="25%" id="md-total"
                                                                class="text-right">
                                                                0,000៛
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row" width="25%">ទឹកប្រាក់សរុប (៛)</th>
                                                            <td width="25%" id="md-riel" class="text-right">
                                                                0,000៛</td>
                                                            <td width="25%">នៅសល់</td>
                                                            <td width="25%" id="md-riel-balance"
                                                                class="text-right">0៛</td>
                                                        </tr>
                                                        <tr>
                                                            <th width="25%" scope="row">ទឹកប្រាក់សរុប ($)</th>
                                                            <td width="25%" id="md-usd" class="text-right">
                                                                $0.00</td>
                                                            <td width="25%">នៅសល់ ($)</td>
                                                            <td width="25%" id="md-usd-balance"
                                                                class="text-right">$0</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>


                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label for="amount">ទឹកប្រាក់</label> (៛)
                                                    <input name="amount_main" type="text" id="amount_main"
                                                        class="pa form-control kb-pad amount_main"
                                                        onkeypress="return isDecimalNumber(event)" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label for="amount">វិធីសាស្រ្តទូទាត់</label>
                                                    <select id="paid_by" name="paid_by"
                                                        class="form-control select2" style="width: 100%;">
                                                        <option value="cash">cash</option>
                                                        <option value="credit card">credit card</option>
                                                        <option value="debit card">debit card</option>
                                                        <option value="cheque">cheque</option>
                                                        <option value="mobile payment">mobile payment</option>
                                                        <option value="electronic bank transfers">electronic bank
                                                            transfers
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xs-12">
                                                <div class="form-group"><label
                                                        for="payment_note">កំណត់ចំណាំចំណាយ</label>
                                                    <input type="text" id="payment_note"
                                                        class="form-control payment_note kb-text">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-3">
                                            <div class="btn-group btn-group-vertical" style="width:100%;">
                                                <button type="button" class="btn btn-primary btn-block quick-cash"
                                                    id="quick-payable">9000<span class="badge badge-right ">1</span>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-block btn-warning quick-cash">10000
                                                </button>
                                                <button type="button"
                                                    class="btn btn-block btn-warning quick-cash">20000
                                                </button>
                                                <button type="button"
                                                    class="btn btn-block btn-warning quick-cash">30000
                                                </button>
                                                <button type="button"
                                                    class="btn btn-block btn-warning quick-cash">40000
                                                </button>
                                                <button type="button"
                                                    class="btn btn-block btn-warning quick-cash">50000
                                                </button>
                                                <button type="button"
                                                    class="btn btn-block btn-warning quick-cash">100000
                                                </button>
                                                <button type="button" class="btn btn-block btn-danger"
                                                    id="clear-cash-notes"
                                                    style="background-color: orangered; color: #fff">សំអាត
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">បិត
                                    </button>
                                    <button type="button" class="btn btn-success"
                                        id="btn-payment">ការទូទាត់</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>


                    <div class="modal fade" id="modal-alert">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content ">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="modal-title">Default Modal</h4>
                                </div>
                                {{--                                <div class="modal-body"> --}}
                                {{--                                    <button type="button" class="btn btn-primary pull-right">Save changes</button> --}}
                                {{--                                </div> --}}
                                <div class="modal-footer">
                                    {{--                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> --}}
                                    <button type="button" class="btn btn-primary"
                                        data-dismiss="modal">យល់ព្រម</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    @php
                        //   print_r($data);
                        //  print_r($store_id);
                    @endphp


                    <ul class="mailbox-attachments clearfix">

                        {{--                          <li class="product-item" id="1" data-name="i phone14 pro" data-price="2000.0"> --}}
                        {{--                            <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo1.png" alt="Attachment"></span> --}}

                        {{--                            <div class="mailbox-attachment-info"> --}}
                        {{--                              <a href="#" class="mailbox-attachment-name"> photo1.png</a> --}}

                        {{--                            </div> --}}
                        {{--                          </li> --}}

                        {{--                          <li class="product-item" class="product-item" id="2" data-name="i phone13" data-price="1300.0"> --}}
                        {{--                            <span class="mailbox-attachment-icon has-img"><img height="200px" src="../../dist/img/photo2.png" alt="Attachment"></span> --}}

                        {{--                            <div class="mailbox-attachment-info"> --}}
                        {{--                              <a href="#" class="mailbox-attachment-name"> photo2.png</a> --}}

                        {{--                            </div> --}}
                        {{--                          </li> --}}

                        @foreach ($data as $item)
                            <li class="product-item" class="product-item" id="{{ $item->id }}"
                                data-name="{{ $item->name }}" data-price="{{ $item->price }}"
                                data-code="{{ $item->code }}">
                                <span class="mailbox-attachment-icon has-img"><img height="200px"
                                        style="width: 100%;"
                                        src="{{ asset('uploads/' . $item->image) }}"
                                         alt="Attachment"
                                        /onerror='this.src="{{ asset('/uploads/none.jpg') }}"'></span>

                                <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name"> {{ $item->name }}</a>

                                </div>
                            </li>
                        @endforeach

                    </ul>

                </div>
            </div>

            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
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

                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">

                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>

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
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->

    <!-- Select2 -->
    <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- <script src="{{ asset('dist/js/demo.js') }}"></script> -->

    <script type="text/javascript" src="{{ asset('js/pos.js') }}"></script>

</body>

</html>
