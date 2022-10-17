<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Utils
{
    public function get_store_id()
    {
        return DB::table('tec_users')
            ->where('id', Auth::user()->user_id)
            ->first()->store_id;
    }

    public function get_waiting_number()
    {
        $waiting = DB::table('tec_sales')
            ->orderBy('id', 'desc')
            ->first()->waiting_number;

        $id = $waiting + 1;

        if ($waiting == 100) {
            $id = 1;
        }

        return $id;
    }

    public static function get_permissions(){
        return DB::table('tec_permission')
            ->where('user_id', Auth::user()->user_id)
            ->first();
    }

    public static function store_id()
    {
        return DB::table('tec_users')
            ->where('id', Auth::user()->user_id)
            ->first()->store_id;
    }

    public static function add_product_to_stock(){
        DB::insert("
            INSERT INTO `tec_product_store_qty`(`product_id`, `store_id`, `quantity`, `price`, `price_wholesale`)
            SELECT
                tec_products.id product_id,
                tec_stores.id store_id,
                0 quantity,
                tec_products.price,
                tec_products.price_wholesale
            FROM
                `tec_stores`
                CROSS JOIN tec_products
            WHERE NOT EXISTS (
             SELECT * FROM tec_product_store_qty
             WHERE store_id = tec_stores.id
             AND product_id = tec_products.id
            );
        ");
    }
}
