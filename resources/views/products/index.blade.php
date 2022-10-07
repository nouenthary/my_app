@extends('main')
@section('content')
    {{-- {{ $title }} --}}
    <div>
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-create">
            <i class="fa fa-users"></i> Add User
        </button>
    </div>

    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">

            <div class="card">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-striped table-sm" id="table">
                        <thead>
                            <tr>
                                <th style="width: 25px">Photo</th>
                                <th class="col-md-1">Code</th>
                                <th>Name</th>
                                <th class="col-md-1">Category</th>
                                <th class="col-md-1">Unit Cost</th>
                                <th class="col-md-1">Sell Price</th>
                                <th style="width: 25px">Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th> </th>
                                <th> <input class="form-control" placeholder="[Code]" /></th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Unit Cost</th>
                                <th>Sell Price</th>
                                <th width="80px" align="center">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
                ajax: '{{ url('get_products') }}',
                columns: [{
                        data: 'image',
                        name: 'image',
                        "sortable": false,
                        "orderable": false,
                        render: function(data, type, full, meta) {
                            console.log(full);
                            return '<img src="http://192.168.1.55:8009/dist/img/user2-160x160.jpg" width="30px"/>';
                        }
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'cate_name',
                        name: 'cate_name'
                    },
                    {
                        data: 'cost',
                        name: 'cost',
                        render: currency
                    },
                    {
                        data: 'price',
                        name: 'price',
                        render: currency
                    },

                    {
                        data: 'total',
                        name: 'total',
                        render: intQty
                    }
                ],
                "lengthMenu": [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]
                ],
                "pageLength": 20,
                searching: false,
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    //  $(api.column(0).footer()).html('Total');
                    // $(api.column(1).footer()).html(monTotal);
                    //    $(api.column(2).footer()).html(tueTotal);
                    //   $(api.column(3).footer()).html(wedTotal);
                    //   $(api.column(4).footer()).html(thuTotal);

                    $(api.column(6).footer()).html(intQty(api
                        .column(6)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0)));
                }
            });

            //
            $(document).on('submit', '#form-create', function(e) {
                e.preventDefault();
                let form = $(this).serializeArray();

                $.ajax({
                    url: "{{ url('create_users') }}",
                    data: form,
                    type: 'post',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        $('#modal-create').modal('hide');
                        table.draw();
                        $('#form-create').get(0).reset();
                    }
                });
            });


            $(document).on('click', '.btn-pencil', function() {

                let form = {
                    id: $(this).attr('id')
                };

                $.ajax({
                    url: "{{ url('get_user_id', '1') }}",
                    data: form,
                    type: 'get',
                    success: function(data) {
                        if (data.error) {
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
