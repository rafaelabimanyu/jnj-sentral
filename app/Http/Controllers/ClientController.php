<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Menampilkan daftar klien.
     */
    public function index()
    {
        $clients = Client::orderBy('name', 'asc')->get();
        return view('admin_ops.clients', compact('clients'));
    }

    /**
     * Menyimpan klien baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:b2b,residential',
            'contact_info' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        Client::create($request->all());

        return redirect()->back()->with('success', 'Klien berhasil ditambahkan.');
    }

    /**
     * Menghapus klien.
     */
    public function destroy(Client $client)
    {
        // Pastikan tidak ada transaksi terkait sebelum menghapus (opsional, tergantung PRD, tapi aman menggunakan softDeletes atau restrict)
        if ($client->incomes()->count() > 0 || $client->expenses()->count() > 0) {
            return redirect()->back()->with('error', 'Klien tidak bisa dihapus karena memiliki riwayat transaksi.');
        }

        $client->delete();
        return redirect()->back()->with('success', 'Klien berhasil dihapus.');
    }
}
