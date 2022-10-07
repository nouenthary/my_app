@extends('main')
@section('content')
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-create">
      <i class="fa fa-users"></i>  Add User
    </button>

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
                                            placeholder="Enter Password" required value="admin@123">
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirm">Password Confirm</label>
                                        <input type="password" class="form-control" id="password_confirm"
                                            name="password_confirm" placeholder="Enter Password Confirm" required
                                            value="admin@123">
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

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">User Data Table</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <div class="card">

                <table class="table table-bordered table-striped" id="table">
                    <thead>
                        <tr>
                            <th class="col-md-1">First Name</th>
                            <th>Last Name</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th> <input class="form-control" placeholder="[First Name]" /></th>
                            <th>Last Name</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th width="80px" align="center">Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
        <!-- /.box-body -->
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('get_users') }}',
                columns: [{
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'active',
                        name: 'active'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                "lengthMenu": [ [10 ,20, 50, 100, -1], [10 ,20, 50, 100, "All"] ],
                 "pageLength": 20
            });

            //
            $(document).on('submit', '#form-create', function(e) {
                e.preventDefault();
                let form = $(this).serializeArray();

                $.ajax({
                    url: "{{ url('create_users') }}",
                    data: form,
                    type: 'post',
                    success:function(data){
                        if(data.error){
                            alert(data.error);
                            return;
                        }
                        $('#modal-create').modal('hide');
                        table.draw();
                        $('#form-create').get(0).reset();
                    }
                });
            });


            $(document).on('click','.btn-pencil',function(){

                let form = {
                    id : $(this).attr('id')
                };

                $.ajax({
                    url: "{{ url('get_user_id','1') }}",
                    data: form,
                    type: 'get',
                    success:function(data){
                        if(data.error){
                            alert(data.error);
                            return;
                        }
                        $('#modal-create').modal('show');


                        $('#id').val(data.id);
                        $('#first_name').val(data.first_name);
                        $('#last_name').val(data.last_name);
                        $('#phone').val(data.phone);
                        $('#gender').val(data.gender);
                        $('#store_id').val(data.store_id);
                        $('#group_id').val(data.group_id);

                    }
                });

            });

        });
    </script>
@endpush
