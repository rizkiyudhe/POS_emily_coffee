<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories (hanya admin)
    Route::resource('categories', CategoryController::class)->middleware('permission:manage categories');

    // Products (hanya admin)
    Route::resource('products', ProductController::class)->except(['show'])->middleware('permission:manage products');
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search')->middleware('auth');

    // Tables (hanya admin)
    Route::resource('tables', TableController::class)->middleware('permission:manage tables');

    // Users (hanya admin)
    Route::resource('users', UserController::class)->middleware('permission:manage users');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password')->middleware('permission:manage users');

    // Transactions (kasir & admin)
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index')->middleware('permission:view transactions');
        Route::get('/create', [TransactionController::class, 'create'])->name('create')->middleware('permission:create transactions');
        Route::post('/add-to-cart', [TransactionController::class, 'addToCart'])->name('add-to-cart')->middleware('permission:create transactions');
        Route::post('/update-cart', [TransactionController::class, 'updateCart'])->name('update-cart')->middleware('permission:create transactions');
        Route::delete('/remove-from-cart/{productId}', [TransactionController::class, 'removeFromCart'])->name('remove-from-cart')->middleware('permission:create transactions');
        Route::get('/cart-data', [TransactionController::class, 'cartData'])->name('cart-data')->middleware('permission:create transactions');
        Route::post('/store', [TransactionController::class, 'store'])->name('store')->middleware('permission:create transactions');
        Route::get('/receipt/{transaction}', [TransactionController::class, 'receipt'])->name('receipt')->middleware('permission:view transactions');
        Route::post('/reprint-receipt/{transaction}', [TransactionController::class, 'reprintReceipt'])->name('reprint-receipt')->middleware('permission:reprint receipt');
        Route::post('/reprint-kot/{transaction}', [TransactionController::class, 'reprintKOT'])->name('reprint-kot')->middleware('permission:reprint kot');
    });

    // Reports (hanya admin)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:view reports');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate')->middleware('permission:view reports');

    // Activity Logs (hanya admin)
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index')->middleware('permission:view logs');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
