@extends('layouts.app', ['title' => 'Dashboard Admin Ops', 'pageHeader' => 'Pencatatan Operasional & Finansial'])

@section('content')
@php
    // Gunakan data riil dari database yang dioper oleh controller
    $clientsList = $clients;
    $expensesHistory = $todayExpenses;
    $incomesHistory = $todayIncomes;
@endphp

<!-- We import Alpine.js via CDN for tab switching and alert auto-closing -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ formTab: 'expense', historyTab: 'expense' }">
    
    <!-- Left Column: Form Input (Dengan Tab Switcher Alpine.js) -->
    <div class="lg:col-span-1 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm h-fit">
        
        <!-- Tab Switcher Header -->
        <div class="flex border-b border-slate-200 mb-6">
            <button @click="formTab = 'expense'" :class="formTab === 'expense' ? 'border-brandGreen text-brandGreen' : 'border-transparent text-slate-400 hover:text-slate-600'" 
                class="flex-1 pb-3 text-xs font-bold uppercase tracking-wider border-b-2 text-center transition duration-150">
                Biaya Keluar
            </button>
            <button @click="formTab = 'income'" :class="formTab === 'income' ? 'border-brandGreen text-brandGreen' : 'border-transparent text-slate-400 hover:text-slate-600'" 
                class="flex-1 pb-3 text-xs font-bold uppercase tracking-wider border-b-2 text-center transition duration-150">
                Uang Masuk
            </button>
        </div>

        <!-- Form 1: Input Pengeluaran Baru -->
        <div x-show="formTab === 'expense'">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wide">Pencatatan Pengeluaran</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Catat pengeluaran atau overhead harian Rooterin.</p>
            </div>

            <form method="POST" action="{{ route('admin_ops.expenses.store') }}" class="space-y-4">
                @csrf

                <!-- Kategori Pengeluaran -->
                <div>
                    <label for="category" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kategori Biaya</label>
                    <select id="category" name="category" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                        <option value="" disabled selected>Pilih Kategori...</option>
                        <option value="fuel_parking">Operasional Lapangan (Bensin/Parkir)</option>
                        <option value="technician_wage">Upah Teknisi Harian (Flat)</option>
                        <option value="unexpected">Biaya Tak Terduga (Darurat)</option>
                        <option value="marketing_fee">Fee Marketing (Komisi)</option>
                        <option value="ads">Iklan / Ads</option>
                        <option value="entertain">Biaya Entertain (Akuisisi Klien)</option>
                        <option value="infrastructure">Infrastruktur (WiFi, Listrik, Kantor)</option>
                        <option value="bonus_location">Bonus Lokasi Tambahan</option>
                        <option value="bonus_night">Bonus Kerja Malam / Lembur</option>
                        <option value="welfare">Kesejahteraan (Family Gathering)</option>
                    </select>
                </div>

                <!-- Pilih Klien / Proyek -->
                <div>
                    <label for="client_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Klien terkait</label>
                    <select id="client_id" name="client_id"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                        <option value="">Tanpa Klien (Overhead Umum / Kantor)</option>
                        @foreach($clientsList as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ strtoupper($client->type) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Proyek Terkait (Income ID - Nullable) -->
                <div>
                    <label for="income_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">ID Pendapatan Proyek (Opsional)</label>
                    <input type="number" id="income_id" name="income_id"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: 1">
                </div>

                <!-- Jumlah Uang -->
                <div>
                    <label for="amount" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nominal Uang (Rp)</label>
                    <input type="number" id="amount" name="amount" required min="0" step="0.01"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: 150000">
                </div>

                <!-- Deskripsi Detail -->
                <div>
                    <label for="description" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi / Justifikasi</label>
                    <textarea id="description" name="description" required rows="3"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Rincian bensin, material darurat, atau upah..."></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-semibold py-3 px-4 rounded-xl shadow-md transition duration-200 uppercase tracking-wider text-xs flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Simpan Pengeluaran</span>
                </button>
            </form>
        </div>

        <!-- Form 2: Input Pendapatan Baru -->
        <div x-show="formTab === 'income'" x-cloak>
            <div class="mb-4">
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wide">Pencatatan Pendapatan</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Catat arus kas masuk / nilai jasa dari proyek klien.</p>
            </div>

            <form method="POST" action="{{ route('admin_ops.incomes.store') }}" class="space-y-4">
                @csrf

                <!-- Pilih Klien -->
                <div>
                    <label for="income_client_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pilih Klien</label>
                    <select id="income_client_id" name="client_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                        <option value="" disabled selected>Pilih Klien...</option>
                        @foreach($clientsList as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ strtoupper($client->type) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pelayanan -->
                <div>
                    <label for="service_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Pelaksanaan Jasa</label>
                    <input type="date" id="service_date" name="service_date" required value="{{ date('Y-m-d') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                </div>

                <!-- Nominal Pendapatan Kotor -->
                <div>
                    <label for="gross_amount" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pendapatan Kotor (Gross Rp)</label>
                    <input type="number" id="gross_amount" name="gross_amount" required min="0" step="0.01"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: 10000000">
                </div>

                <!-- Detail Pekerjaan Jasa -->
                <div>
                    <label for="service_detail" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Detail Pekerjaan Layanan</label>
                    <textarea id="service_detail" name="service_detail" required rows="3"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Deskripsikan layanan (misal: Pembersihan saluran CCTV RS Abdi Waluyo)..."></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-semibold py-3 px-4 rounded-xl shadow-md transition duration-200 uppercase tracking-wider text-xs flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Simpan Pendapatan</span>
                </button>
            </form>
        </div>

    </div>

    <!-- Right Column: Histori Transaksi Hari Ini (Tab Switcher) -->
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <!-- Header Tab Switcher -->
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
                <div class="flex space-x-6">
                    <button @click="historyTab = 'expense'" :class="historyTab === 'expense' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-400 hover:text-slate-600 font-semibold'" 
                        class="pb-1 text-xs uppercase tracking-wide transition duration-150">
                        Biaya Hari Ini
                    </button>
                    <button @click="historyTab = 'income'" :class="historyTab === 'income' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-400 hover:text-slate-600 font-semibold'" 
                        class="pb-1 text-xs uppercase tracking-wide transition duration-150">
                        Pendapatan Hari Ini
                    </button>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">Riil Database</span>
            </div>

            <!-- Tab 1: Tabel Pengeluaran Hari Ini -->
            <div x-show="historyTab === 'expense'">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                                <th class="py-3.5 px-4">Jam</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Klien / Detail</th>
                                <th class="py-3.5 px-4 text-right">Nominal</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($expensesHistory as $history)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-4 px-4 text-slate-500 font-medium">
                                        {{ $history->created_at->format('H:i') }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-700 uppercase">
                                            {{ str_replace('_', ' ', $history->category) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 font-medium max-w-xs truncate">
                                        <span class="font-bold text-brandNavy block mb-0.5">{{ $history->client->name ?? 'Overhead Umum' }}</span>
                                        {{ $history->description }}
                                    </td>
                                    <td class="py-4 px-4 text-right font-bold text-slate-800">
                                        Rp {{ number_format($history->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($history->status === 'approved')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                                Approved
                                            </span>
                                        @elseif($history->status === 'pending')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                                                Pending
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400">
                                        Belum ada pengeluaran dicatat hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 2: Tabel Pendapatan Hari Ini -->
            <div x-show="historyTab === 'income'" x-cloak>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                                <th class="py-3.5 px-4">Jam</th>
                                <th class="py-3.5 px-4">Klien</th>
                                <th class="py-3.5 px-4">Detail Pelayanan</th>
                                <th class="py-3.5 px-4 text-right">Nominal Pendapatan</th>
                                <th class="py-3.5 px-4 text-center">ID Proyek</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($incomesHistory as $income)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-4 px-4 text-slate-500 font-medium">
                                        {{ $income->created_at->format('H:i') }}
                                    </td>
                                    <td class="py-4 px-4 font-bold text-brandNavy">
                                        {{ $income->client->name }}
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 font-medium max-w-xs truncate">
                                        {{ $income->service_detail }}
                                    </td>
                                    <td class="py-4 px-4 text-right font-bold text-brandGreen">
                                        Rp {{ number_format($income->gross_amount, 2, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-bold text-slate-500">
                                        #{{ $income->id }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400">
                                        Belum ada pendapatan dicatat hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="mt-6 pt-3 border-t border-slate-100">
            <p class="text-[10px] text-slate-400 leading-relaxed">
                Catat setiap transaksi langsung setelah proyek selesai dikerjakan agar visualisasi dashboard utama owner terhitung secara real-time.
            </p>
        </div>
    </div>

</div>
@endsection
