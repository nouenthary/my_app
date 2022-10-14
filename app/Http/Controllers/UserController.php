<?php

namespace App\Http\Controllers;

use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

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
        return view('dashbaord.dashboard', $data);
    }

    public function get_users(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('users as uu')
                ->select('u.id', 'u.id', 'u.username', 'u.email', 'u.first_name', 'u.last_name', 'u.active', 'u.phone',
                    'u.avatar', 'u.gender', 'u.group_id', 'u.store_id'
                )
                ->join('tec_users as u', 'uu.user_id', '=', 'u.id')
                ->orderBy('u.first_name')
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

    function get_button_action($row)
    {
        return '
            <div class="btn-group">
            <a class="btn btn-default btn-flat btn-xs btn-pencil" id="' . $row . '"><i class="fa fa-pencil"></i></a>
            <a class="btn btn-success btn-flat btn-xs btn-eye"><i class="fa fa-eye"></i></a>
        </div>
        ';
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
        $userRecord = DB::table('tec_users')->where('username', $request->username)->first();

        if ($userRecord) {
            return ['error' => 'username has exits ...'];
        }

        $userRecord = DB::table('tec_users')->where('email', $request->email)->first();

        if ($userRecord) {
            return ['error' => 'email has exits ...'];
        }

        $users = array(
            'username' => $request->username,
            'password' => $request->password,
            'email' => $request->email,
            'active' => $request->active,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'avatar' => $request->avatar,
            'gender' => $request->gender,
            'group_id' => $request->group_id,
            'store_id' => $request->store_id,
            'created_on' => '1',
        );

        $id = DB::table('tec_users')->insertGetId($users);

        $values = array(
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_id' => $id
        );

        DB::table('users')->insert($values);

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
    public function profile(){
        return view('users.profile');
    }

    //
    public function update_profile(Request $request){

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

        $user = DB::table('users')->where('name', '=' ,$request->name)->where('user_id','!=',Auth::user()->user_id)->first();

        if($user != null){
            return redirect()->back()->with('error', 'username is exist');
        }

        DB::table('tec_users')->where('id',$request->id)->update($data);

        DB::update('update users set name = ?, updated_at = ? where user_id = ?', [$request->name , date('Y_m_d_H_i_s')  ,$request->id]);

        if($request->password != ''){
            DB::update('update users set password = ? , updated_at = ?  where user_id = ?', [Hash::make($request->password), date('Y_m_d_H_i_s') , $request->id]);
        }

        return redirect()->back()->with('success', 'update successfully.');
    }

}
