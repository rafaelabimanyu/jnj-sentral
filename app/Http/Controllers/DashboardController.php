<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Client;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk Owner (Eksekutif).
     */
    public function owner()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Perhitungan SUM riil bulan berjalan
        $grossIncome = Income::whereMonth('service_date', $currentMonth)
            ->whereYear('service_date', $currentYear)
            ->sum('gross_amount');

        $totalExpenses = Expense::where('status', 'approved')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('amount');

        $netIncome = $grossIncome - $totalExpenses;

        $financialMetrics = (object) [
            'gross_income' => $grossIncome,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
        ];

        // Ambil data expenses pending riil
        $pendingExpenses = Expense::where('status', 'pending')
            ->with(['creator', 'client', 'income'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('owner.dashboard', compact('financialMetrics', 'pendingExpenses'));
    }

    /**
     * Dashboard untuk Admin Operasional.
     */
    public function adminOps()
    {
        $clients = Client::orderBy('name', 'asc')->get();
        
        $today = Carbon::today()->toDateString();

        // Ambil data biaya riil hari ini
        $todayExpenses = Expense::whereDate('created_at', $today)
            ->with(['client', 'income'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil data pendapatan kotor riil hari ini
        $todayIncomes = Income::whereDate('service_date', $today)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin_ops.dashboard', compact('clients', 'todayExpenses', 'todayIncomes'));
    }
}
