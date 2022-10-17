<?php

namespace App\Http\Controllers;

use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // INSERT INTO users (user_id, name, password, email)

    // SELECT id, username, 'admin@123' ,email FROM tec_users

    public function __construct()
    {
        $this->middleware('auth');
    }

    function dashboard()
    {
        $utils = new Utils();
        $store_id = $utils->get_store_id();
        $data['qty_in'] = DB::select("select COALESCE(SUM(qty),0) qty from tec_stock_in where fk_store_id = '$store_id'");
        $data['qty_out'] = DB::select("select COALESCE(SUM(qty),0) qty from tec_stock_out where fk_store_id = '$store_id'");
        $data['qty_sold'] = DB::select("
            SELECT COALESCE(SUM(tec_sale_items.quantity),0) qty
            FROM `tec_sales`
            INNER JOIN tec_sale_items
            ON tec_sales.id = tec_sale_items.sale_id
            WHERE tec_sales.store_id = '$store_id'
        ");
        $data['qty_balance'] = $data['qty_in'][0]->qty - $data['qty_out'][0]->qty - $data['qty_sold'][0]->qty;

        return view('dashboard.dashboard', $data);
    }

    public function get_chart_sale()
    {
        $utils = new Utils();
        $store_id = $utils->get_store_id();

        $start = date("Y-m") . '-01 00:00:00';

        $end = date("Y-m-d") . ' 23:59:00';


        $sold = [];

        $sql = "

            SELECT
                DATE_FORMAT(tec_sales.date, \"%Y-%m-%d\") date,
                SUM(tec_sale_items.quantity) quantity
            FROM
                tec_sales
                INNER JOIN tec_sale_items ON tec_sales.id = tec_sale_items.sale_id

                WHERE store_id = $store_id

                AND date BETWEEN '$start' AND '$end'

                GROUP BY DATE_FORMAT(date, \"%Y-%m-%d\")

                Order By date desc limit 15

        ";

        $sale = DB::select($sql);
        $qty = 0;
        foreach ($sale as $row) {
            $qty = $qty + (int)$row->quantity;
            array_push($sold, [$row->date, (int)$row->quantity]);
        }

        return [
            'sold' => $sold,
            'total' => $qty
        ];
    }

    public function get_users(Request $request)
    {
        $columns = [
            lang("image"),
            lang("name"),
            lang("username"),
            lang('phone'),
            lang("email"),
            lang('gender'),
            lang('status'),
            lang('commission'),
            lang('branch')
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . html('th', $col, 'class="active"');
        }

        $data = DB::table('tec_users')
            ->join('tec_stores', 'tec_stores.id', '=', 'tec_users.store_id')
            ->join('tec_permission', 'tec_permission.user_id', '=', 'tec_users.id')
            ->select(
                'tec_users.id',
                'tec_users.first_name',
                'tec_users.last_name',
                'tec_users.phone',
                'tec_users.email',
                'tec_users.avatar',
                'tec_users.active',
                'tec_users.salt',
                'tec_users.username',
                'tec_stores.id as store_id',
                'tec_stores.name',
                'tec_users.gender',
                'tec_users.group_id',
                'tec_users.password',
                'tec_permission.permission',
                'tec_permission.product',
                'tec_permission.category',
                'tec_permission.import',
                'tec_permission.export',
                'tec_permission.sale',
                'tec_permission.user',
                'tec_permission.setting',
                'tec_permission.report',
                'tec_permission.pos',
                'tec_permission.dashboard',
                'tec_permission.imp',
                'tec_permission.ex'
            )
            ->orderBy('username', 'asc')
            ->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        foreach ($data as $col) {
            $json = json_encode($col);
            $row = "id='$col->id' data='$json' ";
            $value = $value . html('tr',
                    html('td', image("/users/$col->avatar", "25px"), 'class="text-center" width="50px"') .
                    html('td', '' . $col->first_name . ' ' . $col->last_name, '') .
                    html('td', '' . $col->username, '') .
                    html('td', '' . $col->phone, '') .
                    html('td', '' . $col->email, '') .
                    html('td', '' . $col->gender, '') .
                    html('td', '' . $col->active, '') .
                    html('td', '' . $col->salt, '') .
                    html('td', '' . $col->name, '')
                    , $row);
        }

        $footer = '';

        $table = html('table', html('tr', $cols, '') . html('tr', $value, '') . $footer, 'class="table table-bordered table-hover" id="table" ');

        return [
            'table' => $table,
            'page' => $data->currentPage(),
            'per_page' => $data->lastPage(),
            'total' => $data->total(),
        ];
    }

    // users
    public function users()
    {
        $data['store'] = DB::table('tec_stores')
            ->where('city', '<>', 'None')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $data['permission'] = DB::table('tec_groups')
            ->select('id', 'name', 'description')
            ->orderBy('name')
            ->get();

        return view('users.index', $data);
    }

    public function create_users(Request $request)
    {
        $names = $request->photo;
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $names = date('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/users');
            $image->move($destinationPath, $names);
        }

        $users = array(
            'username' => $request->username,
            'password' => $request->password,
            'email' => $request->email,
            'active' => $request->active,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'avatar' => $names,
            'gender' => $request->gender,
            'group_id' => $request->group_id,
            'store_id' => $request->store_id,
            'created_on' => '1',
            'salt' => $request->salt
        );

        $permission = [
            'permission' => $request->Permission == '' ? 0 : 1,
            'product' => $request->Product == '' ? 0 : 1,
            'category' => $request->Category == '' ? 0 : 1,
            'import' => $request->Import == '' ? 0 : 1,
            'export' => $request->Export == '' ? 0 : 1,
            'sale' => $request->Sale == '' ? 0 : 1,
            'user' => $request->User == '' ? 0 : 1,
            'setting' => $request->Setting == '' ? 0 : 1,
            'report' => $request->Report == '' ? 0 : 1,
            'pos' => $request->POS == '' ? 0 : 1,
            'dashboard' => $request->Dashboard == '' ? 0 : 1,
            'ex' => $request->EX == '' ? 0 : 1,
            'imp' => $request->IMP == '' ? 0 : 1,
        ];

        if ($request->id == 0) {

            $userRecord = DB::table('tec_users')->where('username', $request->username)->first();

            if ($userRecord) {
                return ['error' => 'username has exits ...'];
            }

            $userRecord = DB::table('tec_users')->where('email', $request->email)->first();

            if ($userRecord) {
                return ['error' => 'email has exits ...'];
            }

            $id = DB::table('tec_users')->insertGetId($users);

            $values = array(
                'name' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_id' => $id
            );

            DB::table('users')->insert($values);

            $permission['user_id'] = $id;
            DB::table('tec_permission')->insert($permission);
        }

        if($request->id > 0){

            $userRecord = DB::table('tec_users')->where('username', $request->username)->where('id' ,'!=' , $request->id)->first();

            if ($userRecord) {
                return ['error' => 'username has exits ...'];
            }

            $userRecord = DB::table('tec_users')->where('email', $request->email)->where('id' ,'!=' , $request->id)->first();

            if ($userRecord) {
                return ['error' => 'email has exits ...'];
            }
            DB::table('tec_users')->where('id', $request->id)->update($users);


            DB::table('users')->where('user_id', $request->id)->update([
                'name' => $request->username
            ]);

            $permission['user_id'] = $request->id;
            DB::table('tec_permission')->where('user_id',$request->id)->update($permission);
        }

        return ['message' => 'successfully'];
    }

    public function get_user_id(Request $request)
    {
        $userRecord = DB::table('tec_users')->where('id', $request->id)->first();
        return $userRecord;
    }

    //
    public function login()
    {
        return view('auth.login');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

    // add customer
    public function create_customer(Request $request)
    {
        $utils = new Utils();

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status,
            'cf1' => '0',
            'cf2' => '0',
            'hide' => '1',
            'store_id' => $utils->get_store_id()
        ];

        $id = DB::table('tec_customers')->insertGetId($data);

        return $id;
    }

    // profile
    public function profile()
    {
        return view('users.profile');
    }

    //
    public function update_profile(Request $request)
    {

        $names = $request->photo;
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $names = date('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/');
            $image->move($destinationPath, $names);
        }

        $data = [
            'username' => $request->name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'avatar' => $names,
            //'password' => 'deae17c9925f9da5551724805a5ff480557816d6',
        ];

        $user = DB::table('users')->where('name', '=', $request->name)->where('user_id', '!=', Auth::user()->user_id)->first();

        if ($user != null) {
            return redirect()->back()->with('error', 'username is exist');
        }

        DB::table('tec_users')->where('id', $request->id)->update($data);

        DB::update('update users set name = ?, updated_at = ? where user_id = ?', [$request->name, date('Y_m_d_H_i_s'), $request->id]);

        if ($request->password != '') {
            DB::update('update users set password = ? , updated_at = ?  where user_id = ?', [Hash::make($request->password), date('Y_m_d_H_i_s'), $request->id]);
        }

        return redirect()->back()->with('success', 'update successfully.');
    }

}
