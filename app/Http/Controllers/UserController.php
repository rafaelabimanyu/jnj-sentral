<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna (Dashboard Admin Web).
     */
    public function index()
    {
        // Mengambil semua user termasuk yang di-softdelete (Inaktif)
        $users = User::withTrashed()->orderBy('name', 'asc')->get();

        return view('admin_web.dashboard', compact('users'));
    }

    /**
     * Membuat akun pengguna baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:owner,admin_ops,admin_web',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Akun pengguna berhasil dibuat.');
    }

    /**
     * Menonaktifkan (Soft Delete) atau mengaktifkan kembali akun pengguna.
     */
    public function toggleStatus($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
            return redirect()->back()->with('success', "Akun {$user->name} berhasil diaktifkan kembali.");
        }

        // Jangan izinkan menonaktifkan akun sendiri
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        $user->delete(); // Soft Delete
        return redirect()->back()->with('success', "Akun {$user->name} berhasil dinonaktifkan.");
    }
}
