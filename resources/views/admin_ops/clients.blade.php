@extends('layouts.app', ['title' => 'Manajemen Klien', 'pageHeader' => 'Daftar Klien & Proyek'])

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Form Tambah Klien Baru -->
    <div class="lg:col-span-1 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm h-fit">
        <div class="mb-4 pb-3 border-b border-slate-100">
            <h3 class="text-md font-bold text-brandNavy uppercase tracking-wide">Tambah Klien Baru</h3>
            <p class="text-xs text-slate-400 mt-1">Daftarkan institusi atau pelanggan residensial baru.</p>
        </div>

        <form method="POST" action="{{ route('admin_ops.clients.store') }}" class="space-y-4">
            @csrf

            <!-- Nama Klien -->
            <div>
                <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Klien / Instansi</label>
                <input type="text" id="name" name="name" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                    placeholder="Contoh: RS Abdi Waluyo">
            </div>

            <!-- Tipe Klien -->
            <div>
                <label for="type" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kategori / Tipe Klien</label>
                <select id="type" name="type" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                    <option value="" disabled selected>Pilih Kategori...</option>
                    <option value="b2b">B2B (Bisnis/Korporat/RS)</option>
                    <option value="residential">Residensial (Rumah Tangga)</option>
                </select>
            </div>

            <!-- Info Kontak -->
            <div>
                <label for="contact_info" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kontak (Opsional)</label>
                <input type="text" id="contact_info" name="contact_info"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                    placeholder="No. HP atau Email PIC">
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label for="address" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap (Opsional)</label>
                <textarea id="address" name="address" rows="3"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                    placeholder="Alamat kantor atau rumah..."></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-semibold py-3 px-4 rounded-xl shadow-md transition duration-200 uppercase tracking-wider text-xs flex items-center justify-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Simpan Klien</span>
            </button>
        </form>
    </div>

    <!-- Right Column: Daftar Klien -->
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-md font-bold text-brandNavy uppercase tracking-wide">Daftar Klien Terdaftar</h3>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">Database Aktif</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                            <th class="py-3.5 px-4">Nama Instansi / Klien</th>
                            <th class="py-3.5 px-4">Tipe</th>
                            <th class="py-3.5 px-4">Kontak</th>
                            <th class="py-3.5 px-4">Alamat</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($clients as $client)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="py-4 px-4 font-bold text-brandNavy">
                                    {{ $client->name }}
                                </td>
                                <td class="py-4 px-4">
                                    @if($client->type === 'b2b')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">B2B</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase">Residensial</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium">
                                    {{ $client->contact_info ?? '-' }}
                                </td>
                                <td class="py-4 px-4 text-slate-500 max-w-xs truncate">
                                    {{ $client->address ?? '-' }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <form method="POST" action="{{ route('admin_ops.clients.destroy', $client->id) }}" onsubmit="return confirm('Yakin ingin menghapus klien ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold uppercase text-[10px] tracking-wider transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">Belum ada klien terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
