<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmissionController;

Route::post('/generate-encrypted-mobile-url', [AdmissionController::class, 'generateEncryptedMobileUrl']);

Route::post('/update-qrcode-mobile-admission', [AdmissionController::class, 'updateQrCode']);