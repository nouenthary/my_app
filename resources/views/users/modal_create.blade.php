

<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-user"></i>{{lang('add')}} {{lang('username')}}</h4>
            </div>
            <form id="form-create" role="form" method="POST" action="{{ url('create_users') }}">

                @csrf

                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id"
                           value="0">

                    <input type="hidden" class="form-control" id="photo" name="photo"
                           value="0">

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">{{lang('first_name')}}</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                           placeholder="{{lang('enter')}} {{lang('first_name')}}" required value="">

                                </div>

                                <div class="form-group">
                                    <label for="last_name">{{lang('last_name')}}</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                           placeholder="{{lang('enter')}} {{lang('last_name')}}" required value="">

                                </div>

                                <div class="form-group">
                                    <label for="phone">{{lang('phone')}}</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           placeholder="{{lang('enter')}} {{lang('phone')}}" required value="07772655">

                                </div>

                                <div class="form-group">
                                    <label for="gender">{{lang('gender')}}</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="email">{{lang('email')}}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="{{lang('enter')}} {{lang('email')}}" required value="admin@gmail.com">
                                </div>

                                <div class="form-group">
                                    <label for="username">{{lang('username')}}</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                           placeholder="{{lang('enter')}} {{lang('username')}}" required value="admin">

                                </div>

                                <div class="form-group" id="text-password">
                                    <label for="password">{{lang('password')}}</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="{{lang('enter')}} {{lang('password')}}" required value="user@123">
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="avatar">{{lang('image')}}</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar">
                                </div>

                                <div class="form-group">
                                    <label for="store_id">{{lang('branch')}}</label>
                                    <select class="form-control" id="store_id" name="store_id">
                                        @foreach ($store as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="group_id">{{lang('group')}}</label>
                                    <select class="form-control" id="group_id" name="group_id">
                                        @foreach ($permission as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="salt">{{lang('commission')}}</label>
                                    <select class="form-control" id="salt" name="salt">
                                        <option value="branch">Branch</option>
                                        <option value="staff">Staff</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="form-group">

                                </div>


                                <div class="form-group">


                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="Permission" name="Permission">
                                            Permission
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="Product" name="Product">
                                            Product
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="Category" name="Category">
                                            Category
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="Import" name="Import">
                                            Import
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="Export" name="Export">
                                            Export
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="Sale" name="Sale">
                                            Sale
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="User" name="User">
                                            User
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="Setting" name="Setting">
                                            Setting
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="Report" name="Report">
                                            Report
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="POS" name="POS">
                                            POS
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="Dashboard" name="Dashboard">
                                            Dashboard
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="EX" name="EX">
                                            List Export
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="" id="IMP" name="IMP">
                                            List Import
                                        </label>
                                    </div>


                                </div>


                            </div>

                        </div>



                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{lang('close')}}</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{lang('save')}}</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
