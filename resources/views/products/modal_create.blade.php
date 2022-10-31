<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-o"></i> <span
                        id="modal-title">{{lang('add')}} {{lang('product')}}</span></h4>
            </div>
            <form id="form-create" role="form" method="POST" enctype="multipart/form-data"
                  action="{{ url('products') }}">

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
                                    <label for="code">{{lang('code')}} :</label>

                                    <div class="input-group">
                                        <input type="text" class="form-control" id="code" name="code"
                                               placeholder="{{lang('enter')}} {{lang('code')}}" required value="">
                                        <span class="input-group-addon generate" style="cursor: pointer"><i
                                                class="fa fa-random"></i></span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="barcode_symbology">{{lang('Barcode_symbology')}} :</label>
                                    <select class="form-control select2" id="barcode_symbology" name="barcode_symbology"
                                            style="width: 100%">
                                        <option value="Code25">Code25</option>
                                        <option value="Code39">Code39</option>
                                        <option value="Code128">Code128</option>
                                        <option value="EAN8">EAN8</option>
                                        <option value="EAN13">EAN13</option>
                                        <option value="UPC-A">UPC-A</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="qty">{{lang('product')}} :</label>
                                    <input type="name" class="form-control" id="name" name="name"
                                           placeholder="{{lang('enter')}} {{lang('product')}}" required value="">
                                </div>

                                <div class="form-group">
                                    <label for="qty">{{lang('unit_cost')}} :</label>
                                    <input type="name" class="form-control" id="cost" name="cost"
                                           placeholder="{{lang('enter')}} {{lang('unit_cost')}}" required value="0">
                                </div>

                                <div class="form-group">
                                    <label for="qty">{{lang('sale_price')}} :</label>
                                    <input type="name" class="form-control" id="price" name="price"
                                           placeholder="{{lang('enter')}} {{lang('sale_price')}}" required value="0">
                                </div>

                                <div class="form-group">
                                    <label for="category_id">{{lang('category')}} :</label>
                                    <select class="form-control select2" id="category_id" name="category_id" required
                                            style="width: 100%">

                                        @foreach($categories as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach

                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="brand_id">{{lang('brand')}} :</label>
                                    <select class="form-control select2" id="brand_id" name="brand_id"
                                            style="width: 100%">
                                        <option value=""></option>
                                        @foreach($brands as $row)
                                            <option value="{{$row->id}}">{{$row->brand_name}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="image">{{lang('image')}} :</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>


                                <div class="form-group">
                                    <label for="unit">{{lang('unit')}} :</label>
                                    <select class="form-control select2" id="unit" name="unit" style="width: 100%">
                                        <option value="pcs">Pcs</option>
                                        <option value="kg">Kg</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="is_active">{{lang('status')}} :</label>
                                    <select class="form-control select2" id="is_active" name="is_active"
                                            style="width: 100%">
                                        <option value="1">{{lang('enable')}}</option>
                                        <option value="0">{{lang('disable')}}</option>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="brand">{{lang('branch_commission')}} :</label>
                                    <input class="form-control" id="branch_commission" name="branch_commission" value="0" placeholder="{{lang('branch_commission')}}">

                                </div>

                                <div class="form-group">
                                    <label for="staff">{{lang('staff_commission')}} :</label>
                                    <input class="form-control" id="staff_commission" name="staff_commission" value="0" placeholder="{{lang('staff_commission')}}">

                                </div>

                                <div class="form-group">
                                    <label for="other">{{lang('other_commission')}} :</label>
                                    <input class="form-control" id="other_commission" name="other_commission" value="0" placeholder="{{lang('other_commission')}}">

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
