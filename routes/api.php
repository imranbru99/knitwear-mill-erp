<?php

use App\Models\BuyerOrder;
use App\Models\Issue;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/orders', function (Request $request) {
        return BuyerOrder::query()->latest('id')->limit(50)->get();
    });
    Route::get('/orders/{order}', function (BuyerOrder $order) {
        return $order->load('style', 'productionOrders.tnaTasks');
    });
    Route::get('/issues', function () {
        return Issue::query()->where('status', 'open')->latest('id')->limit(50)->get();
    });
    Route::get('/production/{order}', function (ProductionOrder $order) {
        return $order->load('tnaTasks', 'materialRequirements');
    });
});
