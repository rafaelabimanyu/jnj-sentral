<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Tampilkan daftar master data Karyawan & Ringkasan Metrik.
     */
    public function index(Request $request)
    {
        $role = $request->input('role');

        // Metrik Ringkasan Top Section
        $totalActive = Employee::where('status', 'Active')->count();
        $totalTechnicians = Employee::where('role', 'Teknisi')->where('status', 'Active')->count();
        $totalAdminOffice = Employee::whereIn('role', ['Admin', 'Customer Service', 'Marketing', 'Management'])
            ->where('status', 'Active')
            ->count();

        // Query Master Data Karyawan
        $query = Employee::withCount('operationDetails');

        if ($role && in_array($role, ['Admin', 'Teknisi', 'Customer Service', 'Marketing', 'Management'])) {
            $query->where('role', $role);
        }

        $employees = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin_ops.employees.index', compact(
            'employees',
            'totalActive',
            'totalTechnicians',
            'totalAdminOffice',
            'role'
        ));
    }

    /**
     * Simpan data Karyawan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'role'   => 'required|in:Admin,Teknisi,Customer Service,Marketing,Management',
            'level'  => 'required|in:Senior,Junior,Lead,Staff',
            'status' => 'required|in:Active,Inactive',
        ], [
            'name.required'   => 'Nama lengkap karyawan wajib diisi.',
            'name.max'        => 'Nama karyawan maksimal 255 karakter.',
            'role.required'   => 'Posisi / Role karyawan wajib dipilih.',
            'role.in'         => 'Pilihan Role tidak valid.',
            'level.required'  => 'Level / Kualifikasi wajib dipilih.',
            'level.in'        => 'Pilihan Level tidak valid.',
            'status.required' => 'Status karyawan wajib dipilih.',
            'status.in'       => 'Pilihan Status tidak valid.',
        ]);

        $employee = Employee::create($validated);

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'create',
            'table_name' => 'employees',
            'record_id'  => $employee->id,
            'new_values' => $employee->toArray(),
        ]);

        return redirect()->back()->with('success', 'Data karyawan baru berhasil ditambahkan.');
    }

    /**
     * Perbarui data Karyawan.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'role'   => 'required|in:Admin,Teknisi,Customer Service,Marketing,Management',
            'level'  => 'required|in:Senior,Junior,Lead,Staff',
            'status' => 'required|in:Active,Inactive',
        ], [
            'name.required'   => 'Nama lengkap karyawan wajib diisi.',
            'name.max'        => 'Nama karyawan maksimal 255 karakter.',
            'role.required'   => 'Posisi / Role karyawan wajib dipilih.',
            'role.in'         => 'Pilihan Role tidak valid.',
            'level.required'  => 'Level / Kualifikasi wajib dipilih.',
            'level.in'        => 'Pilihan Level tidak valid.',
            'status.required' => 'Status karyawan wajib dipilih.',
            'status.in'       => 'Pilihan Status tidak valid.',
        ]);

        $oldValues = $employee->toArray();
        $employee->update($validated);

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'update',
            'table_name' => 'employees',
            'record_id'  => $employee->id,
            'old_values' => $oldValues,
            'new_values' => $employee->fresh()->toArray(),
        ]);

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Hapus data Karyawan (Soft Delete).
     */
    public function destroy(Request $request, Employee $employee)
    {
        $oldValues = $employee->toArray();
        $employee->delete();

        AuditLog::create([
            'user_id'    => $request->user()->id,
            'action'     => 'delete',
            'table_name' => 'employees',
            'record_id'  => $employee->id,
            'old_values' => $oldValues,
        ]);

        return redirect()->back()->with('success', 'Data karyawan berhasil dihapus.');
    }
}
