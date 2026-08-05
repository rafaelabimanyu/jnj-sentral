@extends('layouts.app', ['title' => 'Overhead & Manajemen Ekstra', 'pageHeader' => 'Overhead & Manajemen Ekstra'])

@section('content')
<style>
    /* Premium Industrial-Modern Theme Overrides */
    body {
        background-color: #F4F6F8 !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
    }

    .ind-card {
        background-color: #FFFFFF !important;
        border: 1px solid #E5E7EB !important;
        border-radius: 6px !important;
        box-shadow: none !important;
        transition: all 0.2s ease-in-out;
    }

    .ind-card-navy {
        background-color: #0F2A44 !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 6px !important;
        box-shadow: none !important;
    }

    .ind-header {
        font-family: 'Inter', sans-serif !important;
        text-transform: uppercase !important;
        font-weight: 700 !important;
        letter-spacing: 0.12em !important;
        color: #0F2A44 !important;
    }

    .ind-input {
        width: 100%;
        background-color: #FFFFFF !important;
        border: 1px solid #D1D5DB !important;
        border-radius: 6px !important;
        padding: 0.65rem 0.85rem !important;
        font-size: 0.875rem !important;
        color: #1F2937 !important;
        transition: all 0.2s ease-in-out !important;
        font-family: 'Inter', sans-serif !important;
    }

    .ind-input:focus {
        border-color: #0F2A44 !important;
        box-shadow: 0 0 0 2px rgba(15, 42, 68, 0.1) !important;
        outline: none !important;
    }

    .ind-input-accent:focus {
        border-color: #1FAF5A !important;
        box-shadow: 0 0 0 2px rgba(31, 175, 90, 0.1) !important;
        outline: none !important;
    }

    .ind-btn-primary {
        background-color: #1FAF5A !important;
        color: #FFFFFF !important;
        font-weight: 700 !important;
        letter-spacing: 0.1em !important;
        text-transform: uppercase !important;
        border-radius: 6px !important;
        transition: all 0.2s ease-in-out !important;
        border: 1px solid #1FAF5A !important;
    }

    .ind-btn-primary:hover {
        background-color: #1a964d !important;
        border-color: #1a964d !important;
    }

    .ind-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.125rem 0.375rem;
        border-radius: 4px;
        font-size: 0.6875rem;
        font-weight: 850;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .ind-badge-action {
        background-color: #1FAF5A !important;
        color: #FFFFFF !important;
    }

    /* Small caps table headers */
    .ind-th {
        font-family: 'Inter', sans-serif !important;
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        color: #4B5563 !important;
        border-bottom: 2px solid #E5E7EB !important;
        padding: 1rem 1rem !important;
    }

    .ind-td {
        padding: 1rem 1rem !important;
        font-size: 0.8rem !important;
        color: #374151 !important;
        border-bottom: 1px solid #F3F4F6 !important;
    }

    /* Custom scrollbars for file uploads */
    .custom-upload-zone {
        border: 2px dashed #D1D5DB;
        border-radius: 6px;
        transition: all 0.2s ease-in-out;
        background-color: #F9FAFB;
    }
    .custom-upload-zone:hover {
        border-color: #1FAF5A;
        background-color: #FFFFFF;
    }
