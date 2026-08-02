@extends('layouts.app', ['title' => 'Operasional Lapangan & Teknisi', 'pageHeader' => 'Operasional Lapangan & Teknisi'])

@section('content')
<div class="space-y-8" x-data="{ 
    operationDate: '{{ old('operation_date', date('Y-m-d')) }}',
    bensinParkirFee: '{{ old('bensin_parkir_fee', '0') }}',
    entertainFee: '{{ old('entertain_fee', '0') }}',
    bonusFee: '{{ old('bonus_fee', '0') }}',
    fileName: '',
    technicians: [
        { technician_name: '', wage_amount: '' }
    ],
    addTechnician() {
        this.technicians.push({ technician_name: '', wage_amount: '' });
    },
    removeTechnician(index) {
        if (this.technicians.length > 1) {
            this.technicians.splice(index, 1);
        }
    },
    handleFileSelect(e) {
        let file = e.target.files[0];
        if (file) {
            this.fileName = file.name;
        }
    },
    get totalWages() {
        return this.technicians.reduce((sum, t) => sum + (parseFloat(t.wage_amount) || 0), 0);
    },
    get grandTotalCost() {
        let bp = parseFloat(this.bensinParkirFee) || 0;
        let ent = parseFloat(this.entertainFee) || 0;
        let bon = parseFloat(this.bonusFee) || 0;
        let total = this.totalWages + bp + ent + bon;
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(total);
    }
}">

    <!-- Top Section: 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric Card 1: Total Upah Teknisi -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Upah Teknisi (Bulan Ini)</span>
                <h3 class="text-xl font-extrabold text-brandGreen">
                    Rp {{ number_format($totalWages, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400">Total upah harian tim teknisi</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-brandGreen flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Metric Card 2: Total Bensin & Parkir -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Bensin / Tol / Parkir</span>
                <h3 class="text-xl font-extrabold text-blue-600">
                    Rp {{ number_format($totalOperational, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400">Biaya armada & transportasi</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>

        <!-- Metric Card 3: Total Entertain & Bonus -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Entertain & Bonus</span>
                <h3 class="text-xl font-extrabold text-amber-500">
                    Rp {{ number_format($totalEntertainBonus, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400">Akuisisi & bonus lokasi/lembur</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 13C10.832 19.877 8 17.8 8 13.5V11h8v2.5c0 4.3-2.832 6.377-4 7.5z"></path>
                </svg>
            </div>
        </div>

        <!-- Metric Card 4: Total Overall Biaya Lapangan -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden flex items-center justify-between bg-gradient-to-br from-slate-900 via-brandNavy to-slate-900 text-white">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block">Total Lapangan (Bulan Ini)</span>
                <h3 class="text-xl font-extrabold text-white">
                    Rp {{ number_format($totalOverall, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-300">Gabungan seluruh biaya operasional</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

    </div>

    <!-- Master-Detail Input Form -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
        <div class="pb-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Form Biaya Operasional Lapangan & Teknisi</h3>
                <p class="text-xs text-slate-400 mt-0.5">Internal expense ledger untuk mencatat rincian upah tim teknisi dan biaya operasional.</p>
            </div>
            <div class="flex items-center space-x-2 bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl">
                <span class="text-xs font-semibold text-slate-500">Estimasi Total Biaya:</span>
                <span class="text-sm font-extrabold text-brandGreen" x-text="grandTotalCost">Rp 0</span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin_ops.field_operations.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Section 1: Info Tanggal Operasional -->
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-brandNavy text-white font-bold text-xs flex items-center justify-center">1</span>
                    <h4 class="text-xs font-bold text-brandNavy uppercase tracking-wider">Info Tanggal Operasional</h4>
                </div>

                <div class="bg-slate-50/70 border border-slate-200 p-5 rounded-2xl">
                    <!-- Tanggal Pengerjaan -->
                    <div class="max-w-md">
                        <label for="operation_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Pengerjaan Lapangan</label>
                        <input type="date" id="operation_date" name="operation_date" x-model="operationDate" required
                            class="w-full bg-white border @error('operation_date') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen transition duration-200">
                        @error('operation_date')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Upah Teknisi (Dynamic Rows) -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-full bg-brandNavy text-white font-bold text-xs flex items-center justify-center">2</span>
                        <h4 class="text-xs font-bold text-brandNavy uppercase tracking-wider">Upah Tim Teknisi (Multi-Personel)</h4>
                    </div>
                    <button type="button" @click="addTechnician()"
                        class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-brandGreen hover:bg-brandGreenHover text-white text-xs font-semibold rounded-xl shadow-sm transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>+ Tambah Teknisi</span>
                    </button>
                </div>

                <div class="bg-slate-50/70 border border-slate-200 p-5 rounded-2xl space-y-3">
                    <template x-for="(tech, index) in technicians" :key="index">
                        <div class="flex flex-col sm:flex-row items-center gap-3 bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm">
                            <div class="w-full sm:flex-1">
                                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Teknisi</label>
                                <input type="text" :name="'technicians[' + index + '][technician_name]'" x-model="tech.technician_name" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                                    placeholder="Contoh: Ardy (Senior) / Abi (Junior)">
                            </div>
                            <div class="w-full sm:w-48">
                                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nominal Upah (Rp)</label>
                                <input type="number" :name="'technicians[' + index + '][wage_amount]'" x-model="tech.wage_amount" required min="0" step="0.01"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                                    placeholder="Contoh: 250000">
                            </div>
                            <div class="sm:pt-5 w-full sm:w-auto text-right">
                                <button type="button" @click="removeTechnician(index)" x-show="technicians.length > 1"
                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition duration-200" title="Hapus baris ini">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Section 3: Biaya Operasional Lainnya -->
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-brandNavy text-white font-bold text-xs flex items-center justify-center">3</span>
                    <h4 class="text-xs font-bold text-brandNavy uppercase tracking-wider">Biaya Operasional & Bonus</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/70 border border-slate-200 p-5 rounded-2xl">
                    <!-- Bensin, Tol & Parkir -->
                    <div>
                        <label for="bensin_parkir_fee" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">1. Bensin, Tol & Parkir (Rp)</label>
                        <input type="number" id="bensin_parkir_fee" name="bensin_parkir_fee" x-model="bensinParkirFee" min="0" step="0.01"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen transition duration-200"
                            placeholder="0">
                    </div>

                    <!-- Biaya Entertain -->
                    <div>
                        <label for="entertain_fee" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">2. Biaya Entertain Akuisisi (Rp)</label>
                        <input type="number" id="entertain_fee" name="entertain_fee" x-model="entertainFee" min="0" step="0.01"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen transition duration-200"
                            placeholder="0">
                    </div>

                    <!-- Bonus Lembur / Lokasi -->
                    <div>
                        <label for="bonus_fee" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">3. Bonus Lembur / Lokasi (Rp)</label>
                        <input type="number" id="bonus_fee" name="bonus_fee" x-model="bonusFee" min="0" step="0.01"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen transition duration-200"
                            placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Section 4: Dokumentasi -->
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-brandNavy text-white font-bold text-xs flex items-center justify-center">4</span>
                    <h4 class="text-xs font-bold text-brandNavy uppercase tracking-wider">Dokumentasi & Bukti Pembayaran</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/70 border border-slate-200 p-5 rounded-2xl">
                    <!-- Deskripsi / Justifikasi Mandatori -->
                    <div>
                        <label for="description" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi / Rincian Pekerjaan Lapangan <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="4" required
                            class="w-full bg-white border @error('description') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen transition duration-200"
                            placeholder="Contoh: Cleaning & flushing pipa utama gedung, pengerjaan 2 manhole dapur tambahan.">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload Struk / Nota -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Upload Struk / Nota / Bukti Bayar (Opsional)</label>
                        <div class="relative border-2 border-dashed border-slate-300 hover:border-brandGreen bg-white rounded-xl p-5 text-center cursor-pointer transition duration-200 h-28 flex flex-col justify-center items-center">
                            <input type="file" id="receipt" name="receipt" accept="image/jpeg,image/png,image/jpg,application/pdf" @change="handleFileSelect"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <svg class="w-7 h-7 text-slate-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <template x-if="fileName">
                                <p class="text-xs font-semibold text-brandGreen truncate" x-text="'File terpilih: ' + fileName"></p>
                            </template>
                            <template x-if="!fileName">
                                <p class="text-xs text-slate-500 font-medium">Klik atau drag file nota (JPG, PNG, PDF max 5MB)</p>
                            </template>
                        </div>
                        @error('receipt')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                <button type="submit"
                    class="bg-brandGreen hover:bg-brandGreenHover text-white font-semibold py-3 px-8 rounded-xl shadow-md transition duration-200 uppercase tracking-wider text-xs flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Operasional Lapangan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table: History Table -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Riwayat Master Operasional Lapangan</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Daftar transaksi pengerjaan operasional beserta rincian upah tim teknisi dan pengeluaran.</p>
            </div>
            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">Master Operation Log</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Rincian Tim Teknisi & Upah</th>
                        <th class="py-3.5 px-4">Biaya Operasional Lainnya</th>
                        <th class="py-3.5 px-4 text-right">Total Biaya Operasional</th>
                        <th class="py-3.5 px-4">Deskripsi / Catatan</th>
                        <th class="py-3.5 px-4 text-center">Nota / Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($operations as $op)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <!-- Tanggal -->
                            <td class="py-4 px-4 whitespace-nowrap font-bold text-brandNavy">
                                {{ $op->operation_date->format('d M Y') }}
                                <span class="block text-[10px] font-normal text-slate-400">{{ $op->created_at->format('H:i') }} WIB</span>
                            </td>

                            <!-- Rincian Tim Teknisi & Upah -->
                            <td class="py-4 px-4">
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    @foreach($op->technicians as $tech)
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 inline-flex items-center space-x-1">
                                            <span>{{ $tech->technician_name }}:</span>
                                            <span class="font-extrabold text-brandGreen">Rp {{ number_format($tech->wage_amount, 0, ',', '.') }}</span>
                                        </span>
                                    @endforeach
                                </div>
                                <span class="block text-[10px] font-bold text-slate-400 mt-1">Subtotal Upah: Rp {{ number_format($op->total_wages, 0, ',', '.') }}</span>
                            </td>

                            <!-- Biaya Operasional Lainnya -->
                            <td class="py-4 px-4 whitespace-nowrap text-[11px] space-y-0.5">
                                <div class="flex items-center justify-between space-x-2">
                                    <span class="text-slate-400 font-medium">Bensin/Parkir:</span>
                                    <span class="font-semibold text-slate-700">Rp {{ number_format($op->bensin_parkir_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between space-x-2">
                                    <span class="text-slate-400 font-medium">Entertain:</span>
                                    <span class="font-semibold text-amber-600">Rp {{ number_format($op->entertain_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between space-x-2">
                                    <span class="text-slate-400 font-medium">Bonus Lembur:</span>
                                    <span class="font-semibold text-blue-600">Rp {{ number_format($op->bonus_fee, 0, ',', '.') }}</span>
                                </div>
                            </td>

                            <!-- Total Biaya Operasional -->
                            <td class="py-4 px-4 text-right font-extrabold text-sm text-slate-800 whitespace-nowrap">
                                Rp {{ number_format($op->total_cost, 0, ',', '.') }}
                            </td>

                            <!-- Deskripsi Pekerjaan -->
                            <td class="py-4 px-4 text-slate-700 font-medium max-w-xs">
                                {{ $op->description }}
                            </td>

                            <!-- Nota / Bukti Bayar -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                @if($op->receipt_path)
                                    <a href="{{ asset($op->receipt_path) }}" target="_blank"
                                        class="inline-flex items-center space-x-1 px-2.5 py-1 bg-slate-100 hover:bg-brandGreen hover:text-white text-slate-700 text-[10px] font-bold rounded-lg transition duration-200 border border-slate-200 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>Lihat Nota</span>
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-400 font-normal">Tanpa Nota</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Belum ada riwayat master operasional lapangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($operations->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $operations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
