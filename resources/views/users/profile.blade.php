@extends('main')
@section('content')

    @php
        $user_id = \Illuminate\Support\Facades\Auth::user()->user_id;
        $user = \Illuminate\Support\Facades\DB::table('tec_users')->where('id', $user_id)->first();
        $permission = App\Utils::get_permissions();
    @endphp


    @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Alert!</h4>
            {{\Session::get('success')}}
        </div>
    @endif

    @if (\Session::has('error'))
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Alert!</h4>
            {{\Session::get('error')}}
        </div>
    @endif


    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img
                        class="profile-user-img img-responsive img-circle"
                        src="{{asset('/uploads/'.$user->avatar)}}"
                        alt="User profile picture"
                        onerror="this.src='{{asset('/uploads/photo/male.png')}}'"
                    >

                    <h3 class="profile-username text-center">{{$user->first_name}} {{$user->last_name}}</h3>

                    <p class="text-muted text-center">Admin</p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Followers</b> <a class="pull-right">0</a>
                        </li>
                        <li class="list-group-item">
                            <b>Following</b> <a class="pull-right">0</a>
                        </li>
                        <li class="list-group-item">
                            <b>Friends</b> <a class="pull-right">0</a>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab">{{lang('setting')}}</a></li>
                </ul>
                <div class="tab-content">


                    <div class="active tab-pane" id="settings">
                        <form class="form-horizontal" method="post" action="{{url('update_profile')}}">
                            {{csrf_field()}}
                            <input type="hidden" class="form-control" id="id" name="id" value="{{$user->id}}">

                            <input type="hidden" class="form-control" id="photo" name="photo" value="{{$user->avatar}}">

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">{{lang('username')}}</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="{{lang('username')}}" required value="{{$user->username}}" {{$permission->permission ==1 ? '': 'disabled'}} >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="first_name" class="col-sm-2 control-label">{{lang('first_name')}} </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                           placeholder="{{lang('first_name')}}" required value="{{$user->first_name}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="col-sm-2 control-label">{{lang('last_name')}}</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="last_name" id="last_name"
                                           placeholder="{{lang('last_name')}}" required value="{{$user->last_name}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="col-sm-2 control-label">{{lang('password')}}</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="password" id="password"
                                           placeholder="{{lang('password')}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="photo" class="col-sm-2 control-label">{{lang('photo')}}</label>

                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="file" name="file"
                                           placeholder="placeholder="{{lang('photo')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">{{lang('submit')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>

@endsection
