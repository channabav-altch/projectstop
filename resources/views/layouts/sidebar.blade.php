<!-- 🔴 បន្ថែម id="sidebar" និង transition-all 🔴 -->
<aside id="sidebar" class="w-64 bg-[#0B132B] border-r border-[#1C2C4E]/60 hidden md:flex font-['Khmer_MEF2'] flex-col min-h-screen z-[999] shrink-0 transition-all duration-300">
    <!-- Brand Logo -->
    <div class="h-16 flex items-center px-6 border-b border-[#1C2C4E]/60">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <span class="font-bold text-white tracking-wider text-base">STOCK.<span class="text-cyan-400">PRO</span></span>
        </a>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1.5">

        <!-- ១. ផ្ទាំងគ្រប់គ្រង (ឃើញទាំង User និង Admin) -->
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'Admin', 'user', 'User', 'retail']))
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-cyan-500/20 to-transparent text-cyan-500 font-['Khmer_MEF2'] text-sm border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span>ផ្ទាំងគ្រប់គ្រង</span>
        </a>
        @endif

        <!-- ចំណងជើងផ្នែក -->
        <div class="pt-6 pb-3 px-4 flex items-center gap-2.5">
            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shadow-[0_0_8px_rgba(6,182,212,0.8)]"></span>
            <span class="text-[11px] font-black text-slate-300 uppercase tracking-widest">ប្រតិបត្តិការស្តុក</span>
        </div>

        <!-- ២. បញ្ចូលផលិតផល (ឃើញតែ Admin ប៉ុណ្ណោះ) -->
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'Admin']))
        <a href="{{ route('products.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-cyan-500/20 to-transparent text-cyan-400 font-['Khmer_MEF2'] text-sm border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
            <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>បញ្ចូលផលិតផល</span>
        </a>
        @endif

        <!-- ៣. ប្រព័ន្ធបញ្ចេញលក់ POS (ឃើញតែ User ប៉ុណ្ណោះ) -->
        @if(auth()->check() && in_array(auth()->user()->role, ['user', 'User', 'retail']))
        <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-cyan-500/20 to-transparent text-cyan-400 font-['Khmer_MEF2'] text-sm border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
            <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            <span>ប្រព័ន្ធបញ្ចេញលក់ (POS)</span>
        </a>
        @endif

        <!-- ៤. គិតស្ទុប (ឃើញតែ Admin ប៉ុណ្ណោះ) -->
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'Admin']))
        <a href="{{ route('audit.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-cyan-500/20 to-transparent text-cyan-400 font-['Khmer_MEF2'] text-sm border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
            <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <span>គិតស្ទុប</span>
        </a>
        @endif

        <!-- ៥. ទិន្នន័យស្តុក (ឃើញតែ User ប៉ុណ្ណោះ) -->
        @if(auth()->check() && in_array(auth()->user()->role, ['user', 'User', 'retail']))
        <div class="relative group w-full mb-2 z-[999]">
            <button onclick="toggleReportMenu()" class="w-full flex items-center justify-between px-4 py-3 rounded-xl border border-blue-500/30 bg-[#0B132B] hover:bg-[#15234b] text-white transition-all shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="font-bold text-sm text-slate-300">ទិន្នន័យស្តុក</span>
                </div>
                <svg id="reportArrow" class="w-4 h-4 text-slate-400 transition-transform duration-300 transform rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            <div class="absolute left-full top-0 ml-3 w-56 bg-[#0B132B] rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.7)] border border-[#1C2C4E] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                <div class="absolute top-5 -left-1.5 w-3.5 h-3.5 bg-[#0B132B] border-l border-b border-[#1C2C4E] transform rotate-45"></div>
                <ul class="py-3 relative z-10 flex flex-col gap-1.5 px-2">
                    <li>
                        <a href="{{ url('/stock-sold') }}" class="flex items-center gap-3 bg-white px-3 py-2.5 rounded-xl hover:bg-[#1C2C4E]/80 transition-all group/item">
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover/item:bg-cyan-400 group-hover/item:shadow-[0_0_8px_rgba(6,182,212,0.8)] transition-all"></div>
                            <span class="text-[13px] font-bold text-slate-400 group-hover/item:text-white transition-colors">ស្តុកបានលក់ចេញ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stock.current') }}" class="flex items-center gap-3 bg-white px-3 py-2.5 rounded-xl hover:bg-[#1C2C4E]/80 transition-all group/item">
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover/item:bg-cyan-400 group-hover/item:shadow-[0_0_8px_rgba(6,182,212,0.8)] transition-all"></div>
                            <span class="text-[13px] font-bold text-slate-400 group-hover/item:text-white transition-colors">ស្តុកបច្ចុប្បន្នជាក់ស្តែង</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/stock-purchase') }}" class="flex items-center gap-3 bg-white px-3 py-2.5 rounded-xl hover:bg-[#1C2C4E]/80 transition-all group/item">
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover/item:bg-cyan-400 group-hover/item:shadow-[0_0_8px_rgba(6,182,212,0.8)] transition-all"></div>
                            <span class="text-[13px] font-bold text-slate-400 group-hover/item:text-white transition-colors">ស្តុកថ្មី & ទិញចូល</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        @endif

        <!-- ៦. របាយការណ៍លក់ (ឃើញតែ User ប៉ុណ្ណោះ) -->
        @if(auth()->check() && in_array(auth()->user()->role, ['user', 'User', 'retail']))
        <div class="relative w-full mb-2">
            <button onclick="toggleReportMenu()" class="w-full flex items-center justify-between px-4 py-3 rounded-xl border border-blue-500/30 bg-[#0B132B] hover:bg-[#15234b] text-white transition-all shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="font-bold text-sm text-slate-300">របាយការណ៍លក់</span>
                </div>
                <svg id="reportArrow" class="w-4 h-4 text-slate-400 transition-transform duration-300 transform rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            <div id="reportFlyout" class="absolute left-[calc(100%+10px)] top-0 w-[280px] bg-[#10172A] border border-[#1e293b] rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.5)] z-[9999] transition-all duration-300 opacity-0 pointer-events-none translate-x-4">
                <div class="p-4">
                    <p class="text-[11px] font-bold text-indigo-400 mb-3 ml-1 uppercase tracking-wider">ជម្រើសរបាយការណ៍</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.report') }}" class="flex items-center gap-3 bg-white p-3 rounded-xl hover:bg-slate-800/50 transition-all text-slate-400 hover:text-white mt-1">
                                <div class="w-1 h-1 rounded-full bg-slate-600"></div>
                                <span class="text-sm font-bold">របាយការណ៍លក់ Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/finance') }}" class="flex items-center gap-3 bg-white p-3 rounded-xl hover:bg-slate-800/50 transition-all text-slate-400 hover:text-white">
                                <div class="w-1 h-1 rounded-full bg-slate-600"></div>
                                <span class="text-sm font-bold">គណនី និងហិរញ្ញវត្ថុ</span>
                            </a>
                        </li>
                        <li>
                            <a href="/team-report" class="flex items-center gap-3 bg-white p-3 rounded-xl hover:bg-slate-800/50 transition-all text-slate-400 hover:text-white">
                                <div class="w-1 h-1 rounded-full bg-slate-600"></div>
                                <span class="text-sm font-bold">របាយការណ៍លក់រួម</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- ៧. ស្តុកទិន្នន័យសរុប (ឃើញតែ Admin ប៉ុណ្ណោះ) -->
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'Admin']))
        <a href="{{ route('stock.summary') ?? '#' }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-cyan-500/20 to-transparent text-cyan-400 font-['Khmer_MEF2'] text-sm border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
            <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
            <span>ស្តុកទិន្នន័យសរុប</span>
        </a>
        @endif

        <!-- ៨. បញ្ជីឈ្មោះអតិថិជន (ឃើញតែ Admin ប៉ុណ្ណោះ) -->
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'Admin']))
        <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-cyan-500/20 to-transparent text-cyan-400 font-['Khmer_MEF2'] text-sm border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
            <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span>បញ្ជីឈ្មោះអតិថិជន</span>
        </a>
        @endif
    </nav>

    <!-- Footer Logout & Status -->
    <div class="p-4 border-t border-[#1C2C4E]/60 flex justify-between items-center">
        <!-- ហ្វម Logout -->
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="group flex items-center gap-2 px-3 py-1.5 rounded-lg text-[11px] font-bold text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/30 transition-all duration-200">
                <svg class="w-4 h-4 shrink-0 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                ចាកចេញ
            </button>
        </form>
        <!-- Connected Status -->
        <span class="text-[10px] text-emerald-400 font-['Khmer_MEF2'] flex items-center gap-1.5 pr-1">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span> Connected
        </span>
    </div>

    <!-- Script សម្រាប់បើកបិទ Report Menu -->
    <script>
        function toggleReportMenu() {
            const flyout = document.getElementById('reportFlyout');
            const arrow = document.getElementById('reportArrow');
            if(!flyout) return; // ការពារ Error ពេល Menu នេះត្រូវបានលាក់ដោយសិទ្ធិ

            const isHidden = flyout.classList.contains('opacity-0');

            if (isHidden) {
                flyout.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-4');
                flyout.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
                if(arrow) arrow.classList.replace('rotate-0', 'rotate-90');
            } else {
                flyout.classList.add('opacity-0', 'pointer-events-none', 'translate-x-4');
                flyout.classList.remove('opacity-100', 'pointer-events-auto', 'translate-x-0');
                if(arrow) arrow.classList.replace('rotate-90', 'rotate-0');
            }
        }

        document.addEventListener('click', function(event) {
            const flyout = document.getElementById('reportFlyout');
            const arrow = document.getElementById('reportArrow');
            if(!flyout) return; // ការពារ Error ពេល Admin អត់មាន Menu នេះ

            const wrapper = flyout.parentElement;

            if (!wrapper.contains(event.target)) {
                flyout.classList.add('opacity-0', 'pointer-events-none', 'translate-x-4');
                flyout.classList.remove('opacity-100', 'pointer-events-auto', 'translate-x-0');
                if(arrow) arrow.classList.replace('rotate-90', 'rotate-0');
            }
        });
    </script>
</aside>
