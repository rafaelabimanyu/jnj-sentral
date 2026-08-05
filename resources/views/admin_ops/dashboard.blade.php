@extends('layouts.app', ['title' => 'Dashboard Admin Ops', 'pageHeader' => 'Pencatatan Operasional & Finansial'])

@section('content')
<!-- We import Alpine.js and Chart.js via CDN -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6" x-data="{ activeTab: 'field_ops', formTab: 'expense' }">
    
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
                        <button @click="activeTab = 'field_ops'" :class="activeTab === 'field_ops' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-450 font-semibold'" class="pb-1 text-xs uppercase tracking-wide transition duration-150">
                            Operasional Lapangan
                        </button>
                        <button @click="activeTab = 'overhead'" :class="activeTab === 'overhead' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-450 font-semibold'" class="pb-1 text-xs uppercase tracking-wide transition duration-150">
                            Overhead & Ekstra
                        </button>
                        <button @click="activeTab = 'incomes'" :class="activeTab === 'incomes' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-450 font-semibold'" class="pb-1 text-xs uppercase tracking-wide transition duration-150">
                            Pendapatan Proyek
                        </button>
                        <button @click="activeTab = 'expenses'" :class="activeTab === 'expenses' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-450 font-semibold'" class="pb-1 text-xs uppercase tracking-wide transition duration-150">
                            Pengeluaran Proyek
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

                <!-- Tab content 3: Incomes -->
                <div x-show="activeTab === 'incomes'" x-cloak>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[9px] tracking-wider">
                                    <th class="py-3 px-4">Tanggal Jasa</th>
                                    <th class="py-3 px-4">Klien</th>
                                    <th class="py-3 px-4">Detail Pekerjaan</th>
                                    <th class="py-3 px-4 text-right">Gross Income</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentIncomes as $inc)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="py-3 px-4 text-slate-500 font-medium whitespace-nowrap">{{ \Carbon\Carbon::parse($inc->service_date)->format('d M Y') }}</td>
                                        <td class="py-3 px-4 font-bold text-brandNavy whitespace-nowrap">{{ $inc->client_name }}</td>
                                        <td class="py-3 px-4 text-slate-650 font-medium max-w-xs truncate">{{ $inc->service_detail }}</td>
                                        <td class="py-3 px-4 text-right font-black text-brandGreen whitespace-nowrap">Rp {{ number_format($inc->gross_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400">Belum ada data pendapatan proyek.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab content 4: Expenses -->
                <div x-show="activeTab === 'expenses'" x-cloak>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[9px] tracking-wider">
                                    <th class="py-3 px-4">Tanggal Input</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4">Klien / Detail</th>
                                    <th class="py-3 px-4 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentExpenses as $exp)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="py-3 px-4 text-slate-500 font-medium whitespace-nowrap">{{ $exp->created_at->format('d M Y') }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap"><span class="px-2 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-700 uppercase">{{ str_replace('_', ' ', $exp->category) }}</span></td>
                                        <td class="py-3 px-4 text-slate-650 font-medium max-w-xs truncate">
                                            <span class="font-semibold text-brandNavy block mb-0.5">{{ $exp->client_name ?? 'Overhead Umum' }}</span>
                                            {{ $exp->description }}
                                        </td>
                                        <td class="py-3 px-4 text-right font-black text-slate-800 whitespace-nowrap">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400">Belum ada data pengeluaran proyek.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

        <!-- Right Column (1/3 width): Fast Recording Forms -->
        <div class="lg:col-span-1 bg-white border border-slate-200 rounded-xl p-6 h-fit">
            <!-- Form Header Switcher -->
            <div class="flex border-b border-slate-100 mb-6">
                <button @click="formTab = 'expense'" :class="formTab === 'expense' ? 'border-brandGreen text-brandGreen' : 'border-transparent text-slate-400 hover:text-slate-600'" 
                    class="flex-1 pb-3 text-xs font-bold uppercase tracking-wider border-b-2 text-center transition duration-150">
                    Biaya Keluar
                </button>
                <button @click="formTab = 'income'" :class="formTab === 'income' ? 'border-brandGreen text-brandGreen' : 'border-transparent text-slate-400 hover:text-slate-600'" 
                    class="flex-1 pb-3 text-xs font-bold uppercase tracking-wider border-b-2 text-center transition duration-150">
                    Uang Masuk
                </button>
            </div>

            <!-- Form 1: Input Pengeluaran Baru -->
            <div x-show="formTab === 'expense'">
                <div class="mb-4">
                    <h3 class="text-xs font-black text-brandNavy uppercase tracking-wide">Pencatatan Pengeluaran</h3>
                    <p class="text-[10px] text-slate-450 mt-0.5">Catat pengeluaran proyek atau operasional harian.</p>
                </div>

                <form method="POST" action="{{ route('admin_ops.expenses.store') }}" class="space-y-4">
                    @csrf

                    <!-- Kategori Pengeluaran -->
                    <div>
                        <label for="category" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kategori Biaya</label>
                        <select id="category" name="category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-150">
                            <option value="" disabled selected>Pilih Kategori...</option>
                            <option value="fuel_parking">Operasional Lapangan (Bensin/Parkir)</option>
                            <option value="technician_wage">Upah Teknisi Harian (Flat)</option>
                            <option value="ads">Iklan / Ads</option>
                            <option value="entertain">Biaya Entertain (Akuisisi Klien)</option>
                            <option value="bonus_location">Bonus Lokasi Tambahan</option>
                            <option value="bonus_night">Bonus Kerja Malam / Lembur</option>
                        </select>
                    </div>

                    <!-- Nama Klien -->
                    <div>
                        <label for="client_name" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Klien Terkait</label>
                        <input type="text" id="client_name" name="client_name" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Contoh: RS Abdi Waluyo (kosongkan jika overhead umum)">
                    </div>

                    <!-- Proyek Terkait (Income ID - Nullable) -->
                    <div>
                        <label for="income_id" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">ID Pendapatan Proyek (Opsional)</label>
                        <input type="number" id="income_id" name="income_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Contoh: 15">
                    </div>

                    <!-- Jumlah Uang -->
                    <div x-data="{ rawAmount: '' }">
                        <label for="amount" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nominal Uang (Rp)</label>
                        <input type="text" id="amount" name="amount" x-model="rawAmount" @input="rawAmount = formatRupiah(rawAmount)" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Contoh: Rp 150.000">
                    </div>

                    <!-- Deskripsi Detail -->
                    <div>
                        <label for="description" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi / Rincian</label>
                        <textarea id="description" name="description" required rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Rincian bensin, material darurat, atau upah..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-bold py-3 px-4 rounded-lg shadow-sm transition duration-150 uppercase tracking-wider text-[10px] flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Simpan Pengeluaran</span>
                    </button>
                </form>
            </div>

            <!-- Form 2: Input Pendapatan Baru -->
            <div x-show="formTab === 'income'" x-cloak>
                <div class="mb-4">
                    <h3 class="text-xs font-black text-brandNavy uppercase tracking-wide">Pencatatan Pendapatan</h3>
                    <p class="text-[10px] text-slate-450 mt-0.5">Catat arus kas masuk / nilai jasa dari proyek klien.</p>
                </div>

                <form method="POST" action="{{ route('admin_ops.incomes.store') }}" class="space-y-4">
                    @csrf

                    <!-- Nama Klien -->
                    <div>
                        <label for="income_client_name" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Klien</label>
                        <input type="text" id="income_client_name" name="client_name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Contoh: RS Abdi Waluyo">
                    </div>

                    <!-- Kategori Klien -->
                    <div>
                        <label for="income_client_category" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kategori Klien</label>
                        <select id="income_client_category" name="client_category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-150">
                            <option value="" disabled selected>Pilih Kategori...</option>
                            <option value="B2B - F&B">B2B - F&B</option>
                            <option value="B2B - Hospital/Medis">B2B - Hospital/Medis</option>
                            <option value="B2B - Pemerintahan">B2B - Pemerintahan</option>
                            <option value="Residensial/Rumah Tangga">Residensial/Rumah Tangga</option>
                        </select>
                    </div>

                    <!-- Tanggal Pelayanan -->
                    <div>
                        <label for="service_date" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Pelaksanaan</label>
                        <input type="date" id="service_date" name="service_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155">
                    </div>

                    <!-- Nominal Pendapatan Kotor -->
                    <div x-data="{ rawAmount: '' }">
                        <label for="gross_amount" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pendapatan Kotor (Gross Rp)</label>
                        <input type="text" id="gross_amount" name="gross_amount" x-model="rawAmount" @input="rawAmount = formatRupiah(rawAmount)" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Contoh: Rp 10.000.000">
                    </div>

                    <!-- Detail Pekerjaan Jasa -->
                    <div>
                        <label for="service_detail" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Detail Pekerjaan</label>
                        <textarea id="service_detail" name="service_detail" required rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Deskripsikan layanan (misal: Pembersihan saluran CCTV)..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-bold py-3 px-4 rounded-lg shadow-sm transition duration-150 uppercase tracking-wider text-[10px] flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Simpan Pendapatan</span>
                    </button>
                </form>
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

    function formatRupiah(value) {
        if (!value) return '';
        let numberString = value.replace(/[^,\d]/g, '').toString();
        let split = numberString.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp ' + rupiah;
    }
</script>
@endsection
