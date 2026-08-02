<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FieldOperation;
use App\Models\FieldOperationTechnician;
use App\Models\Technician;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FieldOperationExpenseController extends Controller
{
    /**
     * Tampilkan Dashboard Analytic & Riwayat Log Operasional Lapangan.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        // Query Master Operasional Lapangan
        $query = FieldOperation::with(['technicians.technician', 'creator']);

        if ($startDate && $endDate) {
            $query->whereBetween('operation_date', [$startDate, $endDate]);
        } else {
            // Default: Month to date (Current Month)
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $query->whereMonth('operation_date', $currentMonth)
                  ->whereYear('operation_date', $currentYear);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('technicians.technician', function ($t) use ($search) {
                      $t->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Summary Metrics Calculation
        $operationsForMetrics = (clone $query)->get();

        $totalWages = $operationsForMetrics->sum(function ($op) {
            return $op->technicians->sum('wage_amount');
        });

        $totalOperational = $operationsForMetrics->sum('bensin_parkir_fee');
        $totalEntertainBonus = $operationsForMetrics->sum(function ($op) {
            return $op->entertain_fee + $op->bonus_fee;
        });

        $totalOverall = $totalWages + $totalOperational + $totalEntertainBonus;

        // Paginated List
        $operations = $query->orderBy('operation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin_ops.field_operations.index', compact(
            'totalWages',
            'totalOperational',
            'totalEntertainBonus',
            'totalOverall',
            'operations',
            'startDate',
            'endDate',
            'search'
        ));
    }

    /**
     * Tampilkan Halaman Form Data Entry Operasional Lapangan Baru.
     */
    public function create()
    {
        $seniorTechnicians = Technician::where('level', 'Senior')->orderBy('name')->get();
        $juniorTechnicians = Technician::where('level', 'Junior')->orderBy('name')->get();

        return view('admin_ops.field_operations.create', compact('seniorTechnicians', 'juniorTechnicians'));
    }

    /**
     * Simpan transaksi Operasional Lapangan & Teknisi baru (Master-Detail).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'operation_date' => 'required|date',
            'technicians' => 'required|array|min:1',
            'technicians.*.technician_id' => 'required|exists:technicians,id',
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
                if (!empty($tech['technician_id'])) {
                    $operation->technicians()->create([
                        'technician_id' => $tech['technician_id'],
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

        return redirect()->route('admin_ops.field_operations.index')
            ->with('success', 'Transaksi Operasional Lapangan & Teknisi berhasil disimpan.');
    }
}
