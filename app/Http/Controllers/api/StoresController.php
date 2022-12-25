<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoresController extends Controller
{

    private $host;

    /**
     * StoresController constructor.
     */
    public function __construct()
    {
        $this->host = $_SERVER['HTTP_HOST'];
    }

    public function get_warehouses_stock()
    {

        $host = $_SERVER['HTTP_HOST'];

        $page = request()->get('page') ?? 1;
        $page_size = request()->get('page_size') ?? 10;
        $product_id = request()->get('product_id') ?? '';
        $store_id = request()->get('store_id') ?? '';

        $store = TableStore;
        $product = TableProduct;
        $qty = TableProductStoreQty;

        $data = DB::table(TableProduct)
            ->selectRaw("
            $product.name proudct_name,
            IFNULL(concat('http://$host/uploads/',image), concat('http://$host/uploads/7527dd8c427584bc7f1942afeae252d1.jpg')) as image,
            $qty.id,
            $store.name as store_name,
            $qty.price,
            $qty.quantity
            ")
            ->join($qty, $product . '.id', '=', $qty . '.product_id')
            ->join($store, $qty . '.store_id', '=', $store . '.id');

        if ($product_id != '') {
            $data = $data->where($product . '.id', $product_id);
        }

        if ($store_id != '') {
            $data = $data->where($qty . '.store_id', $store_id);
        }

        $data = $data->orderBy($qty . '.store_id', 'asc')
            ->paginate($page_size, ['*'], 'page', $page);
        return $data;
    }


    // update
    public function update_qty_warehouse()
    {

        $price = request()->get('price') ?? 0;
        $quantity = request()->get('quantity') ?? 0;
        $id = request()->get('id') ?? 0;

        if ($id > 0 && auth()->user()->id == 1) {
            DB::table(TableProductStoreQty)
                ->where('id', $id)
                ->update([
                    'price' => $price,
                    'quantity' => $quantity
                ]);
        }

        return [
            'message' => 'updated successfully.'
        ];
    }

    // all
    public function get_products_import()
    {
        $host = $_SERVER['HTTP_HOST'];
        $stock_in = TableStockIn;
        $store = TableStore;
        $user = TableUser;
        $product = TableProduct;
        $main_store = TableStore;

        $data = DB::table("$stock_in as s")
            ->join("$product as p", "s.fk_pro_id", "=", "p.id")
            ->join("$store as st", "st.id", "=", "s.fk_store_id")
            ->join("$user as u", "u.id", "=", "s.user_update")
            ->join("$main_store as ms", "ms.id", "=", "s.ware_id")
            ->selectRaw("
                s.id,
                s.no,
                s.date_update,
                IFNULL(concat('http://$host/uploads/',p.image), concat('http://$host/uploads/icon.png')) as image,
                s.qty,
                s.remark,
                p.name,
                st.name as store,
                u.username,
                ms.name as main_store,
                DATE_FORMAT(s.date_update, '%d/%m/%Y %h:%i %p') as date
            ");

        if ($this->requests()['product_id'] != '') {
            $data = $data->where('p.id', $this->requests()['product_id']);
        }

        if ($this->requests()['store_id'] != '') {
            $data = $data->where('st.id', $this->requests()['store_id']);
        }

        if ($this->requests()['start_date'] != '') {
            $data = $data->whereDate('s.date_update', '<=', $this->requests()['start_date']);
        }

        if ($this->requests()['end_date'] != '') {
            $data = $data->whereDate('s.date_update', '>=', $this->requests()['end_date']);
        }

        $data = $data->orderByDesc('s.date_update')
            ->paginate($this->requests()['page_size'], ['*'], 'page', $this->requests()['page']);

        return $data;
    }

    //
    public function search_product()
    {
        $code = request()->get('code');
        $id = request()->get('id');
        $product = TableProduct;
        $data = DB::table("$product as p")
            ->selectRaw("
                p.id,
                p.name,
                IFNULL(concat('http://$this->host/uploads/',p.image), concat('http://$this->host/uploads/icon.png')) as image,
                p.code,
                p.price
            ");

        if ($code != '') {
            $data = $data->where('p.code', '=', $code);
        }

        if ($id != '') {
            $data = $data->where('p.id', '=', $id);
        }

        $data = $data->first();

        if ($data != '') {
            return $data;
        }

        return ['message' => 'product is not found.'];
    }

    private function requests()
    {
        return [
            'page' => request()->get('page') ?? 1,
            'page_size' => request()->get('page_size') ?? 10,
            'start_date' => request()->get('start_date') ?? '',
            'end_date' => request()->get('end_date') ?? '',
            'product_id' => request()->get('product_id') ?? '',
            'store_id' => request()->get('store_id') ?? '',
        ];
    }

}
