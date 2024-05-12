<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/{id}', [OrderController::class, 'show']);
Route::post('orders', [OrderController::class, 'store']);
Route::put('orders/{id}', [OrderController::class, 'update']);
Route::delete('orders/{id}', [OrderController::class, 'destroy']);
Route::get('orders/{id}/status', [OrderController::class, 'getOrderStatus']);

