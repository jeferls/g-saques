<?php

use App\Http\Controllers\TesteController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok']));
Route::get('/teste', [TesteController::class, 'teste']);
