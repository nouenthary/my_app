@extends('main')
@section('content')

    @include('import.modal_create')

    <!-- /.box-header -->
    <div class="form-search">

        <div class="row">
            <div class="col-md-12">
                @include('components.store')
            </div>

            <div class="col-md-12">
                @include('components.product')
            </div>
        </div>
        <!-- /.row -->
    </div>

    <div class="boxs">

        <!-- /.box-header -->
        <div class="box-bodys">

            <div class="cards">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-stripeds table-sm" style="background: #fff" id="table">
                        <thead>
                        <tr>
                            <th width="5px" align="center"></th>
                            <th class="col-md-1"> {{__("language.branch")}}</th>
                            <th style="width: 20px">{{__("language.photo")}}</th>
                            <th>{{__("language.product")}}</th>
                            <th class="col-md-1">{{__("language.category")}}</th>
                            <th class="col-md-1">{{__("language.sell_price")}}</th>
                            <th lass="col-md-1">{{__("language.balance")}}</th>
                            <th width="5px" align="center">{{__("language.action")}}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="6" class="text-uppercase">{{__("language.total")}}</th>
                            <th class="col-md-1" align="center">{{__("language.balance")}}</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
        <!-- /.box-body -->
    </div>

    @php
        $user_id = \Illuminate\Support\Facades\Auth::user()->user_id;
        $permission = \Illuminate\Support\Facades\DB::table('tec_permission')->where('user_id', $user_id)->first();
        //print_r($permission);
        //echo $user_id;
    @endphp

@endsection


