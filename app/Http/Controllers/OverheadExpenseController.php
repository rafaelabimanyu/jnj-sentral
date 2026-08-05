<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OverheadExpense;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OverheadExpenseController extends Controller
{
    /**
     * Tampilkan halaman utama Overhead & Manajemen Ekstra.
     */
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // Ambil pengeluaran prorata yang aktif hari ini OR pengeluaran sekali bayar hari ini
        $activeOverheads = OverheadExpense::where(function ($query) use ($today) {
            $query->where('is_prorated', true)
                ->whereDate('proration_start_date', '<=', $today)
                ->whereDate('proration_end_date', '>=', $today);
        })->orWhere(function ($query) use ($today) {
            $query->where('is_prorated', false)
                ->whereDate('expense_date', $today);
        })->get();

        $totalOverhead = 0;
        $infraTotal = 0;
        $welfareTotal = 0;
        $unexpectedTotal = 0;

        foreach ($activeOverheads as $item) {
            $dailyCost = $item->is_prorated ? $item->daily_amount : $item->amount;
            $totalOverhead += $dailyCost;

            if ($item->category === 'Infrastruktur (WiFi, Listrik, Kantor)') {
                $infraTotal += $dailyCost;
            } elseif ($item->category === 'Kesejahteraan (Family Gathering dll)') {
                $welfareTotal += $dailyCost;
            } elseif ($item->category === 'Biaya Tak Terduga (Darurat)') {
                $unexpectedTotal += $dailyCost;
            }
        }

        $infraPercentage = $totalOverhead > 0 ? round(($infraTotal / $totalOverhead) * 100, 1) : 0;
        $welfarePercentage = $totalOverhead > 0 ? round(($welfareTotal / $totalOverhead) * 100, 1) : 0;
        $unexpectedPercentage = $totalOverhead > 0 ? round(($unexpectedTotal / $totalOverhead) * 100, 1) : 0;

        $overheads = OverheadExpense::with('creator')
            ->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin_ops.overhead_expenses', compact(
            'totalOverhead',
            'infraTotal',
            'infraPercentage',
            'welfareTotal',
            'welfarePercentage',
            'unexpectedTotal',
            'unexpectedPercentage',
            'overheads'
        ));
    }

    /**
     * Simpan transaksi overhead & ekstra baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:Infrastruktur (WiFi, Listrik, Kantor),Kesejahteraan (Family Gathering dll),Biaya Tak Terduga (Darurat)',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'is_prorated' => 'required|boolean',
            'proration_days' => 'required_if:is_prorated,1|nullable|integer|in:26,30',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $receiptPath = null;

            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/receipts'), $filename);
                $receiptPath = 'uploads/receipts/' . $filename;
            }

            $isProrated = (bool) $validated['is_prorated'];
            $prorationDays = $isProrated ? (int) $validated['proration_days'] : null;
            $dailyAmount = $isProrated ? ($validated['amount'] / $prorationDays) : null;
            $startDate = $isProrated ? $validated['expense_date'] : null;
            $endDate = $isProrated ? Carbon::parse($validated['expense_date'])->addDays($prorationDays)->toDateString() : null;

            $overhead = OverheadExpense::create([
                'user_id' => $request->user()->id,
                'category' => $validated['category'],
                'title' => $validated['title'],
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null,
                'receipt_path' => $receiptPath,
                'expense_date' => $validated['expense_date'],
                'is_prorated' => $isProrated,
                'proration_days' => $prorationDays,
                'daily_amount' => $dailyAmount,
                'proration_start_date' => $startDate,
                'proration_end_date' => $endDate,
            ]);

            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'create',
                'table_name' => 'overhead_expenses',
                'record_id' => $overhead->id,
                'new_values' => $overhead->toArray(),
            ]);
        });

        return redirect()->back()->with('success', 'Pencatatan Overhead & Biaya Ekstra berhasil disimpan.');
    }
}
