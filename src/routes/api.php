<?php

use App\Http\Controllers\TransferController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('mock')->group(base_path('routes/mock.php'));

Route::middleware('api')->group(function () {
    Route::get('/', fn() => response()->json(['status' => 'ok']));

    Route::group(['prefix' => 'transfer'], function () {
        Route::post('/batch', [TransferController::class, 'transferBatch']);
        Route::get('/{transfer_id}', [TransferController::class, 'getTransfer']);
    });

    Route::post('/webhook', [WebhookController::class, 'receiveWebhook']);
});
