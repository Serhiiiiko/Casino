<?php

use App\Http\Controllers\Api\Gateways\AntrpayController;
use Illuminate\Support\Facades\Route;

Route::post('antrpay/session', [AntrpayController::class, 'getSessionRequest']);
Route::post('antrpay/publickey', [AntrpayController::class, 'getPublicKey']);

Route::prefix('webhooks')->group(function () {
    Route::post('antrpay', [AntrpayController::class, 'webhooks']);
});
