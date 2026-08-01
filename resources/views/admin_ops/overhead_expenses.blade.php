@extends('layouts.app', ['title' => 'Overhead & Manajemen Ekstra', 'pageHeader' => 'Overhead & Manajemen Ekstra'])

@section('content')
<div class="space-y-8" x-data="{ 
    receiptName: '',
    previewUrl: null,
    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            this.receiptName = file.name;
            if (file.type.startsWith('image/')) {
                this.previewUrl = URL.createObjectURL(file);
            } else {
                this.previewUrl = null;
            }
        }
    }
}">

    <!-- Top Section: Category Aggregation Breakdown & Budget Leak Monitor -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Total Overhead Metric Card -->
        <div class="lg:col-span-1 bg-brandNavy text-white rounded-2xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Total Accumulative Overhead</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-brandGreen text-white uppercase">Budget Monitor</span>
                </div>
                <h2 class="text-3xl font-extrabold text-white tracking-tight">
                    Rp {{ number_format($totalOverhead, 0, ',', '.') }}
                </h2>
                <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                    Pengeluaran non-proyek terakumulasi (Infrastruktur kantor, kesejahteraan tim, dan biaya darurat).
                </p>
            </div>
            
            <div class="mt-6 pt-4 border-t border-slate-700/60 flex items-center justify-between text-xs text-slate-300">
                <span>Rincian 3 Kategori Utama</span>
                <span class="font-bold text-brandGreen">100% Teracak</span>
            </div>
        </div>

        <!-- Visual Breakdown & Progress Bars Card -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Distribusi Biaya Overhead & Ekstra</h3>
                    <span class="text-xs text-slate-400 font-medium">Pemantauan kebocoran anggaran</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-2">
                    
                    <!-- Category 1: Infrastruktur -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Infrastruktur</span>
                            <span class="text-xs font-extrabold text-blue-600">{{ $infraPercentage }}%</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-800">Rp {{ number_format($infraTotal, 0, ',', '.') }}</h4>
                        <div class="w-full bg-slate-200 h-2 rounded-full mt-3 overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: {{ $infraPercentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">WiFi, Listrik, Sewa Kantor</p>
                    </div>

                    <!-- Category 2: Kesejahteraan -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Kesejahteraan</span>
                            <span class="text-xs font-extrabold text-brandGreen">{{ $welfarePercentage }}%</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-800">Rp {{ number_format($welfareTotal, 0, ',', '.') }}</h4>
                        <div class="w-full bg-slate-200 h-2 rounded-full mt-3 overflow-hidden">
                            <div class="bg-brandGreen h-full rounded-full" style="width: {{ $welfarePercentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">Family Gathering, Konsumsi, Bonus</p>
                    </div>

                    <!-- Category 3: Darurat -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Tak Terduga (Darurat)</span>
                            <span class="text-xs font-extrabold text-amber-500">{{ $unexpectedPercentage }}%</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-800">Rp {{ number_format($unexpectedTotal, 0, ',', '.') }}</h4>
                        <div class="w-full bg-slate-200 h-2 rounded-full mt-3 overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full" style="width: {{ $unexpectedPercentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">Perbaikan Alat, Kebocoran, Darurat</p>
                    </div>

                </div>
            </div>

            <!-- Chart Bar visual representation -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex rounded-full h-3 overflow-hidden bg-slate-100">
                <div class="bg-blue-600 h-full" style="width: {{ $infraPercentage }}%" title="Infrastruktur: {{ $infraPercentage }}%"></div>
                <div class="bg-brandGreen h-full" style="width: {{ $welfarePercentage }}%" title="Kesejahteraan: {{ $welfarePercentage }}%"></div>
                <div class="bg-amber-500 h-full" style="width: {{ $unexpectedPercentage }}%" title="Biaya Tak Terduga: {{ $unexpectedPercentage }}%"></div>
            </div>
        </div>

    </div>

    <!-- Main Grid: Form & History Table -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Input Overhead Expense -->
        <div class="lg:col-span-1 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm h-fit">
            <div class="mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Pencatatan Overhead & Ekstra</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Catat biaya fasilitas kantor, event tim, atau pengeluaran darurat.</p>
            </div>

            <form method="POST" action="{{ route('admin_ops.overhead_expenses.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Kategori Overhead -->
                <div>
                    <label for="category" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kategori Non-Proyek</label>
                    <select id="category" name="category" required
                        class="w-full bg-slate-50 border @error('category') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                        <option value="" disabled selected>Pilih Kategori Overhead...</option>
                        <option value="Infrastruktur (WiFi, Listrik, Kantor)" {{ old('category') == 'Infrastruktur (WiFi, Listrik, Kantor)' ? 'selected' : '' }}>Infrastruktur (WiFi, Listrik, Kantor)</option>
                        <option value="Kesejahteraan (Family Gathering dll)" {{ old('category') == 'Kesejahteraan (Family Gathering dll)' ? 'selected' : '' }}>Kesejahteraan (Family Gathering dll)</option>
                        <option value="Biaya Tak Terduga (Darurat)" {{ old('category') == 'Biaya Tak Terduga (Darurat)' ? 'selected' : '' }}>Biaya Tak Terduga (Darurat)</option>
                    </select>
                    @error('category')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Judul Pengeluaran -->
                <div>
                    <label for="title" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Judul Pengeluaran</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="w-full bg-slate-50 border @error('title') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: Tagihan WiFi Indihome Juli / Servis Jet Cleaner">
                    @error('title')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nominal Pengeluaran -->
                <div>
                    <label for="amount" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nominal Biaya (Rp)</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" required min="0" step="0.01"
                        class="w-full bg-slate-50 border @error('amount') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: 750000">
                    @error('amount')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pengeluaran -->
                <div>
                    <label for="expense_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Transaksi</label>
                    <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required
                        class="w-full bg-slate-50 border @error('expense_date') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                    @error('expense_date')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi / Catatan -->
                <div>
                    <label for="description" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi / Justifikasi</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Rincian peruntukan pengeluaran..."></textarea>
                </div>

                <!-- File Upload Dropzone (Receipt Upload) -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Bukti Struk / Kwitansi (Opsional)</label>
                    <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-brandGreen transition duration-200 bg-slate-50/50 cursor-pointer">
                        <input type="file" id="receipt" name="receipt" accept="image/jpeg,image/png,image/jpg,application/pdf" @change="handleFileSelect"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        
                        <template x-if="!receiptName">
                            <div class="space-y-1">
                                <svg class="w-8 h-8 mx-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-xs font-semibold text-slate-600">Klik atau drag file struk ke sini</p>
                                <p class="text-[10px] text-slate-400">JPG, PNG, atau PDF (Maks. 5MB)</p>
                            </div>
                        </template>

                        <template x-if="receiptName">
                            <div class="space-y-2">
                                <template x-if="previewUrl">
                                    <img :src="previewUrl" class="w-16 h-16 object-cover rounded-lg mx-auto border border-slate-200 shadow-sm">
                                </template>
                                <p class="text-xs font-bold text-brandGreen truncate max-w-[200px] mx-auto" x-text="receiptName"></p>
                                <span class="text-[10px] text-slate-400 hover:text-red-500 font-semibold block">Klik untuk mengganti file</span>
                            </div>
                        </template>
                    </div>
                    @error('receipt')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-semibold py-3 px-4 rounded-xl shadow-md transition duration-200 uppercase tracking-wider text-xs flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Simpan Overhead & Ekstra</span>
                </button>
            </form>
        </div>

        <!-- History Table -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Riwayat Overhead & Pengeluaran Ekstra</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Daftar biaya operasional kantor, kesejahteraan, dan keadaan darurat.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">Overhead Logs</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                                <th class="py-3.5 px-4">Tanggal</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Judul & Justifikasi</th>
                                <th class="py-3.5 px-4 text-right">Nominal (Rp)</th>
                                <th class="py-3.5 px-4 text-center">Struk Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($overheads as $item)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-4 px-4 text-slate-500 font-medium whitespace-nowrap">
                                        {{ $item->expense_date ? $item->expense_date->format('d M Y') : $item->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if(str_contains($item->category, 'Infrastruktur'))
                                            <span class="px-2.5 py-1 rounded font-bold text-[9px] bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                                                Infrastruktur
                                            </span>
                                        @elseif(str_contains($item->category, 'Kesejahteraan'))
                                            <span class="px-2.5 py-1 rounded font-bold text-[9px] bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                                Kesejahteraan
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded font-bold text-[9px] bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                                                Biaya Darurat
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="font-bold text-brandNavy block mb-0.5">{{ $item->title }}</span>
                                        <span class="text-slate-500 text-[11px] block">{{ $item->description ?? '-' }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-right font-extrabold text-slate-800 whitespace-nowrap">
                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        @if($item->receipt_path)
                                            <a href="{{ asset($item->receipt_path) }}" target="_blank"
                                                class="inline-flex items-center space-x-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-bold transition duration-150">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>Lihat Struk</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400 text-[10px] italic">Tanpa Struk</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l2-2 4 4m4-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Belum ada riwayat overhead & pengeluaran ekstra yang dicatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($overheads->hasPages())
                <div class="mt-6 pt-4 border-t border-slate-100">
                    {{ $overheads->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
