@extends('layouts.app', ['title' => 'Master Data Teknisi', 'pageHeader' => 'Master Data Teknisi'])

@section('content')
<div x-data="{ 
    showCreateModal: false,
    showEditModal: false, 
    editId: null, 
    editName: '', 
    editLevel: 'Senior',
    
    openCreate() {
        this.showCreateModal = true;
    },
    
    openEdit(id, name, level) {
        this.editId = id;
        this.editName = name;
        this.editLevel = level;
        this.showEditModal = true;
    }
}" class="space-y-6">

    <!-- Top Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white border border-slate-200 rounded-2xl p-6 shadow-sm gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-brandNavy text-white flex items-center justify-center font-bold shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3 3 0 00-3 3h6a3 3 0 00-3-3z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Master Data Teknisi</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola daftar teknisi operasional lapangan beserta level kualifikasi (Senior / Junior).</p>
                </div>
            </div>
        </div>
        <div>
            <button @click="openCreate()"
                class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-brandGreen hover:bg-brandGreenHover text-white font-semibold text-xs uppercase tracking-wider rounded-xl transition duration-200 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>+ Tambah Teknisi</span>
            </button>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Daftar Teknisi Lapangan</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Total {{ $technicians->total() }} teknisi terdaftar dalam sistem.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">
                Operasional Lapangan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                        <th class="py-3.5 px-4 w-16 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Teknisi</th>
                        <th class="py-3.5 px-4 text-center">Level</th>
                        <th class="py-3.5 px-4 text-center">Total Riwayat Penugasan</th>
                        <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($technicians as $index => $tech)
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            <td class="py-4 px-4 text-center font-bold text-slate-400">
                                {{ $technicians->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-4 font-bold text-brandNavy text-sm">
                                {{ $tech->name }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($tech->level === 'Senior')
                                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold text-white shadow-xs" style="background-color: #1FAF5A;">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Senior</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        <svg class="w-3 h-3 text-slate-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Junior</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-[11px] font-semibold text-slate-600">
                                    {{ $tech->operation_details_count ?? 0 }} Penugasan
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Edit Button -->
                                    <button @click="openEdit({{ $tech->id }}, '{{ addslashes($tech->name) }}', '{{ $tech->level }}')"
                                        class="inline-flex items-center space-x-1 px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 text-[11px] font-bold rounded-lg transition duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </button>

                                    <!-- Delete Button with SweetAlert confirmation -->
                                    <button type="button" @click="confirmDelete({{ $tech->id }}, '{{ addslashes($tech->name) }}')"
                                        class="inline-flex items-center space-x-1 px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 text-[11px] font-bold rounded-lg transition duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>

                                    <form id="delete-form-{{ $tech->id }}" method="POST" action="{{ route('admin_ops.technicians.destroy', $tech->id) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Belum ada data teknisi terdaftar.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($technicians->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $technicians->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL CREATE TEKNISI -->
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60" @click="showCreateModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <form method="POST" action="{{ route('admin_ops.technicians.store') }}">
                    @csrf
                    
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4 space-y-4">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Tambah Teknisi Baru</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Daftarkan teknisi baru ke dalam database master.</p>
                            </div>
                            <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Nama Teknisi -->
                        <div>
                            <label for="create_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Teknisi <span class="text-red-500">*</span></label>
                            <input type="text" id="create_name" name="name" value="{{ old('name') }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                                placeholder="Contoh: Ahmad Subagyo">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Level Teknisi -->
                        <div>
                            <label for="create_level" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Level Teknisi <span class="text-red-500">*</span></label>
                            <select id="create_level" name="level" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                <option value="Senior" {{ old('level') == 'Senior' ? 'selected' : '' }}>Senior</option>
                                <option value="Junior" {{ old('level') == 'Junior' ? 'selected' : '' }}>Junior</option>
                            </select>
                            @error('level')
                                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-3.5 flex justify-end space-x-3 border-t border-slate-100">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs uppercase tracking-wider transition duration-150">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-brandGreen hover:bg-brandGreenHover text-white font-semibold rounded-xl text-xs uppercase tracking-wider transition duration-150 shadow-md">
                            Simpan Teknisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT TEKNISI -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60" @click="showEditModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <form :action="'/admin-ops/technicians/' + editId" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4 space-y-4">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Edit Master Teknisi</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Perbarui nama atau level kualifikasi teknisi.</p>
                            </div>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Teknisi <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="editName" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Level Teknisi <span class="text-red-500">*</span></label>
                            <select name="level" x-model="editLevel" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                <option value="Senior">Senior</option>
                                <option value="Junior">Junior</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-3.5 flex justify-end space-x-3 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-xs uppercase tracking-wider transition duration-150">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2 bg-brandGreen hover:bg-brandGreenHover text-white font-semibold rounded-xl text-xs uppercase tracking-wider transition duration-150 shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- SweetAlert & Notification Scripts -->
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Data Teknisi?',
            text: "Apakah Anda yakin ingin menghapus teknisi '" + name + "'? Tindakan ini tidak dapat dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1FAF5A',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl text-xs font-bold uppercase tracking-wider px-4 py-2.5',
                cancelButton: 'rounded-xl text-xs font-bold uppercase tracking-wider px-4 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl'
            }
        });
    @endif
</script>
@endsection
