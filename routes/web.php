<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('products', ProductController::class);
Route::get('/transactions/adjustments/create', [InventoryTransactionController::class, 'adjustment'])
    ->name('transactions.adjustment.create');
Route::post('/transactions/adjustments', [InventoryTransactionController::class, 'storeAdjustment'])
    ->name('transactions.adjustment.store');
Route::resource('transactions', InventoryTransactionController::class)
    ->parameters(['transactions' => 'inventoryTransaction'])
    ->except(['edit', 'update', 'destroy']);

Route::get('/api/dashboard/stats', [DashboardController::class, 'stats']);
Route::get('/api/products/low-stock', [DashboardController::class, 'lowStock']);
Route::get('/api/transactions/recent', [DashboardController::class, 'recentTransactions']);

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/transactions.csv', [ReportController::class, 'export'])->name('reports.export');
Route::get('/notifications', [DashboardController::class, 'lowStockPage'])->name('notifications.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
