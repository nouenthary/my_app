<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-user"></i> Add Users</h4>
            </div>
            <form id="form-create" role="form" method="POST" action="{{ url('create_users') }}">

                @csrf

                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id"
                           value="0">
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname">Firstname</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                           placeholder="Enter First name" required value="admin">

                                </div>

                                <div class="form-group">
                                    <label for="lastname">Lastname</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                           placeholder="Enter Last name" required value="">

                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           placeholder="Enter Phone" required value="07772655">

                                </div>

                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="Enter email" required value="admin@gmail.com">
                                </div>

                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                           placeholder="Enter username" required value="admin">

                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="Enter Password" required value="user@123">
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="avatar">Avatar</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar">
                                </div>

                                <div class="form-group">
                                    <label for="store_id">Store</label>
                                    <select class="form-control" id="store_id" name="store_id">
                                        @foreach ($store as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="group_id">Group</label>
                                    <select class="form-control" id="group_id" name="group_id">
                                        @foreach ($permission as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>

                        </div>



                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save changes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
