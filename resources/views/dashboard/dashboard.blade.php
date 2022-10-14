@extends('main')
@section('content')

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>

    <div class="row">
        <div class="col-lg-3 col-md-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{  number_format($qty_sold[0]->qty) }} pcs</h3>

                    <p>{{ lang('stock_in') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">{{ lang('more_info') }} <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-md-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ number_format($qty_in[0]->qty) }} pcs</h3>

                    <p>{{ lang('stock_in') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">{{ lang('more_info') }} <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ number_format($qty_out[0]->qty) }} pcs</h3>

                    <p>{{ lang('stock_out') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">{{ lang('more_info') }} <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->

        <!-- ./col -->
        <div class="col-lg-3 col-md-6 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ number_format($qty_balance) }} pcs</h3>

                    <p>{{ lang('balance') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">{{ lang('more_info') }} <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->

    </div>

    <style>
        #container {
            height: 600px;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            {{-- min-width: 310px; --}} {{-- max-width: 800px; --}}  margin: 1em auto;
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


    <figure class="highcharts-figure">
        <div id="container"></div>

    </figure>

@endsection

@push('scripts')
    <script>
        $(function () {

            fetch('/get_chart_sale')
                .then((response) => response.json())
                .then((data) =>

                    Highcharts.chart('container', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Top Sale (' + '' + " Summary : " + data.total + " Pcs)"
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
                            data: data.sold,
                            // [
                            //     ['Beijing', 20.8],
                            //     ['Karachi', 14.9],
                            //     ['Shenzhen', 13.7],
                            //     ['Guangzhou', 13.1],
                            //     ['Istanbul', 12.7],
                            //     ['Mumbai', 12.4],
                            //     ['Moscow', 12.2],
                            //
                            // ],
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
                    })

                );





        });
    </script>
@endpush
