<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/produk', [ProductController::class, 'products'])->name('products.index');
Route::get('/produk/{slug}', [HomeController::class, 'details'])->name('products.detail');

// login & logout pelanggan
Route::get('/auth/login', [AuthController::class, 'index'])->name('login');
Route::post('/auth/login', [AuthController::class, 'loginProses'])
    ->name('login.proses');
Route::get('/auth/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/register', [AuthController::class, 'RegisterProses'])
    ->name('register.proses');
Route::post('/auth/logout', [AuthController::class, 'logoutProses'])
    ->name('logout.proses');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {

    if (! $request->hasValidSignature()) {
        abort(403, 'Invalid or expired verification link.');
    }

    $user = User::findOrFail($id);

    Auth::login($user);

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('dashboard')
        ->with('success', 'Email berhasil diverifikasi 🎉');
})->middleware('signed')->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Link verifikasi dikirim ulang.');
})->middleware(['auth'])->name('verification.send');

// cegah jika blom verifikasi akun tidak bisa membeli 
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/checkout', [CheckoutController::class, 'index']);
});

// dashboard yang sudah verified
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});
