<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\AuditLog;

class IncomeController extends Controller
{
    /**
     * Menyimpan data pendapatan kotor (gross income) baru.
     */
    public function store(Request $request)
    {
        // Sanitize gross_amount from Rupiah format (e.g. "Rp 25.000.000" -> 25000000)
        if ($request->has('gross_amount')) {
            $rawAmount = $request->gross_amount;
            $cleanAmount = str_replace(['Rp', '.', ' '], '', $rawAmount);
            $cleanAmount = str_replace(',', '.', $cleanAmount);
            $request->merge([
                'gross_amount' => is_numeric($cleanAmount) ? (float) $cleanAmount : $rawAmount
            ]);
        }

        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_category' => 'required|string|in:B2B - F&B,B2B - Hospital/Medis,B2B - Pemerintahan,Residensial/Rumah Tangga',
            'service_date' => 'required|date',
            'service_detail' => 'required|string',
            'gross_amount' => 'required|numeric|min:0',
        ]);

        $income = Income::create([
            'user_id' => $request->user()->id, // Admin pencatat
            'client_name' => $request->client_name,
            'client_category' => $request->client_category,
            'service_date' => $request->service_date,
            'service_detail' => $request->service_detail,
            'gross_amount' => $request->gross_amount,
        ]);

        // Catat ke AuditLog
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'create',
            'table_name' => 'incomes',
            'record_id' => $income->id,
            'new_values' => $income->toArray(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pendapatan berhasil dicatat.', 'data' => $income], 201);
        }

        return redirect()->route('admin_ops.transactions', ['tab' => 'income'])->with('success', 'Pendapatan kotor berhasil dicatat.');
    }
}
