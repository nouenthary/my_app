<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\Facades\DNS2DFacade;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('brands.index');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $names = $request->photo;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $names = date('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/brands');
            $image->move($destinationPath, $names);
        }

        $name = DB::table('brands')
            ->where('brand_name', '=', $request->name)
            ->first();

        if($name != '' && $request->id == 0){
            return ['error' => "name `$request->name` is exist..."];
        }

        $code = DB::table('brands')
            ->where('code', '=', $request->code)
            ->first();

        if($code != '' && $request->id == 0){
            return ['error' => "code `$request->code` is exist..."];
        }

        $data = array(
            'code' => $request->code,
            'brand_name' => $request->name,
            'image' => $names,
            'is_active' => 1,
            'user_id' => auth()->user()->user_id,
            'created_at' => date('Y_m_d_H_i_s')
        );

        if($request->id == '0'){
            DB::table('brands')->insert($data);
            return ['message' => 'created.'];
        }

        $data['is_active'] = $request->is_active;
        $data['image'] = $names;

        DB::table('brands')->where('id',$request->id)->update($data);

        return ['message' => 'updated.'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        //
    }

    // get brands
    public function get_brands(Request $request)
    {
        $columns = [
            lang("image"),
            lang("code"),
            '',
            lang("brand"),
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . html('th', $col, 'class="active"');
        }

        $data = Brand::orderBy('brand_name', 'asc')->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        foreach ($data as $col) {

            $json = json_encode($col);

            $row = "id='$col->id' data='$json' " ;
            $bar = 'data:image/svg+xml;base64,PHN2ZyBpZD0iYmFyY29kZSIgd2lkdGg9IjI4OHB4IiBoZWlnaHQ9IjE0MnB4IiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDI4OCAxNDIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmVyc2lvbj0iMS4xIiBzdHlsZT0idHJhbnNmb3JtOiB0cmFuc2xhdGUoMCwwKSI+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjI4OCIgaGVpZ2h0PSIxNDIiIHN0eWxlPSJmaWxsOiNmZmZmZmY7Ii8+PGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTAsIDEwKSIgc3R5bGU9ImZpbGw6IzAwMDAwMDsiPjxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSI0IiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjYiIHk9IjAiIHdpZHRoPSIyIiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjEyIiB5PSIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSIyMiIgeT0iMCIgd2lkdGg9IjQiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iMzIiIHk9IjAiIHdpZHRoPSIyIiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjM2IiB5PSIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSI0NCIgeT0iMCIgd2lkdGg9IjIiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iNTQiIHk9IjAiIHdpZHRoPSI0IiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjYwIiB5PSIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSI2NiIgeT0iMCIgd2lkdGg9IjQiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iNzIiIHk9IjAiIHdpZHRoPSI0IiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjgwIiB5PSIwIiB3aWR0aD0iNCIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSI4OCIgeT0iMCIgd2lkdGg9IjgiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iMTAwIiB5PSIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSIxMDQiIHk9IjAiIHdpZHRoPSIyIiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjExMCIgeT0iMCIgd2lkdGg9IjIiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iMTE4IiB5PSIwIiB3aWR0aD0iOCIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSIxMjgiIHk9IjAiIHdpZHRoPSIyIiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjEzMiIgeT0iMCIgd2lkdGg9IjIiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iMTM4IiB5PSIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSIxNDQiIHk9IjAiIHdpZHRoPSI4IiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjE1NCIgeT0iMCIgd2lkdGg9IjQiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iMTYyIiB5PSIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSIxNjYiIHk9IjAiIHdpZHRoPSIyIiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjE3NiIgeT0iMCIgd2lkdGg9IjIiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iMTg2IiB5PSIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSIxOTIiIHk9IjAiIHdpZHRoPSI0IiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjE5OCIgeT0iMCIgd2lkdGg9IjQiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iMjA2IiB5PSIwIiB3aWR0aD0iNCIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSIyMTIiIHk9IjAiIHdpZHRoPSI0IiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjIyMCIgeT0iMCIgd2lkdGg9IjIiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iMjI4IiB5PSIwIiB3aWR0aD0iMiIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSIyMzYiIHk9IjAiIHdpZHRoPSI0IiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjI0MiIgeT0iMCIgd2lkdGg9IjQiIGhlaWdodD0iMTAwIi8+PHJlY3QgeD0iMjUyIiB5PSIwIiB3aWR0aD0iNiIgaGVpZ2h0PSIxMDAiLz48cmVjdCB4PSIyNjAiIHk9IjAiIHdpZHRoPSIyIiBoZWlnaHQ9IjEwMCIvPjxyZWN0IHg9IjI2NCIgeT0iMCIgd2lkdGg9IjQiIGhlaWdodD0iMTAwIi8+PHRleHQgc3R5bGU9ImZvbnQ6IDIwcHggbW9ub3NwYWNlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiB4PSIxMzQiIHk9IjEyMiI+SGkgd29ybGQhPC90ZXh0PjwvZz48L3N2Zz4=';
            $value = $value . html('tr',
                    html('td',  image("/brands/$col->image" ,"50px")  , 'class="text-center" width="50px"') .
                    html('td', '<img width="50px" src="data:image/png;base64,' . DNS2DFacade::getBarcodePNG($col->code, 'QRCODE') . '" alt="barcode"   />' , 'width="100px"') .
                    html('td', '' . $col->code, 'width="100px"') .
                    html('td', '' . $col->brand_name, '')
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
