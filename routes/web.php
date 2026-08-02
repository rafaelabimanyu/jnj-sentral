<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MarketingFeeController;
use App\Http\Controllers\MarketerController;
use App\Http\Controllers\OverheadExpenseController;
use App\Http\Controllers\FieldOperationExpenseController;
use App\Http\Controllers\EmployeeController;

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

        // Module 1: Manajemen Komisi & Fee Marketing
        Route::get('/marketing-fees', [MarketingFeeController::class, 'index'])->name('marketing_fees.index');
        Route::post('/marketing-fees', [MarketingFeeController::class, 'store'])->name('marketing_fees.store');
        Route::patch('/marketing-fees/{marketingFee}/pay', [MarketingFeeController::class, 'markAsPaid'])->name('marketing_fees.pay');
        Route::resource('marketers', MarketerController::class)->only(['index', 'store', 'update', 'destroy']);

        // Module 2: Overhead & Manajemen Ekstra
        Route::get('/overhead-expenses', [OverheadExpenseController::class, 'index'])->name('overhead_expenses.index');
        Route::post('/overhead-expenses', [OverheadExpenseController::class, 'store'])->name('overhead_expenses.store');

        // Module 3: Operasional Lapangan & Karyawan
        Route::get('/field-operations', [FieldOperationExpenseController::class, 'index'])->name('field_operations.index');
        Route::get('/field-operations/create', [FieldOperationExpenseController::class, 'create'])->name('field_operations.create');
        Route::post('/field-operations', [FieldOperationExpenseController::class, 'store'])->name('field_operations.store');
        Route::resource('employees', EmployeeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('/technicians', function () {
            return redirect()->route('admin_ops.employees.index');
        });
    });

    // Route Group untuk Admin Website / Developer
    Route::middleware(['role:admin_web'])->prefix('admin-web')->name('admin_web.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\UserController::class, 'index'])->name('dashboard');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::post('/users/{id}/toggle', [\App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle');
    });
});
