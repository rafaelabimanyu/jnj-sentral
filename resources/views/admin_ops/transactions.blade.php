@extends('layouts.app', ['title' => 'Riwayat Transaksi', 'pageHeader' => 'Histori Transaksi Keseluruhan'])

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="{ tab: 'expense' }" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
    
    <!-- Header & Tabs -->
    <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
        <div class="flex space-x-6">
            <button @click="tab = 'expense'" :class="tab === 'expense' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-400 hover:text-slate-600 font-semibold'" 
                class="pb-1 text-sm uppercase tracking-wide transition duration-150">
                Semua Pengeluaran
            </button>
            <button @click="tab = 'income'" :class="tab === 'income' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-400 hover:text-slate-600 font-semibold'" 
                class="pb-1 text-sm uppercase tracking-wide transition duration-150">
                Semua Pendapatan
            </button>
        </div>
    </div>

    <!-- Tab Pengeluaran -->
    <div x-show="tab === 'expense'">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse mb-4">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                        <th class="py-3.5 px-4">Tanggal & Jam</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Klien / Detail</th>
                        <th class="py-3.5 px-4 text-right">Nominal</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="py-4 px-4 text-slate-500 font-medium">
                                {{ $expense->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-700 uppercase">
                                    {{ str_replace('_', ' ', $expense->category) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-600 font-medium max-w-xs truncate">
                                <span class="font-bold text-brandNavy block mb-0.5">{{ $expense->client->name ?? 'Overhead Umum' }}</span>
                                {{ $expense->description }}
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-slate-800">
                                Rp {{ number_format($expense->amount, 2, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($expense->status === 'approved')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">Approved</span>
                                @elseif($expense->status === 'pending')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase">Pending</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada data pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Pagination Links -->
            <div class="mt-4">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>

    <!-- Tab Pendapatan -->
    <div x-show="tab === 'income'" x-cloak>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse mb-4">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                        <th class="py-3.5 px-4">Tgl Pelayanan</th>
                        <th class="py-3.5 px-4">Klien</th>
                        <th class="py-3.5 px-4">Detail Pelayanan</th>
                        <th class="py-3.5 px-4 text-right">Nominal Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($incomes as $income)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="py-4 px-4 text-slate-500 font-medium">
                                {{ \Carbon\Carbon::parse($income->service_date)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-4 font-bold text-brandNavy">
                                {{ $income->client->name ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-slate-600 font-medium max-w-xs truncate">
                                {{ $income->service_detail }}
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-brandGreen">
                                Rp {{ number_format($income->gross_amount, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">Belum ada data pendapatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="mt-4">
                {{ $incomes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
