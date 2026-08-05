<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\FieldOperation;
use App\Models\MarketingFee;
use App\Models\OverheadExpense;
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

        // Metric 1: Total Pemasukan Bulan Ini
        $totalPemasukanBulanIni = Income::whereMonth('service_date', $currentMonth)
            ->whereYear('service_date', $currentYear)
            ->sum('gross_amount');

        // Metric 2: Total Overhead Berjalan (Daily Burn)
        $today = Carbon::today()->toDateString();
        $activeOverheads = OverheadExpense::where(function ($query) use ($today) {
            $query->where('is_prorated', true)
                ->whereDate('proration_start_date', '<=', $today)
                ->whereDate('proration_end_date', '>=', $today);
        })->orWhere(function ($query) use ($today) {
            $query->where('is_prorated', false)
                ->whereDate('expense_date', $today);
        })->get();

        $totalOverheadBerjalan = 0;
        foreach ($activeOverheads as $item) {
            $totalOverheadBerjalan += $item->is_prorated ? $item->daily_amount : $item->amount;
        }

        // Metric 3: Total Pekerjaan Selesai Bulan Ini
        $totalPekerjaanSelesai = FieldOperation::whereMonth('operation_date', $currentMonth)
            ->whereYear('operation_date', $currentYear)
            ->count();

        // Metric 4: Komisi Aktif (Pending)
        $komisiAktif = MarketingFee::where('status', 'Pending')->sum('fee_amount');

        // Tren Data 6 Bulan Terakhir
        $chartData = $this->getMonthlyTrendData();

        // Pending Expenses untuk Approval
        $pendingExpenses = Expense::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Recent Activity Data
        $recentFieldOperations = FieldOperation::with('technicians.employee')
            ->orderBy('operation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        $recentOverheads = OverheadExpense::orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        return view('owner.dashboard', compact(
            'totalPemasukanBulanIni',
            'totalOverheadBerjalan',
            'totalPekerjaanSelesai',
            'komisiAktif',
            'chartData',
            'pendingExpenses',
            'recentFieldOperations',
            'recentOverheads'
        ));
    }

    /**
     * Dashboard untuk Admin Operasional.
     */
    public function adminOps()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Metric 1: Total Pemasukan Bulan Ini
        $totalPemasukanBulanIni = Income::whereMonth('service_date', $currentMonth)
            ->whereYear('service_date', $currentYear)
            ->sum('gross_amount');

        // Metric 2: Total Overhead Berjalan (Daily Burn)
        $today = Carbon::today()->toDateString();
        $activeOverheads = OverheadExpense::where(function ($query) use ($today) {
            $query->where('is_prorated', true)
                ->whereDate('proration_start_date', '<=', $today)
                ->whereDate('proration_end_date', '>=', $today);
        })->orWhere(function ($query) use ($today) {
            $query->where('is_prorated', false)
                ->whereDate('expense_date', $today);
        })->get();

        $totalOverheadBerjalan = 0;
        foreach ($activeOverheads as $item) {
            $totalOverheadBerjalan += $item->is_prorated ? $item->daily_amount : $item->amount;
        }

        // Metric 3: Total Pekerjaan Selesai Bulan Ini
        $totalPekerjaanSelesai = FieldOperation::whereMonth('operation_date', $currentMonth)
            ->whereYear('operation_date', $currentYear)
            ->count();

        // Metric 4: Komisi Aktif (Pending)
        $komisiAktif = MarketingFee::where('status', 'Pending')->sum('fee_amount');

        // Tren Data 6 Bulan Terakhir
        $chartData = $this->getMonthlyTrendData();

        // Recent Activity Data
        $recentFieldOperations = FieldOperation::with('technicians.employee')
            ->orderBy('operation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        $recentOverheads = OverheadExpense::orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        $recentIncomes = Income::orderBy('service_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        $recentExpenses = Expense::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        return view('admin_ops.dashboard', compact(
            'totalPemasukanBulanIni',
            'totalOverheadBerjalan',
            'totalPekerjaanSelesai',
            'komisiAktif',
            'chartData',
            'recentFieldOperations',
            'recentOverheads',
            'recentIncomes',
            'recentExpenses'
        ));
    }

    /**
     * Helper to get trend data for the last 6 months
     */
    private function getMonthlyTrendData()
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];
        $profitData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $monthVal = $date->month;
            $yearVal = $date->year;

            // Incomes
            $inc = Income::whereMonth('service_date', $monthVal)->whereYear('service_date', $yearVal)->sum('gross_amount');
            
            // Expenses (Approved general expenses)
            $exp = Expense::where('status', 'approved')->whereMonth('created_at', $monthVal)->whereYear('created_at', $yearVal)->sum('amount');
            
            // Overhead expenses
            $oh = OverheadExpense::whereMonth('expense_date', $monthVal)->whereYear('expense_date', $yearVal)->sum('amount');
            
            // Field operations costs
            $fo = FieldOperation::whereMonth('operation_date', $monthVal)->whereYear('operation_date', $yearVal)->get()->sum(function($item) {
                return $item->total_cost;
            });

            $totalExp = $exp + $oh + $fo;
            $profit = $inc - $totalExp;

            $incomeData[] = (float)$inc;
            $expenseData[] = (float)$totalExp;
            $profitData[] = (float)$profit;
        }

        return [
            'labels' => $months,
            'income' => $incomeData,
            'expense' => $expenseData,
            'profit' => $profitData,
        ];
    }
}
