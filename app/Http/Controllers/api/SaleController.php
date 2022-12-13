<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function get_product()
    {
        $perPage = 10;
        $page = 1;

        if(request()->get('per_page') != '') $perPage = request()->get('per_page');

        if(request()->get('page') != '') $page = request()->get('page');

        $host = $_SERVER['HTTP_HOST'];

        return DB::table('tec_products')
            ->selectRaw(
                "id, name, code, CAST(price AS DECIMAL) as price , IFNULL(concat('http://$host/uploads/',image), concat('http://$host/uploads/7527dd8c427584bc7f1942afeae252d1.jpg'))  as image "
            )
            ->orderByDesc('name')
            ->paginate($perPage, '','',$page);
    }
}
