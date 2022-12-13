@extends('main')
@section('content')

    @php
        $type =  $_GET['import_type'];
        if($type == ''){
           header('Location: '.'/list_import');
        }

    @endphp

    <form id="form-create" role="form" method="POST" enctype="multipart/form-data"
          action="{{ url('create_import') }}">

        @csrf

        <div class="modal-body">


            <input type="hidden" class="form-control" id="id" name="id"
                   value="0" required>

            <input type="hidden" class="form-control" id="type_import" name="type_import"
                   value="{{$type_import}}" >

            <div class="box-body">

                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group" style="display: none">
                            <label for="store_id_no" style="width: 150px">{{lang('warehouse')}}{{lang('branch')}} : </label>
                            <select disabled="" style="width: 30%" class="form-control select2" id="ware_id" name="ware_id" required>

                                @foreach($store as $s)
                                    <option {{$s->id == 1 ? 'selected' : ''}} value="{{$s->id}}">{{$s->name}} / {{$s->city}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="store_id_no" style="width: 150px">{{lang('transfer_stock')}} : </label>
                            <select {{$main_store != '' ? 'disableds' : ''}} style="width: 30%" class="form-control select2" id="store_id" name="store_id" required>
                                <option value="">{{lang('all')}}</option>
                                @foreach($store as $s)
                                    <option {{$s->id == $main_store ? 'selected' : ''}}  value="{{$s->id}}">{{$s->name}} / {{$s->city}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="product_id" style="width: 150px">{{lang('product')}} : </label>
                            <select readonly="" style="width: 30%" class="form-control select2" id="product_id" name="product_id" >
                                <option value="">{{lang('all')}}</option>
                                @foreach($product as $s)
                                    <option value="{{$s->id}}" data-price="{{$s->price}}">{{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <table class="table table-sm table-bordered table-striped-columns">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{lang('product')}}</th>
                                    <th scope="col">{{lang('qty')}}</th>
                                    <th scope="col" >{{lang('price')}}</th>
                                    <th scope="col" >{{lang('amount')}}</th>
                                    <th scope="col">{{lang('')}}</th>
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
                                    <th scope="col" >{{lang('')}}</th>
                                    <th scope="col" >{{lang('')}}</th>
                                    <th scope="col" >{{lang('')}}</th>
                                </tr>
                                </tfoot>

                            </table>

                        </div>



                        <div class="form-group ">
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

@endsection

@push('scripts')
    <script>
        $(function () {
            //
            $(document).on('change', '#product_id', function () {
                //alert($(this).val())
                //alert($('#product_id_add option:selected').text())
                //alert($('#product_id_add option:selected').attr('data-price'));
                if($(this).val() == ''){
                    return;
                }
                let products = localStorage.getItem('products');

                let items = {
                    id: $(this).val(),
                    name: $('#product_id option:selected').text(),
                    price : $('#product_id option:selected').attr('data-price'),
                    qty: 1,
                    amount: parseFloat($('#product_id option:selected').attr('data-price')) * 1,
                    warehouse_id: 1
                };

                if (products == null) {
                    localStorage.setItem('products', JSON.stringify([items]));
                    get_table();
                    return;
                }

                let pro = JSON.parse(localStorage.getItem('products'));

                let has = pro.filter(i => i.id == $(this).val());
                //console.log(has)
                if( has != 0 ){
                    get_table();
                    return;
                }

                localStorage.setItem('products',JSON.stringify([...pro,items]));

                get_table();
            });

            //
            $(document).on('change','.qty',function () {
                let qty = $(this).val()
                if(qty == '' || qty == 0){
                    qty = 1
                    $(this).val(1)
                }

                let pro = JSON.parse(localStorage.getItem('products'));
                let id = $(this).closest('tr').attr('id');
                let index = pro.findIndex(i => i.id == id);
                pro[index].qty = parseInt(qty)
                pro[index].amount = parseInt(qty) * parseFloat(pro[index].price)
                localStorage.setItem('products',JSON.stringify([...pro]));
                get_table();
            });

            $(document).on('change','.store_id_noz',function () {
                let pro = JSON.parse(localStorage.getItem('products'));
                let id = $(this).closest('tr').attr('id');
                let index = pro.findIndex(i => i.id == id);
                pro[index].warehouse_id = $(this).val()
                localStorage.setItem('products',JSON.stringify([...pro]));
                //  get_table();
            });


            $(document).on('click', '.remove-item', function () {
                let pro = JSON.parse(localStorage.getItem('products'));
                let products = pro.filter(i => i.id != $(this).closest('tr').attr('id'));
                localStorage.setItem('products',JSON.stringify(products));
                get_table()
            })

            $(document).on('submit', '#form-create', function (e) {
                e.preventDefault();

                let data = JSON.parse(localStorage.getItem('products'));

                if(data == null){
                    alert("បន្ថែមផលិតផលខាងក្រោម");
                    return;
                }

                let json = {
                    data: data,
                    _token : "{{ csrf_token() }}",
                    store_id: $('#store_id').val(),
                    ware_id: $('#ware_id').val(),
                    remark: $('iframe').contents().find('.wysihtml5-editor').text(),
                    image: JSON.stringify($('input[type=file]')[0].files[0]),
                    status: $('#status').val(),
                    type_import :  $('#type_import').val(),
                };

                $('#submit').attr('disabled','');

                $.ajax({
                    url: $(this).attr('action'),
                    data: json,
                    type: 'post',
                    enctype: 'multipart/form-data',
                    success: function (data) {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        localStorage.removeItem('products');

                        $('#submit').removeAttr('disabled');

                        if(data.invoice){
                            window.open('{{route('receipt')}}?id=' + data.invoice +'&token=' + '{{ csrf_token() }}' ,'_blank')
                        }

                        location.reload();
                    },
                    error: function (ajaxContext) {
                        $('#submit').removeAttr('disabled')
                    }
                });
            });


            function get_table(){
                let pro = JSON.parse(localStorage.getItem('products'));
                $('#list-item').empty();
                let total = 0;
                //+ " - " + parseFloat(pro[i].price).toLocaleString()
                if(pro != null){
                    for (i in pro){
                        //console.log(pro[i]);
                        total = total + parseInt(pro[i].qty);
                        $('#list-item').append(`
                            <tr id="` + pro[i].id +`">
                                <th scope="row" style="width: 20px">`+ (parseInt(i) + 1) +`</th>
                                <td>`+pro[i].name  +`</td>

                                <td class="text-center" style="width: 200px"> <input required style="width: 100%" class="form-control text-center qty numeric" value="` + parseInt(pro[i].qty) +`"/> </td>
                                <td  class="text-right">`+ parseFloat(pro[i].price).toLocaleString()  +`៛</td>
                                 <td class="text-right">`+ (parseFloat(pro[i].price) * parseInt(pro[i].qty)).toLocaleString()  +`៛</td>

                                <td class="text-center" style="width: 20px"><i style="cursor: pointer" class="fa fa-close text-danger remove-item"></i></td>
                            </tr>
                        `);
                    }
                    $('#t-total').text(total);
                }
            }

            get_table()


            $(document).on('click', '.btn-return', function (e) {
                //alert($(this).attr('id'));
                $.ajax({
                    url: "{{ url('return_import') }}",
                    type: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $(this).attr('id'),
                    },
                    success: function (data) {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                    }
                });
            });

        });
    </script>
@endpush
