<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale report daily</title>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <style type="text/css">
        body {
            margin: 10px;
        }

        td {
            vertical-align: middle !important;
        }

        .table > thead > tr > th {

            border-bottom: 1px solid #ccc !important;
        }

        table {
            white-space: nowrap;
            width: 100%;
        }

        .watermark {
            position: fixed;
            opacity: 0.1;
            /* Safari */

            /* Internet Explorer */
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
            position: absolute;
            font-size: 60px;
            margin: auto;
            white-space: nowrap;
        }
    </style>
</head>
<body >

<div id="image" style="padding: 10px">

    <div style="display: flex">
        <img width="60px" src="/uploads/photo_2022-01-04_13-55-21.jpg" style="border-radius: 5px"/>
        <h3 class="text-info" style="padding-left: 10px">របាយការណ៏លក់ប្រចាំថ្ងៃ </h3>
    </div>
    <div style="padding-left: 70px">
        <p><i class="fa fa-home"></i> {{$store->city}} - {{$store->name}}</p>
        <p><i class="fa fa-phone"></i> 096 216 9065</p>
        <p><i class="fa  fa-chrome"></i> http://www.kstock4500.blogspot.com/</p>

{{--        {{print_r($store)}}--}}
    </div>
    <br/>

    {{--    {{print_r($data[0])}}--}}

    <table class="table table-bordered table-stripeds">
        <thead >
        <tr class="active">
            <th scope="col">ល.រ</th>
            <th scope="col">ថ្ងៃ​ ខែ ឆ្នាំ</th>
            <th scope="col">ឈ្មោះទំនិញ</th>
            <th scope="col" class="text-right" width="120px">តម្លៃលក់</th>
            <th scope="col" class="text-right" width="120px">ចំនួនលក់</th>
            <th scope="col" class="text-right" width="120px">លុយរៀល​</th>
            <th scope="col" class="text-right" width="120px">លុយដុល្លា</th>
            <th scope="col" class="text-right" width="120px">កម្រៃជើងសារ</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $qty = 0;
        $total = 0;
        $commission = 0;
        ?>

        @foreach($data as $row)
            <tr>
                <th scope="row" width="60px" class="text-center">{{ $loop->iteration }}</th>
                <td width="120px">{{$row->date}}</td>
                <td>{{$row->product_name}}</td>
                <td class="text-right">{{number_format($row->unit_price) }}៛</td>
                <td class="text-right">{{number_format($row->quantity)}} <?php $qty = $qty + $row->quantity;  ?></td>
                <td class="text-right">{{ number_format($row->subtotal) . '៛'}} <?php $total = $total + $row->subtotal;  ?> </td>
                <td class="text-right">${{ sprintf('%0.3f', $row->subtotal / 4000)}}</td>
                <td class="text-right">{{ number_format($row->quantity * 500) . '៛'}}  <?php $commission = $commission + $row->quantity * 500;  ?> </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr class="active">
            <th scope="col" ​ colspan="4">សរុប</th>
            <th scope="col" class="text-right">{{number_format($qty)}}</th>
            <th scope="col" class="text-right">{{number_format($total). '៛'}}</th>
            <th scope="col" class="text-right">{{'$' . sprintf('%0.3f', $total / 4000)}}</th>
            <th scope="col" class="text-right">{{number_format( $commission ) . '៛'}}</th>
        </tr>
        </tfoot>
    </table>

    <p>អ្នកគ្រប់គ្រង់ហាង  : </p>
    <p>ហត្ថលេខា : </p>



{{--    <p>ហត្ថលេខា : ----------</p>--}}
</div>

<div style="text-align: center;">

    <a id="btnConvert" style="cursor: pointer">
        <img src="/uploads/Screenshot.png" height="40px">
        Screenshot</a>
</div>
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script>
    function exportCanvasAsPNG() {
        html2canvas(document.querySelector("#image")).then(canvas => {
            a = document.createElement('a');
            document.body.appendChild(a);
            a.download = "sale_report_daily.png";
            a.href = canvas.toDataURL();
            a.click();
        });
    }

    document.querySelector('#btnConvert').addEventListener('click', function () {
        exportCanvasAsPNG();
    });
    // $(function () {
    //     $("#image").each(function () {
    //         $(this).after('<div class="watermark">TIGER INSENCE</div>');
    //     });
    // });
</script>
</body>
</html>
