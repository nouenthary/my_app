<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('stores.index');
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
            $destinationPath = public_path('/uploads/stores');
            $image->move($destinationPath, $names);
        }

        $data = [
            'name' => $request->name,
            'code' => $request->code,
            'logo' => $names,
            'phone' => $request->phone,
            'address1' => $request->address,
            'city' => $request->city,
            'id' => $request->id
        ];

        if($request->id > 0){
            DB::table('tec_stores')->where('id',$request->id)->update($data);
        }

        if($request->id == 0){
            DB::table('tec_stores')->updateOrInsert($data);
        }

        Utils::add_product_to_stock();

        return [
            'message' => 'success.'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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

    public function get_stores(Request $request)
    {
        $columns = [
            lang("image"),
            lang("code"),
            lang("branch"),
            lang('phone'),
            lang('city'),
            lang('address')
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . html('th', $col, 'class="active"');
        }

        $data = Store::orderBy('name', 'asc')->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        foreach ($data as $col) {
            $json = json_encode($col);
            $row = "id='$col->id' data='$json' ";
            $value = $value . html('tr',
                    html('td', image("/stores/$col->logo", "25px"), 'class="text-center" width="50px"') .
                    html('td', '' . $col->code, 'width="100px"') .
                    html('td', '' . $col->name, '') .
                    html('td', '' . $col->phone, '') .
                    html('td', '' . $col->city, '') .
                    html('td', '' . $col->address1, '')
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
