@extends('layouts.app', ['title' => 'Dashboard Owner', 'pageHeader' => 'Dashboard Ringkasan Eksekutif'])

@section('content')
<!-- We import Alpine.js and Chart.js via CDN -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6" x-data="{ activeTab: 'field_ops' }">
    
    <!-- Top Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Metric 1: Total Pemasukan Bulan Ini -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between hover:border-slate-300 transition duration-150">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Pemasukan Bulan Ini</span>
                <span class="text-2xl font-black text-brandNavy">Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</span>
            </div>
            <div class="mt-4 flex items-center space-x-1">
                <span class="w-1.5 h-1.5 rounded-full bg-brandGreen"></span>
                <span class="text-[10px] font-semibold text-slate-450">Akumulasi Real-Time</span>
            </div>
        </div>

        <!-- Metric 2: Daily Burn Rate (Overhead) -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between hover:border-slate-300 transition duration-150">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Overhead (Daily Burn)</span>
                <span class="text-2xl font-black text-brandNavy">Rp {{ number_format($totalOverheadBerjalan, 0, ',', '.') }}<span class="text-xs font-normal text-slate-400">/hari</span></span>
            </div>
            <div class="mt-4 flex items-center space-x-1">
                <span class="w-1.5 h-1.5 rounded-full bg-brandGreen"></span>
                <span class="text-[10px] font-semibold text-slate-450">Prorata Aktif</span>
            </div>
        </div>

        <!-- Metric 3: Total Pekerjaan Selesai -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between hover:border-slate-300 transition duration-150">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Pekerjaan Lapangan</span>
                <span class="text-2xl font-black text-brandNavy">{{ $totalPekerjaanSelesai }} <span class="text-xs font-normal text-slate-400">selesai</span></span>
            </div>
            <div class="mt-4 flex items-center space-x-1">
                <span class="w-1.5 h-1.5 rounded-full bg-brandGreen"></span>
                <span class="text-[10px] font-semibold text-slate-450">Bulan Berjalan</span>
            </div>
        </div>

        <!-- Metric 4: Komisi Aktif -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between hover:border-slate-300 transition duration-150">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Komisi Marketer Pending</span>
                <span class="text-2xl font-black text-red-500">Rp {{ number_format($komisiAktif, 0, ',', '.') }}</span>
            </div>
            <div class="mt-4 flex items-center space-x-1">
                <span class="w-1.5 h-1.5 rounded-full bg-red-450"></span>
                <span class="text-[10px] font-semibold text-slate-450">Perlu Pembayaran</span>
            </div>
        </div>
    </div>

    <!-- Main Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left & Center Column (2/3 width): Chart & Recent Activity -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Financial Trend Chart -->
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wide">Tren Profitabilitas & Biaya</h3>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">6 Bulan Terakhir</span>
                </div>
                <div class="h-64 relative">
                    <canvas id="profitTrendChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity Tabs -->
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <!-- Tabs Header -->
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-3 mb-4">
                    <div class="flex flex-wrap gap-4">
                        <button @click="activeTab = 'field_ops'" :class="activeTab === 'field_ops' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-455 font-semibold'" class="pb-1 text-xs uppercase tracking-wide transition duration-150">
                            Operasional Lapangan
                        </button>
                        <button @click="activeTab = 'overhead'" :class="activeTab === 'overhead' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-455 font-semibold'" class="pb-1 text-xs uppercase tracking-wide transition duration-150">
                            Overhead & Ekstra
                        </button>
                    </div>
                    <span class="text-[10px] font-bold text-slate-450 uppercase tracking-widest">Aktivitas Terbaru</span>
                </div>

                <!-- Tab content 1: Field Ops -->
                <div x-show="activeTab === 'field_ops'">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[9px] tracking-wider">
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Deskripsi Pekerjaan</th>
                                    <th class="py-3 px-4">Teknisi</th>
                                    <th class="py-3 px-4 text-right">Total Biaya</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentFieldOperations as $op)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="py-3 px-4 text-slate-500 font-medium whitespace-nowrap">{{ $op->operation_date->format('d M Y') }}</td>
                                        <td class="py-3 px-4 font-semibold text-slate-700 max-w-xs truncate">{{ $op->description }}</td>
                                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                            @foreach($op->technicians as $tech)
                                                <span class="inline-block bg-slate-100 text-slate-700 text-[9px] font-bold px-1.5 py-0.5 rounded uppercase mr-1">{{ $tech->employee->name ?? 'Teknisi' }}</span>
                                            @endforeach
                                        </td>
                                        <td class="py-3 px-4 text-right font-black text-slate-800 whitespace-nowrap">Rp {{ number_format($op->total_cost, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400">Belum ada data operasional lapangan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab content 2: Overhead -->
                <div x-show="activeTab === 'overhead'" x-cloak>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[9px] tracking-wider">
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4">Deskripsi</th>
                                    <th class="py-3 px-4 text-right">Nominal</th>
                                    <th class="py-3 px-4 text-center">Tipe</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentOverheads as $oh)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="py-3 px-4 text-slate-500 font-medium whitespace-nowrap">{{ $oh->expense_date->format('d M Y') }}</td>
                                        <td class="py-3 px-4 text-slate-650 font-medium whitespace-nowrap"><span class="px-2 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-700 uppercase">{{ $oh->category }}</span></td>
                                        <td class="py-3 px-4 font-semibold text-slate-700 max-w-xs truncate">{{ $oh->description }}</td>
                                        <td class="py-3 px-4 text-right font-black text-slate-850 whitespace-nowrap">Rp {{ number_format($oh->amount, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-center whitespace-nowrap">
                                            @if($oh->is_prorated)
                                                <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-emerald-50 text-brandGreen border border-emerald-250 uppercase">{{ $oh->proration_days }} HARI</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase">FLAT</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400">Belum ada data overhead.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column (1/3 width): Approval Center -->
        <div class="lg:col-span-1 bg-white border border-slate-200 rounded-xl p-6 flex flex-col justify-between h-fit">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-xs font-black text-brandNavy uppercase tracking-wide">Approval Center</h3>
                    <span class="px-2.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-250 uppercase">Perlu Tindakan</span>
                </div>

                <!-- Pending Request List -->
                <div class="space-y-4 max-h-[420px] overflow-y-auto pr-1">
                    @forelse($pendingExpenses as $expense)
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $expense->created_at->format('d M Y H:i') }}</span>
                                <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-blue-50 text-brandNavy border border-brandNavy/15 uppercase">
                                    {{ str_replace('_', ' ', $expense->category) }}
                                </span>
                            </div>
                            <h4 class="text-sm font-black text-slate-800 mb-1">Rp {{ number_format($expense->amount, 2, ',', '.') }}</h4>
                            <p class="text-[11px] text-slate-500 mb-3 leading-relaxed">
                                @if($expense->client_name)
                                    <span class="font-bold text-brandNavy block mb-0.5">Klien: {{ $expense->client_name }}</span>
                                @endif
                                {{ $expense->description }}
                            </p>
                            
                            <div class="flex items-center space-x-2">
                                <!-- Approve Form -->
                                <form method="POST" action="{{ route('owner.expenses.approve', $expense->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengeluaran ini?');" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-brandGreen hover:bg-brandGreenHover text-white text-[9px] font-black py-2 px-3 rounded transition duration-150 shadow-sm uppercase tracking-wider">
                                        Approve
                                    </button>
                                </form>
                                <!-- Reject Form -->
                                <form method="POST" action="{{ route('owner.expenses.reject', $expense->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengeluaran ini?');" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full text-center bg-slate-800 hover:bg-red-700 text-slate-200 hover:text-white text-[9px] font-black py-2 px-3 rounded transition duration-150 border border-slate-700 uppercase tracking-wider">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <span class="text-xs text-slate-400">Tidak ada pengajuan pengeluaran tertunda.</span>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="mt-6 pt-3 border-t border-slate-100 text-center">
                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">J&J Sentral Security Audit</span>
            </div>
        </div>

    </div>

</div>

<!-- Chart JS Setup Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('profitTrendChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Pendapatan Kotor',
                        data: chartData.income,
                        backgroundColor: 'rgba(15, 42, 68, 0.1)',
                        borderColor: '#0F2A44',
                        borderWidth: 1.5,
                        type: 'line',
                        tension: 0.2
                    },
                    {
                        label: 'Pengeluaran + Cost',
                        data: chartData.expense,
                        backgroundColor: 'rgba(239, 68, 68, 0.05)',
                        borderColor: '#EF4444',
                        borderWidth: 1.5,
                        type: 'line',
                        tension: 0.2
                    },
                    {
                        label: 'Laba Bersih',
                        data: chartData.profit,
                        backgroundColor: '#1FAF5A',
                        borderColor: '#1FAF5A',
                        borderWidth: 0,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 10,
                                weight: '600',
                                family: 'Outfit, sans-serif'
                            },
                            color: '#475569'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 9,
                                weight: '600',
                                family: 'Outfit, sans-serif'
                            },
                            color: '#64748B'
                        }
                    },
                    y: {
                        grid: {
                            color: '#F1F5F9'
                        },
                        ticks: {
                            font: {
                                size: 9,
                                weight: '600',
                                family: 'Outfit, sans-serif'
                            },
                            color: '#64748B',
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
