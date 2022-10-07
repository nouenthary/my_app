<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-cube"></i> <span id="modal-title">Add Import</span></h4>
            </div>
            <form id="form-create" role="form" method="POST" enctype="multipart/form-data"
                  action="{{ url('create_import') }}">

                @csrf

                <div class="modal-body">
                    <input type="hidden" class="form-control" id="warehouse_id" name="warehouse_id"
                           value="0" required>

                    <input type="hidden" class="form-control" id="status" name="status"
                           value="Import" >

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="qty">Quantity </label>
                                    <input type="text" class="form-control numeric" id="qty" name="qty"
                                           placeholder="Enter Quantity" required value="0">

                                </div>

                                <div class="form-group">
                                    <label for="file">Reference</label>
                                    <input type="file" class="form-control" id="file" name="file">
                                </div>

                                <div class="form-group">
                                    <textarea id="remark" name="remark" class="textarea"
                                              placeholder="Place some text here"
                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>

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
