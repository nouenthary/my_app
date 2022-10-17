<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-o"></i> <span
                        id="modal-title">{{lang('add')}} {{lang('customer')}}</span></h4>
            </div>
            <form id="form-create" role="form" method="POST" enctype="multipart/form-data"
                  action="{{ url('customers') }}">

                @csrf

                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id"
                           value="0">

                    <input type="hidden" class="form-control" id="photo" name="photo"
                           value="">

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="phone">{{lang('customer')}} :</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="{{lang('enter')}} {{lang('customer')}}" required value="">

                                </div>


                                <div class="form-group">
                                    <label for="phone">{{lang('phone')}} :</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           placeholder="{{lang('enter')}} {{lang('phone')}}" required value="">

                                </div>

                                <div class="form-group">
                                    <label for="city">{{lang('email')}} :</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                           placeholder="{{lang('enter')}} {{lang('email')}}" required value="">

                                </div>


                                <div class="form-group" style="display: none">
                                    <label for="image">{{lang('image')}} :</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>


                                <div class="form-group">
                                    <label for="status">{{lang('status')}} :</label>
                                    <select  class="form-control" id="status" name="status"
                                    >
                                        <option value="enable">Enable</option>
                                        <option value="disabled">Disabled</option>
                                    </select>


                                </div>

                                <div class="form-group">
                                    <label for="address">{{lang('address')}} :</label>
                                    <textarea rows="4" class="form-control" id="address" name="address"
                                              placeholder="{{lang('enter')}} {{lang('address')}}"></textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                            class="fa fa-close"></i> {{lang('close')}}</button>
                    <button type="submit" class="btn btn-success "><i class="fa fa-paste"></i> {{lang('save')}}
                    </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
