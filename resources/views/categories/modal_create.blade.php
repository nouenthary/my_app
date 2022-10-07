<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-o"></i> <span id="modal-title">{{lang('add')}} {{lang('category')}}</span></h4>
            </div>
            <form id="form-create" role="form" method="POST" enctype="multipart/form-data"
                  action="{{ url('categories') }}">

                @csrf

                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id"
                           value="0" required>

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="code">{{lang('code')}} </label>
                                    <input type="text" class="form-control" id="code" name="code"
                                           placeholder="{{lang('enter')}} {{lang('code')}}" required value="">

                                </div>

                                <div class="form-group">
                                    <label for="qty">{{lang('category')}} </label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="{{lang('enter')}} {{lang('category')}}" required value="">

                                </div>


                                <div class="form-group">
                                    <label for="image">{{lang('image')}}</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>



                                <div class="form-group">
                                    <label for="status">{{lang('status')}}</label>
                                    <select class="form-control select2" id="status" name="status" style="width: 100%">
                                        <option value="0">{{lang('enable')}}</option>
                                        <option value="1">{{lang('disable')}}</option>
                                    </select>

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
