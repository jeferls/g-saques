<?php

use App\Http\Controllers\mocks\MockTransferController;
use Illuminate\Support\Facades\Route;

Route::post('/postTransferPagarme/transfers', [MockTransferController::class, 'postTransferPagarme']);
