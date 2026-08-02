@extends('layouts.app', ['title' => 'Master Data Marketing', 'pageHeader' => 'Master Data Marketer & Channel'])

@section('content')
<div class="space-y-8" x-data="{ 
    showEditModal: false, 
    editId: null, 
    editName: '', 
    editDefaultFee: '',
    openEdit(id, name, defaultFee) {
        this.editId = id;
        this.editName = name;
        this.editDefaultFee = defaultFee;
        this.showEditModal = true;
    }
}">

    <!-- Top Action Header -->
    <div class="flex items-center justify-between bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div>
            <h2 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Master Data Marketer & Channel</h2>
            <p class="text-xs text-slate-400 mt-0.5">Kelola daftar unit bisnis/marketer dan persentase komisi default masing-masing channel.</p>
        </div>
        <a href="{{ route('admin_ops.marketing_fees.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-brandNavy font-semibold text-xs rounded-xl transition duration-200 border border-slate-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali ke Transaksi Komisi</span>
        </a>
    </div>

    <!-- Main Grid: Add Form & Data Table -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Add Marketer -->
        <div class="lg:col-span-1 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm h-fit">
            <div class="mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Tambah Marketer Baru</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Daftarkan unit bisnis atau saluran marketer baru.</p>
            </div>

            <form method="POST" action="{{ route('admin_ops.marketers.store') }}" class="space-y-4">
                @csrf

                <!-- Nama Marketer -->
                <div>
                    <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Marketer / Channel</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full bg-slate-50 border @error('name') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: MARKETING-SURABAYA">
                    @error('name')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Default Fee Percentage -->
                <div>
                    <label for="default_fee_percentage" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Default Persentase Komisi (%)</label>
                    <input type="number" id="default_fee_percentage" name="default_fee_percentage" value="{{ old('default_fee_percentage', '10.00') }}" required min="0" max="100" step="0.01"
                        class="w-full bg-slate-50 border @error('default_fee_percentage') border-red-500 @else border-slate-200 @enderror rounded-xl px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                        placeholder="Contoh: 10 atau 12.5">
                    <p class="text-[11px] text-slate-400 mt-1.5 font-medium">Persentase ini akan otomatis terisi saat pencatatan transaksi baru.</p>
                    @error('default_fee_percentage')
                        <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-semibold py-3 px-4 rounded-xl shadow-md transition duration-200 uppercase tracking-wider text-xs flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Simpan Master Marketer</span>
                </button>
            </form>
        </div>

        <!-- Data Table Marketer -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Daftar Master Marketer</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Total {{ $marketers->total() }} channel terdaftar dalam sistem.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">Master Registry</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                                <th class="py-3.5 px-4">Nama Marketer / Channel</th>
                                <th class="py-3.5 px-4 text-center">Default Fee (%)</th>
                                <th class="py-3.5 px-4 text-center">Total Transaksi</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($marketers as $m)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-4 px-4 font-bold text-brandNavy">
                                        {{ $m->name }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-extrabold text-brandGreen">
                                        {{ number_format($m->default_fee_percentage, 1) }}%
                                    </td>
                                    <td class="py-4 px-4 text-center text-slate-500 font-medium">
                                        <span class="px-2.5 py-1 bg-slate-100 rounded-full text-[11px] font-semibold text-slate-700">
                                            {{ $m->marketing_fees_count }} Transaksi
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- Edit Button -->
                                            <button @click="openEdit({{ $m->id }}, '{{ addslashes($m->name) }}', {{ $m->default_fee_percentage }})"
                                                class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 text-[10px] font-bold rounded-lg transition duration-200">
                                                Edit
                                            </button>

                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('admin_ops.marketers.destroy', $m->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus marketer ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 text-[10px] font-bold rounded-lg transition duration-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-400">
                                        Belum ada master data marketer.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($marketers->hasPages())
                <div class="mt-6 pt-4 border-t border-slate-100">
                    {{ $marketers->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Modal Edit Marketer -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60" @click="showEditModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <form :action="'/admin-ops/marketers/' + editId" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4 space-y-4">
                        <div class="border-b border-slate-100 pb-3">
                            <h3 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Edit Master Marketer</h3>
                            <p class="text-xs text-slate-400">Perbarui nama atau persentase komisi default.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Marketer / Channel</label>
                            <input type="text" name="name" x-model="editName" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Default Persentase Komisi (%)</label>
                            <input type="number" name="default_fee_percentage" x-model="editDefaultFee" required min="0" max="100" step="0.01"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-3.5 flex justify-end space-x-3 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs uppercase tracking-wider transition duration-150">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-brandGreen hover:bg-brandGreenHover text-white font-semibold rounded-xl text-xs uppercase tracking-wider transition duration-150 shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
