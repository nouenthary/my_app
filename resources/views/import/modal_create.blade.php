<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-cube"></i> <span id="modal-title">{{lang('add_import')}}</span></h4>
            </div>
            <form id="form-create" role="form" method="POST" enctype="multipart/form-data"
                  action="{{ url('create_import') }}">

                @csrf

                <div class="modal-body">


                    <input type="hidden" class="form-control" id="id" name="id"
                           value="0" required>

                    <input type="hidden" class="form-control" id="product_id_no" name="product_id_no"
                           value="0" required>

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="store_id_no" style="width: 150px">{{lang('branch')}} : </label>
                                    <select disabled="" style="width: 50%" class="form-control select2" id="store_id_no" name="store_id_no" required>
                                        <option value="">{{lang('all')}}</option>
                                        @foreach($store as $s)
                                            <option value="{{$s->id}}">{{$s->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="product_id_add" style="width: 150px">{{lang('product')}} : </label>
                                    <select readonly="" style="width: 50%" class="form-control select2" id="product_id_add" name="product_id_add" >
                                        <option value="">{{lang('all')}}</option>
                                        @foreach($product as $s)
                                            <option value="{{$s->id}}" data-price="{{$s->price}}">{{$s->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{lang('product')}}</th>
                                                <th scope="col">{{lang('qty')}}</th>
                                                <th scope="col">{{lang('warehouse')}}</th>
                                                <th scope="col">{{lang('action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="list-item">
                                       </tbody>

                                        <!-- On rows -->
                                        <tfoot>
                                            <tr>
                                                <th scope="col">{{lang('total')}}</th>
                                                <th scope="col">{{lang('')}}</th>
                                                <th class="text-center" scope="col" id="t-total">{{lang('')}}</th>
                                                <th scope="col">{{lang('')}}</th>
                                                <th scope="col" >{{lang('')}}</th>
                                            </tr>
                                        </tfoot>

                                    </table>

                                </div>

                                @include('components.status',['name' => 'status'])


                                <div class="form-group">
                                    <label for="file">{{lang('photo')}}</label>
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
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{lang('close')}}</button>
                    <button type="submit" class="btn btn-success" id="submit"><i class="fa fa-save"></i> {{lang('save')}}</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
