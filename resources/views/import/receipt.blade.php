<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        invoice
    </title>
    <link rel="shortcut icon" href="/shop/themes/default/assets/images/icon.png">

    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">

    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">

    <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">
    <style type="text/css">
        #wrapper {
            max-width: 480px;
            margin: 0 auto;
            padding: 5px;
            font-size: 12px;
        }

        table {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <!-- Array
(
    [0] => Array
        (
            [id] => 4
            [fk_pro_id] => 5
            [fk_store_id] => 4
            [qty] => 1
            [image] => no_image.png
            [user_update] => 25
            [date_update] => 2022-10-05 12:59:35
            [time_update] => 2022-10-05 12:59:35
            [remark] =>
            [ware_id] => 1
            [no] => 0000112
            [name] => K STOCK 4
            [code] => POS
            [logo] =>
            [email] => admin@gmail.com
            [phone] => 0
            [address1] => ផ្លូវ217 សង្គាត់ ដង្កោ ខណ្ឌដង្កោ​ ភ្នំពេញ
            [address2] =>
            [city] => សាខា គួរស្រូវ
            [state] =>
            [postal_code] => 500
            [country] =>
            [currency_code] => MYR
            [receipt_header] =>
            [receipt_footer] => រាល់វិក័យបត្រចេញជូនអតិថិជនហេីយមិនអាចសុំដូរទំនិញ រឺ  ដូរលុយបានវិញទេ
​​​   សូមអរគុណ
            [pro_name] => ទំនិញ 4500​៛
        )

)
 -->
 {{-- {{ print_r($data); }} --}}

    <div id="wrapper">
        <h2 class="text-uppercase text-center"> Invoice KS4</h2>
        <span>No : IN{{ $data[0]->no }}</span><br>
        <span>Date : {{ $data[0]->date_update }} </span><br>
        <span>Branch : {{ $store[0]->name }} - {{ $store[0]->city }} </span> <br> ​

        <div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="row" style="border: 1px solid black !important"> ប្រភេទ</th>
                        <th style="border: 1px solid black !important">ចំនួន
                        </th>
                        <th style="border: 1px solid black !important">បរិយាយ
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <?php  $qty = 0 ;?>

                    @foreach ($data as $row)
                        <tr>
                            <?php $qty = $qty + $row->qty; ?>
                            <td style="border: 1px solid black !important">{{ $row->name }}</td>
                            <td style="border: 1px solid black !important">{{  $row->qty  }}</td>
                            <td style="border: 1px solid black !important"></td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="border: 1px solid black !important">សរុប </td>
                        <td colspan="2" style="border: 1px solid black !important">{{ $qty }} PCS</td>
                    </tr>

                </tbody>

            </table>

            <table class="table">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top: unset">
                            <div style="border-bottom: 1px solid black !important;">អ្នកប្រគល់ : </div>
                        </td>
                        <td width="50%" style="border-top: unset">
                            <div style="border-bottom: 1px solid black !important;">អ្នកទទួល : </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p class="text-center">សូមពិនិត្យទំនិញដែលទទួលបាន សូមអរគុណ !</p>
        </div>
    </div>


    <script type="text/javascript">
        window.print();
    </script>

</body>

</html>
