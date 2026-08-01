@extends('layouts.app', ['title' => 'Manajemen Komisi & Fee Marketing', 'pageHeader' => 'Manajemen Komisi & Fee Marketing'])

@section('content')
<div class="space-y-8" x-data="{ 
    projectValue: '{{ old('project_value', '') }}', 
    feePercentage: '{{ old('fee_percentage', '') }}',
    status: '{{ old('status', 'Pending') }}',
    get calculatedFee() {
        let val = parseFloat(this.projectValue) || 0;
        let pct = parseFloat(this.feePercentage) || 0;
        let result = val * (pct / 100);
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(result);
    }
}">

    <!-- Top Section: 3 Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Metric Card 1: Total Fee Paid Month -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Fee Terbayar (Bulan Ini)</span>
                <h3 class="text-2xl font-extrabold text-brandGreen">
                    Rp {{ number_format($totalPaidThisMonth, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400">Komisi akuisisi klien yang telah lunas</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-brandGreen flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Metric Card 2: Pending Fees -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Komisi Belum Dibayar (Pending)</span>
                <h3 class="text-2xl font-extrabold text-amber-500">
                    Rp {{ number_format($pendingFeesTotal, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400">{{ $pendingFeesCount }} transaksi menunggu pembayaran</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Metric Card 3: Top Marketer -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Top Marketer / Mitras</span>
                <h3 class="text-xl font-extrabold text-brandNavy truncate max-w-[200px]" title="{{ $topMarketer }}">
                    {{ $topMarketer }}
                </h3>
                <p class="text-xs text-slate-400">Total komisi: <span class="font-bold text-slate-700">Rp {{ number_format($topMarketerFees, 0, ',', '.') }}</span></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-brandNavy flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>
        </div>

    </div>

    <!-- Main Grid: Form Input & Table History -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Modern Input Form -->
        <div class="lg:col-span-1 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm h-fit">
            <div class="mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Catat Fee Marketing Baru</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Input data akuisisi klien baru dan perhitungan komisi partner.</p>
            </div>

            <form method="POST" action="{{ route('admin_ops.marketing_fees.store') }}" class="space-y-4">
                @csrf

                <!-- Nama Marketer -->
                <div>
                    <label for="marketer_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Marketer / Agent</label>
                    <input type="text" id="marketer_name" name="marketer_name" value="{{ old('marketer_name') }}" required
                        class="w-full bg-slate-50 border @error('marketer_name') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: Hendra Wijaya">
                    @error('marketer_name')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Klien Terakuisisi -->
                <div>
                    <label for="client_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Klien B2B Terakuisisi</label>
                    <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" required
                        class="w-full bg-slate-50 border @error('client_name') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: Sushi Tei Grand Indonesia / RS Abdi Waluyo">
                    @error('client_name')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nilai Proyek -->
                <div>
                    <label for="project_value" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nilai Proyek (Rp)</label>
                    <input type="number" id="project_value" name="project_value" x-model="projectValue" required min="0" step="0.01"
                        class="w-full bg-slate-50 border @error('project_value') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: 15000000">
                    @error('project_value')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Persentase Fee -->
                <div>
                    <label for="fee_percentage" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Persentase Fee (%)</label>
                    <input type="number" id="fee_percentage" name="fee_percentage" x-model="feePercentage" required min="0" max="100" step="0.1"
                        class="w-full bg-slate-50 border @error('fee_percentage') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: 10">
                    @error('fee_percentage')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Realtime Calculation Preview Badge -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500">Estimasi Fee (Otmatis):</span>
                    <span class="text-sm font-extrabold text-brandGreen" x-text="calculatedFee">Rp 0</span>
                </div>

                <!-- Status Pembayaran -->
                <div>
                    <label for="status" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status Pembayaran</label>
                    <select id="status" name="status" x-model="status" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                        <option value="Pending">Pending (Belum Dibayar)</option>
                        <option value="Paid">Paid (Sudah Dibayar)</option>
                    </select>
                </div>

                <!-- Tanggal Pembayaran (Muncul Jika Paid) -->
                <div x-show="status === 'Paid'" transition>
                    <label for="payment_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Pembayaran</label>
                    <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                </div>

                <button type="submit"
                    class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-semibold py-3 px-4 rounded-xl shadow-md transition duration-200 uppercase tracking-wider text-xs flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Simpan Komisi Marketing</span>
                </button>
            </form>
        </div>

        <!-- Right: Professional History Table -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Riwayat Komisi Marketing</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Daftar fee komisi akuisisi klien beserta status pelunasannya.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">Acquisition History</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                                <th class="py-3.5 px-4">Marketer</th>
                                <th class="py-3.5 px-4">Klien Terakuisisi</th>
                                <th class="py-3.5 px-4 text-right">Nilai Proyek</th>
                                <th class="py-3.5 px-4 text-center">Fee (%)</th>
                                <th class="py-3.5 px-4 text-right">Nominal Fee</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($fees as $fee)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-4 px-4 font-bold text-brandNavy">
                                        {{ $fee->marketer_name }}
                                        <span class="block text-[10px] font-normal text-slate-400">{{ $fee->created_at->format('d M Y, H:i') }}</span>
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-700">
                                        {{ $fee->client_name }}
                                    </td>
                                    <td class="py-4 px-4 text-right font-medium text-slate-600">
                                        Rp {{ number_format($fee->project_value, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-bold text-slate-700">
                                        {{ number_format($fee->fee_percentage, 1) }}%
                                    </td>
                                    <td class="py-4 px-4 text-right font-extrabold text-slate-800">
                                        Rp {{ number_format($fee->fee_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($fee->status === 'Paid')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide inline-flex items-center space-x-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Paid</span>
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide inline-flex items-center space-x-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                <span>Pending</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if($fee->status === 'Pending')
                                            <form method="POST" action="{{ route('admin_ops.marketing_fees.pay', $fee->id) }}" onsubmit="return confirm('Konfirmasi pelunasan komisi ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-2.5 py-1 bg-brandGreen hover:bg-brandGreenHover text-white text-[10px] font-bold rounded-lg transition duration-200 shadow-sm">
                                                    Tandai Lunas
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[10px] text-slate-400 font-medium">
                                                {{ $fee->payment_date ? $fee->payment_date->format('d/m/Y') : 'Lunas' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Belum ada riwayat fee marketing yang dicatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($fees->hasPages())
                <div class="mt-6 pt-4 border-t border-slate-100">
                    {{ $fees->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
