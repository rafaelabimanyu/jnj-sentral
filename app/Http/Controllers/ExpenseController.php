<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;
use App\Models\AuditLog;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Menyimpan transaksi pengeluaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'income_id' => 'nullable|exists:incomes,id',
            'client_id' => 'nullable|exists:clients,id',
            'category' => 'required|in:ads,entertain,infrastructure,fuel_parking,technician_wage,bonus_location,bonus_night,marketing_fee,welfare,unexpected',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        // Aturan Bisnis 1: Tentukan Status Approval
        $status = 'approved';

        if ($request->category === 'unexpected') {
            $status = 'pending';
        } elseif ($request->category === 'marketing_fee' && $request->filled('income_id')) {
            $income = Income::find($request->income_id);
            if ($income && $income->gross_amount > 0) {
                // Hitung persentase fee marketing terhadap gross_amount proyek
                $percentage = ($request->amount / $income->gross_amount) * 100;
                if ($percentage > 20) {
                    $status = 'pending';
                }
            }
        }

        // Simpan data pengeluaran
        $expense = Expense::create([
            'user_id' => $request->user()->id, // Admin pencatat
            'income_id' => $request->income_id,
            'client_id' => $request->client_id,
            'category' => $request->category,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => $status,
        ]);

        // Catat aktivitas di AuditLog
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'create',
            'table_name' => 'expenses',
            'record_id' => $expense->id,
            'new_values' => $expense->toArray(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pengeluaran berhasil dicatat.', 'data' => $expense], 201);
        }

        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    /**
     * Memperbarui transaksi pengeluaran.
     */
    public function update(Request $request, Expense $expense)
    {
        // Aturan Bisnis 2: Pengecekan Batas Waktu Koreksi 24 Jam
        if ($expense->created_at->diffInHours(Carbon::now()) > 24) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Batas waktu koreksi telah habis. Data di atas 24 jam tidak dapat diubah.'
                ], 403);
            }
            abort(403, 'Batas waktu koreksi telah habis (Maksimal 24 jam).');
        }

        $request->validate([
            'income_id' => 'nullable|exists:incomes,id',
            'client_id' => 'nullable|exists:clients,id',
            'category' => 'required|in:ads,entertain,infrastructure,fuel_parking,technician_wage,bonus_location,bonus_night,marketing_fee,welfare,unexpected',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $oldValues = $expense->toArray();

        // Update data pengeluaran sementara
        $expense->fill($request->only(['income_id', 'client_id', 'category', 'amount', 'description']));

        // Recalculate status jika ada perubahan kategori/nilai
        $status = 'approved';
        if ($expense->category === 'unexpected') {
            $status = 'pending';
        } elseif ($expense->category === 'marketing_fee' && $expense->income_id) {
            $income = Income::find($expense->income_id);
            if ($income && $income->gross_amount > 0) {
                $percentage = ($expense->amount / $income->gross_amount) * 100;
                if ($percentage > 20) {
                    $status = 'pending';
                }
            }
        }
        
        $expense->status = $status;
        $expense->save();

        // Catat perubahan di AuditLog
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'update',
            'table_name' => 'expenses',
            'record_id' => $expense->id,
            'old_values' => $oldValues,
            'new_values' => $expense->fresh()->toArray(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pengeluaran berhasil diperbarui.', 'data' => $expense]);
        }

        return redirect()->back()->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    /**
     * Menyetujui transaksi pengeluaran pending (Khusus Owner).
     */
    public function approve(Request $request, Expense $expense)
    {
        $oldValues = $expense->toArray();

        $expense->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'approve',
            'table_name' => 'expenses',
            'record_id' => $expense->id,
            'old_values' => $oldValues,
            'new_values' => $expense->fresh()->toArray(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Transaksi berhasil disetujui.']);
        }

        return redirect()->back()->with('success', 'Transaksi berhasil disetujui.');
    }

    /**
     * Menolak transaksi pengeluaran pending (Khusus Owner).
     */
    public function reject(Request $request, Expense $expense)
    {
        $oldValues = $expense->toArray();

        $expense->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'reject',
            'table_name' => 'expenses',
            'record_id' => $expense->id,
            'old_values' => $oldValues,
            'new_values' => $expense->fresh()->toArray(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Transaksi berhasil ditolak.']);
        }

        return redirect()->back()->with('success', 'Transaksi berhasil ditolak.');
    }
}
