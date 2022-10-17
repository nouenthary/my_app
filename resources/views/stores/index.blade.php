@extends('main')
@section('content')



    <!-- /.box-header -->
    <div class="form-search">

        <div class="row">


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

                <li class="pull-left header"><i class="fa fa-th"></i> {{lang("branch")}}</li>

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

                <li>
                    <a class="btn btn-default" id="btn-create"><i class="fa fa-file-o"></i> {{lang('new')}} </a>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
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

    @include('stores.modal_create')

@endsection

@push('scripts')
    <script>
        $(function () {

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
                let data = {
                    _token: "{{ csrf_token() }}",
                    page_size: $('#page_size').val() ?? 10,
                    page: parseInt($('#page').text()) ?? 1
                };
                let params = new URLSearchParams(data).toString();
                $.ajax({
                    url: "{{ url('get_stores') }}" + "?" + params,
                    type: 'get',
                    success: function (data) {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        document.querySelector('#page').innerHTML = data.page;
                        document.querySelector('#per_page').innerHTML = data.per_page;
                        document.querySelector('#total').innerHTML = data.total;
                        document.querySelector('#table-show').innerHTML = data.table;
                    }
                });

                return;
            }

            get_data();

            $(document).on('click', '#btn-create', function () {
                $('#modal-create').modal('show');
                $('#id').val(0);
                $('#code').val('');
                $('#name').val('');
                $('#city').val('');
                $('#photo').val('');
                $('#address').text('')
            });

            $(document).on('submit', '#form-create', function (e) {
                e.preventDefault();

                let form = $('#form-create')[0];
                let formData = new FormData(form);
                formData.append('image', $('input[type=file]')[0].files[0]);

                $.ajax({
                    url: $(this).attr('action'),
                    data: formData,
                    type: 'post',
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        $('#modal-create').modal('hide');
                        $('#form-create').get(0).reset();
                        get_data();
                    }
                });
            });

            // row click
            $(document).on('dblclick', 'tbody tr', function () {
                let data = $(this).attr('data');
                if (data) {
                    let data_source = JSON.parse(data);
                    $('#modal-create').modal('show');
                    $('#id').val(data_source.id);
                    $('#code').val(data_source.code);
                    $('#name').val(data_source.name);
                    $('#phone').val(data_source.phone);
                    $('#city').val(data_source.city);
                    $('#address').text(data_source.address1);
                    $('#photo').val(data_source.logo);
                }
            });

            // generate
            $(document).on('click', '.generate', function () {
                $('#code').val(barcode());
            });

            let barcode = function () {
                return Math.floor(Math.random() * 10000000000000);
            }

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

