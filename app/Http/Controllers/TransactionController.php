<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi (Pengeluaran dan Pendapatan) dengan pagination.
     */
    public function index()
    {
        // Ambil data pengeluaran dan pendapatan, diurutkan terbaru
        $expenses = Expense::with(['client', 'creator'])->orderBy('created_at', 'desc')->paginate(10, ['*'], 'expenses_page');
        $incomes = Income::with(['client', 'creator'])->orderBy('service_date', 'desc')->paginate(10, ['*'], 'incomes_page');

        return view('admin_ops.transactions', compact('expenses', 'incomes'));
    }
}
