@extends('layouts.app', ['title' => 'Laporan Keuangan', 'pageHeader' => 'Laporan Finansial Bulanan'])

@section('content')
<div class="space-y-6">

    <!-- Filter Form -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <form method="GET" action="{{ route('owner.reports') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="month" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Bulan</label>
                <select id="month" name="month" class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="year" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tahun</label>
                <select id="year" name="year" class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                    @php $currentYear = date('Y'); @endphp
                    @for($i = $currentYear; $i >= $currentYear - 5; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="bg-brandNavy hover:bg-slate-800 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md transition duration-200 uppercase tracking-wider text-xs">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pendapatan -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Pendapatan</h3>
            <div class="text-2xl font-black text-brandGreen">Rp {{ number_format($totalIncomes, 0, ',', '.') }}</div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Pengeluaran (Approved)</h3>
            <div class="text-2xl font-black text-red-500">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
        </div>

        <!-- Laba/Rugi -->
        <div class="bg-white border {{ $netProfit >= 0 ? 'border-brandGreen bg-emerald-50' : 'border-red-500 bg-red-50' }} rounded-2xl p-6 shadow-sm">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Laba Bersih</h3>
            <div class="text-2xl font-black {{ $netProfit >= 0 ? 'text-brandGreen' : 'text-red-500' }}">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Breakdown Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pendapatan per Klien -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wide mb-4">Rincian Pendapatan per Klien</h3>
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                        <th class="py-3 px-4">Klien</th>
                        <th class="py-3 px-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($incomesByClient as $income)
                        <tr>
                            <td class="py-3 px-4 font-bold text-slate-700">{{ $income->client_name ?? 'Lainnya' }}</td>
                            <td class="py-3 px-4 text-right font-bold text-brandGreen">Rp {{ number_format($income->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-slate-400">Belum ada data pendapatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pengeluaran per Kategori -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wide mb-4">Rincian Pengeluaran per Kategori</h3>
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($expensesByCategory as $expense)
                        <tr>
                            <td class="py-3 px-4 font-bold text-slate-700 uppercase text-[10px]">{{ str_replace('_', ' ', $expense->category) }}</td>
                            <td class="py-3 px-4 text-right font-bold text-red-500">Rp {{ number_format($expense->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-slate-400">Belum ada data pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
