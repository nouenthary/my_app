<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function get_product()
    {
        $host = $_SERVER['HTTP_HOST'];
        $product = DB::table('tec_products')
            ->selectRaw(
                "id,name, code, CAST(price AS DECIMAL) as price , concat('http://$host/uploads/',image) as image "
            )
            ->paginate(10);

        return $product;
    }
}
