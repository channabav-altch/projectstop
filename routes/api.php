<?php

use App\Http\Controllers\AiVisionController;

Route::post('/ai-vision', [AiVisionController::class, 'extractInvoice']);
Route::post('/extract-invoice', [App\Http\Controllers\AiVisionController::class, 'extractInvoice']);
