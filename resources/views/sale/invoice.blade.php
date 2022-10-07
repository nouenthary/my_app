<!doctype html>
<html lang="en">

<head>
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

    <style>
        body {
            font-family: 'Battambang';
            color: black;
            line-height: 1.42857143;
            margin: 0 5px;
            font-weight: bold;
        }

        .containers {
            margin: auto;
            width: 500px;
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
            font-size: 12px;
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
                    <tr>
                        <th scope="col" width="10px">#</th>
                        <th scope="col">បរិយាយ</th>
                        <th scope="col">ចំនួន</th>
                        <th scope="col">តម្លៃ</th>
                        <th scope="col">សរុប</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($records as $i)
                        <tr>
                            <th scope="row"> {{ $loop->iteration }}</th>
                            <td>{{ $i->product_name }}</td>
                            <td class="text-center">{{ number_format($i->quantity) }}</td>
                            <td class="text-right">{{ number_format($i->unit_price) }}​៛</td>
                            <td class="text-right">{{ number_format($i->subtotal) }}​៛ <?php $qty = $qty + $i->quantity;
                            $total = $total + $i->subtotal; ?></td>
                        </tr>
                    @endforeach
                    <tr>
                        <th scope="col">សរុប</th>
                        <th scope="col"></th>
                        <th scope="col">{{ $items }} ( {{ $qty }} )</th>
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
