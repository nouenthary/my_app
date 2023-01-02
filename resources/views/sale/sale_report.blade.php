@extends('main')
@section('content')

    <!-- /.box-header -->
    <div class="form-search">

        <div class="row">
            <div class="col-md-12">
                @include('components.store')
            </div>

            <div class="col-md-12">
                @include('components.category')
            </div>

            <div class="col-md-12">
                @include('components.product')
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    <label for="start_date" class="col-sm-1 control-label">{{__('language.date')}}</label>

                    <div class="col-sm-3">
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right datepicker" id="start_date"
                                   name="start_date" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="end_date" class="col-sm-1 control-label">{{__('language.date')}}</label>

                    <div class="col-sm-3">
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right datepicker" id="end_date" name="end_date"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                @include('components.users',['style' => 'inline'])
            </div>

            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-1">

                    </div>
                    <div class="col-md-3">
                        <div>
                            <div class="mailbox-controls">
                                <!-- Check all button -->
                                <!-- /.btn-group -->
                                <div class="" style="display: flex; justify-content: center; align-items: center">
                                    <span id="page">1</span>-<span id="per_page">50</span>/<span id="total"
                                                                                                 style="padding-right: 10px">200</span>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-flat btn-md btn-previous"
                                                style="background-color: #fff"><i
                                                class="fa fa-chevron-left"></i></button>
                                        <button type="button" class="btn btn-default btn-flat btn-md btn-next"
                                                style="background-color: #fff"><i
                                                class="fa fa-chevron-right"></i></button>
                                    </div>

                                    <select class="form-control form-control-smm pull-right select2"
                                            style="width: 70px " id="page_size" name="page_size">
                                        <option value="10" selected="">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="1000">All</option>

                                    </select>
                                    <!-- /.btn-group -->
                                </div>
                                <!-- /.pull-right -->
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <!-- /.row -->
    </div>



    <div class="test">
        <!-- Custom Tabs (Pulled to the right) -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">

                <li class="pull-left header"><i class="fa fa-th"></i> {{__("language.sale_report")}}</li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-gear"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="cmd-excel">Excel</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="cmd-pdf">PDF</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="cmd-img">Image</a></li>
                    </ul>
                </li>

            </ul>

            <div class="tab-content">

                <div class="tab-pane active" id="tab_1-1">
                    <a class="btn btn-default btn-flat btn-sale-report" style="background-color: #fff">
                        <i class="fa fa-bar-chart"></i>
                        បង្ហាញរបាយការណ៏លក់
                    </a>
                    <div class="box-body table-responsive no-padding">

                        <div id="table-show">
                            <img src="uploads/loading.gif" style="width: 100%; height: 10%">

                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>

    <br/>

@endsection

@push('scripts')
    <script>
        $(function () {

            //let start_date = moment().startOf('month').format('DD-MM-YYYY');
            //let end_date = moment().endOf('month').format('DD-MM-YYYY');
            let now = moment().format("DD-MM-YYYY")

            $('.datepicker').datepicker(
                {
                    showButtonPanel: true,
                    changeMonth: true,
                    dateFormat: 'dd-mm-yyyy'
                }
            ).val();

            $('#start_date').val(now);
            $('#end_date').val(now);

            $(document).on('click', '.btn-previous', function () {
                let page = parseInt($('#page').text());

                if (page == '1') {
                    return;
                }
                page -= 1;

                $('#page').text(page);
                get_data();
            });

            $(document).on('click', '.btn-next', function () {
                let page = parseInt($('#page').text());
                let per_page = parseInt($('#p_page').text());

                if (page == per_page - 1) {
                    return;
                }

                page += 1;

                $('#page').text(page);
                get_data();
            });


            let get_data = function () {

                $.ajax({
                    url: "{{ url('get_sale_report') }}",
                    type: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        store_id: $('#store_id').val(),
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        page_size: $('#page_size').val() ?? 10,
                        page: parseInt($('#page').text()) ?? 1,
                        product_id : $('#product_id').val(),
                        seller_id: $('#seller_id').val(),
                        category_id: $('#category_id_search').val(),
                    },
                    success: function (data) {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        document.querySelector('#page').innerHTML = data.page;
                        document.querySelector('#per_page').innerHTML = data.per_page;
                        document.querySelector('#total').innerHTML = data.total;
                        document.querySelector('#table-show').innerHTML = data.table;
                        //console.log(data);
                    }
                });
            }

            $(document).on('click', '.btn-sale-report', function () {
                let storeId = $('#store_id').val();
                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();
                let page = parseInt($('#page').text()) ?? 1;
                let page_size= $('#page_size').val() ?? 10;
                let seller_id = $('#seller_id').val();
                let product_id = $('#product_id').val();
                let json = `?start_date=${start_date}&store_id=${storeId}&end_date=${end_date}&page=${page}&page_size=${page_size}&seller_id=${seller_id}&product_id=${product_id}`;
                window.open('/sale_report_daily' + json);
            });

            get_data();

            $('.form-search select, input').change(function () {
                get_data();
            });

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


            let filename = location.href.replace(location.host, '').replace('http:///', '');

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
                    pdf.save(filename + ".pdf");
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
                    a.download = filename + ".png";
                    a.href = canvas.toDataURL();
                    a.click();
                });
            }

        });
    </script>
@endpush
