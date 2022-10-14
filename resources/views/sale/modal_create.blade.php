<div class="modal fade" id="modal-close-register">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-o"></i>
                    <span id="modal-titles">{{lang('close_register')}}  {{get_current_date()}}
                    </span></h4>
            </div>
            <form id="form-create" role="form" method="get" enctype="multipart/form-data"
                  action="{{ url('pos') }}">

                {{csrf_field()}}

                <div class="modal-body">
                    <input type="hidden" class="form-control" id="status" name="status" value="close">

                    <div class="box-body">

                        <div class="row">
                            <div class="form-group">
                                <label for="status">{{lang('note')}} :</label>

                                <textarea rows="4" class="form-control" id="note" name="note" placeholder="{{lang('enter')}} {{lang('note')}}"></textarea>

                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i
                            class="fa fa-close"></i> {{lang('close')}}</button>
                    <button type="submit" class="btn btn-success "><i
                            class="fa fa-telegram"></i> {{lang('close_register')}} </button>
                </div>
                {{csrf_field()}}
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
