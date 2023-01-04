<?php

namespace App\Http\Controllers;

use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'អ្នកលក់',
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
                'tec_sales.paid',
                'tec_sales.note'
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
            'tec_sales.paid',
            'tec_sales.note'
        )
            ->orderByDesc('tec_sales.id')
            ->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

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

            $inv = $col->id;
            if ($col->note != '') {
                $inv = $col->note;
            }

            $value = $value . $this->html('tr',
                    $this->html('td', '#' . $inv, 'width="100px"') .
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
                      <a type="button" class="btn btn-danger btn-flat btn-xs btn-remove"><i class="fa fa-trash"></i></a>
                    </div>
                    ', 'style="width: 130px"')
                    , "id='$col->id'");
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

    //
    public function get_list_sale(Request $request)
    {
        return $this->get_table($request);
    }

    // remove
    public function return_sale(Request $request)
    {
        $sale = DB::table('tec_sales')
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->where('tec_sales.id', $request->sale_id)
            ->get();

        if (Auth::user()->user_id != $sale[0]->created_by) {
            return ['error' => 'You can not delete.'];
        }

        foreach ($sale as $data) {
            $item = [
                'sale_date' => $data->date,
                'customer_name' => $data->customer_name,
                'total_sale' => $data->total,
                'tax' => 0,
                'discount' => 0,
                'grand_total' => $data->total,
                'paid' => $data->total,
                'status' => $data->status,
                'product_id' => $data->product_id,
                'product_name' => $data->product_name,
                'user_updated' => $data->created_by,
                'date_delete' => date('Y-m-d H:i:s'),
                'customer_id' => $data->customer_id,
                'sale_id' => $data->sale_id,
                'store_id' => $data->store_id,
                'quantity' => $data->quantity,
                'user_delete' => Auth::user()->name
            ];

            DB::table('tec_sale_history')->insert($item);

            $current_qty = DB::table('tec_product_store_qty')->where('product_id', $data->product_id)->where('store_id', $data->store_id)->first();

            $total_qty = $current_qty->quantity + $data->quantity;

            DB::update('update tec_product_store_qty set quantity = ? where product_id = ?  and store_id = ? ', [$total_qty, $data->product_id, $data->store_id]);

            DB::table('tec_sales')->where('id', $request->sale_id)->delete();
            DB::table('tec_sale_items')->where('sale_id', $request->sale_id)->delete();
            DB::table('tec_payments')->where('sale_id', $request->sale_id)->delete();
        }

        return ['data' => 'successfully.'];
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
            lang('seller'),
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
            ->selectRaw("tec_sales.id,
                DATE_FORMAT(tec_sales.date, ' %d/%m/%Y %h:%i %p') as date,
                tec_sales.customer_name,
                tec_sale_items.quantity,
                tec_sales.total,
                tec_sales.status,
                tec_sales.total_discount,
                tec_sales.paid,
                tec_stores.name,
                tec_sale_items.product_id,
                tec_sale_items.product_name,
                tec_sale_items.unit_price,
                tec_sale_items.subtotal,
                tec_products.branch_commission,
                tec_products.staff_commission,
                tec_products.other_commission,
                tec_users.salt,
                tec_sales.note,
                tec_users.username
                "
            )
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_products', 'tec_sale_items.product_id', '=', 'tec_products.id')
            ->join('tec_users', 'tec_sales.created_by', '=', 'tec_users.id')
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

        if ($request->product_id != '') {
            $data = $data->where('tec_sale_items.product_id', '=', $request->product_id);
        }

        if ($request->seller_id != '') {
            $data = $data->where('tec_sales.created_by', '=', $request->seller_id);
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

            $commission_sale = 0;
            if ($col->salt == 'branch') {
                $commission_sale = $col->branch_commission * $col->quantity;
                $commission = $commission + $col->branch_commission * $col->quantity;
            } else if ($col->salt == 'other') {
                $commission_sale = $col->other_commission * $col->quantity;
                $commission = $commission + $col->other_commission * $col->quantity;
            } else if ($col->salt == "staff") {
                $commission = $commission + $col->staff_commission * $col->quantity;
                $commission_sale = $col->staff_commission * $col->quantity;
            }

            $inv = $col->id;
            if ($col->note != '') {
                $inv = $col->note;
            }

            $value = $value . $this->html('tr',
                    $this->html('td', $col->name, '') .
                    $this->html('td', '#' . $inv, 'width="100px"') .
                    $this->html('td', $col->date, '') .
                    $this->html('td', $col->username, 'width="100px"') .
                    $this->html('td', $col->customer_name, '') .
                    $this->html('td', $col->product_name, 'class="text-left" ') .
                    $this->html('td', number_format($col->unit_price) . '៛', 'class="text-right"') .
                    $this->html('td', number_format($col->quantity), 'class="text-right"') .
                    $this->html('td', number_format($col->subtotal) . '៛', 'class="text-right"') .
                    $this->html('td', number_format($commission_sale) . '៛', 'class="text-right"')
                    , '');
        }

        $footer =
            $this->html('tr',
                $this->html('th', 'សរុប', 'class="text-uppercase" width="100px"') .
                $this->html('th', '', '') .
                $this->html('th', '', 'width="100px"') .
                $this->html('th', '', 'width="100px"') .
                $this->html('th', '', '') .
                $this->html('th', '', '') .
                $this->html('th', '', 'width="100px"') .
                $this->html('th', number_format($qty), 'class="text-right" width="100px"') .
                $this->html('th', number_format($total) . '៛', 'class="text-right" width="100px"') .
                $this->html('th', number_format($commission) . '៛', 'class="text-right" width="100px"')

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
                DB::raw("SUM(tec_sale_items.subtotal) subtotal"),
                "tec_products.branch_commission",
                "tec_products.staff_commission",
                "tec_products.other_commission",
                "tec_users.salt",
                "c.name as category_name"
            )
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_products', 'tec_sale_items.product_id', '=', 'tec_products.id')
            ->join('tec_users', 'tec_sales.created_by', '=', 'tec_users.id')
            ->join('tec_stores', 'tec_sales.store_id', '=', 'tec_stores.id')
            ->join('tec_categories as c', 'tec_products.category_id', '=', 'c.id');


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

        if ($request->product_id != '') {
            $data = $data->where('tec_sale_items.product_id', '=', $request->product_id);
        }

        if ($request->seller_id != '') {
            $data = $data->where('tec_sales.created_by', '=', $request->seller_id);
        }

        if ($request->category_id != '') {
            $data = $data->where('c.id', '=', $request->category_id);
        }

        $data = $data
            ->groupBy(
                DB::raw("DATE_FORMAT(tec_sales.date, '%d/%m/%Y') "),
                'tec_sale_items.product_name',
                'tec_stores.name',
                'tec_sale_items.unit_price',
                "tec_products.branch_commission",
                "tec_products.staff_commission",
                "tec_products.other_commission",
                "tec_users.salt",
                "c.name"
            )
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

            $price = (float)$col->subtotal / 4000;

            $usd = $usd + $price;

            $commission_sale = 0;
            if ($col->salt == 'branch') {
                $commission_sale = $col->branch_commission * $col->quantity;
                $commission = $commission + $col->branch_commission * $col->quantity;
            } else if ($col->salt == 'other') {
                $commission_sale = $col->other_commission * $col->quantity;
                $commission = $commission + $col->other_commission * $col->quantity;
            } else if ($col->salt == "staff") {
                $commission = $commission + $col->staff_commission * $col->quantity;
                $commission_sale = $col->staff_commission * $col->quantity;
            }

            $value = $value . $this->html('tr',
                    $this->html('td', $col->name, '') .
                    $this->html('td', '' . $col->date, 'width="100px"') .
                    $this->html('td', $col->product_name, 'class="text-left"') .
                    $this->html('td', number_format($col->unit_price) . '៛', 'class="text-right"') .
                    $this->html('td', number_format($col->quantity), 'class="text-right" ') .
                    $this->html('td', number_format($col->subtotal) . '៛', 'class="text-right"') .
                    $this->html('td', '$' . sprintf('%0.3f', $price), 'class="text-right"') .
                    $this->html('td', number_format($commission_sale) . '៛', 'class="text-right"')
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
        if ($request->store_id == '') {
            return back();
        }

        $data = DB::table('tec_sales')
            ->select(
                'tec_stores.name',
                DB::raw("DATE_FORMAT(tec_sales.date, '%d/%m/%Y') as date"),
                'tec_sale_items.product_name',
                DB::raw("SUM(tec_sale_items.quantity) quantity"),
                'tec_sale_items.unit_price',
                DB::raw("SUM(tec_sale_items.subtotal) subtotal"),
                "tec_products.branch_commission",
                "tec_products.staff_commission",
                "tec_products.other_commission",
                "tec_users.salt"
            )
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_products', 'tec_sale_items.product_id', '=', 'tec_products.id')
            ->join('tec_users', 'tec_sales.created_by', '=', 'tec_users.id')
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

        if ($request->product_id != '') {
            $data = $data->where('tec_sale_items.product_id', '=', $request->product_id);
        }

        if ($request->seller_id != '') {
            $data = $data->where('tec_sales.created_by', '=', $request->seller_id);
        }

        $store = DB::table('tec_stores')->where('id', $request->store_id)->first();

        $data = $data
            ->groupBy(
                DB::raw("DATE_FORMAT(tec_sales.date, '%d/%m/%Y') "),
                'tec_sale_items.product_name',
                'tec_stores.name',
                'tec_sale_items.unit_price',
                "tec_products.branch_commission",
                "tec_products.staff_commission",
                "tec_products.other_commission",
                "tec_users.salt"
            )
            //->orderByDesc('tec_sales.date' ,'asc')
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
            ->where('p.is_active', '1')
            ->where('p.category_id', '1')
            ->get();

        $data['customer'] = DB::table('tec_customers as c')
            ->select('c.*')
            ->get();

        $register = DB::table('tec_registers')
            ->where('user_id', Auth::user()->user_id)
            ->orderByDesc('id')
            ->first();

        if (isset($_GET['cash_in_hand']) != '') {
            $open_cash = [
                'date' => get_current_date(),
                'user_id' => Auth::user()->user_id,
                'status' => 'open',
                'store_id' => Utils::store_id(),
                'cash_in_hand' => (float)$_GET['cash_in_hand']
            ];

            if ($register == '' || $register == null) {
                DB::table('tec_registers')->insert($open_cash);
            }

            if ($register != null && $register->status == 'close') {
                DB::table('tec_registers')->insert($open_cash);
            }
        }

        if (isset($_GET['status']) != '') {

            $registers = DB::table('tec_registers')
                ->where('user_id', Auth::user()->user_id)
                ->orderByDesc('id')
                ->first();

            if ($registers != null || $registers != '') {
                $user_id = Auth::user()->user_id;
                $store_id = $registers->store_id;
                $date = $registers->date;
                $total = DB::select("
                SELECT
	                COALESCE(SUM(grand_total),0) total
                FROM
                    `tec_sales`
                WHERE
                    created_by = '$user_id'
                    AND store_id = '$store_id'
                    AND date >= '$date'
               ");

                $close = [
                    'closed_at' => get_current_date(),
                    'closed_by' => Auth::user()->user_id,
                    'status' => 'close',
                    'total_cash' => $total[0]->total + $registers->cash_in_hand,
                    'note' => $_GET['note'],
                    'total_cash_submitted' => $total[0]->total
                ];

                DB::table('tec_registers')
                    ->where('id', $registers->id)
                    ->update($close);
            }

        }

        $register = DB::table('tec_registers')
            ->where('user_id', Auth::user()->user_id)
            ->orderByDesc('id')
            ->first();

        $data['category'] = DB::table(TableCategory)
            ->select('id', 'name', 'image')
            ->orderByDesc('name')
            ->get();

        $data['list_category'] = [];

        foreach ($data['category'] as $row) {
            $product = DB::table(TableProduct)
                ->select('id', 'name', 'image')
                ->where('category_id', $row->id)
                ->get();

            array_push($data['list_category'], [
                'id' => $row->id,
                'name' => $row->name,
                'image' => $row->image,
                'product' => $product
            ]);
        }

        //return $data['list_category'];

        if ($register == '' || $register == null) {
            return view('sale.register');
        }

        if ($register->status == 'close') {
            return view('sale.register');
        }

        if ($register->status == 'open') {
            return view('sale.pos', $data);
        }

        return view('sale.pos', $data);
    }

    public function post_sale(Request $request)
    {
        $utils = new Utils();

        $old_sale = DB::table('tec_sales')->where('store_id', $utils->get_store_id())->orderByDesc('id')->first();

        $invoice = 1;

        if ($old_sale != null && $old_sale->note != '') {
            $ref = explode("-", $old_sale->note);
            $invoice = (int)$ref[2] + 1;
        }

        $inv = str_pad($invoice, 7, "0", STR_PAD_LEFT);

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
            'note' => 'POS-' . date('Ymd-') . $inv,
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

            $current_qty = DB::table('tec_product_store_qty')->where('product_id', $i['id'])->where('store_id', $utils->get_store_id())->first();

            $total_qty = $current_qty->quantity - $i['qty'];

            DB::update('update tec_product_store_qty set quantity = ? where product_id = ?  and store_id = ? ', [$total_qty, $i['id'], $utils->get_store_id()]);

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
        $dataSource = [];
        $year = $request->year;
        $month = $request->month;

        $data = DB::table('tec_sales')
            ->select('tec_stores.city', DB::raw("SUM(tec_sale_items.quantity) quantity"))
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_stores', 'tec_sales.store_id', '=', 'tec_stores.id')
            ->where('tec_stores.city', '<>', 'None');

        $products = DB::table('tec_sales')
            ->select('tec_sale_items.product_name', DB::raw("SUM(tec_sale_items.quantity) quantity"), DB::raw("SUM(tec_sale_items.subtotal) subtotal"))
            ->join('tec_sale_items', 'tec_sales.id', '=', 'tec_sale_items.sale_id')
            ->join('tec_stores', 'tec_sales.store_id', '=', 'tec_stores.id')
            ->where('tec_stores.city', '<>', 'None');

        if ($request->type == 'day') {
            $time = strtotime($request->date);
            $newformat = date('Y-m-d', $time);
            $data = $data->whereDate('tec_sales.date', '>=', "$newformat");
            $data = $data->whereDate('tec_sales.date', '<=', "$newformat");

            $products = $products->whereDate('tec_sales.date', '>=', "$newformat");
            $products = $products->whereDate('tec_sales.date', '<=', "$newformat");

        } else if ($request->type == 'Year') {
            $start = date('Y-m-d', strtotime("$year-1-1"));
            $end = date('Y-m-d', strtotime("$year-12-31"));
            $data = $data->whereDate('tec_sales.date', '>=', $start);
            $data = $data->whereDate('tec_sales.date', '<=', $end);

            $products = $products->whereDate('tec_sales.date', '>=', $start);
            $products = $products->whereDate('tec_sales.date', '<=', $end);
        } else if ($request->type == 'Month') {
            $start = date('Y-m-d', strtotime("$year-$month-1"));
            $end = date('Y-m-d', strtotime("$year-$month-31"));
            $data = $data->whereDate('tec_sales.date', '>=', $start);
            $data = $data->whereDate('tec_sales.date', '<=', $end);

            $products = $products->whereDate('tec_sales.date', '>=', $start);
            $products = $products->whereDate('tec_sales.date', '<=', $end);
        }

        $data = $data->groupBy('tec_stores.city')
            ->orderByDesc('quantity')
            ->get();

        $products = $products->groupBy('tec_sale_items.product_name')
            ->orderBy('quantity')
            ->get();

        foreach ($data as $row) {
            array_push($dataSource, [$row->city, (int)$row->quantity]);
        }

        return [
            'data' => $dataSource,
            'total' => $products
        ];
    }

    public function sale_qr_code()
    {
        return view('sale.sale_qr_code');
    }

    //
    public function search_product()
    {
        $category_id = request()->get('category_id');

        if ($category_id != null) {
            $data = DB::table(TableProduct)
                ->select('id','name','price', 'code','image')
                ->where('category_id', $category_id)
                ->get();

            if($data != null){
                return $data;
            }
        }

        return [
            'error' => 'not found.',
        ];
    }

}
