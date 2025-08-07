<?php

use App\Http\Controllers\TesteController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('/health', fn () => response()->json(['status' => 'ok']));
    Route::get('/teste', [TesteController::class, 'teste']);
});
