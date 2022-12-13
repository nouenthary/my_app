<?php

namespace App\Http\Controllers;

use App\Repository\ProductRepositoryInterface;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Milon\Barcode\Facades\DNS2DFacade;
use PDF;
use Yajra\DataTables\DataTables;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    private $products;

    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $products
     */
    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = lang('list_product');
        $data['categories'] = DB::table('tec_categories')->orderBy('name', 'asc')->get();
        $data['brands'] = DB::table('brands')->orderBy('brand_name', 'asc')->get();
        return view('products.index', $data);
    }

    #f
    public function get_products(Request $request)
    {
        $columns = [
            lang("image"),
            lang("code"),
            lang(""),
            lang("product"),
            lang('unit_cost'),
            lang('sale_price'),
            lang('qty'),
            lang('category'),
            lang('brand'),
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . html('th', $col, 'class="active"');
        }
        $data = DB::table('tec_products as p')
            ->selectRaw(
                'p.id, p.name, p.cost, p.price, p.image, p.code, p.unit,
                    p.category_id, p.brand_id, p.alert_quantity, p.details, p.type,
                    c.name as cate_name,
                    b.id as brand_id ,b.brand_name,
                    (SELECT COALESCE (SUM(quantity),0) FROM `tec_product_store_qty` WHERE product_id=p.id ) as quantity,
                    p.branch_commission,p.staff_commission,p.other_commission, p.unit, p.barcode_symbology, p.is_active
                '
            )
            ->leftJoin('tec_categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin('brands as b', 'p.brand_id', '=', 'b.id');

        if ($request->product_id != '') {
            $data = $data->where('p.id', '=', $request->product_id);
        }

        if ($request->code != '') {
            $data = $data->where('p.code', 'LIKE', $request->code);
        }

        if ($request->brand_id != '') {
            $data = $data->where('p.brand_id', '=', $request->brand_id);
        }

        if ($request->category_id != '') {
            $data = $data->where('p.category_id', '=', $request->category_id);
        }

        $data = $data->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        $qty = 0;

        foreach ($data as $col) {

            $json = json_encode($col);
            $qty = $qty + $col->quantity;
            $row = "id='$col->id' data='$json' ";
            $value = $value . html('tr',
                    html('td', image("/$col->image", "25px"), 'class="text-center" width="25px"') .
                    html('td', '' . $col->code, 'width="100px"') .
                    html('td', '<img width="35px" src="data:image/png;base64,' . DNS2DFacade::getBarcodePNG($col->code, 'QRCODE') . '" alt="barcode"   />', 'width="30px"') .
                    html('td', '' . $col->name, '') .
                    html('td', '' . number_format($col->cost) . '៛', 'width="80px" class="text-right"') .
                    html('td', '' . number_format($col->price) . '៛', 'width="80px" class="text-right"') .
                    html('td', '' . number_format($col->quantity) . ' ' . $col->unit, 'width="100px" class="text-right"') .
                    html('td', '' . $col->cate_name, 'width="100px"') .
                    html('td', '' . $col->brand_name, 'width="100px"')
                    , $row);
        }

        $footer = html('tr',
            html('th', 'សរុប', 'class="text-uppercase" width="100px"') .
            html('th', '', '') .
            html('th', '', '') .
            html('th', '', 'class="text-right"') .
            html('th', '', 'class="text-right"') .
            html('th', number_format($qty) . '', 'class="text-right text-primary"') .
            html('th', '', 'class="text-right"') .
            html('th', '', 'class="text-right"') .
            html('th', '', 'class="text-right"')

            , 'class="active"');

        $table = html('table', html('tr', $cols, '') . html('tr', $value, '') . $footer, 'class="table table-bordered table-striped table-hover" id="table" ');

        return [
            'table' => $table,
            'page' => $data->currentPage(),
            'per_page' => $data->lastPage(),
            'total' => $data->total(),
        ];

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
        $names = $request->photo;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $names = date('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $names);
        }
        $id = $request->id;
        $code = DB::table('tec_products')
            ->where('code', 'LIKE', $request->code);

        if ($code->first() != '' && $id == 0) {
            return ['error' => "code `$request->code` is exist..."];
        }

        $data = [
            'type' => 'standard',
            'code' => $request->code,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'cost' => $request->cost,
            'price_wholesale' => 0,
            'image' => $names,
            'tax' => 0,
            'tax_method' => 0,
            'quantity' => 0,
            'barcode_symbology' => $request->barcode_symbology,
            'details' => $request->deatils,
            'alert_quantity' => 0,
            'brand_id' => $request->brand_id,
            'unit' => $request->unit,
            'is_active' => $request->is_active,
            'user_id' => auth()->user()->user_id,
            'branch_commission' => (float)$request->branch_commission,
            'staff_commission' => (float)$request->staff_commission,
            'other_commission' => (float)$request->other_commission
        ];

        if ($id == 0) {
            DB::table('tec_products')->insert($data);
        }

        if ($code->where('id', '!=', $id)->first() != '' && $id > 0) {
            return ['error' => "code `$request->code` is exist..."];
        }

        if ($id > 0) {
            DB::table('tec_products')->where('id', $id)->update($data);
        }

        Utils::add_product_to_stock();

        return ['message' => 'created'];
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
        $store = DB::table('tec_stores')->select('id', 'name')->where('city', '<>', 'None')->get();

        $product = DB::table('tec_products')->select('id', 'name', 'price')->get();

        $warehouse = DB::table('tec_warehouse')->select('warehouse_id as id', 'ware_name as name')->get();

        return view('import.import', compact('store', 'product', 'warehouse'));
    }

    //
    public function get_imports(Request $request)
    {
        if ($request->ajax()) {

            $store_id = (int)$request->store_id;

            $product_id = (int)$request->product_id;

            $data = DB::table('tec_categories as uu');

            if ($store_id > 0) {
                $data = $data->where('uuu.store_id', $store_id);
            }

            if ($product_id > 0) {
                $data = $data->where('uuu.product_id', $product_id);
            }

            //(SELECT COALESCE(SUM(qty),0) FROM `tec_stock_in` WHERE fk_pro_id = u.id and fk_store_id = uuu.store_id ) as total,
            $data = $data->selectRaw(
                "u.* ,
                    uu.id ,
                    uu.name as cate_name,
                    uuu.store_id,
                    uuuu.name as store_name,
                    u.id as product_id,
                    uuu.quantity"
            )
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
        //return $request->all();
        $store_id = $request->store_id;
        $invoice = str_pad(1, 7, '0', STR_PAD_LEFT);

        $no = DB::table('tec_stock_in')
            ->where('fk_store_id', '=', $store_id)
            ->orderByDesc('id')
            ->first();

        if ($no != null) {
            $last_no = (int)$no->no + 1;
            $invoice = str_pad($last_no, 7, '0', STR_PAD_LEFT);
        }

        $name = 'no_image.png';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = date('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/import');
            $image->move($destinationPath, $name);
        }

        $id = 0;

        $type_import = $request->type_import;

        if (count($request->data) > 0) {

            foreach ($request->data as $row) {
                $product_id = $row['id'];
                $qty = (int)$row['qty'];
                $warehouse_id = $request->ware_id;

                if ($type_import == 'transfer' || $type_import == 'import') {
                    $data = array(
                        'fk_pro_id' => $product_id,
                        'fk_store_id' => $store_id,
                        'qty' => $qty,
                        'image' => $name,
                        'user_update' => auth()->user()->user_id,
                        'date_update' => date('Y-m-d H:i:s'),
                        //'time_update' => date('Y-m-d H:i:s'),
                        'remark' => $request->remark,
                        'ware_id' => $warehouse_id,
                        'no' => $invoice,
                    );
                    $id = DB::table('tec_stock_in')->insertGetId($data);
                }

                if ($row['qty'] > 0) {

                    if ($type_import == 'transfer') {

                        DB::update("
                            UPDATE `tec_product_store_qty`
                            SET `quantity` = `quantity` + '$qty'
                            WHERE
                            `product_id` = '$product_id' AND `store_id` = '$store_id'
                        ");

                        DB::update("
                            UPDATE `tec_product_store_qty`
                            SET `quantity` = `quantity` - '$qty'
                            WHERE
                            `product_id` = '$product_id' AND `store_id` = '$warehouse_id'
                        ");
                    }

                    if ($type_import == 'import') {
                        DB::update("
                            UPDATE `tec_product_store_qty`
                            SET `quantity` = `quantity` + '$qty'
                            WHERE
                            `product_id` = '$product_id' AND `store_id` = '$warehouse_id'
                        ");
                    }

                    if ($type_import == 'export') {

                        $export = [
                            'fk_pro_id' => $product_id,
                            'fk_store_id' => $store_id,
                            'qty' => $qty,
                            'status' => '1',
                            'user_update' => auth()->user()->user_id,
                            'date_update' => date('Y-m-d H:i:s'),
                            'remark' => $request->remark,
                            'ware_id' => 1
                        ];

                        DB::table('tec_stock_out')->insert($export);

                        DB::update("
                            UPDATE `tec_product_store_qty`
                            SET `quantity` = `quantity` - '$qty'
                            WHERE
                            `product_id` = '$product_id' AND `store_id` = '$store_id'
                        ");

                        if ($store_id > 1) {
                            DB::update("
                                UPDATE `tec_product_store_qty`
                                SET `quantity` = `quantity` + '$qty'
                                WHERE
                                `product_id` = '$product_id' AND `store_id` = '$warehouse_id'
                            ");
                        }
                    }
                }
            }
        }

        return ['message' => 'successfully.', 'invoice' => $id];
    }

    public function list_import()
    {
        //$this->sum_stock();
        return view('import.list_import');
    }

    public function get_list_imports(Request $request)
    {
        $columns = [
            __("language.invoice"),
            __("language.date"),
            __("language.product"),
            __("language.qty") . 'បញ្ជូល',
            __("language.username"),
            __("language.remark"),
            __("language.branch"),
            lang('from_branch'),
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
                's.name as ware_name',
                'tec_stores.name as store_name',
                'tec_stores.city'
            )
            ->join('tec_products', 'tec_stock_in.fk_pro_id', '=', 'tec_products.id')
            ->join('tec_stores', 'tec_stock_in.fk_store_id', '=', 'tec_stores.id')
            ->join('tec_users', 'tec_stock_in.user_update', '=', 'tec_users.id')
            ->join('tec_stores as s', 'tec_stock_in.ware_id', '=', 's.id');

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

        $permission = DB::table('tec_permission')
            ->where('user_id', '=', Auth::user()->user_id)
            ->first();


        foreach ($data as $col) {

            $qty = $qty + $col->qty;

            $token = csrf_token();

            $row = json_encode($col);

            $action_return = "";
            if ($permission->setting == 1) {
                $action_return = "<a id='$col->id' class='btn btn-xs btn-return' ><i class='fa fa-remove text-danger'></i></a>";
            }

            $value = $value . $this->html('tr',
                    $this->html('td', '#' . $col->no, 'width="80px"') .
                    $this->html('td', '' . $col->date, 'width="160px"') .
                    $this->html('td', $col->product_name, 'class="text-left"') .
                    $this->html('td', number_format($col->qty) . ' pcs', 'class="text-right"') .
                    $this->html('td', $col->username, 'class="text-left" ') .
                    $this->html('td', $col->remark, 'class="text-left" ') .
                    $this->html('td', $col->store_name, 'class="text-left" ') .
                    $this->html('td', $col->ware_name, 'class="text-left" ') .

                    $this->html('td', "
                    $action_return
                    <a id='' href='/receipt?id=$col->id&token=$token' class='btn btn-xs btn-print'  target='_blank'><i class='fa fa-file-text-o'></i></a>
                ", 'class="text-rights" ')
                    , "class='text-center' data-item='$row'");
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
                's.name as ware_name'
            )
            ->join('tec_products', 'tec_stock_out.fk_pro_id', '=', 'tec_products.id')
            ->join('tec_stores', 'tec_stock_out.fk_store_id', '=', 'tec_stores.id')
            ->join('tec_users', 'tec_stock_out.user_update', '=', 'tec_users.id')
            ->join('tec_stores as s', 'tec_stock_out.ware_id', '=', 's.id');

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

        $store_id = $request->store_id;
        $remark = $request->remark;

        if (count($request->data) > 0) {

            foreach ($request->data as $row) {
                $product_id = $row['id'];
                $qty = (int)$row['qty'];
                $warehouse_id = $row['warehouse_id'];

                $data = array(
                    'fk_pro_id' => $product_id,
                    'fk_store_id' => $store_id,
                    'qty' => $qty,
                    'user_update' => auth()->user()->user_id,
                    'date_update' => date('Y-m-d H:i:s'),
                    'time_update' => date('Y-m-d H:i:s'),
                    'remark' => $remark,
                    'ware_id' => $warehouse_id,
                    'status' => $request->status,
                );

                if ($qty > 0) {
                    DB::table('tec_stock_out')->insert($data);

                    DB::update("
                        UPDATE `tec_warehouse`
                        SET `in` = `in` + '$qty'
                        WHERE
                        `warehouse_id` = '$warehouse_id'
                    ");

                    DB::update("
                        UPDATE `tec_product_store_qty`
                        SET `quantity` = `quantity` - '$qty'
                        WHERE
                        `product_id` = '$product_id' AND `store_id` = '$store_id'
                    ");
                }
            }
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

            $warehouse_id = (int)$request->warehouse_id;

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
        $row = DB::table('tec_stock_in')
            ->join('tec_products', 'tec_stock_in.fk_pro_id', '=', 'tec_products.id')
            ->where('tec_stock_in.id', $request->id)
            ->get();

        $data['store'] = DB::table('tec_stores')
            ->where('id', $row[0]->fk_store_id)
            ->get();

        $data['data'] = DB::table('tec_stock_in')
            ->join('tec_products', 'tec_stock_in.fk_pro_id', '=', 'tec_products.id')
            ->where('tec_stock_in.fk_store_id', $row[0]->fk_store_id)
            ->where('tec_stock_in.no', $row[0]->no)
            ->get();

        return view('import.receipt', $data);
    }

    // barcode
    public function get_barcode()
    {
        $barcode = DB::table('tec_products')->pluck('code');
        return $barcode;
    }

    // return import
    public function return_import(Request $request)
    {
        $import = DB::table('tec_stock_in')->where('id', $request->id)->first();

        if ($import != null) {
            $data = [
                'stock_in_id' => $import->id,
                'product_id' => $import->fk_pro_id,
                'store_id' => $import->fk_store_id,
                'qty' => $import->qty,
                'image' => $import->image,
                'created_by' => $import->user_update,
                'created_date' => $import->date_update,
                'remark' => $import->remark,
                'warehouse_id' => $import->ware_id,
                'reference_no' => $import->no,
                'return_by' => Auth::user()->user_id,
                'return_date' => date('Y-m-d H:i:s')
            ];
            DB::table('return_imports')->insert($data);

//            DB::update("
//                Update tec_warehouse
//                set `in` = `in` + $import->qty
//                where warehouse_id = '$import->ware_id'
//            ");

            DB::update("
                Update tec_product_store_qty
                set `quantity` = `quantity` - $import->qty
                where product_id = '$import->fk_pro_id'
                and store_id = '$import->fk_store_id'
            ");

            if ($import->fk_store_id > 1) {
                DB::update("
                Update tec_product_store_qty
                set `quantity` = `quantity` + $import->qty
                where product_id = '$import->fk_pro_id'
                and store_id = '$import->ware_id'
            ");
            }

            DB::table('tec_stock_in')->where('id', $request->id)->delete();
        }

        return ['message' => 'delete.'];
    }

    public function list_products()
    {
        $data['title'] = 'List Product';

        $data['products'] = DB::table('tec_products')
            ->select('tec_products.*', 'tec_product_store_qty.*')
            ->join('tec_product_store_qty', 'tec_products.id', '=', 'tec_product_store_qty.product_id')
            ->where('tec_product_store_qty.store_id', '=', Utils::store_id())
            ->get();

        $price = DB::table('tec_products')
            ->select('tec_products.price')
            ->join('tec_product_store_qty', 'tec_products.id', '=', 'tec_product_store_qty.product_id')
            ->where('tec_product_store_qty.store_id', '=', Utils::store_id())
            ->groupBy('tec_products.price')
            ->orderBy('tec_products.price')
            ->get();

        $product = [];

        if (count($price) > 0) {

            foreach ($price as $p) {
                $price_list = DB::table('tec_products')
                    ->select('tec_products.*', 'tec_products.price as sell_price')
                    ->join('tec_product_store_qty', 'tec_products.id', '=', 'tec_product_store_qty.product_id')
                    ->where('tec_product_store_qty.store_id', '=', Utils::store_id())
                    ->where('tec_products.price', '=', $p->price)
                    ->get();

                if ($price_list != null) {
                    array_push($product, $price_list);
                }
            }
        }

        $data['product'] = $product;

        //return $product;

        return view('products.list_products', $data);
    }

    public function import_stock()
    {

        if (isset($_GET['import_type']) == '') {
            return Redirect::to('/list_import');
        }

        if ($this->get_permission()->permission == 0) {
            return Redirect::to('/list_import');
        }

        $type_import = $_GET['import_type'];

        $main_store = '';

        if ($type_import == 'import') {
            $main_store = 1;
        }

        $main_store = 1;

        $product = $this->get_product();
        $store = $this->products->getStore();
        return view('import.import_stock', compact('product', 'store', 'type_import', 'main_store'));
    }

    //
    public function get_current_stock()
    {
        print_r($this->sum_qty());
        //print_r($this->transfer());
    }

    private function sum_qty()
    {
        $stock = DB::table('tec_stock_in')
            ->selectRaw(
                '
                tec_products.id,
                tec_products.name pro_name,
                tec_stores.id store_id,
                tec_stores.name store,
                SUM(tec_stock_in.qty) as in_qty,

                (
                    SELECT
                    COALESCE(SUM(tec_sale_items.quantity),0)
                    FROM `tec_sales`
                    INNER JOIN tec_sale_items
                    ON tec_sales.id = tec_sale_items.sale_id
                    WHERE tec_sale_items.product_id = tec_products.id
                    AND tec_sales.store_id = tec_stores.id
                ) sale_qty,

                (
                    SELECT
                    COALESCE(SUM(qty),0)
                    FROM `tec_stock_out`
                    WHERE fk_pro_id = tec_products.id
                    AND fk_store_id = tec_stores.id
                ) out_qty,

               (
                 SUM(tec_stock_in.qty)
                 -
               (
                    SELECT
                    COALESCE(SUM(qty),0)
                    FROM `tec_stock_out`
                    WHERE fk_pro_id = tec_products.id
                    AND fk_store_id = tec_stores.id
                )

                -

                (
                    SELECT
                    COALESCE(SUM(tec_sale_items.quantity),0)
                    FROM `tec_sales`
                    INNER JOIN tec_sale_items
                    ON tec_sales.id = tec_sale_items.sale_id
                    WHERE tec_sale_items.product_id = tec_products.id
                    AND tec_sales.store_id = tec_stores.id
                )

                ) total


                '
            )
            ->join('tec_products', 'tec_stock_in.fk_pro_id', '=', 'tec_products.id')
            ->join('tec_stores', 'tec_stock_in.fk_store_id', '=', 'tec_stores.id')
            ->groupBy('tec_products.id', 'tec_products.name', 'tec_stores.id', 'tec_stores.name')
            ->orderBy('tec_stores.id')
            ->get();

        if ($stock != null) {
            foreach ($stock as $i) {
                DB::table('tec_product_store_qty')
                ->where('product_id',$i->id)
                ->where('store_id', $i->store_id)
                ->update([
                    'quantity' => $i->total
                ]);
            }
        }
        return $stock;
    }

    private function transfer()
    {
        $transfer = DB::table('tec_transfers')->get();

        if ($transfer != null) {
            foreach ($transfer as $i) {

                $store_id = 1;
                $product_id = 5;

                $qty = $i->qty;
                $date = $i->date;
                $user_id = $i->transfer_by;
                $note = $i->note;

                if ($i->ware_id < 4 && $i->status == 'Import') {

                    $has = DB::select("
                        select count(*) count from tec_stock_in
                        where fk_pro_id = '$product_id'
                        and fk_store_id = '$store_id'
                        and qty = '$qty'
                        and date_update = '$date'
                    ");

                    if ($has[0]->count == 0) {

                        $invoice = str_pad(1, 7, '0', STR_PAD_LEFT);
                        $no = DB::table('tec_stock_in')
                            ->where('fk_store_id', '=', $store_id)
                            ->orderByDesc('id')
                            ->first();
                        if ($no != null) {
                            $last_no = (int)$no->no + 1;
                            $invoice = str_pad($last_no, 7, '0', STR_PAD_LEFT);
                        }

                        DB::table('tec_stock_in')->insert([
                            'fk_pro_id' => $product_id,
                            'fk_store_id' => $store_id,
                            'qty' => $qty,
                            'image' => 'no_image.png',
                            'user_update' => $user_id,
                            'date_update' => $date,
                            'time_update' => $date,
                            'remark' => $note,
                            'no' => $invoice,
                            'ware_id' => 1
                        ]);

                    }

                }
                if ($i->ware_id == 5 && $i->status == 'Import') {
                    $product_id = 6;

                    $has = DB::select("
                        select count(*) count from tec_stock_in
                        where fk_pro_id = '$product_id'
                        and fk_store_id = '$store_id'
                        and qty = '$qty'
                        and date_update = '$date'
                    ");

                    if ($has[0]->count == 0) {

                        $invoice = str_pad(1, 7, '0', STR_PAD_LEFT);
                        $no = DB::table('tec_stock_in')
                            ->where('fk_store_id', '=', $store_id)
                            ->orderByDesc('id')
                            ->first();
                        if ($no != null) {
                            $last_no = (int)$no->no + 1;
                            $invoice = str_pad($last_no, 7, '0', STR_PAD_LEFT);
                        }

                        DB::table('tec_stock_in')->insert([
                            'fk_pro_id' => $product_id,
                            'fk_store_id' => $store_id,
                            'qty' => $qty,
                            'image' => 'no_image.png',
                            'user_update' => $user_id,
                            'date_update' => $date,
                            'time_update' => $date,
                            'remark' => $note,
                            'no' => $invoice,
                            'ware_id' => 1
                        ]);

                    }

                }
                if ($i->ware_id == 6 && $i->status == 'Import') {
                    $product_id = 7;

                    $has = DB::select("
                        select count(*) count from tec_stock_in
                        where fk_pro_id = '$product_id'
                        and fk_store_id = '$store_id'
                        and qty = '$qty'
                        and date_update = '$date'
                    ");

                    if ($has[0]->count == 0) {

                        $invoice = str_pad(1, 7, '0', STR_PAD_LEFT);
                        $no = DB::table('tec_stock_in')
                            ->where('fk_store_id', '=', $store_id)
                            ->orderByDesc('id')
                            ->first();
                        if ($no != null) {
                            $last_no = (int)$no->no + 1;
                            $invoice = str_pad($last_no, 7, '0', STR_PAD_LEFT);
                        }

                        DB::table('tec_stock_in')->insert([
                            'fk_pro_id' => $product_id,
                            'fk_store_id' => $store_id,
                            'qty' => $qty,
                            'image' => 'no_image.png',
                            'user_update' => $user_id,
                            'date_update' => $date,
                            'time_update' => $date,
                            'remark' => $note,
                            'no' => $invoice,
                            'ware_id' => 1
                        ]);

                    }
                }
                if ($i->ware_id == 7 && $i->status == 'Import') {
                    $product_id = 9;

                    $has = DB::select("
                        select count(*) count from tec_stock_in
                        where fk_pro_id = '$product_id'
                        and fk_store_id = '$store_id'
                        and qty = '$qty'
                        and date_update = '$date'
                    ");

                    if ($has[0]->count == 0) {

                        $invoice = str_pad(1, 7, '0', STR_PAD_LEFT);
                        $no = DB::table('tec_stock_in')
                            ->where('fk_store_id', '=', $store_id)
                            ->orderByDesc('id')
                            ->first();
                        if ($no != null) {
                            $last_no = (int)$no->no + 1;
                            $invoice = str_pad($last_no, 7, '0', STR_PAD_LEFT);
                        }

                        DB::table('tec_stock_in')->insert([
                            'fk_pro_id' => $product_id,
                            'fk_store_id' => $store_id,
                            'qty' => $qty,
                            'image' => 'no_image.png',
                            'user_update' => $user_id,
                            'date_update' => $date,
                            'time_update' => $date,
                            'remark' => $note,
                            'no' => $invoice,
                            'ware_id' => 1
                        ]);

                    }
                }
                if ($i->ware_id == 8 && $i->status == 'Import') {
                    $product_id = 8;

                    $has = DB::select("
                        select count(*) count from tec_stock_in
                        where fk_pro_id = '$product_id'
                        and fk_store_id = '$store_id'
                        and qty = '$qty'
                        and date_update = '$date'
                    ");

                    if ($has[0]->count == 0) {

                        $invoice = str_pad(1, 7, '0', STR_PAD_LEFT);
                        $no = DB::table('tec_stock_in')
                            ->where('fk_store_id', '=', $store_id)
                            ->orderByDesc('id')
                            ->first();
                        if ($no != null) {
                            $last_no = (int)$no->no + 1;
                            $invoice = str_pad($last_no, 7, '0', STR_PAD_LEFT);
                        }

                        DB::table('tec_stock_in')->insert([
                            'fk_pro_id' => $product_id,
                            'fk_store_id' => $store_id,
                            'qty' => $qty,
                            'image' => 'no_image.png',
                            'user_update' => $user_id,
                            'date_update' => $date,
                            'time_update' => $date,
                            'remark' => $note,
                            'no' => $invoice,
                            'ware_id' => 1
                        ]);

                    }
                }

            }
        }

        return $transfer;
    }

    private function get_product()
    {
        return DB::table('tec_products')->where('is_active', 1)->limit(5)->orderBy('id')->get();
    }

    private function get_permission()
    {
        return DB::table('tec_permission')->where('user_id', Auth::user()->user_id)->first();
    }


    /**
     * @return mixed
     */
    public function getProductByColumn()
    {
        return $this->products->findProductByColumn();
    }

}
