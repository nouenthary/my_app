@extends('main')
@section('content')

    <!-- /.box-header -->

    <!-- /.box-header -->
    <div class="form-search ">

        <div class="row">

            <div class="col-md-12">
                @include('components.warehouse',['style' => 'inline'])
            </div>

            <div class="col-md-12">
                @include('components.date',['style' => 'inline'])
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
                            <th style="width: 20px">{{__("language.reference")}}</th>
                            <th class="col-md-1"> {{__("language.warehouse")}}</th>
                            <th lass="col-md-1"> {{__("language.date")}}</th>
                            <th lass="col-md-1">{{__("language.qty")}} </th>
                            <th lass="col-md-1"> {{__("language.username")}}</th>
                            <th lass="col-md-1"> {{__("language.status")}}</th>
                            <th lass="col-md-1"> {{__("language.remark")}}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-uppercase">{{__("language.total")}}</th>
                            <th colspan="4" align="center">{{__("language.balance")}}</th>
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
        $(function () {
            let table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    type: 'post',
                    url: '{{ url('get_adjustment') }}',
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                        d.warehouse_id = $('#warehouse_id').val() | 0;
                        d.date = $('#date').val();
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
                        data: 'image',
                        name: 'image',
                        "sortable": false,
                        "orderable": false,
                        render: imageAvatar
                    },
                    {
                        data: 'ware_name',
                        name: 'ware_name'
                    },

                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        render: intQty
                    },
                    {
                        data: 'username',
                        name: 'username',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: status
                    },
                    {
                        data: 'note',
                        name: 'note',
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
                    $(api.column(4).footer()).html(intQty(api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0)));
                }
            });

            $('.form-search select, input').change(function () {
                table.draw();
            });


            $(document).on('change', '.minimal', function () {
                $(this).parent().addClass('checked');
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
                    pdf.save("warehouse.pdf");
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
                    a.download = "warehouse.png";
                    a.href = canvas.toDataURL();
                    a.click();
                });
            }

        });
    </script>
@endpush
