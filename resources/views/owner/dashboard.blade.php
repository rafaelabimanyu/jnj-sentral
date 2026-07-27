@extends('layouts.app', ['title' => 'Dashboard Owner', 'pageHeader' => 'Dashboard Ringkasan Eksekutif'])

@section('content')
@php
    // Data mockup aman jika controller belum mengirimkan data riil
    $metrics = $financialMetrics ?? (object)[
        'gross_income' => 150000000.00,
        'total_expenses' => 450000000.00, // Diubah untuk ilustrasi, mari gunakan 45jt
        'net_income' => 105000000.00
    ];
    // Pastikan total pengeluaran mockup tertulis 45jt
    $metrics->total_expenses = 45000000.00;
    $metrics->net_income = $metrics->gross_income - $metrics->total_expenses;

    $expensesList = $pendingExpenses ?? collect([
        (object)[
            'id' => 1,
            'created_at' => \Carbon\Carbon::now()->subHours(2),
            'creator' => (object)['name' => 'Admin Ardy'],
            'category' => 'marketing_fee',
            'amount' => 6000000.00,
            'description' => 'Fee Marketing 30% RS Abdi Waluyo'
        ],
        (object)[
            'id' => 2,
            'created_at' => \Carbon\Carbon::now()->subHours(4),
            'creator' => (object)['name' => 'Admin Ardy'],
            'category' => 'unexpected',
            'amount' => 1500000.00,
            'description' => 'Pembelian pompa darurat di RS Sentosa'
        ]
    ]);
@endphp

<!-- Financial Overview Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Gross Income Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-2">Pendapatan Kotor</span>
            <h3 class="text-2xl font-extrabold text-brandNavy">Rp {{ number_format($metrics->gross_income, 2, ',', '.') }}</h3>
            <span class="text-xs font-medium text-emerald-600 mt-1 inline-flex items-center space-x-1">
                <span>Layanan B2B & Residensial</span>
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <!-- Total Expenses Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-2">Total Pengeluaran</span>
            <h3 class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($metrics->total_expenses, 2, ',', '.') }}</h3>
            <span class="text-xs font-medium text-amber-600 mt-1 inline-flex items-center space-x-1">
                <span>Operasional & Overhead</span>
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <!-- Net Income Card -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-2">Pendapatan Bersih (Net)</span>
            <h3 class="text-2xl font-extrabold text-brandGreen">Rp {{ number_format($metrics->net_income, 2, ',', '.') }}</h3>
            <span class="text-xs font-medium text-brandGreen mt-1 inline-flex items-center space-x-1">
                <span>Real-Time Kalkulasi Laba</span>
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-brandGreen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Visual Chart Mockup (Industrial Style) -->
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-md font-bold text-brandNavy uppercase tracking-wide">Tren Profitabilitas Rooterin</h3>
            <span class="text-xs font-medium text-slate-400">3 Bulan Terakhir</span>
        </div>
        
        <!-- Chart Mockup Visualizer -->
        <div class="h-64 flex items-end justify-between px-6 pt-4 border-b border-slate-200">
            <!-- May -->
            <div class="flex flex-col items-center w-1/4 group">
                <div class="w-12 bg-slate-200 rounded-t-lg h-36 transition duration-200 hover:bg-slate-300 relative flex justify-center">
                    <span class="absolute -top-6 text-[10px] font-bold text-slate-500">80jt</span>
                </div>
                <div class="w-12 bg-brandGreen/40 rounded-t-lg h-24 transition duration-200 hover:bg-brandGreen/50 relative flex justify-center mt-[-96px] z-10">
                    <span class="absolute -top-6 text-[10px] font-bold text-brandGreen">55jt</span>
                </div>
                <span class="text-xs font-semibold text-slate-500 mt-3">Mei</span>
            </div>

            <!-- June -->
            <div class="flex flex-col items-center w-1/4 group">
                <div class="w-12 bg-slate-200 rounded-t-lg h-44 transition duration-200 hover:bg-slate-300 relative flex justify-center">
                    <span class="absolute -top-6 text-[10px] font-bold text-slate-500">120jt</span>
                </div>
                <div class="w-12 bg-brandGreen/40 rounded-t-lg h-32 transition duration-200 hover:bg-brandGreen/50 relative flex justify-center mt-[-128px] z-10">
                    <span class="absolute -top-6 text-[10px] font-bold text-brandGreen">85jt</span>
                </div>
                <span class="text-xs font-semibold text-slate-500 mt-3">Juni</span>
            </div>

            <!-- July -->
            <div class="flex flex-col items-center w-1/4 group">
                <div class="w-12 bg-slate-200 rounded-t-lg h-52 transition duration-200 hover:bg-slate-300 relative flex justify-center">
                    <span class="absolute -top-6 text-[10px] font-bold text-slate-500">150jt</span>
                </div>
                <div class="w-12 bg-brandGreen rounded-t-lg h-40 transition duration-200 hover:bg-brandGreenHover relative flex justify-center mt-[-160px] z-10">
                    <span class="absolute -top-6 text-[10px] font-bold text-brandGreen">105jt</span>
                </div>
                <span class="text-xs font-semibold text-slate-500 mt-3">Juli</span>
            </div>
        </div>
        <div class="flex justify-center space-x-6 mt-4">
            <div class="flex items-center space-x-1.5">
                <span class="w-3 h-3 bg-slate-200 rounded-sm"></span>
                <span class="text-[11px] font-semibold text-slate-500">Pendapatan Kotor</span>
            </div>
            <div class="flex items-center space-x-1.5">
                <span class="w-3 h-3 bg-brandGreen rounded-sm"></span>
                <span class="text-[11px] font-semibold text-slate-500">Laba Bersih</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Approval Center -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h3 class="text-md font-bold text-brandNavy uppercase tracking-wide">Approval Center</h3>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">Perlu Tindakan</span>
            </div>

            <!-- Pending Request List -->
            <div class="space-y-4 max-h-80 overflow-y-auto pr-1">
                @forelse($expensesList as $expense)
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $expense->created_at->format('d M Y H:i') }}</span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-blue-150 text-brandNavy border border-brandNavy/15 uppercase">
                                {{ str_replace('_', ' ', $expense->category) }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 mb-1">Rp {{ number_format($expense->amount, 2, ',', '.') }}</h4>
                        <p class="text-xs text-slate-500 mb-3 truncate">{{ $expense->description }}</p>
                        
                        <div class="flex items-center space-x-2">
                            <!-- Approve Form -->
                            <form method="POST" action="{{ route('owner.expenses.approve', $expense->id ?? 1) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengeluaran ini?');" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full text-center bg-brandGreen hover:bg-brandGreenHover text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition duration-150 shadow-sm uppercase tracking-wider">
                                    Approve
                                </button>
                            </form>
                            <!-- Reject Form -->
                            <form method="POST" action="{{ route('owner.expenses.reject', $expense->id ?? 1) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengeluaran ini?');" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full text-center bg-slate-800 hover:bg-red-700 text-slate-200 hover:text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition duration-150 border border-slate-700 uppercase tracking-wider">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <span class="text-sm text-slate-400">Tidak ada pengeluaran tertunda.</span>
                    </div>
                @endforelse
            </div>
        </div>
        
        <div class="mt-4 pt-3 border-t border-slate-100 text-center">
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">J&J Sentral Security Audit</span>
        </div>
    </div>

</div>
@endsection
