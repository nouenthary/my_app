<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-o"></i> <span id="modal-title">{{lang('add')}} {{lang('branch')}}</span></h4>
            </div>
            <form id="form-create" role="form" method="POST" enctype="multipart/form-data"
                  action="{{ url('stores') }}">

                @csrf

                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id"
                           value="0" >

                    <input type="hidden" class="form-control" id="photo" name="photo"
                           value="" >

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="code">{{lang('code')}} :</label>

                                    <div class="input-group">
                                        <input type="text" class="form-control" id="code" name="code"
                                               placeholder="{{lang('enter')}} {{lang('code')}}" required value="">
                                        <span class="input-group-addon generate" style="cursor: pointer"><i class="fa fa-random"></i></span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="qty">{{lang('branch')}} :</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="{{lang('enter')}} {{lang('branch')}}" required value="">
                                </div>



                                <div class="form-group">
                                    <label for="image">{{lang('image')}} :</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>



                                <div class="form-group">
                                    <label for="phone">{{lang('phone')}} :</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           placeholder="{{lang('enter')}} {{lang('phone')}}" required value="">

                                </div>

                                <div class="form-group">
                                    <label for="city">{{lang('city')}} :</label>
                                    <input type="text" class="form-control" id="city" name="city"
                                           placeholder="{{lang('enter')}} {{lang('city')}}" required value="">

                                </div>

                                <div class="form-group">
                                    <label for="address">{{lang('address')}} :</label>
                                    <textarea rows="4" class="form-control" id="address" name="address"
                                              placeholder="{{lang('enter')}} {{lang('address')}}" ></textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> {{lang('close')}}</button>
                    <button type="submit" class="btn btn-success "><i class="fa fa-paste"></i> {{lang('save')}} </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
