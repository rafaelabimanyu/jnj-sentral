<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'J&J Sentral' }} - Rooterin Portal</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandNavy: '#0F2A44',
                        brandGreen: '#1FAF5A',
                        brandGreenHover: '#178a46',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" style="display: none;" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 z-20 lg:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-brandNavy text-white flex flex-col justify-between h-full shadow-xl flex-shrink-0 transform transition-transform duration-300 lg:relative lg:translate-x-0">
        <div>
            <!-- Header Brand -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700">
                <div class="flex items-center space-x-2">
                    <!-- Industrial Logo Representation -->
                    <div class="w-8 h-8 rounded bg-brandGreen flex items-center justify-center font-extrabold text-white text-lg tracking-wider">
                        R
                    </div>
                    <span class="font-extrabold text-lg tracking-widest text-slate-100 uppercase">J&J Sentral</span>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="mt-6 px-4 space-y-1.5">
                @if(Auth::user()->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('owner.dashboard') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('owner.reports') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('owner.reports') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Laporan Keuangan</span>
                    </a>
                @elseif(Auth::user()->role === 'admin_ops')
                    <a href="{{ route('admin_ops.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('admin_ops.dashboard') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin_ops.transactions') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('admin_ops.transactions') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <span>Riwayat Transaksi</span>
                    </a>
                    <a href="{{ route('admin_ops.clients.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('admin_ops.clients.index') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Manajemen Klien</span>
                    </a>
                    <a href="{{ route('admin_ops.marketing_fees.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('admin_ops.marketing_fees.*') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Manajemen Komisi</span>
                    </a>
                    <a href="{{ route('admin_ops.marketers.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('admin_ops.marketers.*') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Master Data Marketer</span>
                    </a>
                    <a href="{{ route('admin_ops.overhead_expenses.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('admin_ops.overhead_expenses.*') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span>Overhead & Ekstra</span>
                    </a>
                    <a href="{{ route('admin_ops.field_operations.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('admin_ops.field_operations.*') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Operasional Lapangan</span>
                    </a>
                    <a href="{{ route('admin_ops.technicians.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('admin_ops.technicians.*') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3 3 0 00-3 3h6a3 3 0 00-3-3z"></path></svg>
                        <span>Master Data Teknisi</span>
                    </a>
                @elseif(Auth::user()->role === 'admin_web')
                    <a href="{{ route('admin_web.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('admin_web.dashboard') ? 'bg-brandGreen text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Pengguna (Users)</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- User Profil & Log Out -->
        <div class="p-4 border-t border-slate-700 bg-slate-900 bg-opacity-40">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-slate-600 flex items-center justify-center font-bold text-slate-200">
                    {{ substr(Auth::user()->name ?? 'Guest', 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-sm font-semibold text-slate-200 truncate">{{ Auth::user()->name ?? 'Wibowo Pratikno' }}</h4>
                    <span class="text-xs text-brandGreen font-medium uppercase tracking-wider">{{ Auth::user()->role ?? 'owner' }}</span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-slate-800 hover:bg-red-700 hover:text-white text-slate-300 rounded-lg text-sm font-medium transition duration-200 border border-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 flex-shrink-0">
            <div class="flex items-center space-x-3 lg:space-x-2">
                <!-- Hamburger Button (Mobile Only) -->
                <button @click="sidebarOpen = true" class="text-slate-500 hover:text-brandNavy focus:outline-none lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg lg:text-xl font-bold text-brandNavy truncate">{{ $pageHeader ?? 'Dashboard' }}</h1>
            </div>
            
            <div class="flex items-center space-x-6">
                <!-- Notifications -->
                <button class="relative text-slate-500 hover:text-brandNavy transition duration-150">
                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-600 rounded-full"></span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
                
                <div class="h-6 w-px bg-slate-200"></div>
                
                <!-- Quick Info -->
                <span class="text-sm font-medium text-slate-500">Rooterin Operations</span>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center justify-between text-emerald-800">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center justify-between text-red-800">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
