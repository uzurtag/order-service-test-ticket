<?php

use App\Order\Presentation\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('/order')->group(function () {
    Route::post('/', [OrderController::class, 'create']);
    Route::get('/latest', [OrderController::class, 'last']);
});
