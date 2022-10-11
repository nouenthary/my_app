<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['categories'] = Category::select('id','name')->where('is_active','=','1')->get();
        return view('categories.index', $data);
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
     * @return string[]
     */
    public function store(Request $request)
    {
        $names = $request->photo;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $names = date('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/categories');
            $image->move($destinationPath, $names);
        }

        $name = DB::table('tec_categories')
            ->where('name', '=', $request->name)
            ->first();

        if($name != '' && $request->id == 0){
            return ['error' => "name `$request->name` is exist..."];
        }

        $code = DB::table('tec_categories')
            ->where('code', '=', $request->code)
            ->first();

        if($code != '' && $request->id == 0){
            return ['error' => "code `$request->code` is exist..."];
        }

        $data = array(
            'code' => $request->code,
            'name' => $request->name,
            'image' => $names,
            'is_active' => 1,
            'parent_id' => (int)$request->parent_id,
            'default_store_cate' => 1,
            'store_id' => 1,
            'user_id' => auth()->user()->user_id,
            'created_at' => date('Y_m_d_H_i_s')
        );

        if($request->id == '0'){
            DB::table('tec_categories')->insert($data);
            return ['message' => 'created.'];
        }

        $data['is_active'] = $request->is_active;
        $data['image'] = $names;

        DB::table('tec_categories')->where('id',$request->id)->update($data);

        return ['message' => 'updated.'];
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }


    // get categories
    public function get_categories(Request $request)
    {
        $columns = [
            lang("image"),
            lang("code"),
            lang("category"),
        ];

        $cols = '';

        foreach ($columns as $col) {
            $cols = $cols . html('th', $col, 'class="active"');
        }

        $data = Category::orderBy('name', 'asc')->paginate($request->page_size, ['*'], 'page', $request->page);

        $value = '';

        foreach ($data as $col) {

            $json = json_encode($col);

            $row = "id='$col->id' data='$json' " ;

            $value = $value . html('tr',
                    html('td',  image("/categories/$col->image" ), 'class="text-center" width="50px"') .
                    html('td', '' . $col->code, 'width="100px"') .
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
}
