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
        $totalOverhead = OverheadExpense::sum('amount');

        $infraTotal = OverheadExpense::where('category', 'Infrastruktur (WiFi, Listrik, Kantor)')->sum('amount');
        $welfareTotal = OverheadExpense::where('category', 'Kesejahteraan (Family Gathering dll)')->sum('amount');
        $unexpectedTotal = OverheadExpense::where('category', 'Biaya Tak Terduga (Darurat)')->sum('amount');

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
        ]);

        DB::transaction(function () use ($request, $validated) {
            $receiptPath = null;

            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/receipts'), $filename);
                $receiptPath = 'uploads/receipts/' . $filename;
            }

            $overhead = OverheadExpense::create([
                'user_id' => $request->user()->id,
                'category' => $validated['category'],
                'title' => $validated['title'],
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null,
                'receipt_path' => $receiptPath,
                'expense_date' => $validated['expense_date'],
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
