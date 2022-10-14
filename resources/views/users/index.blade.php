@extends('main')
@section('content')
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-create">
      <i class="fa fa-users"></i>  Add User
    </button>

    @include('users.modal_create')

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

                console.log(form)
               // return
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
