<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\OrderListController;
use App\Http\Controllers\API\TakeOrderController;
use App\Http\Controllers\API\PlaceOrderController;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('orders')->group(function(){

Route::post('/', [PlaceOrderController::class, 'store']);

Route::get('/', [OrderListController::class, 'index']);

Route::patch('/{id}', [TakeOrderController::class, 'update'])->name('orders.update');

});



