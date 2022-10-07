<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Products';
        return view('products.index', $data);
    }

    public function get_products(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('tec_categories as uu')
                ->selectRaw("u.* , uu.id , uu.name as cate_name, (SELECT COALESCE(SUM(qty),0) FROM `tec_stock_in` WHERE fk_pro_id = u.id ) as total")
                ->join('tec_products as u', 'u.category_id', '=', 'uu.id')
                ->orderBy('u.name')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $this->get_button_action($row->id);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function get_button_action($row)
    {
        return '
            <div class="btn-group">
            <a class="btn btn-default btn-flat btn-xs btn-pencil" id="' . $row . '"><i class="fa fa-pencil"></i></a>
            <a class="btn btn-success btn-flat btn-xs btn-eye"><i class="fa fa-eye"></i></a>
        </div>
        ';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // import
    public function import()
    {
        return view('import.import');
    }

    //
    public function get_imports(Request $request)
    {
        if ($request->ajax()) {

            $store_id = (int) $request->store_id;

            $product_id = (int) $request->product_id;

            $data = DB::table('tec_categories as uu');

            if ($store_id > 0) {
                $data = $data->where('uuu.store_id', $store_id);
            }

            if ($product_id > 0) {
                $data = $data->where('uuu.product_id', $product_id);
            }

            $data = $data->selectRaw("u.* , uu.id , uu.name as cate_name, (SELECT COALESCE(SUM(qty),0) FROM `tec_stock_in` WHERE fk_pro_id = u.id and fk_store_id = uuu.store_id ) as total, uuu.store_id, uuuu.name as store_name, u.id as product_id")
                ->join('tec_products as u', 'u.category_id', '=', 'uu.id')
                ->join('tec_product_store_qty as uuu', 'u.id', '=', 'uuu.product_id')
                ->join('tec_stores as uuuu', 'uuu.store_id', '=', 'uuuu.id')
                ->orderBy('store_name')
                ->get();

            try {
                return Datatables::of($data)->make(true);
            } catch (\Exception $e) {
            }
        }
    }

    // create_import
    public function create_import(Request $request)
    {
        $invoice = str_pad(1, 7, '0', STR_PAD_LEFT);
        $no = DB::table('tec_stock_in')
            ->where('fk_store_id', '=', $request->store_id_no)
            ->orderByDesc('id')
            ->first();
        if ($no != null) {
            $last_no = (int) $no->no + 1;
            $invoice = str_pad($last_no, 7, '0', STR_PAD_LEFT);
        }

        $name = 'no_image.png';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = date('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);
        }

        $data = array(
            'fk_pro_id' => $request->product_id_no,
            'fk_store_id' => $request->store_id_no,
            'qty' => $request->qty,
            'image' => $name,
            'user_update' => auth()->user()->user_id,
            'date_update' => date('Y-m-d H:i:s'),
            'time_update' => date('Y-m-d H:i:s'),
            'remark' => $request->remark,
            'ware_id' => $request->warehouse_id,
            'no' => $invoice,
        );

        if ($request->qty > 0) {
            DB::table('tec_stock_in')->insert($data);
            DB::update("
                UPDATE `tec_warehouse`
                SET `in` = `in` - '$request->qty'
                WHERE
                `warehouse_id` = '$request->warehouse_id'
            ");

            DB::update("
                    UPDATE `tec_product_store_qty`
                    SET `quantity` = `quantity` + '$request->qty'
                    WHERE
                    `product_id` = '$request->product_id_no' AND `store_id` = '$request->store_id_no'
                ");
        }

        return ['message' => 'successfully.'];
    }

    public function list_import()
    {
        return view('import.list_import');
    }

    public function get_list_imports(Request $request)
    {
        $columns = [
            __("language.invoice"),
            __("language.date"),
            __("language.product"),
            __("language.qty") . 'កាត់ / ដក',
            __("language.username"),
            __("language.remark"),
            __("language.branch"),
            __("language.warehouse"),
            __("language.action"),
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . $this->html('th', $col, 'class="active"');
        }

        $data = DB::table('tec_stock_in')
            ->select(
                'tec_stock_in.id',
                DB::raw("DATE_FORMAT(tec_stock_in.date_update, '%d/%m/%Y %h:%i %p') as date"),
                'tec_products.name as product_name',
                'tec_stock_in.qty',
                'tec_stock_in.no',
                'tec_stores.name as store_name',
                'tec_users.username',
                'tec_stock_in.remark',
                'tec_warehouse.ware_name'
            )
            ->join('tec_products', 'tec_stock_in.fk_pro_id', '=', 'tec_products.id')
            ->join('tec_stores', 'tec_stock_in.fk_store_id', '=', 'tec_stores.id')
            ->join('tec_users', 'tec_stock_in.user_update', '=', 'tec_users.id')
            ->join('tec_warehouse', 'tec_stock_in.ware_id', '=', 'tec_warehouse.warehouse_id');

        if ($request->store_id != '') {
            $data = $data->where('tec_stock_in.fk_store_id', '=', $request->store_id);
        }

        if ($request->product_id != '') {
            $data = $data->where('tec_stock_in.fk_pro_id', '=', $request->product_id);
        }

        if ($request->warehouse_id != '') {
            $data = $data->where('tec_stock_in.ware_id', '=', $request->warehouse_id);
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
            $data = $data->where('tec_stock_in.date_update', '>=', $start);
        }

        if ($end != '') {
            $data = $data->where('tec_stock_in.date_update', '<=', $end);
        }

        $data = $data
            ->orderByDesc('tec_stock_in.id')
            ->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        $qty = 0;

        $action = '';

        foreach ($data as $col) {

            $qty = $qty + $col->qty;

            $token = csrf_token();

            $value = $value . $this->html('tr',
                $this->html('td', '#' . $col->no, 'width="80px"') .
                $this->html('td', '' . $col->date, 'width="160px"') .
                $this->html('td', $col->product_name, 'class="text-left"') .
                $this->html('td', number_format($col->qty) . ' pcs', 'class="text-right"') .
                $this->html('td', $col->username, 'class="text-rights" ') .
                $this->html('td', $col->remark, 'class="text-rights" ') .
                $this->html('td', $col->store_name, 'class="text-rights" ') .
                $this->html('td', $col->ware_name, 'class="text-rights" ') .
                $this->html('td', "
                    <a class='btn btn-xs' href='/receipt?id=$col->id&token=$token' target='_blank'><i class='fa fa-file-text-o'></i></a>
                ", 'class="text-rights" ')
                , 'class="text-center"');
        }

        $footer =
        $this->html('tr',
            $this->html('th', 'សរុប', 'class="text-uppercase" width="100px"') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', number_format($qty) . ' pcs', 'class="text-right text-primary"') .
            $this->html('th', '', 'class="text-right"') .
            $this->html('th', '', 'class="text-right"') .
            $this->html('th', '', 'class="text-right"') .
            $this->html('th', '', 'class="text-right"') .
            $this->html('th', '', 'class="text-right"')
            , 'class="active"');

        $table = $this->html('table', $this->html('tr', $cols, '') . $this->html('tr', $value, '') . $footer, 'class="table table-bordered table-stripeds" id="table"');

        return [
            'table' => $table,
            'page' => $data->currentPage(),
            'per_page' => $data->lastPage(),
            'total' => $data->total(),
        ];
    }

    public function generatePDF()
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
        ];

        view()->share('welcome', ['']);

        //  if($request->has('download')){
        $pdf = PDF::loadView('welcome');
        return $pdf->download('welcome.pdf');
        // }
    }

    public function html($tag, $text = '', $classname = '')
    {
        return "<$tag $classname >" . $text . "</$tag>";
    }

    public function list_export()
    {
        return view('export.list_export');
    }

    public function get_list_exports(Request $request)
    {
        $columns = [
            __("language.invoice"),
            __("language.date"),
            __("language.product"),
            __("language.qty") . 'កាត់ / ដក',
            __("language.status"),
            __("language.username"),
            __("language.remark"),
            __("language.branch"),
            __("language.warehouse"),
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . $this->html('th', $col, 'class="active"');
        }

        $data = DB::table('tec_stock_out')
            ->select(
                'tec_stock_out.pk_stock_out_id as id',
                DB::raw("DATE_FORMAT(tec_stock_out.date_update, '%d/%m/%Y %h:%i %p') as date"),
                'tec_products.name as product_name',
                'tec_stock_out.qty',
                'tec_stock_out.status',
                'tec_stores.name as store_name',
                'tec_users.username',
                'tec_stock_out.remark',
                'tec_warehouse.ware_name'
            )
            ->join('tec_products', 'tec_stock_out.fk_pro_id', '=', 'tec_products.id')
            ->join('tec_stores', 'tec_stock_out.fk_store_id', '=', 'tec_stores.id')
            ->join('tec_users', 'tec_stock_out.user_update', '=', 'tec_users.id')
            ->join('tec_warehouse', 'tec_stock_out.ware_id', '=', 'tec_warehouse.warehouse_id');

        if ($request->store_id != '') {
            $data = $data->where('tec_stock_out.fk_store_id', '=', $request->store_id);
        }

        if ($request->product_id != '') {
            $data = $data->where('tec_stock_out.fk_pro_id', '=', $request->product_id);
        }

        if ($request->warehouse_id != '') {
            $data = $data->where('tec_stock_out.ware_id', '=', $request->warehouse_id);
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
            $data = $data->where('tec_stock_out.date_update', '>=', $start);
        }

        if ($end != '') {
            $data = $data->where('tec_stock_out.date_update', '<=', $end);
        }

        $data = $data
            ->orderByDesc('tec_stock_out.pk_stock_out_id')
            ->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        $qty = 0;

        foreach ($data as $col) {

            $qty = $qty + $col->qty;

            $status = 'ទំនិញកាត់ស្តុក';

            if ($col->status == "0") {
                $status = 'ទំនិញបាត់';
            }

            $value = $value . $this->html('tr',
                $this->html('td', '#' . $col->id, 'width="80px"') .
                $this->html('td', '' . $col->date, 'width="160px"') .
                $this->html('td', $col->product_name, 'class="text-left"') .
                $this->html('td', number_format($col->qty) . ' pcs', 'class="text-right"') .
                $this->html('td', $status, 'class="text-rights" ') .
                $this->html('td', $col->username, 'class="text-rights" ') .
                $this->html('td', $col->remark, 'class="text-rights" ') .
                $this->html('td', $col->store_name, 'class="text-rights" ') .
                $this->html('td', $col->ware_name, 'class="text-rights" ')
                , '');
        }

        $footer =
        $this->html('tr',
            $this->html('th', 'សរុប', 'class="text-uppercase" width="100px"') .
            $this->html('th', '', '') .
            $this->html('th', '', '') .
            $this->html('th', number_format($qty) . ' pcs', 'class="text-right text-primary"') .
            $this->html('th', '', 'class="text-right"') .
            $this->html('th', '', 'class="text-right"') .
            $this->html('th', '', 'class="text-right"') .
            $this->html('th', '', 'class="text-right"') .
            $this->html('th', '', 'class="text-right"')
            , 'class="active"');

        $table = $this->html('table', $this->html('tr', $cols, '') . $this->html('tr', $value, '') . $footer, 'class="table table-bordered table-stripeds" id="table"');

        return [
            'table' => $table,
            'page' => $data->currentPage(),
            'per_page' => $data->lastPage(),
            'total' => $data->total(),
        ];
    }

    // export
    public function create_export(Request $request)
    {

        $name = 'no_image.png';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = date('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);
        }

        $data = array(
            'fk_pro_id' => $request->product_id_no,
            'fk_store_id' => $request->store_id_no,
            'qty' => $request->qty,
            'image' => $name,
            'user_update' => auth()->user()->user_id,
            'date_update' => date('Y-m-d H:i:s'),
            'time_update' => date('Y-m-d H:i:s'),
            'remark' => $request->remark,
            'ware_id' => $request->warehouse_id,
            'status' => $request->status,
        );

        if ($request->qty > 0) {
            DB::table('tec_stock_out')->insert($data);

            DB::update("
                UPDATE `tec_warehouse`
                SET `in` = `in` + '$request->qty'
                WHERE
                `warehouse_id` = '$request->warehouse_id'
            ");
        }

        return ['message' => 'successfully.'];
    }

    // warehouse
    public function warehouse()
    {
        return view('warehouse.warehouse');
    }

    // get warehouse
    public function get_warehouse(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('tec_warehouse as u')
                ->selectRaw("u.*")
                ->orderBy('u.ware_name')
                ->get();

            return Datatables::of($data)->make(true);
        }
    }

    // create warehouse
    public function create_warehouse(Request $request)
    {
        $data = array(
            'ware_name' => $request->ware_name,
            'ware_code' => $request->ware_code,
            'created_by' => auth()->user()->user_id,
            'created_update' => date('Y-m-d H:i:s'),
            'status' => 1,
            'image' => $request->ware_name,
        );

        if ($request->warehouse_id == 0) {
            array_push($data, [
                'transfer' => 0,
                'int' => 0,
                'out' => 0,
                'return' => 0,
            ]);
            DB::table('tec_warehouse')->insert($data);
        }

        if ($request->warehouse_id > 0) {
            DB::table('tec_warehouse')
                ->where('warehouse_id', "$request->warehouse_id")
                ->update($data);
        }

        return ['message' => 'successfully.'];
    }

    // add stock
    public function add_stock_warehouse(Request $request)
    {
        $data = array(
            'ware_id' => $request->warehouse_id,
            'qty' => $request->qty,
            'transfer_by' => auth()->user()->user_id,
            'date' => date('Y-m-d H:i:s'),
            'status' => $request->status,
            'image' => $request->ware_name,
            'note' => $request->remark,
        );

        if ($request->qty > 0) {
            DB::table('tec_transfers')->insert($data);
            if ($request->status == 'Import') {
                DB::update("
                    UPDATE `tec_warehouse`
                    SET `in` = `in` + '$request->qty'
                    WHERE
                    `warehouse_id` = '$request->warehouse_id'
                ");
            } else if ($request->status == 'Export') {
                DB::update("
                    UPDATE `tec_warehouse`
                    SET `in` = `in` - '$request->qty'
                    WHERE
                    `warehouse_id` = '$request->warehouse_id'
                ");
            }
        }
        return ['message' => 'successfully.'];
    }

    // adjustment
    public function adjustment()
    {
        return view('warehouse.adjustment');
    }

    // get warehouse
    public function get_adjustment(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('tec_transfers as u');

            $warehouse_id = (int) $request->warehouse_id;

            $date = $request->date;

            $start = '';

            $end = '';

            if ($date != '') {
                $date_last = explode('-', $date);

                $start = date('Y-m-d H:i:s', strtotime($date_last[0]));

                $end = date('Y-m-d H:i:s', strtotime($date_last[1]));
            }

            if ($warehouse_id > 0) {
                $data = $data->where('u.ware_id', '=', $warehouse_id);
            }

            if ($start != '') {
                $data = $data->where('u.date', '>=', $start);
            }

            if ($end != '') {
                $data = $data->where('u.date', '<=', $end);
            }

            $data = $data->selectRaw("u.*, w.ware_name, us.username, DATE_FORMAT(u.date, '%a %d %b %Y %h:%i %p') as date")
                ->join('tec_warehouse as w', 'u.ware_id', '=', 'w.warehouse_id')
                ->join('tec_users as us', 'us.id', '=', 'u.transfer_by')
                ->orderBy('u.id', 'desc')
                ->get();

            return Datatables::of($data)->make(true);
        }
    }

    //  receipt
    public function receipt(Request $request)
    {
        $data['data'] = DB::table('tec_stock_in')
            ->join('tec_products', 'tec_stock_in.fk_pro_id', '=', 'tec_products.id')
            ->where('tec_stock_in.id', $request->id)
            ->get();

        $data['store'] = DB::table('tec_stores')
            ->where('id', $data['data'][0]->fk_store_id)
            ->get();

        return view('import.receipt', $data);
    }

}
