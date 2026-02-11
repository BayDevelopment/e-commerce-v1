<?php

use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Route::get('/', [HomeController::class, 'index']);
Route::middleware('redirect.dashboard')->get('/', [HomeController::class, 'index']);

Route::middleware('redirect.dashboard')->get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::middleware('redirect.dashboard')->get('/produk/{slug}', [HomeController::class, 'details'])->name('products.detail');


/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Guest Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->prefix('auth')->group(function () {

    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProses'])->name('login.proses');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerProses'])->name('register.proses');
});

Route::post('/auth/logout', [AuthController::class, 'logoutProses'])
    ->middleware('auth')
    ->name('logout.proses');


/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {

    if (! $request->hasValidSignature()) {
        abort(403, 'Invalid or expired verification link.');
    }

    $user = User::findOrFail($id);

    Auth::login($user);

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('customer.dashboard')
        ->with('success', 'Email berhasil diverifikasi 🎉');
})->middleware('signed')->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Link verifikasi dikirim ulang.');
})->middleware('auth')->name('verification.send');


/*
|--------------------------------------------------------------------------
| CUSTOMER AREA (WAJIB LOGIN + ROLE CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        // Dashboard (boleh walau belum verified)
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/product', [ProductController::class, 'productsCustomer'])
            ->name('product');

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan');

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders');

        // Checkout wajib verified
        Route::middleware('verified')->group(function () {
            Route::get('/checkout', [CheckoutController::class, 'index'])
                ->name('checkout');
        });
    });
