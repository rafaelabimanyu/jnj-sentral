<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarketingFee;
use App\Models\Marketer;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarketingFeeController extends Controller
{
    /**
     * Tampilkan halaman utama Manajemen Komisi & Fee Marketing.
     */
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Metric 1: Total Fee Paid bulan ini
        $totalPaidThisMonth = MarketingFee::where('status', 'Paid')
            ->where(function($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('payment_date', $currentMonth)
                      ->whereYear('payment_date', $currentYear)
                      ->orWhere(function($q) use ($currentMonth, $currentYear) {
                          $q->whereNull('payment_date')
                            ->whereMonth('updated_at', $currentMonth)
                            ->whereYear('updated_at', $currentYear);
                      });
            })
            ->sum('fee_amount');

        // Metric 2: Pending Fees
        $pendingFeesTotal = MarketingFee::where('status', 'Pending')->sum('fee_amount');
        $pendingFeesCount = MarketingFee::where('status', 'Pending')->count();

        // Metric 3: Top Marketer
        $topMarketerData = MarketingFee::select('marketer_id', DB::raw('SUM(fee_amount) as total_fees'), DB::raw('COUNT(*) as total_deals'))
            ->with('marketer')
            ->groupBy('marketer_id')
            ->orderByDesc('total_fees')
            ->first();

        $topMarketer = ($topMarketerData && $topMarketerData->marketer) ? $topMarketerData->marketer->name : 'Belum Ada';
        $topMarketerFees = $topMarketerData ? $topMarketerData->total_fees : 0;

        // List Riwayat Marketing Fees
        $fees = MarketingFee::with(['creator', 'marketer'])->orderBy('created_at', 'desc')->paginate(15);
        $marketers = Marketer::orderBy('name')->get();

        return view('admin_ops.marketing_fees', compact(
            'totalPaidThisMonth',
            'pendingFeesTotal',
            'pendingFeesCount',
            'topMarketer',
            'topMarketerFees',
            'fees',
            'marketers'
        ));
    }

    /**
     * Simpan data fee marketing / komisi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'marketer_id' => 'required|exists:marketers,id',
            'project_value' => 'required|numeric|min:0',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:Pending,Paid',
            'payment_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $projectValue = (float) $validated['project_value'];
            $feePercentage = (float) $validated['fee_percentage'];
            $feeAmount = $projectValue * ($feePercentage / 100);

            $paymentDate = $validated['status'] === 'Paid'
                ? ($validated['payment_date'] ?? Carbon::now()->toDateString())
                : null;

            $marketingFee = MarketingFee::create([
                'user_id' => $request->user()->id,
                'marketer_id' => $validated['marketer_id'],
                'project_value' => $projectValue,
                'fee_percentage' => $feePercentage,
                'fee_amount' => $feeAmount,
                'status' => $validated['status'],
                'payment_date' => $paymentDate,
            ]);

            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'create',
                'table_name' => 'marketing_fees',
                'record_id' => $marketingFee->id,
                'new_values' => $marketingFee->toArray(),
            ]);
        });

        return redirect()->back()->with('success', 'Pencatatan Komisi & Fee Marketing berhasil disimpan.');
    }

    /**
     * Ubah status komisi pending menjadi Paid (Lunas).
     */
    public function markAsPaid(Request $request, MarketingFee $marketingFee)
    {
        if ($marketingFee->status === 'Paid') {
            return redirect()->back()->with('error', 'Status komisi ini sudah Lunas.');
        }

        $oldValues = $marketingFee->toArray();

        DB::transaction(function () use ($request, $marketingFee, $oldValues) {
            $marketingFee->update([
                'status' => 'Paid',
                'payment_date' => Carbon::now()->toDateString(),
            ]);

            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'update_status_paid',
                'table_name' => 'marketing_fees',
                'record_id' => $marketingFee->id,
                'old_values' => $oldValues,
                'new_values' => $marketingFee->fresh()->toArray(),
            ]);
        });

        return redirect()->back()->with('success', 'Status komisi berhasil diperbarui menjadi Lunas (Paid).');
    }
}
