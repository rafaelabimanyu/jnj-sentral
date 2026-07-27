<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - J&J Sentral</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font: Inter -->
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
</head>
<body class="font-sans antialiased bg-slate-950 flex items-center justify-center min-h-screen p-4 relative overflow-hidden">
    
    <!-- Decorative Grid Pattern for Industrial Theme -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#0f172a_1px,transparent_1px),linear-gradient(to_bottom,#0f172a_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-40 z-0"></div>

    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-8 z-10 relative">
        <!-- Brand Header -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-xl bg-brandGreen flex items-center justify-center font-extrabold text-white text-3xl mb-4 tracking-wider shadow-lg shadow-brandGreen/25">
                R
            </div>
            <h2 class="text-2xl font-bold text-slate-100 tracking-wider">J&J SENTRAL</h2>
            <p class="text-xs text-slate-400 font-medium tracking-widest uppercase mt-1">Rooterin Operational System</p>
        </div>

        <!-- Form Login -->
        <form method="POST" action="#">
            @csrf

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-950/40 border border-red-900/50 rounded-lg text-red-400 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Email Address -->
            <div class="mb-5">
                <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-widest mb-2">Alamat Email</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-brandGreen focus:ring-1 focus:ring-brandGreen transition duration-200"
                        placeholder="nama@rooterin.com">
                </div>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-widest mb-2">Kata Sandi</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-brandGreen focus:ring-1 focus:ring-brandGreen transition duration-200"
                        placeholder="••••••••">
                </div>
            </div>

            <!-- Remember Me / Forgot -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm text-slate-400 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-slate-950 border-slate-800 text-brandGreen focus:ring-brandGreen/20 focus:ring-offset-slate-900 mr-2">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-brandGreen hover:bg-brandGreenHover text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-brandGreen/20 hover:shadow-brandGreen/30 transition duration-200 uppercase tracking-wider text-sm flex items-center justify-center space-x-2">
                <span>Masuk Aplikasi</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>
    </div>

    <!-- Footer Copyright -->
    <div class="absolute bottom-4 left-0 right-0 text-center text-xs text-slate-600 z-10">
        © 2026 J&J Group. All rights reserved.
    </div>

</body>
</html>
