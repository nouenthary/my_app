<?php

namespace App\Http\Controllers;

use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SaleController extends Controller
{
    // import
    public function list_sale()
    {
        return view('sale.list_sale');
    }

    public function html($tag, $text = '', $classname = '')
    {
        return "<$tag $classname >" . $text . "</$tag>";
    }

    public function get_table($request)
    {

        $columns = [
            __("language.invoice"),
            'កាលបរិច្ឆេទ',
            'អតិថិជន',
            'ចំនួន',
            'បញ្ចុះតម្លៃ',
            'តំលៃ​សរុប',
            'ប្រាក់ត្រូវបង់',
            'ស្ថានភាព	',
            'អ្នកលក់	',
            'ប្រតិបត្តិការ',
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . $this->html('th', $col, 'class="active"');
        }

        $data = DB::table('tec_sales')
            ->select('tec_sales.id',
                DB::raw("DATE_FORMAT(tec_sales.date, ' %d/%m/%Y %h:%i %p') as date"),
                'tec_sales.customer_name',
                DB::raw('SUM(tec_sale_items.quantity) as quantity'),
                'tec_sales.total',
                'tec_sales.status',
                'tec_users.username',
                'tec_sales.total_discount',
                'tec_sales.paid'
            )
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_users', 'tec_sales.created_by', '=', 'tec_users.id');

        if ($request->store_id != '') {
            $data = $data->where('tec_sales.store_id', '=', $request->store_id);
        }

        $date = $request->date;

        $start = '';

        $end = '';

        if ($date != '') {
            $date_last = explode('-', $date);

            $start = date('Y-m-d H:i:s', strtotime($date_last[0]));

            $end = date('Y-m-d H:i:s', strtotime($date_last[1]));
        }

        if ($start != '') {
            $data = $data->where('tec_sales.date', '>=', $start);
        }

        if ($end != '') {
            $data = $data->where('tec_sales.date', '<=', $end);
        }

        $data = $data->groupBy('tec_sales.id',
            'tec_sales.date',
            'tec_sales.customer_name',
            'tec_sales.status',
            'tec_users.username',
            'tec_sales.total',
            'tec_sales.total_discount',
            'tec_sales.paid'
        )
            ->orderByDesc('tec_sales.id')
            ->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        //   return $data->lastPage()

        $qty = 0;
        $total = 0;
        $paid = 0;
        $discount = 0;

        foreach ($data as $col) {

            $qty = $qty + $col->quantity;
            $total = $total + $col->total;
            $paid = $paid + $col->paid;
            $discount = $discount + $col->total_discount;

            $status = 'បង់ប្រាក់រួច';

            if ($col->status != 'paid') {
                $status = 'មិនទាន់បង់ប្រាក់';
            }

            $value = $value . $this->html('tr',
                $this->html('td', '#' . $col->id, 'width="100px"') .
                $this->html('td', $col->date, '') .
                $this->html('td', $col->customer_name, '') .
                $this->html('td', number_format($col->quantity), 'class="text-right" ') .
                $this->html('td', number_format($col->total_discount) . '៛', 'class="text-right" ') .
                $this->html('td', number_format($col->total) . '៛', 'class="text-right"') .
                $this->html('td', number_format($col->paid) . '៛', 'class="text-right"') .
                $this->html('td', $status, 'class="text-centers" ') .
                $this->html('td', $col->username, 'class="text-centers" ') .
                $this->html('td', '
                        <div class="btn-group" >
                      <a href="/invoice/' . $col->id . '" target="_blank" type="button" class="btn btn-default btn-flat btn-xs"><i class="fa fa-list"></i></a>
                      <a type="button" class="btn btn-default btn-flat btn-xs"><i class="fa fa-money"></i></a>
                      <a type="button" class="btn btn-default btn-flat btn-xs"><i class="fa fa-briefcase"></i></a>
                      <a type="button" class="btn btn-primary btn-flat btn-xs"><i class="fa fa-pencil"></i></a>
                      <a type="button" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-trash"></i></a>
                    </div>
                    ', 'style="width: 130px"')
                , '');
        }

        $footer = $this->html('tr',
            $this->html('th', 'សរុប', 'class="text-uppercase" width="100px"') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', number_format($qty), 'class="text-right"') .
            $this->html('th', number_format($discount) . '៛', 'class="text-right"') .
            $this->html('th', number_format($total) . '៛', 'class="text-right"') .
            $this->html('th', number_format($paid) . '៛', 'class="text-right"') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', '', '')
            , 'class="active"');

        $table = $this->html('table', $this->html('tr', $cols, '') . $this->html('tr', $value, '') . $footer, 'class="table table-bordered table-stripeds" id="table"');

        return [
            'table' => $table,
            'page' => $data->currentPage(),
            'per_page' => $data->lastPage(),
            'total' => $data->total(),
        ];
    }

    public function get_list_sale(Request $request)
    {
        return $this->get_table($request);

        return $request;

        if ($request->ajax()) {
            $store_id = (int) $request->store_id;

            $date = $request->date;

            $sql = "
                SELECT
                    tec_sales.id,
                    tec_sales.customer_name,
                    DATE_FORMAT(tec_sales.date, '%a %d %b %Y %h:%i %p') as date,
                    tec_sales.`status`,
                    Sum(tec_sale_items.quantity) as quantity,
                    Sum(tec_sale_items.subtotal) as grand_total,
                    tec_users.username
                FROM
                    tec_sales
                    INNER JOIN tec_sale_items ON tec_sales.id = tec_sale_items.sale_id
                    INNER JOIN tec_users ON tec_sales.created_by = tec_users.id
            ";

//            if ($store_id > 0) {
            //                $sql = $sql . " Where tec_sales.store_id = '$store_id'";
            //            }
            //            if ($store_id == 0) {
            //                $sql = $sql . " Where tec_sales.store_id > '$store_id'";
            //            }
            //
            //            $start = '';
            //            $end = '';
            //
            //            if ($date != '') {
            //                $date_last = explode('-', $date);
            //
            //                $start = date('Y-m-d H:i:s', strtotime($date_last[0]));
            //
            //                $end = date('Y-m-d H:i:s', strtotime($date_last[1]));
            //            }
            //
            //            if ($start != '') {
            //                $sql = $sql . " and tec_sales.date >= '$start'";
            //            }
            //
            //            if ($end != '') {
            //                $sql = $sql . " and tec_sales.date <= '$end'";
            //            }

            $sql = $sql . "
                GROUP BY
                    tec_sales.id
                ORDER BY
                    tec_sales.id DESC
                    LIMIT 1000
            ";

            $data = DB::select(DB::raw($sql));

            try {
                return Datatables::of($data)->make(true);
            } catch (\Exception $e) {
            }
        }
    }

    // sale record
    public function sale_record()
    {
        return view('sale.sale_record');
    }

    //
    public function get_sale_record(Request $request)
    {
//        print_r($request->all());

        $columns = [
            __("language.branch"),
            __("language.invoice"),
            'កាលបរិច្ឆេទ',
            'អតិថិជន',
            __("language.product"),
            __("language.price"),
            'ចំនួន',
            'តំលៃ​សរុប',
            'កម្រៃជើងសារ',
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . $this->html('th', $col, 'class="active"');
        }

        $data = DB::table('tec_sales')
            ->select('tec_sales.id',
                DB::raw("DATE_FORMAT(tec_sales.date, ' %d/%m/%Y %h:%i %p') as date"),
                'tec_sales.customer_name',
                'tec_sale_items.quantity',
                'tec_sales.total',
                'tec_sales.status',
                'tec_sales.total_discount',
                'tec_sales.paid',
                'tec_stores.name',
                'tec_sale_items.product_name',
                'tec_sale_items.unit_price',
                'tec_sale_items.subtotal'
            )
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_stores', 'tec_sales.store_id', '=', 'tec_stores.id');

        if ($request->store_id != '') {
            $data = $data->where('tec_sales.store_id', '=', $request->store_id);
        }

        $date = $request->date;

        $start = '';

        $end = '';

        if ($date != '') {
            $date_last = explode('-', $date);

            $start = date('Y-m-d H:i:s', strtotime($date_last[0]));

            $end = date('Y-m-d H:i:s', strtotime($date_last[1]));
        }

        if ($start != '') {
            $data = $data->where('tec_sales.date', '>=', $start);
        }

        if ($end != '') {
            $data = $data->where('tec_sales.date', '<=', $end);
        }

        $data = $data
            ->orderByDesc('tec_sales.id')
            ->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        $qty = 0;
        $total = 0;
        $commission = 0;

        foreach ($data as $col) {

            $qty = $qty + $col->quantity;
            $total = $total + $col->subtotal;
            $commission = $commission + ($col->quantity * 500);

            $value = $value . $this->html('tr',
                $this->html('td', $col->name, '') .
                $this->html('td', '#' . $col->id, 'width="100px"') .
                $this->html('td', $col->date, '') .
                $this->html('td', $col->customer_name, '') .
                $this->html('td', $col->product_name, 'class="text-left" ') .
                $this->html('td', number_format($col->unit_price) . '៛', 'class="text-right"') .
                $this->html('td', number_format($col->quantity), 'class="text-right"') .
                $this->html('td', number_format($col->subtotal) . '៛', 'class="text-right"') .
                $this->html('td', number_format($col->quantity * 500) . '៛', 'class="text-right"')
                , '');
        }

        $footer =
        $this->html('tr',
            $this->html('th', 'សរុប', 'class="text-uppercase" width="100px"') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', number_format($qty), 'class="text-right"') .
            $this->html('th', number_format($total) . '៛', 'class="text-right"') .
            $this->html('th', number_format($commission) . '៛', 'class="text-right"')
            , 'class="active"');

        $table = $this->html('table', $this->html('tr', $cols, '') . $this->html('tr', $value, '') . $footer, 'class="table table-bordered table-stripeds" id="table"');

        return [
            'table' => $table,
            'page' => $data->currentPage(),
            'per_page' => $data->lastPage(),
            'total' => $data->total(),
        ];
    }

    //
    public function sale_report()
    {
        return view('sale.sale_report');
    }

    public function get_sale_report(Request $request)
    {
//        print_r($request->all());

        $columns = [
            __("language.branch"),
            'កាលបរិច្ឆេទ',
            __("language.product"),
            __("language.price"),
            'ចំនួនលក់',
            'លុយរៀល​',
            'លុយដុល្លា​',
            'កម្រៃជើងសារ',
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . $this->html('th', $col, 'class="active"');
        }

        $data = DB::table('tec_sales')
            ->select(
                'tec_stores.name',
                DB::raw("DATE_FORMAT(tec_sales.date, '%d/%m/%Y') as date"),
                'tec_sale_items.product_name',
                DB::raw("SUM(tec_sale_items.quantity) quantity"),
                'tec_sale_items.unit_price',
                DB::raw("SUM(tec_sale_items.subtotal) subtotal")
            )
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_stores', 'tec_sales.store_id', '=', 'tec_stores.id');

        if ($request->store_id != '') {
            $data = $data->where('tec_sales.store_id', '=', $request->store_id);
        }

        $start = $request->start_date;

        $end = $request->end_date;

        if ($start != '') {
            $starts = date('Y-m-d ', strtotime($start));
            $data = $data->where('tec_sales.date', '>=', $starts . ' 01:00:00');
        }

        if ($end != '') {
            $ends = date('Y-m-d ', strtotime($end));
            $data = $data->where('tec_sales.date', '<=', $ends . ' 23:59:00');
        }

        $data = $data
            ->groupBy(DB::raw("DATE_FORMAT(tec_sales.date, '%d/%m/%Y') "), 'tec_sale_items.product_name', 'tec_stores.name', 'tec_sale_items.unit_price')
            ->orderByDesc('tec_sales.date')
            ->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        $qty = 0;
        $total = 0;
        $commission = 0;
        $usd = 0;
        foreach ($data as $col) {

            $qty = $qty + $col->quantity;
            $total = $total + $col->subtotal;
            $commission = $commission + ($col->quantity * 500);

            $price = (float) $col->subtotal / 4000;

            $usd = $usd + $price;

            $value = $value . $this->html('tr',
                $this->html('td', $col->name, '') .
                $this->html('td', '' . $col->date, 'width="100px"') .
                $this->html('td', $col->product_name, 'class="text-left"') .
                $this->html('td', number_format($col->unit_price) . '៛', 'class="text-right"') .
                $this->html('td', number_format($col->quantity), 'class="text-right" ') .
                $this->html('td', number_format($col->subtotal) . '៛', 'class="text-right"') .
                $this->html('td', '$' . sprintf('%0.3f', $price), 'class="text-right"') .
                $this->html('td', number_format($col->quantity * 500) . '៛', 'class="text-right"')
                , '');
        }

        $footer =
        $this->html('tr',
            $this->html('th', 'សរុប', 'class="text-uppercase" width="100px"') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', number_format($qty), 'class="text-right"') .
            $this->html('th', number_format($total) . '៛', 'class="text-right"') .
            $this->html('th', '$' . sprintf('%0.3f', $usd) . '', 'class="text-right"') .
            $this->html('th', number_format($commission) . '៛', 'class="text-right"')
            , 'class="active"');

        $table = $this->html('table', $this->html('tr', $cols, '') . $this->html('tr', $value, '') . $footer, 'class="table table-bordered table-stripeds" id="table"');

        return [
            'table' => $table,
            'page' => $data->currentPage(),
            'per_page' => $data->lastPage(),
            'total' => $data->total(),
        ];
    }

    //
    public function sale_report_daily(Request $request)
    {
        $data = DB::table('tec_sales')
            ->select(
                'tec_stores.name',
                DB::raw("DATE_FORMAT(tec_sales.date, '%d/%m/%Y') as date"),
                'tec_sale_items.product_name',
                DB::raw("SUM(tec_sale_items.quantity) quantity"),
                'tec_sale_items.unit_price',
                DB::raw("SUM(tec_sale_items.subtotal) subtotal")
            )
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_stores', 'tec_sales.store_id', '=', 'tec_stores.id');

        if ($request->store_id != '') {
            $data = $data->where('tec_sales.store_id', '=', $request->store_id);
        }

        $start = $request->start_date;

        $end = $request->end_date;

        if ($start != '') {
            $starts = date('Y-m-d ', strtotime($start));
            $data = $data->where('tec_sales.date', '>=', $starts . ' 01:00:00');
        }

        if ($end != '') {
            $ends = date('Y-m-d ', strtotime($end));
            $data = $data->where('tec_sales.date', '<=', $ends . ' 23:59:00');
        }

        $store = DB::table('tec_stores')->where('id', $request->store_id)->first();

        $data = $data
            ->groupBy(DB::raw("DATE_FORMAT(tec_sales.date, '%d/%m/%Y') "), 'tec_sale_items.product_name', 'tec_stores.name', 'tec_sale_items.unit_price')
            ->orderByDesc('tec_sales.date')
            ->paginate($request->page_size, ['*'], 'page', $request->page);
        return view('sale.sale_report_daily', ['data' => $data, 'store' => $store]);
    }

    public function pos()
    {
        $data['store_id'] = DB::table('tec_users')
            ->where('id', Auth::user()->user_id)
            ->first()->store_id;

        $data['data'] = DB::table('tec_products as p')
            ->select('p.*')
            ->join('tec_product_store_qty as s', 'p.id', '=', 's.product_id')
            ->where('s.store_id', $data['store_id'])
            ->get();

        $data['customer'] = DB::table('tec_customers as c')
            ->select('c.*')
            ->get();

        return view('sale.pos', $data);
    }

    public function post_sale(Request $request)
    {
        $utils = new Utils();

        $data = [
            'date' => date('Y-m-d H:i:s'),
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'total' => $request->total,
            'product_discount' => 0,
            'grand_total' => $request->total,
            'total_items' => $request->total_item,
            'total_quantity' => $request->total_quantity,
            'paid' => $request->total,
            'created_by' => Auth::user()->user_id,
            'status' => 'paid',
            'rounding' => 0,
            'store_id' => $utils->get_store_id(),
            'date_out' => date('Y-m-d H:i:s'),
            'waiting_number' => $utils->get_waiting_number(),
            'note' => $request->note,
        ];

        $id = DB::table('tec_sales')->insertGetId($data);

        foreach ($request->items as $i) {
            $product = [
                'sale_id' => $id,
                'product_id' => $i['id'],
                'quantity' => $i['qty'],
                'unit_price' => $i['price'],
                'net_unit_price' => $i['price'],
                'cost' => $i['price'],
                'real_unit_price' => $i['price'],
                'subtotal' => $i['amount'],
                'product_name' => $i['name'],
                'product_code' => $i['code'],
                'discount' => 0,
                'item_discount' => 0,
                'tax' => 0,
                'item_tax' => 0,
            ];
            DB::table('tec_sale_items')->insert($product);
        }

        $payment = [
            'date' => date('Y-m-d H:i:s'),
            'sale_id' => $id,
            'customer_id' => $request->customer_id,
            'paid_by' => $request->paid_by,
            'amount' => $request->total,
            'created_by' => Auth::user()->user_id,
            'pos_paid' => $request->amount_paid,
            'pos_balance' => $request->amount_paid - $request->total,
            'store_id' => $utils->get_store_id(),
            'pos_paid_main' => $request->amount_paid,
        ];

        DB::table('tec_payments')->insert($payment);

        return ['sale_id' => $id];
    }

    public function invoice($id)
    {
        $data['sales'] = DB::table('tec_sales')
            ->where('id', $id)
            ->first();

        $data['store'] = DB::table('tec_stores')
            ->where('id', $data['sales']->store_id)
            ->first();

        $data['customer'] = DB::table('tec_customers')
            ->where('id', $data['sales']->customer_id)
            ->first();

        $data['user'] = DB::table('tec_users')
            ->where('id', $data['sales']->created_by)
            ->first();

        $data['records'] = DB::table('tec_sale_items')
            ->where('sale_id', $id)
            ->get();

        $data['payment'] = DB::table('tec_payments')
            ->where('sale_id', $id)
            ->first();

        return view('sale.invoice', $data);
    }

    // count_stock
    public function count_stock(Request $request)
    {
        $utils = new Utils();

        $data = DB::table('tec_product_store_qty')
            ->where('product_id', $request->id)
            ->where('store_id', $utils->get_store_id())
            ->first();
        return $data;
    }
    //stock_report
    public function stock_report()
    {
        return view('sale.stock_report');
    }
    //get_stock_report
    public function get_stock_report(Request $request)
    {
        $columns = [
            __("language.branch"),
            __("language.product"),
            'ស្តុកចូល​',
            'ចំនួនកាត់ស្តុក',
            'ចំនួនលក់​',
            'ស្តុកនៅសល់',
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . $this->html('th', $col, 'class="active"');
        }

        $sql = "
            SELECT
                    tec_stores.`name`,
                    tec_products.`name` 'product_name',
                    tec_products.id,
                    ( SELECT COALESCE ( SUM( qty ), 0 ) FROM `tec_stock_in` WHERE fk_pro_id = tec_products.id AND fk_store_id = tec_stores.id ) AS in_qty ,
                    (SELECT COALESCE ( SUM( qty ), 0 ) FROM `tec_stock_out` WHERE fk_pro_id = tec_products.id and fk_store_id = tec_stores.id) as out_qty,
                    (
                        SELECT
                            COALESCE(SUM(tec_sale_items.quantity) , 0)
                        FROM
                            `tec_sale_items`
                            INNER JOIN tec_sales ON tec_sale_items.sale_id = tec_sales.id
                            WHERE tec_sale_items.product_id = tec_products.id AND tec_sales.store_id = tec_stores.id
                    ) as qty_sold,
                    (select in_qty - out_qty - qty_sold ) as qty_balance
                FROM
                    `tec_products`
                    INNER JOIN tec_product_store_qty ON tec_products.id = tec_product_store_qty.product_id
                    INNER JOIN tec_stores ON tec_product_store_qty.store_id = tec_stores.id
                Where tec_stores.city != 'None'
        ";

        if ($request->store_id != "") {
            $sql = $sql . " and tec_stores.id = '$request->store_id' ";
        }

        $sql = $sql . "ORDER BY tec_stores.`name` ";

        $data = DB::select($sql);

        $value = '';

        $in = 0;
        $out = 0;
        $sold = 0;
        $balance = 0;
        foreach ($data as $col) {

            $in = $in + $col->in_qty;
            $out = $out + $col->out_qty;
            $sold = $sold + $col->qty_sold;
            $balance = $balance + $col->qty_balance;

            $value = $value . $this->html('tr',
                $this->html('td', '' . $col->name, 'width="120px"') .
                $this->html('td', $col->product_name, 'class="text-left"') .
                $this->html('td', number_format($col->in_qty) . 'pcs', 'class="text-right"') .
                $this->html('td', number_format($col->out_qty) . 'pcs', 'class="text-right" ') .
                $this->html('td', number_format($col->qty_sold) . 'pcs', 'class="text-right"') .
                $this->html('td', number_format($col->qty_balance) . 'pcs', 'class="text-right"')
                , '');
        }

        $footer =
        $this->html('tr',
            $this->html('th', 'សរុប', 'class="text-uppercase" width="100px"') .
            $this->html('th', '', '') .
            $this->html('th', number_format($in), 'class="text-right"') .
            $this->html('th', number_format($out) . 'pcs', 'class="text-right"') .
            $this->html('th', number_format($sold) . 'pcs', 'class="text-right"') .
            $this->html('th', number_format($balance) . 'pcs', 'class="text-right"')
            , 'class="active"');

        $table = $this->html('table', $this->html('tr', $cols, '') . $this->html('tr', $value, '') . $footer, 'class="table table-bordered table-stripeds" id="table"');

        return [
            'table' => $table,
            'page' => 1,
            'per_page' => 1000,
            'total' => 100,
        ];
    }

    // chart_report
    public function chart_report()
    {
        return view('sale.chart_report');
    }
    //get_chart_report
    public function get_chart_report(Request $request)
    {
        // print_r($request->all());
        $data = [];
        $dataSource = [];
        $year = $request->year;
        $month = $request->month;

        $data = DB::table('tec_sales')
            ->select('tec_stores.city', DB::raw("SUM(tec_sale_items.quantity) quantity"))
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_stores', 'tec_sales.store_id', '=', 'tec_stores.id')
            ->where('tec_stores.city', '<>', 'None');

        if ($request->type == 'day') {
            $time = strtotime($request->date);
            $newformat = date('Y-m-d', $time);
            $data = $data->whereDate('tec_sales.date', '>=', "$newformat");
            $data = $data->whereDate('tec_sales.date', '<=', "$newformat");
        } else if ($request->type == 'Year') {
            $start = date('Y-m-d', strtotime("$year-1-1"));
            $end = date('Y-m-d', strtotime("$year-12-31"));
            $data = $data->whereDate('tec_sales.date', '>=', $start);
            $data = $data->whereDate('tec_sales.date', '<=', $end);
        } else if ($request->type == 'Month') {
            $start = date('Y-m-d', strtotime("$year-$month-1"));
            $end = date('Y-m-d', strtotime("$year-$month-31"));
            $data = $data->whereDate('tec_sales.date', '>=', $start);
            $data = $data->whereDate('tec_sales.date', '<=', $end);
        }

        $data = $data->groupBy('tec_stores.city')
            ->orderByDesc('quantity')
            ->get();

        foreach ($data as $row) {
            array_push($dataSource, [$row->city, (int) $row->quantity]);
        }

        return [
            'data' => $dataSource,
        ];
    }
}
