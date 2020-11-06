<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'UserController@logout');
    Route::get('/user/list', 'UserController@userList');
    Route::post('/user/delete/{id}', 'UserController@userDelete');


    Route::get('/purchase-order', 'PurchaseOrderController@index');
    Route::get('/purchase-order/{id}', 'PurchaseOrderController@show');
    Route::post('/purchase-order/add', 'PurchaseOrderController@store');
    Route::post('/purchase-order/edit/{id}', 'PurchaseOrderController@update');
    Route::post('/purchase-order/delete/{id}', 'PurchaseOrderController@delete');
    Route::post('/purchase-order/activate/{id}', 'PurchaseOrderController@activate');
    Route::post('/serial-number/activate/{serial}', 'SerialNumberController@activate');
    Route::post('/serial-number/add', 'SerialNumberController@store');
    Route::get('/serial-number/delete/{id}', 'SerialNumberController@delete');
    Route::get('/serial-number', 'SerialNumberController@index');
    Route::get('/serial-number/purchase-order/{purchase_order_number}', 'SerialNumberController@showForPurchaseOrder');
    Route::get('warranty/', 'WarrantyController@index');
    Route::post('warranty/add', 'WarrantyController@store');
    Route::post('warranty/update-status/{id}', 'WarrantyController@updateState');
    Route::get('warranty/{id}', 'WarrantyController@show');
    Route::post('warranty/delete/{id}', 'WarrantyController@destroy');
    Route::post('warranty/fixed-warranty', 'WarrantyController@fixedWarranty');
    Route::post('warranty/not-fixed-warranty', 'WarrantyController@notFixedWarranty');
    Route::post('warranty/void-warranty', 'WarrantyController@voidWarranty');
});

Route::post('/login', 'UserController@login');
Route::post('/register', 'UserController@register');
Route::get('/hello', 'UserController@sayHello');
Route::post('/register_other', 'UserController@registerOther');



//Fallback function
Route::fallback(function () {
    return response()->json(['message' => 'Page not found', 'status' => 404]);
});
