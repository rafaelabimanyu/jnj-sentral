<?php

namespace App\Http\Controllers;

use App\Models\Marketer;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class MarketerController extends Controller
{
    /**
     * Tampilkan daftar master data Marketer.
     */
    public function index()
    {
        $marketers = Marketer::withCount('marketingFees')->orderBy('name')->paginate(15);
        return view('admin_ops.marketers.index', compact('marketers'));
    }

    /**
     * Simpan data Marketer baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:marketers,name',
            'default_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $marketer = Marketer::create($validated);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'create',
            'table_name' => 'marketers',
            'record_id' => $marketer->id,
            'new_values' => $marketer->toArray(),
        ]);

        return redirect()->back()->with('success', 'Master Marketer / Channel berhasil ditambahkan.');
    }

    /**
     * Perbarui data Marketer.
     */
    public function update(Request $request, Marketer $marketer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:marketers,name,' . $marketer->id,
            'default_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $oldValues = $marketer->toArray();
        $marketer->update($validated);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'update',
            'table_name' => 'marketers',
            'record_id' => $marketer->id,
            'old_values' => $oldValues,
            'new_values' => $marketer->fresh()->toArray(),
        ]);

        return redirect()->back()->with('success', 'Master Marketer / Channel berhasil diperbarui.');
    }

    /**
     * Hapus data Marketer.
     */
    public function destroy(Request $request, Marketer $marketer)
    {
        $oldValues = $marketer->toArray();
        $marketer->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'delete',
            'table_name' => 'marketers',
            'record_id' => $marketer->id,
            'old_values' => $oldValues,
        ]);

        return redirect()->back()->with('success', 'Master Marketer / Channel berhasil dihapus.');
    }
}
