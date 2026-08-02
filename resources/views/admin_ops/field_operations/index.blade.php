@extends('layouts.app', ['title' => 'Operasional Lapangan & Teknisi', 'pageHeader' => 'Operasional Lapangan & Teknisi'])

@section('content')
<div class="space-y-8">

    <!-- Top Action Header & Main Navigation Button -->
    <div class="flex flex-wrap items-center justify-between bg-white border border-slate-200 rounded-2xl p-6 shadow-sm gap-4">
        <div>
            <h2 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Dashboard Operasional Lapangan & Teknisi</h2>
            <p class="text-xs text-slate-400 mt-0.5">Analisis ringkasan pengeluaran, filter riwayat transaksi, dan audit finansial tim lapangan.</p>
        </div>
        <a href="{{ route('admin_ops.field_operations.create') }}"
            class="inline-flex items-center space-x-2 px-5 py-3 bg-brandGreen hover:bg-brandGreenHover text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-md transition duration-200 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Catat Biaya Operasional</span>
        </a>
    </div>

    <!-- Top Section: 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric Card 1: Total Upah Teknisi -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Upah Teknisi</span>
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
                <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block">Total Pengeluaran Lapangan</span>
                <h3 class="text-xl font-extrabold text-white">
                    Rp {{ number_format($totalOverall, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-300">Gabungan biaya terfilter</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

    </div>

    <!-- Data Table & Search/Filter Controls -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
        
        <!-- Filter Header Bar -->
        <div class="pb-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Riwayat Master Operasional Lapangan</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Daftar audit pengerjaan operasional beserta rincian upah tim teknisi dan pengeluaran.</p>
            </div>

            <!-- Filter Controls Form -->
            <form method="GET" action="{{ route('admin_ops.field_operations.index') }}" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <!-- Search Input -->
                <div class="relative min-w-[200px] flex-1 sm:flex-none">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari teknisi / rincian..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Start Date -->
                <div class="flex items-center space-x-1 text-xs text-slate-400 font-semibold">
                    <span>Dari:</span>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:border-brandGreen transition duration-200">
                </div>

                <!-- End Date -->
                <div class="flex items-center space-x-1 text-xs text-slate-400 font-semibold">
                    <span>Sampai:</span>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 focus:outline-none focus:border-brandGreen transition duration-200">
                </div>

                <button type="submit"
                    class="px-3.5 py-2 bg-brandNavy hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-sm transition duration-200">
                    Filter
                </button>

                @if($search || $startDate || $endDate)
                    <a href="{{ route('admin_ops.field_operations.index') }}"
                        class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition duration-200">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table View -->
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
