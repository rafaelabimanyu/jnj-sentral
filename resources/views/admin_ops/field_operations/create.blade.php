@extends('layouts.app', ['title' => 'Catat Operasional Lapangan', 'pageHeader' => 'Form Biaya Operasional Lapangan'])

@section('content')
<div class="space-y-8" x-data="{ 
    operationDate: '{{ old('operation_date', date('Y-m-d')) }}',
    bensinParkirFee: '{{ old('bensin_parkir_fee', '0') }}',
    entertainFee: '{{ old('entertain_fee', '0') }}',
    bonusFee: '{{ old('bonus_fee', '0') }}',
    fileName: '',
    seniors: [
        { technician_id: '', wage_amount: '' }
    ],
    juniors: [],
    addSenior() {
        this.seniors.push({ technician_id: '', wage_amount: '' });
    },
    removeSenior(index) {
        if (this.seniors.length > 0) {
            this.seniors.splice(index, 1);
        }
    },
    addJunior() {
        this.juniors.push({ technician_id: '', wage_amount: '' });
    },
    removeJunior(index) {
        if (this.juniors.length > 0) {
            this.juniors.splice(index, 1);
        }
    },
    handleFileSelect(e) {
        let file = e.target.files[0];
        if (file) {
            this.fileName = file.name;
        }
    },
    get totalSeniorWages() {
        return this.seniors.reduce((sum, s) => sum + (parseFloat(s.wage_amount) || 0), 0);
    },
    get totalJuniorWages() {
        return this.juniors.reduce((sum, j) => sum + (parseFloat(j.wage_amount) || 0), 0);
    },
    get totalWages() {
        return this.totalSeniorWages + this.totalJuniorWages;
    },
    get grandTotalCost() {
        let bp = parseFloat(this.bensinParkirFee) || 0;
        let ent = parseFloat(this.entertainFee) || 0;
        let bon = parseFloat(this.bonusFee) || 0;
        let total = this.totalWages + bp + ent + bon;
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(total);
    }
}">

    <!-- Top Action Header Bar -->
    <div class="flex items-center justify-between bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div>
            <h2 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Pencatatan Biaya Operasional Lapangan & Teknisi</h2>
            <p class="text-xs text-slate-400 mt-0.5">Internal expense ledger untuk menginput upah tim teknisi dan rincian operasional.</p>
        </div>
        <a href="{{ route('admin_ops.field_operations.index') }}"
            class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-brandNavy font-bold text-xs rounded-xl transition duration-200 border border-slate-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>← Kembali ke Dashboard</span>
        </a>
    </div>

    <!-- Master-Detail Input Form -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
        <div class="pb-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Form Terpadu Input Operasional</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Pilih master data teknisi (Senior & Junior) dan isi nominal upah serta pengeluaran.</p>
            </div>
            <!-- Sticky / Prominent Realtime Total Cost Badge -->
            <div class="flex items-center space-x-2.5 bg-emerald-50 border border-emerald-200 px-4 py-2.5 rounded-xl shadow-sm">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Total Estimasi Biaya:</span>
                <span class="text-base font-extrabold text-brandGreen" x-text="grandTotalCost">Rp 0</span>
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
                            class="w-full bg-white border @error('operation_date') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:ring-1 focus:ring-brandGreen transition duration-200">
                        @error('operation_date')
                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Upah Tim Teknisi (Sub-Sections: Senior & Junior) -->
            <div class="space-y-6">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-brandNavy text-white font-bold text-xs flex items-center justify-center">2</span>
                    <h4 class="text-xs font-bold text-brandNavy uppercase tracking-wider">Upah Tim Teknisi (Master Data)</h4>
                </div>

                <!-- Sub-Section A: Teknisi Senior (Lead) -->
                <div class="bg-slate-50/70 border border-slate-200 p-5 rounded-2xl space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider">Teknisi Senior (Lead)</span>
                            <span class="text-xs text-slate-400 font-medium" x-text="'Subtotal: Rp ' + new Intl.NumberFormat('id-ID').format(totalSeniorWages)"></span>
                        </div>
                        <button type="button" @click="addSenior()"
                            class="inline-flex items-center space-x-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-sm transition duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>+ Tambah Senior</span>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(senior, index) in seniors" :key="index">
                            <div class="flex flex-col sm:flex-row items-center gap-3 bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm">
                                <div class="w-full sm:flex-1">
                                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pilih Teknisi Senior</label>
                                    <select :name="'technicians[' + index + '][technician_id]'" x-model="senior.technician_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                        <option value="" disabled selected>-- Pilih Teknisi Senior --</option>
                                        @foreach($seniorTechnicians as $tech)
                                            <option value="{{ $tech->id }}">{{ $tech->name }} (Senior)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full sm:w-48">
                                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nominal Upah (Rp)</label>
                                    <input type="number" :name="'technicians[' + index + '][wage_amount]'" x-model="senior.wage_amount" required min="0" step="0.01"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                                        placeholder="Contoh: 300000">
                                </div>
                                <div class="sm:pt-5 w-full sm:w-auto text-right">
                                    <button type="button" @click="removeSenior(index)" x-show="seniors.length > 0"
                                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition duration-200" title="Hapus teknisi ini">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Sub-Section B: Teknisi Junior (Helper) -->
                <div class="bg-slate-50/70 border border-slate-200 p-5 rounded-2xl space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold bg-blue-100 text-blue-800 uppercase tracking-wider">Teknisi Junior (Helper)</span>
                            <span class="text-xs text-slate-400 font-medium" x-text="'Subtotal: Rp ' + new Intl.NumberFormat('id-ID').format(totalJuniorWages)"></span>
                        </div>
                        <button type="button" @click="addJunior()"
                            class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm transition duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>+ Tambah Junior</span>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(junior, index) in juniors" :key="index">
                            <div class="flex flex-col sm:flex-row items-center gap-3 bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm">
                                <div class="w-full sm:flex-1">
                                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pilih Teknisi Junior</label>
                                    <select :name="'technicians[' + (seniors.length + index) + '][technician_id]'" x-model="junior.technician_id" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                        <option value="" disabled selected>-- Pilih Teknisi Junior --</option>
                                        @foreach($juniorTechnicians as $tech)
                                            <option value="{{ $tech->id }}">{{ $tech->name }} (Junior)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full sm:w-48">
                                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nominal Upah (Rp)</label>
                                    <input type="number" :name="'technicians[' + (seniors.length + index) + '][wage_amount]'" x-model="junior.wage_amount" required min="0" step="0.01"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                                        placeholder="Contoh: 200000">
                                </div>
                                <div class="sm:pt-5 w-full sm:w-auto text-right">
                                    <button type="button" @click="removeJunior(index)" x-show="juniors.length > 0"
                                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition duration-200" title="Hapus teknisi ini">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="juniors.length === 0" class="text-xs text-slate-400 italic py-1">
                            Belum ada teknisi junior ditambahkan. Klik "+ Tambah Junior" jika tim menyertakan helper/junior.
                        </div>
                    </div>
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
                            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:ring-1 focus:ring-brandGreen transition duration-200"
                            placeholder="0">
                    </div>

                    <!-- Biaya Entertain -->
                    <div>
                        <label for="entertain_fee" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">2. Biaya Entertain Akuisisi (Rp)</label>
                        <input type="number" id="entertain_fee" name="entertain_fee" x-model="entertainFee" min="0" step="0.01"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:ring-1 focus:ring-brandGreen transition duration-200"
                            placeholder="0">
                    </div>

                    <!-- Bonus Lembur / Lokasi -->
                    <div>
                        <label for="bonus_fee" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">3. Bonus Lembur / Lokasi (Rp)</label>
                        <input type="number" id="bonus_fee" name="bonus_fee" x-model="bonusFee" min="0" step="0.01"
                            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:ring-1 focus:ring-brandGreen transition duration-200"
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
                            class="w-full bg-white border @error('description') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:ring-1 focus:ring-brandGreen transition duration-200"
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
                <a href="{{ route('admin_ops.field_operations.index') }}"
                    class="px-5 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">
                    Batal
                </a>
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

</div>
@endsection
