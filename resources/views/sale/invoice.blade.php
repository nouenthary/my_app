<!doctype html>
<html lang="en" style="-webkit-print-color-adjust: exact;">

<head>
    <link rel="shortcut icon" href="/uploads/icon.png"/>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">

    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">

    <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Battambang:wght@100&family=Roboto:wght@100;300;400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
    <style>
        body {
            font-family: 'Battambang';
            color: black;
            line-height: 1.42857143;
            margin: 0 5px;
            font-weight: bold;
            background-color: #fff;
        }

        .containers {
            margin: auto;
            width: 450px;
        }

        ul {
            padding: 0;
        }

        li {
            list-style-type: none;
        }

        .invoice-header {
            margin: 0;
            font-size: 12px;
        }

        span,
        p,
        table {
            font-size: 10px;
        }

        .box-bold {
            border: 1px solid black;
            border-radius: 5px;
            padding: 10px;
        }

        @media print {
            .printPageButton {
                display: none;
            }

            .containers{
                margin: 0;
                width: 100%;
            }

            .tr{
                -webkit-print-color-adjust: exact !important;
                background-color: #fff  !important;
                color: black !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
            }
        }

        tr, td , th{
            /*border: 1px solid black;*/
        }

        .tr{
            background-color: black ; color: #fff !important;
            -webkit-print-color-adjust: exact !important;
        }

    </style>


</head>

<body>
    <br />
    <div class="containers">
        <a href="{{ route('pos') }}" class="btn btn-lg btn-info btn-block printPageButton">
            <h4>ត្រឡប់ទៅបញ្ជាលក់</h4>
        </a>

        <div id="printer">

            <div class="text-center">
                <h3>{{ $store->name }}</h3>

                @php
                    //print_r($records);
                    $items = count($records);
                    $qty = 0;
                    $total = 0;
                @endphp
            </div>

            <ul>
                <li>
                    <p class="invoice-header">វិក័យបត្រ​ : <span> {{ $sales->id }}</span></p>
                </li>
                <li>
                    <p class="invoice-header">អតិថិជន : <span> {{ $sales->customer_name }}</span></p>
                </li>
                <li>
                    <p class="invoice-header">លេខទូរសព្ទ : <span> {{ $customer->phone }}</span></p>
                </li>
                <li>
                    <p class="invoice-header">អ្នកលក់ : <span> {{ $user->first_name }} {{ $user->last_name }}</span></p>
                </li>
                <li>
                    <p class="invoice-header">កាលបរិច្ឋេទ: <span> {{ $sales->date }}</span></p>
                </li>
            </ul>

            <table class="table">
                <thead>
                    <tr class="tr">
                        <th  scope="col" width="10px" style="border-bottom: 1px solid black; border-top: 1px solid black;">#</th>
                        <th scope="col" style="border-bottom: 1px solid black; border-top: 1px solid black;">ឈ្មោះទំនិញ</th>
                        <th class="text-center" scope="col" style="border-bottom: 1px solid black; border-top: 1px solid black;">ចំនួនទំនិញ</th>
                        <th  class="text-center" scope="col" style="border-bottom: 1px solid black; border-top: 1px solid black;">តម្លៃលក់</th>
                        <th class="text-center" scope="col" style="border-bottom: 1px solid black; border-top: 1px solid black;">សរុបទឹកប្រាក់</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($records as $i)
                        <tr>
                            <th scope="row" style="border-bottom: 1px solid black; "> {{ $loop->iteration }}</th>
                            <td style="border-bottom: 1px solid black;">{{ $i->product_name }}</td>
                            <td class="text-center" style="border-bottom: 1px solid black;">{{ number_format($i->quantity) }}</td>
                            <td class="text-right" style="border-bottom: 1px solid black;">{{ number_format($i->unit_price) }}​៛</td>
                            <td class="text-right" style="border-bottom: 1px solid black;">{{ number_format($i->subtotal) }}​៛ <?php $qty = $qty + $i->quantity;
                            $total = $total + $i->subtotal; ?></td>
                        </tr>
                    @endforeach
                    <tr class="tr">
                        <th scope="col">សរុប</th>
                        <th scope="col"></th>
                        <th scope="col" class="text-center">{{ $items }} ( {{ $qty }} )</th>
                        <th scope="col"></th>
                        <th scope="col" class="text-right">{{ number_format($total) }}៛</th>
                    </tr>
                </tbody>
            </table>

            <div style="" class="box-bold">
                <span>ទឹកប្រាក់ (៛):</span>
                <span class="pull-right">{{ number_format($payment->pos_paid) }}៛</span>
            </div>
            <p></p>
            <div style="" class="box-bold">
                <span>ប្រាក់អាប់ (៛):</span>
                <span class="pull-right">{{ number_format($payment->pos_balance) }}៛</span>
            </div>
            <p></p>
            <div class="text-center">
                <p>{{ $store->receipt_footer }}</p>
            </div>

        </div>

        <a href="#" class="btn btn-lg btn-primary btn-block printPageButton" onclick="window.print()">
            <h4>បោះពុម្ព</h4>
        </a>

        <a href="{{ route('list_sale') }}" class="btn btn-lg btn-success btn-block printPageButton">
            <h4>បង្ហាញបញ្ជីនៃការលក់</h4>
        </a>

    </div>
</body>
<script>
    window.print();
</script>

</html>
