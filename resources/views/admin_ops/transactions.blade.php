@extends('layouts.app', ['title' => 'Riwayat Transaksi', 'pageHeader' => 'Histori Transaksi Keseluruhan'])

@section('content')
<!-- Import Alpine JS -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="{ tab: '{{ request('tab', 'expense') }}', showModal: false, rawAmount: '' }" class="space-y-6">
    
    <!-- Success Alert -->
    @if(session('success'))
        <div x-data="{ open: true }" x-show="open" class="p-4 bg-emerald-50 border border-emerald-250 border-l-4 border-l-brandGreen rounded-lg flex items-center justify-between text-emerald-800 text-xs font-semibold shadow-sm transition duration-150">
            <div class="flex items-center space-x-2.5">
                <svg class="w-4 h-4 text-brandGreen" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="open = false" class="text-emerald-500 hover:text-emerald-700 focus:outline-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <!-- Main Container -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        
        <!-- Header & Action Row -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
            <!-- Navigation Tabs -->
            <div class="flex space-x-6">
                <button @click="tab = 'expense'" :class="tab === 'expense' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-400 hover:text-slate-600 font-semibold'" 
                    class="pb-2 text-sm uppercase tracking-wide transition duration-150">
                    Semua Pengeluaran
                </button>
                <button @click="tab = 'income'" :class="tab === 'income' ? 'text-brandNavy font-extrabold border-b-2 border-brandNavy' : 'text-slate-400 hover:text-slate-600 font-semibold'" 
                    class="pb-2 text-sm uppercase tracking-wide transition duration-150">
                    Semua Pendapatan
                </button>
            </div>

            <!-- Action Button -->
            <button @click="showModal = true" class="bg-brandGreen hover:bg-brandGreenHover text-white font-bold py-2.5 px-4 rounded-lg text-[10px] uppercase tracking-wider transition duration-150 flex items-center space-x-1.5 shadow-sm border border-brandGreenHover">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Pemasukan</span>
            </button>
        </div>

        <!-- Tab 1: Pengeluaran -->
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
                                <td class="py-4 px-4 text-slate-500 font-medium whitespace-nowrap">
                                    {{ $expense->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-700 uppercase">
                                        {{ str_replace('_', ' ', $expense->category) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium max-w-xs truncate">
                                    <span class="font-bold text-brandNavy block mb-0.5">{{ $expense->client_name ?? 'Overhead Umum' }}</span>
                                    {{ $expense->description }}
                                </td>
                                <td class="py-4 px-4 text-right font-black text-slate-800 whitespace-nowrap">
                                    Rp {{ number_format($expense->amount, 2, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 text-center whitespace-nowrap">
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

        <!-- Tab 2: Pendapatan -->
        <div x-show="tab === 'income'" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse mb-4">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                            <th class="py-3.5 px-4">Tgl Pelayanan</th>
                            <th class="py-3.5 px-4">Klien & Kategori</th>
                            <th class="py-3.5 px-4">Detail Pelayanan</th>
                            <th class="py-3.5 px-4 text-right">Nominal Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($incomes as $income)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="py-4 px-4 text-slate-500 font-medium whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($income->service_date)->format('d M Y') }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="font-bold text-brandNavy block mb-0.5">{{ $income->client_name ?? '-' }}</span>
                                    @if($income->client_category)
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">{{ $income->client_category }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-slate-650 font-medium max-w-xs truncate">
                                    {{ $income->service_detail }}
                                </td>
                                <td class="py-4 px-4 text-right font-black text-brandGreen whitespace-nowrap">
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

    <!-- Modal Form Tambah Pemasukan (Premium Industrial-Modern Style) -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
         
        <div class="bg-white border border-slate-200 rounded-xl w-full max-w-lg overflow-hidden flex flex-col shadow-lg" @click.away="showModal = false">
            <!-- Modal Header -->
            <div class="bg-white border-b border-slate-150 px-6 py-4 flex items-center justify-between">
                <h3 class="text-sm font-black text-brandNavy uppercase tracking-wide">Pencatatan Pemasukan Baru</h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form method="POST" action="{{ route('admin_ops.incomes.store') }}" class="p-6 space-y-4">
                @csrf

                <!-- Tanggal Pelayanan -->
                <div>
                    <label for="service_date" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Pelaksanaan</label>
                    <input type="date" id="service_date" name="service_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155">
                </div>

                <!-- Nama Klien / Instansi -->
                <div>
                    <label for="client_name" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Klien / Instansi</label>
                    <input type="text" id="client_name" name="client_name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Contoh: RS Abdi Waluyo">
                </div>

                <!-- Kategori Klien -->
                <div>
                    <label for="client_category" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kategori Klien</label>
                    <select id="client_category" name="client_category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-750 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-150">
                        <option value="" disabled selected>Pilih Kategori...</option>
                        <option value="B2B - F&B">B2B - F&B</option>
                        <option value="B2B - Hospital/Medis">B2B - Hospital/Medis</option>
                        <option value="B2B - Pemerintahan">B2B - Pemerintahan</option>
                        <option value="Residensial/Rumah Tangga">Residensial/Rumah Tangga</option>
                    </select>
                </div>

                <!-- Nominal Pendapatan -->
                <div>
                    <label for="modal_gross_amount" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nominal Pemasukan (Rp)</label>
                    <input type="text" id="modal_gross_amount" name="gross_amount" x-model="rawAmount" @input="rawAmount = formatRupiah(rawAmount)" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Contoh: Rp 25.000.000">
                </div>

                <!-- Detail Pelayanan -->
                <div>
                    <label for="service_detail" class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Detail Pelayanan</label>
                    <textarea id="service_detail" name="service_detail" required rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-155" placeholder="Deskripsikan layanan (misal: Flushing total instalasi pipa vertikal)..."></textarea>
                </div>

                <!-- Modal Footer Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="showModal = false" class="border border-slate-200 text-slate-500 hover:bg-slate-50 font-bold py-2.5 px-4 rounded-lg text-[10px] uppercase tracking-wider transition duration-150">
                        Batal
                    </button>
                    <button type="submit" class="bg-brandGreen hover:bg-brandGreenHover text-white font-bold py-2.5 px-5 rounded-lg text-[10px] uppercase tracking-wider transition duration-150 shadow-sm border border-brandGreenHover">
                        Simpan Pemasukan
                    </button>
                </div>
            </form>
        </div>
         
    </div>

</div>

<!-- Script for Rupiah Formatting -->
<script>
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
