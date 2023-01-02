<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\SaleController;
use App\Http\Controllers\api\StoresController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user_profile', [AuthController::class, 'userProfile']);

    Route::get('get_product',[SaleController::class,'get_product']);
    //
    Route::get('get_warehouses_stock', [StoresController::class,'get_warehouses_stock']);
    Route::post('update_qty_warehouse', [StoresController::class,'update_qty_warehouse']);

    Route::get('get_products_import', [StoresController::class,'get_products_import']);
    Route::get('search_product', [StoresController::class,'search_product']);

    //
    Route::resource('transfers', \App\Http\Controllers\api\TransferController::class);

});