</style>

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
        
        <!-- Total Overhead Metric Card (Focal Point Room Control) -->
        <div class="lg:col-span-1 ind-card-navy p-6 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Total Overhead (Hari Ini)</span>
                    <span class="ind-badge ind-badge-action">Daily Burn</span>
                </div>
                <h2 class="text-3xl font-extrabold text-white tracking-tight">
                    Rp {{ number_format($totalOverhead, 0, ',', '.') }}
                </h2>
                <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                    Beban pengeluaran hari ini (Daily Burn Rate dari prorata aktif + sekali bayar hari ini).
                </p>
            </div>
            
            <div class="mt-6 pt-4 border-t border-slate-700/60 flex items-center justify-between text-xs text-slate-300">
                <span>Rincian Beban Kategori</span>
                <span class="font-bold text-[#1FAF5A]">Hari Ini</span>
            </div>
        </div>

        <!-- Visual Breakdown & Progress Bars Card -->
        <div class="lg:col-span-2 ind-card p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="ind-header text-xs">Distribusi Biaya Overhead & Ekstra</h3>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Pemantauan beban harian aktif</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-2">
                    
                    <!-- Category 1: Infrastruktur -->
                    <div class="p-4 rounded-[4px] bg-slate-50 border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Infrastruktur</span>
                            <span class="text-xs font-extrabold text-blue-600">{{ $infraPercentage }}%</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-800">Rp {{ number_format($infraTotal, 0, ',', '.') }}</h4>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full mt-3 overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full" style="width: {{ $infraPercentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">WiFi, Listrik, Sewa Kantor</p>
                    </div>

                    <!-- Category 2: Kesejahteraan -->
                    <div class="p-4 rounded-[4px] bg-slate-50 border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Kesejahteraan</span>
                            <span class="text-xs font-extrabold text-[#1FAF5A]">{{ $welfarePercentage }}%</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-800">Rp {{ number_format($welfareTotal, 0, ',', '.') }}</h4>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full mt-3 overflow-hidden">
                            <div class="bg-[#1FAF5A] h-full rounded-full" style="width: {{ $welfarePercentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">Family Gathering, Konsumsi, Bonus</p>
                    </div>

                    <!-- Category 3: Darurat -->
                    <div class="p-4 rounded-[4px] bg-slate-50 border border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Tak Terduga (Darurat)</span>
                            <span class="text-xs font-extrabold text-amber-500">{{ $unexpectedPercentage }}%</span>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-800">Rp {{ number_format($unexpectedTotal, 0, ',', '.') }}</h4>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full mt-3 overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full" style="width: {{ $unexpectedPercentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">Perbaikan Alat, Kebocoran, Darurat</p>
                    </div>

                </div>
            </div>

            <!-- Chart Bar visual representation -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex rounded-full h-2 overflow-hidden bg-slate-100">
                <div class="bg-blue-600 h-full" style="width: {{ $infraPercentage }}%" title="Infrastruktur: {{ $infraPercentage }}%"></div>
                <div class="bg-[#1FAF5A] h-full" style="width: {{ $welfarePercentage }}%" title="Kesejahteraan: {{ $welfarePercentage }}%"></div>
                <div class="bg-amber-500 h-full" style="width: {{ $unexpectedPercentage }}%" title="Biaya Tak Terduga: {{ $unexpectedPercentage }}%"></div>
            </div>
        </div>

    </div>

    <!-- Main Grid: Form & History Table -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Input Overhead Expense -->
        <div class="lg:col-span-1 ind-card p-6 h-fit">
            <div class="mb-5 pb-3 border-b border-slate-100">
                <h3 class="ind-header text-xs">PENCATATAN OVERHEAD & EKSTRA</h3>
                <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Catat biaya fasilitas kantor, event tim, atau pengeluaran darurat.</p>
            </div>

            <form method="POST" action="{{ route('admin_ops.overhead_expenses.store') }}" enctype="multipart/form-data" class="space-y-4"
                x-data="{
                    isProrated: {{ old('is_prorated', 0) ? 'true' : 'false' }},
                    prorationDays: {{ old('proration_days', 30) }},
                    amount: '{{ old('amount') }}',
                    get dailyAmountText() {
                        if (!this.amount || this.amount <= 0) return '';
                        const daily = Math.floor(this.amount / this.prorationDays);
                        const formatted = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(daily);
                        return `✨ Otomatis: Sistem mencatat beban biaya Rp ${formatted} / hari.`;
                    }
                }">
                @csrf

                <!-- Kategori Overhead -->
                <div>
                    <label for="category" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kategori Non-Proyek</label>
                    <select id="category" name="category" required class="ind-input">
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
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="ind-input"
                        placeholder="Contoh: Tagihan WiFi Indihome Juli / Servis Jet Cleaner">
                    @error('title')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nominal Pengeluaran -->
                <div>
                    <label for="amount" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nominal Biaya (Rp)</label>
                    <input type="number" id="amount" name="amount" x-model="amount" required min="0" step="0.01" class="ind-input ind-input-accent"
                        placeholder="Contoh: 750000">
                    <div x-show="isProrated && dailyAmountText" x-transition.duration.300ms class="mt-2 text-xs font-bold text-[#1FAF5A]" x-text="dailyAmountText"></div>
                    @error('amount')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Distribusi Biaya (Toggle Switch Machinery Style) -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tipe Distribusi Biaya</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="isProrated = true" 
                            :class="isProrated ? 'bg-[#0F2A44] text-white border-[#0F2A44]' : 'bg-white text-[#0F2A44] border-[#0F2A44] hover:bg-slate-50'"
                            class="border text-center py-2 text-xs font-bold uppercase tracking-wider transition-all duration-200 focus:outline-none rounded-[4px]">
                            Bulanan/Berulang
                        </button>
                        <button type="button" @click="isProrated = false" 
                            :class="!isProrated ? 'bg-[#0F2A44] text-white border-[#0F2A44]' : 'bg-white text-[#0F2A44] border-[#0F2A44] hover:bg-slate-50'"
                            class="border text-center py-2 text-xs font-bold uppercase tracking-wider transition-all duration-200 focus:outline-none rounded-[4px]">
                            Sekali Bayar
                        </button>
                    </div>
                    <input type="hidden" name="is_prorated" :value="isProrated ? 1 : 0">
                </div>

                <!-- Pembagi Waktu Aktif (Dropdown jika prorata) -->
                <div x-show="isProrated" x-transition.duration.300ms>
                    <label for="proration_days" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pembagi Waktu Aktif</label>
                    <select id="proration_days" name="proration_days" x-model="prorationDays" class="ind-input">
                        <option value="30">30 Hari Penuh</option>
                        <option value="26">26 Hari Kerja</option>
                    </select>
                    @error('proration_days')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pengeluaran -->
                <div>
                    <label for="expense_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Transaksi</label>
                    <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required class="ind-input">
                    @error('expense_date')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi / Catatan -->
                <div>
                    <label for="description" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi / Justifikasi</label>
                    <textarea id="description" name="description" rows="3" class="ind-input"
                        placeholder="Rincian peruntukan pengeluaran..."></textarea>
                </div>

                <!-- File Upload Dropzone (Receipt Upload) -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Bukti Struk / Kwitansi (Opsional)</label>
                    <div class="relative custom-upload-zone p-4 text-center cursor-pointer">
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
                                    <img :src="previewUrl" class="w-16 h-16 object-cover rounded-[4px] mx-auto border border-slate-200 shadow-sm">
                                </template>
                                <p class="text-xs font-bold text-[#1FAF5A] truncate max-w-[200px] mx-auto" x-text="receiptName"></p>
                                <span class="text-[10px] text-slate-400 hover:text-red-500 font-semibold block">Klik untuk mengganti file</span>
                            </div>
                        </template>
                    </div>
                    @error('receipt')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full ind-btn-primary py-3 px-4 shadow-sm flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Simpan Overhead & Ekstra</span>
                </button>
            </form>
        </div>

        <!-- History Table -->
        <div class="lg:col-span-2 ind-card p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="ind-header text-xs">Riwayat Overhead & Pengeluaran Ekstra</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Daftar biaya operasional kantor, kesejahteraan, dan keadaan darurat.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-[4px] text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">Overhead Logs</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr>
                                <th class="ind-th">Tanggal</th>
                                <th class="ind-th">Kategori</th>
                                <th class="ind-th">Judul & Justifikasi</th>
                                <th class="ind-th text-right">Nominal (Rp)</th>
                                <th class="ind-th text-center">Struk Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($overheads as $item)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="ind-td text-slate-500 font-medium whitespace-nowrap">
                                        {{ $item->expense_date ? $item->expense_date->format('d M Y') : $item->created_at->format('d M Y') }}
                                    </td>
                                    <td class="ind-td whitespace-nowrap">
                                        @if(str_contains($item->category, 'Infrastruktur'))
                                            <span class="px-2.5 py-1 rounded-[4px] font-bold text-[9px] bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                                                Infrastruktur
                                            </span>
                                        @elseif(str_contains($item->category, 'Kesejahteraan'))
                                            <span class="px-2.5 py-1 rounded-[4px] font-bold text-[9px] bg-emerald-50 text-[#1FAF5A] border border-emerald-200 uppercase">
                                                Kesejahteraan
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-[4px] font-bold text-[9px] bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                                                Biaya Darurat
                                            </span>
                                        @endif
                                    </td>
                                    <td class="ind-td">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-brandNavy block">{{ $item->title }}</span>
                                            @if($item->is_prorated)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-[4px] text-[9px] font-extrabold bg-[#1FAF5A]/10 text-[#1FAF5A] border border-[#1FAF5A]/20">
                                                    {{ $item->proration_days }} HARI
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-slate-500 text-[11px] block mt-0.5">{{ $item->description ?? '-' }}</span>
                                    </td>
                                    <td class="ind-td text-right whitespace-nowrap">
                                        <span class="font-extrabold text-slate-800 block">Rp {{ number_format($item->amount, 0, ',', '.') }}</span>
                                        @if($item->is_prorated)
                                            <span class="text-[#1FAF5A] text-[10px] block mt-0.5 font-bold">Rp {{ number_format($item->daily_amount, 0, ',', '.') }} / hari</span>
                                        @endif
                                    </td>
                                    <td class="ind-td text-center whitespace-nowrap">
                                        @if($item->receipt_path)
                                            <a href="{{ asset($item->receipt_path) }}" target="_blank"
                                                class="inline-flex items-center space-x-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-[4px] text-[10px] font-bold transition duration-150">
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
