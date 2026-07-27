<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal - Stock Management</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased font-['Plus_Jakarta_Sans'] bg-[#090D16] text-slate-100 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <!-- Ambient Glow ខាងក្រោយ -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[300px] bg-gradient-to-tr from-cyan-600/20 to-blue-600/20 blur-[140px] -z-10 pointer-events-none"></div>

    <div class="w-full max-w-md">

        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600 shadow-lg shadow-cyan-500/20 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">STOCK.PRO <span class="text-cyan-400">ADMIN</span></h1>
            <p class="text-xs text-slate-400 mt-1">Product Management System (PostgreSQL)</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/80 border border-slate-800/80 backdrop-blur-xl rounded-3xl p-8 shadow-2xl relative">

            @auth
                <!-- បង្ហាញផ្ទាំងនេះ បើ Admin បាន Log in រួចហើយ -->
                <div class="text-center py-6">
                    <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-3 border border-emerald-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-white font-medium text-base mb-1">លោកអ្នកបានចូលប្រព័ន្ធរួចហើយ</h3>
                    <p class="text-xs text-slate-400 mb-6">គណនី៖ {{ Auth::user()->name }}</p>

                    <a href="{{ url('/dashboard') }}" class="block w-full py-3 px-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-cyan-500/25 transition">
                        ចូលទៅកាន់ Dashboard →
                    </a>
                </div>
            @else
                <!-- បង្ហាញផ្ទាំង Login នេះ បើមិនទាន់ Log in -->
                <div class="text-center mb-6">
                    <h2 class="text-lg font-semibold text-white">ចូលប្រព័ន្ធគ្រប់គ្រង</h2>
                    <p class="text-xs text-slate-400 mt-1">សូមបញ្ចូលគណនី Admin ដើម្បីបន្ត</p>
                </div>

                <!-- កន្លែងលោតសារ Error ពេលវាយ Password ខុស -->
                @if ($errors->any())
                    <div class="mb-5 p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>អ៊ីមែល ឬ ពាក្យសម្ងាត់មិនត្រឹមត្រូវទេ!</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">អ៊ីមែល (Admin Email)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-10 pr-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition"
                                placeholder="admin@stock.pro">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1.5">ពាក្យសម្ងាត់ (Password)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input type="password" name="password" required
                                class="w-full pl-10 pr-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded bg-slate-950 border-slate-800 text-cyan-500 focus:ring-0 w-3.5 h-3.5">
                            <span class="text-xs text-slate-400">ចងចាំខ្ញុំ (Remember me)</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full mt-2 py-3.5 px-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-semibold rounded-xl shadow-lg shadow-cyan-500/25 transition duration-200 text-sm">
                        ចូលប្រព័ន្ធ (Log In)
                    </button>
                </form>

                <div class="mt-6 pt-4 border-t border-slate-800/80 text-center">
                    <p class="text-[11px] text-slate-500 flex items-center justify-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Authorized Personnel Only (តំបន់កម្រិតសិទ្ធិ)
                    </p>
                </div>
            @endauth

        </div>

        <p class="text-center text-[11px] text-slate-600 mt-6">
            © {{ date('Y') }} Product Management System.
        </p>

    </div>
</body>
</html>