@push('scripts')
    <script>
        $(function () {

            let permission = '<?= $permission->setting; ?>';

            let table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    type: 'post',
                    url: '{{ url('get_imports') }}',
                    data: function (d) {
                        d.store_id = $('#store_id').val();
                        d.product_id = $('#product_id').val();
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        "sortable": false,
                        "orderable": false,
                        render: function (data, type, full, meta) {
                            return `
                                    <div class="form-group">
                                    <label>
                                      <div class="icheckbox_minimal-blue " aria-checked="true" aria-disabled="true" style="position: relative;">
                                        <input type="checkbox" class="minimal" checked="" style="position: absolute; opacity: 0;">
                                        </div>
                                    </label>

                                  </div>
                                `;
                        }
                    },
                    {
                        data: 'store_name',
                        name: 'store_name'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        "sortable": false,
                        "orderable": false,
                        render: imageAvatar
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
                        data: 'price',
                        name: 'price',
                        render: currency
                    },

                    {
                        data: 'total',
                        name: 'total',
                        render: intQty
                    },
                    {
                        data: 'store_id',
                        name: 'store_id',
                        render: function (data, type, full, meta) {
                            let imports = `<button class="btn btn-success btn-xs btn-import" id="` + full.store_id + `" data-id=" ` + full.product_id + ` "><i class="fa fa-plus"></i></button>`;
                            let exports = ``;
                            if(permission == 1){
                                exports = `<button class="btn btn-danger btn-xs btn-export" id="` + full.store_id + ` " data-id="` + full.product_id + `"><i class="fa fa-minus"></i></button>`;
                            }
                            return imports + " | " + exports;
                        }
                    },
                ],
                "lengthMenu": [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]
                ],
                "pageLength": 20,
                searching: false,
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    $(api.column(6).footer()).html(intQty(api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0)));
                }
            });

            //
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
                    store_id: $('#store_id_no').val(),
                    remark: $('iframe').contents().find('.wysihtml5-editor').text(),
                    image: JSON.stringify($('input[type=file]')[0].files[0]),
                    status: $('#status').val()
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
                        $('#modal-create').modal('hide');
                        $('#form-create').get(0).reset();
                        table.draw();
                        localStorage.removeItem('products');
                        get_table()
                        $('#submit').removeAttr('disabled')
                    },
                    error: function (ajaxContext) {
                        $('#submit').removeAttr('disabled')
                    }
                });
            });

            $(document).on('click', '.btn-import', function () {
                $('#store_id_no').val(parseInt($(this).attr('id')));
                $('#store_id_no').val(parseInt($(this).attr('id'))).select2();

                $('#product_id_no').val($(this).attr('data-id'));
                $("#warehouse_id").select2().select2('val', '');
                $('#modal-create').modal('show');
                $('#status').parent('div').css('display', 'none');
                $('#form-create').attr('action', 'create_import');
                $('#modal-title').text('{{lang('add_import')}}');
                get_table();
            });

            $(document).on('click', '.btn-export', function () {
                $('#store_id_no').val(parseInt($(this).attr('id')));
                $('#store_id_no').val(parseInt($(this).attr('id'))).select2();

                $('#product_id_no').val($(this).attr('data-id'));
                $("#warehouse_id").select2().select2('val', '');
                $('#modal-create').modal('show');
                $('#status').parent('div').css('display', 'block');
                $('#form-create').attr('action', 'create_export');
                $('#modal-title').text('{{lang('add_export')}}');
                get_table();
            });

            $(document).on('change', '.minimal', function () {
                $(this).parent().addClass('checked');
            });


            //
            $(document).on('change', '#product_id_add', function () {
                //alert($(this).val())
                //alert($('#product_id_add option:selected').text())
                //alert($('#product_id_add option:selected').attr('data-price'));
                if($(this).val() == ''){
                    return;
                }
                let products = localStorage.getItem('products');

                let items = {
                  id: $(this).val(),
                  name: $('#product_id_add option:selected').text(),
                  price : $('#product_id_add option:selected').attr('data-price'),
                  qty: 1,
                  amount: parseFloat($('#product_id_add option:selected').attr('data-price')) * 1,
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


            //let warehouse = '<?= $warehouse?>'
            //console.log( warehouse);

            function get_table(){
                let pro = JSON.parse(localStorage.getItem('products'));
                $('#list-item').empty();
                let total = 0;

                if(pro != null){
                    for (i in pro){
                        //console.log(pro[i]);
                        total = total + parseInt(pro[i].qty);
                        $('#list-item').append(`
                            <tr id="` + pro[i].id +`">
                                <th scope="row">`+ (parseInt(i) + 1) +`</th>
                                <td>`+pro[i].name + " - " + parseFloat(pro[i].price).toFixed(2) +`</td>
                                <td class="text-center"> <input required style="width: 50%" class="form-control text-center qty numeric" value="` + parseInt(pro[i].qty) +`"/> </td>
                                <td>
                                    <select  style="width: 100%" class="form-control select2 store_id_noz" id="store_id_noz" name="store_id_noz" required>
                                        <option value="">{{lang('all')}}</option>
                                        @foreach($warehouse as $s)
                                            <option value="{{$s->id}}" >{{$s->name}}</option>
                                        @endforeach
                                    </select>
                                 </td>
                                 <td class="text-center"><i style="cursor: pointer" class="fa fa-trash remove-item"></i></td>
                            </tr>
                    `);
                    }
                    $('#t-total').text(total);
                }
            }


            $('.form-search select').change(function () {
                table.draw();
            });

            $('#table_wrapper .row .col-sm-6:last').append(`<div>

                <div class="btn-group pull-right">
                      <button type="button" class="btn btn-default btn-flat btn-sm" id="cmd-pdf"><i class="fa  fa-file-pdf-o"></i> {{__("language.pdf")}}</button>
                      <button type="button" class="btn btn-dropbox btn-flat btn-sm" id="cmd-img"><i class="fa fa-file-image-o"></i> {{__("language.image")}}</button>
                      <button type="button" class="btn btn-success btn-flat btn-sm" id="cmd-excel"><i class="fa  fa-file-excel-o"></i> {{__("language.excel")}}</button>
                    </div>

                </div>`);

            $('div.dataTables_length select').addClass('select2');


            $('#cmd-pdf').click(function () {
                CreatePDFfromHTML();
            });

            $('#cmd-img').click(function () {

                exportCanvasAsPNG();
            });

            $('#cmd-excel').click(function () {
                fnExcelReport();
            });


            function CreatePDFfromHTML() {
                let HTML_Width = $("#table").width();
                let HTML_Height = $("#table").height();
                let top_left_margin = 15;
                let PDF_Width = HTML_Width + (top_left_margin * 2);
                let PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
                let canvas_image_width = HTML_Width;
                let canvas_image_height = HTML_Height;

                let totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

                html2canvas($("#table")[0]).then(function (canvas) {
                    let imgData = canvas.toDataURL("image/jpeg", 1.0);
                    let pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
                    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
                    for (let i = 1; i <= totalPDFPages; i++) {
                        pdf.addPage(PDF_Width, PDF_Height);
                        pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height * i) + (top_left_margin * 4), canvas_image_width, canvas_image_height);
                    }
                    pdf.save("list_export.pdf");
                });
            }


            function fnExcelReport() {
                var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
                var textRange;
                var j = 0;
                tab = document.getElementById('table'); // id of table

                for (j = 0; j < tab.rows.length; j++) {
                    tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                }

                tab_text = tab_text + "</table>";
                tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
                tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
                tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE ");

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                    txtArea1.document.open("txt/html", "replace");
                    txtArea1.document.write(tab_text);
                    txtArea1.document.close();
                    txtArea1.focus();
                    sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
                } else                 //other browser not tested on IE 11
                    sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

                return (sa);
            }


            function exportCanvasAsPNG() {
                html2canvas(document.querySelector("#table")).then(canvas => {
                    a = document.createElement('a');
                    document.body.appendChild(a);
                    a.download = "list_import.png";
                    a.href = canvas.toDataURL();
                    a.click();
                });
            }

        });
    </script>
@endpush
