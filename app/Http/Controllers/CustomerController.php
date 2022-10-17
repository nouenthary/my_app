<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customers.index');
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
        $customer = [
            'name' => $request->name,
            'cf1' => $request->address,
            'cf2' => '0',
            'phone' => $request->phone,
            'email' => $request->email,
            'store_id' => Utils::store_id(),
            'status' => $request->status,
            'hide' => 0
        ];

        if ($request->id == 0) {
            DB::table('tec_customers')->insert($customer);
        }

        if ($request->id > 0) {
            DB::table('tec_customers')->where('id', $request->id)->update($customer);
        }

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

    public function get_customers(Request $request)
    {
        $columns = [
            lang("customer"),
            lang('phone'),
            lang("email"),
            lang('address'),
            lang('status')
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . html('th', $col, 'class="active"');
        }

        $data = Customer::orderBy('name', 'asc')->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        foreach ($data as $col) {
            $json = json_encode($col);
            $row = "id='$col->id' data='$json' ";
            $value = $value . html('tr',
                    html('td', '' . $col->name, '') .
                    html('td', '' . $col->phone, '') .
                    html('td', '' . $col->email, '') .
                    html('td', '' . $col->cf1, '') .
                    html('td', '' . $col->status, '')
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
}
