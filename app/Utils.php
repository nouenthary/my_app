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
}
