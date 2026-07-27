<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

// Rute Publik (Autentikasi)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    return redirect()->route('login');
});

// Otentikasi dan Otorisasi Peran
Route::middleware(['auth'])->group(function () {

    // Route Group untuk Owner
    Route::middleware(['role:owner'])->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'owner'])->name('dashboard');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        
        // Approval
        Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
        Route::post('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    });

    // Route Group untuk Admin Operasional
    Route::middleware(['role:admin_ops'])->prefix('admin-ops')->name('admin_ops.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'adminOps'])->name('dashboard');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');

        // Manajemen Klien
        Route::resource('clients', ClientController::class)->only(['index', 'store', 'destroy']);

        // Transaksi Pendapatan & Pengeluaran
        Route::resource('expenses', ExpenseController::class)->only(['store', 'update', 'index']);
        Route::resource('incomes', IncomeController::class)->only(['store', 'update', 'index']);
    });

    // Route Group untuk Admin Website / Developer
    Route::middleware(['role:admin_web'])->prefix('admin-web')->name('admin_web.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\UserController::class, 'index'])->name('dashboard');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::post('/users/{id}/toggle', [\App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle');
    });
});
