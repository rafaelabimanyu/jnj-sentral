@extends('layouts.app', ['title' => 'Master Data Karyawan', 'pageHeader' => 'Master Data Karyawan & Staff'])

@section('content')
<div x-data="{ 
    showCreateModal: false,
    showEditModal: false, 
    editId: null, 
    editName: '', 
    editRole: 'Teknisi',
    editLevel: 'Senior',
    editStatus: 'Active',
    
    openCreate() {
        this.showCreateModal = true;
    },
    
    openEdit(id, name, role, level, status) {
        this.editId = id;
        this.editName = name;
        this.editRole = role;
        this.editLevel = level;
        this.editStatus = status;
        this.showEditModal = true;
    }
}" class="space-y-8">

    <!-- Top Action & Banner Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between bg-white border border-slate-200 rounded-2xl p-6 shadow-sm gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-11 h-11 rounded-xl bg-brandNavy text-white flex items-center justify-center font-bold shadow-md flex-shrink-0">
                <svg class="w-6 h-6 text-brandGreen" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Master Data Karyawan</h2>
                <p class="text-xs text-slate-400 mt-0.5">Kelola seluruh database staf, teknisi lapangan, dan divisi operasional J&J Sentral.</p>
            </div>
        </div>
        <div>
            <button @click="openCreate()"
                class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-brandGreen hover:bg-brandGreenHover text-white font-semibold text-xs uppercase tracking-wider rounded-xl transition duration-200 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>+ Tambah Karyawan</span>
            </button>
        </div>
    </div>

    <!-- Metric Cards Top Section -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Metric Card 1: Total Karyawan Aktif -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Total Karyawan Aktif</span>
                <span class="text-2xl font-black text-brandNavy">{{ number_format($totalActive) }}</span>
                <span class="text-[11px] text-emerald-600 font-semibold block mt-1">Seluruh Divisi Perusahaan</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Metric Card 2: Total Tim Teknisi -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Tim Teknisi Lapangan</span>
                <span class="text-2xl font-black text-brandGreen">{{ number_format($totalTechnicians) }}</span>
                <span class="text-[11px] text-slate-500 font-medium block mt-1">Siap Bertugas di Lapangan</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brandGreen/10 text-brandGreen flex items-center justify-center border border-brandGreen/20 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Metric Card 3: Total Admin / Office -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Admin & Backoffice</span>
                <span class="text-2xl font-black text-sky-700">{{ number_format($totalAdminOffice) }}</span>
                <span class="text-[11px] text-slate-500 font-medium block mt-1">Staf Operasional & Manajemen</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center border border-sky-100 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4 0v-4m0 0h4m-4 0v-4m0 0h4m-4 0V5"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        
        <!-- Filter Bar Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-brandNavy uppercase tracking-wider">Daftar Karyawan & Staff</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Menampilkan {{ $employees->total() }} entri karyawan terdaftar.</p>
            </div>

            <!-- Filter Role Dropdown Form -->
            <form method="GET" action="{{ route('admin_ops.employees.index') }}" class="flex items-center space-x-3">
                <label for="filter_role" class="text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Filter Posisi:</label>
                <select id="filter_role" name="role" onchange="this.form.submit()"
                    class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:border-brandGreen transition duration-200">
                    <option value="">Semua Posisi (All Roles)</option>
                    <option value="Teknisi" {{ request('role') == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                    <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Customer Service" {{ request('role') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                    <option value="Marketing" {{ request('role') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="Management" {{ request('role') == 'Management' ? 'selected' : '' }}>Management</option>
                </select>
                @if(request('role'))
                    <a href="{{ route('admin_ops.employees.index') }}" class="text-xs font-semibold text-red-500 hover:text-red-700 underline">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase font-semibold text-[10px] tracking-wider">
                        <th class="py-3.5 px-4 w-16 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Karyawan</th>
                        <th class="py-3.5 px-4 text-center">Posisi (Role)</th>
                        <th class="py-3.5 px-4 text-center">Level / Kualifikasi</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $index => $emp)
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            <td class="py-4 px-4 text-center font-bold text-slate-400">
                                {{ $employees->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-4 font-bold text-brandNavy text-sm">
                                {{ $emp->name }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($emp->role === 'Teknisi')
                                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold text-white shadow-xs" style="background-color: #1FAF5A;">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                        <span>Teknisi</span>
                                    </span>
                                @elseif(in_array($emp->role, ['Admin', 'Management']))
                                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-brandNavy text-white shadow-xs">
                                        <svg class="w-3 h-3 text-brandGreen" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4 0v-4m0 0h4m-4 0v-4m0 0h4m-4 0V5"></path></svg>
                                        <span>{{ $emp->role }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-sky-100 text-sky-800 border border-sky-200">
                                        <svg class="w-3 h-3 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span>{{ $emp->role }}</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center font-semibold text-slate-700">
                                <span class="px-2.5 py-1 bg-slate-100 border border-slate-200 rounded-lg text-[11px]">
                                    {{ $emp->level }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($emp->status === 'Active')
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>Non-Aktif</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Edit Button -->
                                    <button @click="openEdit({{ $emp->id }}, '{{ addslashes($emp->name) }}', '{{ $emp->role }}', '{{ $emp->level }}', '{{ $emp->status }}')"
                                        class="inline-flex items-center space-x-1 px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 text-[11px] font-bold rounded-lg transition duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span>Edit</span>
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button" @click="confirmDelete({{ $emp->id }}, '{{ addslashes($emp->name) }}')"
                                        class="inline-flex items-center space-x-1 px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 text-[11px] font-bold rounded-lg transition duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Hapus</span>
                                    </button>

                                    <form id="delete-form-{{ $emp->id }}" method="POST" action="{{ route('admin_ops.employees.destroy', $emp->id) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Belum ada data karyawan terdaftar.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL CREATE KARYAWAN -->
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60" @click="showCreateModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <form method="POST" action="{{ route('admin_ops.employees.store') }}">
                    @csrf
                    
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4 space-y-4">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Tambah Karyawan Baru</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Daftarkan karyawan / staf baru ke dalam sistem.</p>
                            </div>
                            <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="create_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="create_name" name="name" value="{{ old('name') }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200"
                                placeholder="Contoh: Wibowo Pratikno">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Posisi / Role -->
                        <div>
                            <label for="create_role" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Posisi / Role <span class="text-red-500">*</span></label>
                            <select id="create_role" name="role" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                <option value="Teknisi" {{ old('role') == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                                <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Customer Service" {{ old('role') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                                <option value="Marketing" {{ old('role') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Management" {{ old('role') == 'Management' ? 'selected' : '' }}>Management</option>
                            </select>
                            @error('role')
                                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Level / Kualifikasi -->
                        <div>
                            <label for="create_level" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Level / Kualifikasi <span class="text-red-500">*</span></label>
                            <select id="create_level" name="level" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                <option value="Senior" {{ old('level') == 'Senior' ? 'selected' : '' }}>Senior</option>
                                <option value="Junior" {{ old('level') == 'Junior' ? 'selected' : '' }}>Junior</option>
                                <option value="Lead" {{ old('level') == 'Lead' ? 'selected' : '' }}>Lead</option>
                                <option value="Staff" {{ old('level') == 'Staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                            @error('level')
                                <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Karyawan -->
                        <div>
                            <label for="create_status" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status <span class="text-red-500">*</span></label>
                            <select id="create_status" name="status" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active (Aktif)</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive (Non-Aktif)</option>
                            </select>
                            @error('status')
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
                            Simpan Karyawan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT KARYAWAN -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60" @click="showEditModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <form :action="'/admin-ops/employees/' + editId" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4 space-y-4">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-extrabold text-brandNavy uppercase tracking-wider">Edit Master Karyawan</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Perbarui informasi data karyawan.</p>
                            </div>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="editName" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                        </div>

                        <!-- Posisi / Role -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Posisi / Role <span class="text-red-500">*</span></label>
                            <select name="role" x-model="editRole" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                <option value="Teknisi">Teknisi</option>
                                <option value="Admin">Admin</option>
                                <option value="Customer Service">Customer Service</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Management">Management</option>
                            </select>
                        </div>

                        <!-- Level / Kualifikasi -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Level / Kualifikasi <span class="text-red-500">*</span></label>
                            <select name="level" x-model="editLevel" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                <option value="Senior">Senior</option>
                                <option value="Junior">Junior</option>
                                <option value="Lead">Lead</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status <span class="text-red-500">*</span></label>
                            <select name="status" x-model="editStatus" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-brandGreen focus:bg-white transition duration-200">
                                <option value="Active">Active (Aktif)</option>
                                <option value="Inactive">Inactive (Non-Aktif)</option>
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
            title: 'Hapus Data Karyawan?',
            text: "Apakah Anda yakin ingin menghapus data karyawan '" + name + "'? Tindakan ini tidak dapat dibatalkan.",
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
