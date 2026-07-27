@extends('layouts.app', ['title' => 'User Management', 'pageHeader' => 'Manajemen Pengguna Sistem'])

@section('content')
<!-- We import Alpine.js via CDN for tab switching and alert auto-closing (if not loaded) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Form Tambah Akun Baru -->
    <div class="lg:col-span-1 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm h-fit">
        <div class="mb-4 pb-3 border-b border-slate-100">
            <h3 class="text-md font-bold text-brandNavy uppercase tracking-wide">Buat Pengguna Baru</h3>
            <p class="text-xs text-slate-400 mt-1">Daftarkan akun staf admin operasional baru atau eksekutif.</p>
        </div>

        <form method="POST" action="{{ route('admin_web.users.store') }}" class="space-y-4">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                    placeholder="Contoh: Rian Hidayat">
            </div>

            <!-- Alamat Email -->
            <div>
                <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Alamat Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                    placeholder="rian.ops@rooterin.com">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kata Sandi Default</label>
                <input type="password" id="password" name="password" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                    placeholder="Minimal 6 Karakter">
            </div>

            <!-- Hak Akses (Role) -->
            <div>
                <label for="role" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Peran / Hak Akses (Role)</label>
                <select id="role" name="role" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                    <option value="" disabled selected>Pilih Hak Akses...</option>
                    <option value="admin_ops">Admin Operasional (Entry Data)</option>
                    <option value="owner">Owner (Eksekutif / Analitik)</option>
                    <option value="admin_web">Admin Website / Developer</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-semibold py-3 px-4 rounded-xl shadow-md transition duration-200 uppercase tracking-wider text-xs flex items-center justify-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Daftarkan Pengguna</span>
            </button>
        </form>
    </div>

    <!-- Right Column: Daftar Pengguna (Steril dari nominal keuangan) -->
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-md font-bold text-brandNavy uppercase tracking-wide">Pengguna Sistem Terdaftar</h3>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">Steril Finansial</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                            <th class="py-3.5 px-4">Nama Lengkap</th>
                            <th class="py-3.5 px-4">Alamat Email</th>
                            <th class="py-3.5 px-4">Peran (Role)</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-center">Aksi Keanggotaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="py-4 px-4 font-bold text-slate-800">
                                    {{ $user->name }}
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium">
                                    {{ $user->email }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-brandNavy border border-brandNavy/15 uppercase">
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($user->trashed())
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase">
                                            Inaktif
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                            Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if(auth()->id() === $user->id)
                                        <span class="text-[10px] text-slate-400">Akun Anda</span>
                                    @else
                                        <form method="POST" action="{{ route('admin_web.users.toggle', $user->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                class="font-bold uppercase text-[10px] tracking-wider transition {{ $user->trashed() ? 'text-brandGreen hover:text-brandGreenHover' : 'text-red-600 hover:text-red-700' }}">
                                                {{ $user->trashed() ? 'Aktifkan' : 'Nonaktifkan' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 pt-3 border-t border-slate-100">
            <p class="text-[10px] text-slate-400 leading-relaxed">
                *Akun yang <strong>Dinonaktifkan</strong> (Soft-deleted) tidak akan bisa melakukan login ke sistem. Riwayat log audit dan pencatatan transaksi yang dibuat oleh akun tersebut di masa lalu tetap aman demi kepatuhan finansial.
            </p>
        </div>
    </div>

</div>
@endsection
