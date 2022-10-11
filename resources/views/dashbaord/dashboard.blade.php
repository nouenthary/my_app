@extends('main')
@section('content')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

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
                <a href="#" class="small-box-footer">{{ lang('more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
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
                <a href="#" class="small-box-footer">{{ lang('more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
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
                <a href="#" class="small-box-footer">{{ lang('more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
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
                <a href="#" class="small-box-footer">{{ lang('more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->

    </div>

    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            {{-- min-width: 360px; --}}
            {{-- max-width: 800px; --}}
            margin: 1em auto;
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
    </style>

    <figure class="highcharts-figure" style="display: none">
        <div id="container"></div>

    </figure>

    <script>
        function getPointCategoryName(point, dimension) {
            var series = point.series,
                isY = dimension === 'y',
                axis = series[isY ? 'yAxis' : 'xAxis'];
            return axis.categories[point[isY ? 'y' : 'x']];
        }

        Highcharts.chart('container', {

            chart: {
                type: 'heatmap',
                marginTop: 40,
                marginBottom: 80,
                plotBorderWidth: 1
            },


            title: {
                text: 'Sales per employee per weekday'
            },

            xAxis: {
                categories: ['K1', 'K2', 'K3', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8',
                    'K9','K10','K11','K12','K13','K14'
                ]
            },

            yAxis: {
                categories: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday','Saturday','Sunday'],
                title: null,
                reversed: true
            },

            accessibility: {
                point: {
                    descriptionFormatter: function(point) {
                        var ix = point.index + 1,
                            xName = getPointCategoryName(point, 'x'),
                            yName = getPointCategoryName(point, 'y'),
                            val = point.value;
                        return ix + '. ' + xName + ' sales ' + yName + ', ' + val + '.';
                    }
                }
            },

            colorAxis: {
                min: 0,
                minColor: '#FFFFFF',
                maxColor: Highcharts.getOptions().colors[0]
            },

            legend: {
                align: 'right',
                layout: 'vertical',
                margin: 0,
                verticalAlign: 'top',
                y: 25,
                symbolHeight: 280
            },

            tooltip: {
                formatter: function() {
                    return '<b>' + getPointCategoryName(this.point, 'x') + '</b> sold <br><b>' +
                        this.point.value + '</b> items on <br><b>' + getPointCategoryName(this.point, 'y') +
                        '</b>';
                }
            },

            series: [{
                name: 'Sales per employee',
                borderWidth: 1,
                data: [
                    [0, 0, 0],
                    [0, 1, 0],
                    [0, 2, 0],
                    [0, 3, 0],
                    [0, 4, 0],

                    [1, 0, 0],
                    [1, 1, 0],
                    [1, 2, 0],
                    [1, 3, 0],
                    [1, 4, 0],

                    [2, 0, 0],
                    [2, 1, 0],
                    [2, 2, 0],
                    [2, 3, 0],
                    [2, 4, 0],

                    [3, 0, 0],
                    [3, 1, 0],
                    [3, 2, 0],
                    [3, 3, 0],
                    [3, 4, 0],

                    [4, 0, 0],
                    [4, 1, 0],
                    [4, 2, 0],
                    [4, 3, 0],
                    [4, 4, 0],

                    [5, 0, 0],
                    [5, 1, 0],
                    [5, 2, 0],
                    [5, 3, 0],
                    [5, 4, 0],

                    [6, 0, 0],
                    [6, 1, 0],
                    [6, 2, 0],
                    [6, 3, 0],
                    [6, 4, 0],

                    [7, 0, 0],
                    [7, 1, 0],
                    [7, 2, 0],
                    [7, 3, 0],
                    [7, 4, 0],

                    [8, 0, 0],
                    [8, 1, 0],
                    [8, 2, 0],
                    [8, 3, 0],
                    [8, 4, 0],

                    [9, 0, 0],
                    [9, 1, 0],
                    [9, 2, 0],
                    [9, 3, 0],
                    [9, 4, 0],
                    [9, 4, 0],

                    [10, 0, 0],
                    [10, 1, 0],
                    [10, 2, 0],
                    [10, 3, 0],
                    [10, 4, 0],
                    [10, 5, 0],
                    [10, 6, 0],

                    [11, 0, 0],
                    [11, 1, 0],
                    [11, 2, 0],
                    [11, 3, 0],
                    [11, 4, 0],
                    [11, 5, 0],
                    [11, 6, 0],

                    [12, 1, 0],
                    [12, 2, 0],
                    [12, 3, 0],
                    [12, 4, 0],
                    [12, 5, 0],
                    [12, 6, 0],

                    [13, 1, 0],
                    [13, 2, 0],
                    [13, 3, 0],
                    [13, 4, 0],
                    [13, 5, 0],
                    [13, 6, 0],

                    [14, 1, 0],
                    [14, 2, 0],
                    [14, 3, 0],
                    [14, 4, 0],
                    [14, 5, 0],
                    [14, 6, 0],
                ],
                dataLabels: {
                    enabled: true,
                    color: '#000000'
                }
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        yAxis: {
                            labels: {
                                formatter: function() {
                                    return this.value.charAt(0);
                                }
                            }
                        }
                    }
                }]
            }

        });
    </script>
@endsection
