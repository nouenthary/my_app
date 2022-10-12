<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [UserController::class,'dashboard']);

    Route::get('post_logout', [UserController::class,'logout']);

    Route::get('shop', [UserController::class,'dashboard']);
    Route::get('/dashboard', [UserController::class,'dashboard'])->name('dashboard');

    Route::get('users', [UserController::class,'users'])->name('users');
    Route::get('get_users', [UserController::class,'get_users'])->name('get_users');
    Route::get('get_user_id/{id}', [UserController::class,'get_user_id'])->name('get_user_id');
    Route::post('create_users', [UserController::class,'create_users']);
    Route::get('profile', [UserController::class,'profile'])->name('profile');
    Route::post('update_profile', [UserController::class,'update_profile'])->name('update_profile');

    Route::post('create_customer', [UserController::class,'create_customer']);

    //
    Route::resource('products', ProductController::class);
    Route::get('get_products', [ProductController::class,'get_products'])->name('get_products');


    // import
    Route::get('import', [ProductController::class,'import'])->name('import');
    Route::get('list_import', [ProductController::class,'list_import'])->name('list_import');
    Route::post('get_imports', [ProductController::class,'get_imports'])->name('get_imports');
    Route::post('get_list_imports', [ProductController::class,'get_list_imports'])->name('get_list_imports');
    Route::post('create_import', [ProductController::class,'create_import'])->name('create_import');
    Route::get('receipt', [ProductController::class,'receipt'])->name('receipt');
    // export
    Route::get('list_export', [ProductController::class,'list_export'])->name('list_export');
    Route::post('get_list_exports', [ProductController::class,'get_list_exports'])->name('get_list_exports');
    Route::post('create_export', [ProductController::class,'create_export'])->name('create_export');

    // warehouse
    Route::get('warehouse', [ProductController::class,'warehouse'])->name('warehouse');
    Route::post('get_warehouse', [ProductController::class,'get_warehouse'])->name('get_warehouse');
    Route::post('create_warehouse', [ProductController::class,'create_warehouse'])->name('create_warehouse');
    Route::post('add_stock_warehouse', [ProductController::class,'add_stock_warehouse'])->name('add_stock_warehouse');
    // adjustment
    Route::get('adjustment', [ProductController::class,'adjustment'])->name('adjustment');
    Route::post('get_adjustment', [ProductController::class,'get_adjustment'])->name('get_adjustment');
    // sale
    Route::get('list_sale', [SaleController::class,'list_sale'])->name('list_sale');
    Route::post('get_list_sale', [SaleController::class,'get_list_sale'])->name('get_list_sale');
    Route::post('return_sale', [SaleController::class,'return_sale'])->name('return_sale');
    // sale record
    Route::get('sale_record', [SaleController::class,'sale_record'])->name('sale_record');
    Route::post('get_sale_record', [SaleController::class,'get_sale_record'])->name('get_sale_record');
    // sale report
    Route::get('sale_report', [SaleController::class,'sale_report'])->name('sale_report');
    Route::post('get_sale_report', [SaleController::class,'get_sale_report'])->name('get_sale_report');
    Route::get('sale_report_daily', [SaleController::class,'sale_report_daily'])->name('sale_report_daily');
    Route::get('stock_report', [SaleController::class,'stock_report'])->name('stock_report');
    Route::post('get_stock_report', [SaleController::class,'get_stock_report'])->name('get_stock_report');
    Route::get('chart_report', [SaleController::class,'chart_report'])->name('chart_report');
    Route::get('get_chart_report', [SaleController::class,'get_chart_report'])->name('get_chart_report');
    //
    Route::get('pos', [SaleController::class,'pos'])->name('pos');
    Route::post('post_sale', [SaleController::class,'post_sale'])->name('post_sale');
    Route::get('invoice/{id}', [SaleController::class,'invoice'])->name('invoice');
    Route::get('count_stock', [SaleController::class,'count_stock'])->name('count_stock');

    // categories
    Route::resource('categories', CategoryController::class);
    Route::get('get_categories', [CategoryController::class,'get_categories']);
    //
    Route::resource('brands', BrandController::class);
    Route::get('get_brands', [BrandController::class,'get_brands']);
});

Route::get('lang/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/home', [UserController::class,'dashboard'])->name('home');

//
Route::get('ui',function (){
    return view('ui');
});

// 404
Route::get('not_found',function (){
    return view('404');
})->name('not_found');

