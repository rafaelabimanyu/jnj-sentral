<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Menampilkan laporan keuangan Laba/Rugi.
     */
    public function index(Request $request)
    {
        // Filter by month and year, default to current month/year
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Menghitung Total Pendapatan bulan ini
        $totalIncomes = Income::whereMonth('service_date', $month)
            ->whereYear('service_date', $year)
            ->sum('gross_amount');

        // Menghitung Total Pengeluaran bulan ini (hanya yang approved)
        $totalExpenses = Expense::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', 'approved')
            ->sum('amount');

        // Menghitung Laba/Rugi Bersih
        $netProfit = $totalIncomes - $totalExpenses;

        // Rincian Pengeluaran per Kategori
        $expensesByCategory = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('status', 'approved')
            ->groupBy('category')
            ->get();

        // Rincian Pendapatan per Klien
        $incomesByClient = Income::select('client_name', DB::raw('SUM(gross_amount) as total'))
            ->whereMonth('service_date', $month)
            ->whereYear('service_date', $year)
            ->groupBy('client_name')
            ->get();

        return view('owner.reports', compact(
            'month', 'year', 'totalIncomes', 'totalExpenses', 'netProfit', 
            'expensesByCategory', 'incomesByClient'
        ));
    }
}
