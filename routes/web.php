<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdmissionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/userList', function () {
    return Inertia::render('UserList');
})->middleware(['auth', 'verified'])->name('userList');

Route::resource('/users', UserController::class);
Route::put('/users/{id}', [UserController::class, 'update']);

// Generate encrypted Url
//Route::post('/generate-encrypted-mobile-url', [AdmissionController::class, 'generateEncryptedMobileUrl']);

// Define the route for rendering mobile screen
Route::get('/admission/mobile/{encrypted_admission_no}', [AdmissionController::class, 'gotoMobileMenu']);

//Route::put('/admission/updateQrCode/{admission_no}', [AdmissionController::class, 'updateQrCode']);

Route::get('/qrCodeError', function () {
    return Inertia::render('MobileApp/QrCodeError');
})->name('qrCodeError');


require __DIR__.'/auth.php';
