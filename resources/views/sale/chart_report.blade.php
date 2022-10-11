@extends('main')
@section('content')
    <style>
        #container {
            height: 600px;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            {{-- min-width: 310px; --}} {{-- max-width: 800px; --}} margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        .datepicker {
            -webkit-border-radius: 0px;
        }

        @media only screen and (max-width: 768px) {
            .btn-flat {
                width: 100%;
            }
        }
    </style>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>

    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="btn-group">
                <button type="button" id="btn-all" class=" btn btn-success btn-flat" value="All"><i
                        class="fa fa-calendar"></i> {{lang('all_report')}}</button>
                <button type="button" id="btn-year" class="btn btn-warning btn-flat" value="Year"><i
                        class="fa fa-calendar"></i> {{lang('report_year')}}</button>
                <button type="button" id="btn-month" class="btn btn-primary btn-flat" value="Month"><i
                        class="fa fa-calendar"></i> {{lang('report_monthly')}}</button>
                <button type="button" id="btn-day" class="btn btn-danger btn-flat" value="day"><i
                        class="fa fa-calendar"></i> {{lang('report_daily')}}</button>
            </div>
        </div>

        <div class="col-lg-2" id="y">
            <select class="form-control select2 select2-hidden-accessible" id="ddl-year" style="width: 100%;">
                <option></option>
            </select>
        </div>

        <div class="col-lg-2 " id="m">
            <select class="form-control select2 select2-hidden-accessible" id="ddl-month" style="width: 100%;">
                <option></option>
            </select>
        </div>
        <div class=" col-lg-2" id="d">
            <input class="form-control form-control-sm datepicker" id="date" placeholder="{{lang('input_date')}}"
                autocomplete="off">
        </div>



    </div>

    <div id="json"></div>

    <figure class="highcharts-figure">
        <div id="container"></div>

    </figure>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.datepicker')
                .datepicker({
                    {{-- showButtonPanel: true,
                    changeMonth: true,
                    dateFormat: 'dd-mm-yyyy', --}}
                    setDate: '01/26/2014'
                });

            let json = {
                _token: "{{ csrf_token() }}",
            }

            $('.datepicker').change(function() {
                let date = $(this).val();

                json = {
                    ...json,
                    year: $('#ddl-year').val(),
                    month: $('#ddl-month').val(),
                    date: $('#date').val(),
                    type: 'day'
                }
                Load(json);
            });


            let date = moment();

            let year = date.format('YYYY');

            let month = date.format('MMMM');

            $('#date').val(date.format('DD-MM-YYYY'));

            for (let i = year - 10; i <= parseInt(year) + 10; i++) {
                if (year == i) {
                    $('#ddl-year').append('<option value=' + i + ' selected>' + i + '</option>');
                } else {
                    $('#ddl-year').append('<option value=' + i + ' >' + i + '</option>');
                }
            }

            const months = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            for (let i = 0; i < months.length; i++) {
                if (months[i] == month) {
                    $('#ddl-month').append('<option value=' + (i + 1) + ' selected>' + months[i] + '</option>');
                } else {
                    $('#ddl-month').append('<option value=' + (i + 1) + ' >' + months[i] + '</option>');
                }
            }

            $('#y').css('display', 'none');
            $('#m').css('display', 'none');


            $('.btn-flat').click(function() {
                var val = $(this).val();

                if (val == "Month") {
                    $('#y').css('display', 'block');
                    $('#m').css('display', 'block');
                    $('#d').css('display', 'none');
                    json = {
                        ...json,
                        type: val,
                        year: $('#ddl-year').val(),
                        month: $('#ddl-month').val(),
                        date: $('#date').val(),
                    }
                } else if (val == "day") {
                    $('#y').css('display', 'none');
                    $('#m').css('display', 'none');
                    $('#d').css('display', 'block');
                    json = {
                        ...json,
                        type: val,
                        year: $('#ddl-year').val(),
                        month: $('#ddl-month').val(),
                        date: $('#date').val(),
                    }
                } else if (val == "Year") {
                    $('#y').css('display', 'block');
                    $('#m').css('display', 'none');
                    $('#d').css('display', 'none');
                    json = {
                        ...json,
                        type: val,
                        year: $('#ddl-year').val(),
                        month: $('#ddl-month').val(),
                        date: $('#date').val(),
                    }
                } else {
                    $('#y').css('display', 'none');
                    $('#m').css('display', 'none');
                    $('#d').css('display', 'none');
                    json = {
                        ...json,
                        type: val,
                        year: $('#ddl-year').val(),
                        month: $('#ddl-month').val(),
                        date: $('#date').val(),
                    }
                }

                Load(json);
            });


            //
            $(document).on('change', '.form-control', function() {
                json = {
                    ...json,
                    year: $('#ddl-year').val(),
                    month: $('#ddl-month').val(),
                    date: $('#date').val(),
                }
                Load(json);
            });

            function Load(data) {
                let date = $('#date').val();

                let type = "Daily : " + date;

                if (json.type == "Year") {
                    type = "Year : " + json.year;
                }
                if (json.type == "Month") {
                    type = "Monthly : " + json.year + '/' + json.month;
                }
                if (json.type == "day") {
                    type = "Daily : " + date;
                }

                if (json.type == "All") {
                    type = "All : Total ";
                }


                $.ajax({
                    url: '{{ url('get_chart_report') }}',
                    type: 'get',
                    data: data,
                    success: function(r) {
                        let store = r.data;

                        //console.log(store);

                        var qty = store;

                        let summary = 0;

                        for (let i in qty) {
                            summary += qty[i][1];
                        }

                        Highcharts.chart('container', {
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: 'Top Sale (' + type + " ~ Summary : " + summary + " Pcs)"
                            },
                            subtitle: {
                                text: ''
                            },
                            xAxis: {
                                type: 'category',
                                labels: {
                                    rotation: -45,
                                    style: {
                                        fontSize: '13px',
                                        fontFamily: 'Khmer OS Battambang, sans-serif'
                                    }
                                }
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Population (Pcs)'
                                }
                            },
                            legend: {
                                enabled: false
                            },
                            tooltip: {
                                pointFormat: 'Top Sale of K Stock'
                            },
                            series: [{
                                name: 'Population',
                                data: store,
                                //    [
                                //         ['Shanghai', "24.2"],
                                //         ['Beijing', 20.8],
                                //         ['Karachi', 14.9],
                                //         ['Shenzhen', 13.7],
                                //         ['Guangzhou', 13.1],
                                //         ['Istanbul', 12.7],
                                //         ['Mumbai', 12.4],
                                //         ['Moscow', 12.2],

                                //    ],
                                dataLabels: {
                                    enabled: true,
                                    rotation: 0,
                                    color: '#FFFFFF',
                                    align: 'right',
                                    // format: '{point.y:.1f}', // one decimal
                                    y: -1, // 10 pixels down from the top
                                    style: {
                                        fontSize: '13px',
                                        fontFamily: 'Verdana, sans-serif'
                                    }
                                }
                            }]
                        });

                        $('.highcharts-credits').text('K STOCK 4500 R');

                    }
                });
            }

            Load({
                ...json,
                type: 'day',
                date: $('#date').val()
            });


        });
    </script>
@endpush
