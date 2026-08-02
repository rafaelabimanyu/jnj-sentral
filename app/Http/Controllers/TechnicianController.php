<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    /**
     * Tampilkan daftar master data Teknisi.
     */
    public function index()
    {
        $technicians = Technician::withCount('operationDetails')
            ->orderBy('name')
            ->paginate(15);

        return view('admin_ops.technicians.index', compact('technicians'));
    }

    /**
     * Simpan data Teknisi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'level' => 'required|in:Senior,Junior',
        ], [
            'name.required'  => 'Nama teknisi wajib diisi.',
            'name.max'       => 'Nama teknisi maksimal 255 karakter.',
            'level.required' => 'Level teknisi wajib dipilih.',
            'level.in'       => 'Level teknisi harus Senior atau Junior.',
        ]);

        $technician = Technician::create($validated);

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'create',
            'table_name' => 'technicians',
            'record_id'  => $technician->id,
            'new_values' => $technician->toArray(),
        ]);

        return redirect()->back()->with('success', 'Teknisi baru berhasil ditambahkan.');
    }

    /**
     * Perbarui data Teknisi.
     */
    public function update(Request $request, Technician $technician)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'level' => 'required|in:Senior,Junior',
        ], [
            'name.required'  => 'Nama teknisi wajib diisi.',
            'name.max'       => 'Nama teknisi maksimal 255 karakter.',
            'level.required' => 'Level teknisi wajib dipilih.',
            'level.in'       => 'Level teknisi harus Senior atau Junior.',
        ]);

        $oldValues = $technician->toArray();
        $technician->update($validated);

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'update',
            'table_name' => 'technicians',
            'record_id'  => $technician->id,
            'old_values' => $oldValues,
            'new_values' => $technician->fresh()->toArray(),
        ]);

        return redirect()->back()->with('success', 'Data teknisi berhasil diperbarui.');
    }

    /**
     * Hapus data Teknisi.
     */
    public function destroy(Request $request, Technician $technician)
    {
        $oldValues = $technician->toArray();
        $technician->delete();

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'delete',
            'table_name' => 'technicians',
            'record_id'  => $technician->id,
            'old_values' => $oldValues,
        ]);

        return redirect()->back()->with('success', 'Data teknisi berhasil dihapus.');
    }
}
