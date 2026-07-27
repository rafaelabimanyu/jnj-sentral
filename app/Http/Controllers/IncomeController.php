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
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_date' => 'required|date',
            'service_detail' => 'required|string',
            'gross_amount' => 'required|numeric|min:0',
        ]);

        $income = Income::create([
            'user_id' => $request->user()->id, // Admin pencatat
            'client_id' => $request->client_id,
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

        return redirect()->back()->with('success', 'Pendapatan kotor berhasil dicatat.');
    }
}
