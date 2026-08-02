<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FieldOperation;
use App\Models\FieldOperationTechnician;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FieldOperationExpenseController extends Controller
{
    /**
     * Tampilkan halaman utama Operasional Lapangan & Teknisi.
     */
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Metric 1: Total Upah Teknisi Bulan Ini
        $totalWages = FieldOperationTechnician::whereHas('fieldOperation', function ($q) use ($currentMonth, $currentYear) {
            $q->whereMonth('operation_date', $currentMonth)
              ->whereYear('operation_date', $currentYear);
        })->sum('wage_amount');

        // Metric 2: Total Operasional Bensin & Parkir Bulan Ini
        $totalOperational = FieldOperation::whereMonth('operation_date', $currentMonth)
            ->whereYear('operation_date', $currentYear)
            ->sum('bensin_parkir_fee');

        // Metric 3: Total Entertain & Bonus Lembur Bulan Ini
        $totalEntertainBonus = FieldOperation::whereMonth('operation_date', $currentMonth)
            ->whereYear('operation_date', $currentYear)
            ->selectRaw('SUM(entertain_fee + bonus_fee) as total')
            ->value('total') ?? 0;

        // Metric 4: Total Pengeluaran Lapangan Bulan Ini
        $totalOverall = $totalWages + $totalOperational + $totalEntertainBonus;

        // Query Master-Detail Operasional Lapangan
        $operations = FieldOperation::with(['technicians', 'creator'])
            ->orderBy('operation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin_ops.field_operations', compact(
            'totalWages',
            'totalOperational',
            'totalEntertainBonus',
            'totalOverall',
            'operations'
        ));
    }

    /**
     * Simpan transaksi Operasional Lapangan & Teknisi baru (Master-Detail).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'operation_date' => 'required|date',
            'technicians' => 'required|array|min:1',
            'technicians.*.technician_name' => 'required|string|max:255',
            'technicians.*.wage_amount' => 'required|numeric|min:0',
            'bensin_parkir_fee' => 'nullable|numeric|min:0',
            'entertain_fee' => 'nullable|numeric|min:0',
            'bonus_fee' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $receiptPath = null;

            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/field_operations');
                
                if (!file_exists($destination)) {
                    mkdir($destination, 0777, true);
                }

                $file->move($destination, $filename);
                $receiptPath = 'uploads/field_operations/' . $filename;
            }

            // Create Master Record
            $operation = FieldOperation::create([
                'user_id' => $request->user()->id,
                'operation_date' => $validated['operation_date'],
                'bensin_parkir_fee' => $validated['bensin_parkir_fee'] ?? 0,
                'entertain_fee' => $validated['entertain_fee'] ?? 0,
                'bonus_fee' => $validated['bonus_fee'] ?? 0,
                'description' => $validated['description'],
                'receipt_path' => $receiptPath,
            ]);

            // Create Detail Records (Technicians)
            foreach ($validated['technicians'] as $tech) {
                if (!empty($tech['technician_name'])) {
                    $operation->technicians()->create([
                        'technician_name' => $tech['technician_name'],
                        'wage_amount' => (float) $tech['wage_amount'],
                    ]);
                }
            }

            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'create',
                'table_name' => 'field_operations',
                'record_id' => $operation->id,
                'new_values' => $operation->load('technicians')->toArray(),
            ]);
        });

        return redirect()->back()->with('success', 'Transaksi Operasional Lapangan & Teknisi berhasil disimpan.');
    }
}
