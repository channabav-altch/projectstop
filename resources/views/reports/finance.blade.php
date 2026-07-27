<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>គណនី និងហិរញ្ញវត្ថុ - STOCK.PRO</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />

    <!-- 🔴 Library សម្រាប់ប្រតិទិន (Flatpickr CSS) ត្រូវតែមានដើម្បីកុំឱ្យលោតប្រអប់ស 🔴 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        /* ធ្វើឱ្យប្រតិទិនស៊ីពណ៌ជាមួយ Dark Theme */
        .flatpickr-calendar { background: #0B132B !important; border: 1px solid #1C2C4E !important; box-shadow: 0 10px 40px rgba(0,0,0,0.5) !important; }
        .flatpickr-day.selected { background: #06b6d4 !important; border-color: #06b6d4 !important; }
    </style>
</head>

<body class="bg-gradient-to-br from-[#070b19] via-[#0B132B] to-[#04060d] text-slate-300 h-screen flex overflow-hidden">

    <!-- ========================================== -->
    <!-- 🔴 ១. SIDEBAR ម៉ឺនុយរង (ខាងឆ្វេង) 🔴 -->
    <!-- ========================================== -->
    <aside class="w-[300px] h-full bg-[#0d1120]/90 border-r border-[#1C2C4E] p-5 flex flex-col shrink-0 relative z-30">

        <!-- Logo Header -->
        <div class="flex items-center gap-3 mb-8 pb-4 border-b border-[#1C2C4E]/50">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <div>
                <h2 class="font-black text-white text-base tracking-wide">គណនី & ហិរញ្ញវត្ថុ</h2>
                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">Management</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <nav class="flex-1 space-y-2 overflow-y-auto hide-scroll">

            <!-- ម៉ឺនុយ ទិន្នន័យស្តុក ជាមួយ Flyout -->
            {{-- <div class="relative">
                <button onclick="toggleStockFlyout(event)" id="btn-stock" class="tab-btn w-full flex items-center justify-between px-4 py-3.5 rounded-xl text-slate-400 hover:text-white hover:bg-[#15234b]/50 border border-transparent transition-all font-bold text-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        <span>ទិន្នន័យស្តុក</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <!-- Flyout Menu -->
                <div id="stockFlyout" class="absolute left-[105%] top-0 w-[260px] bg-[#111827] border border-[#1C2C4E] rounded-2xl shadow-2xl p-3 opacity-0 pointer-events-none translate-x-3 transition-all duration-300 z-50">
                    <ul class="space-y-1">
                        <li><button onclick="switchTab('stock_sale', 'ស្តុកបានលក់ចេញ')" class="w-full text-left p-3 text-xs font-bold text-slate-400 hover:text-white hover:bg-[#15234b] rounded-xl flex items-center gap-2 transition-all"> <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span> ស្តុកបានលក់ចេញ</button></li>
                        <li><button onclick="switchTab('stock_current', 'ស្តុកបច្ចុប្បន្នជាក់ស្តែង')" class="w-full text-left p-3 text-xs font-bold text-slate-400 hover:text-white hover:bg-[#15234b] rounded-xl flex items-center gap-2 transition-all"> <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span> ស្តុកបច្ចុប្បន្នជាក់ស្តែង</button></li>
                        <li><button onclick="switchTab('stock_new', 'ស្តុកថ្មី & នាំចូល')" class="w-full text-left p-3 text-xs font-bold text-slate-400 hover:text-white hover:bg-[#15234b] rounded-xl flex items-center gap-2 transition-all"> <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span> ស្តុកថ្មី & នាំចូល</button></li>
                        <li><button onclick="switchTab('supplier', 'អ្នកផ្គត់ផ្គង់ (ស្នើទិញ)')" class="w-full text-left p-3 text-xs font-bold text-slate-400 hover:text-white hover:bg-[#15234b] rounded-xl flex items-center gap-2 transition-all"> <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span> អ្នកផ្គត់ផ្គង់ (ស្នើទិញ)</button></li>
                    </ul>
                </div>
            </div> --}}

            <!-- 1. វិភាគទិន្នន័យ -->
<a href="{{ url('/finance?tab=analytics') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ ($activeTab ?? 'pd') == 'analytics' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}">
    📊 <span>វិភាគទិន្នន័យ</span>
</a>

<!-- 2. គណនីការងារ -->
<a href="{{ url('/finance?tab=account') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ ($activeTab ?? 'pd') == 'account' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}">
    💰 <span>គណនីការងារ</span>
</a>

<!-- 3. តារាង PD -->
<a href="{{ url('/finance?tab=pd') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ ($activeTab ?? 'pd') == 'pd' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}">
    📦 <span>តារាង PD</span>
</a>
        </nav>

        <div class="pt-4 border-t border-[#1C2C4E]/50">
           <a href="{{ url('/dashboard') }}"
   class="flex items-center justify-center gap-2 w-full py-2.5 px-4 bg-[#15234b]/60 hover:bg-[#15234b] text-slate-300 hover:text-white text-xs font-bold rounded-xl border border-[#1C2C4E] transition-all">
    <span>←</span> ត្រឡប់ក្រោយវិញ
</a>
        </div>
    </aside>

   <!-- ========================================== -->
<!-- 🔴 Content Area (ផ្នែកខាងស្តាំ) 🔴 -->
<!-- ========================================== -->
<main class="flex-1 p-6 md:p-8 overflow-y-auto flex flex-col space-y-6 w-full relative z-10">

    <!-- 1. Header ជាមួយប្រតិទិន និង Form Filter -->
    <div class="bg-[#15234b]/60 backdrop-blur-xl rounded-2xl p-4 flex flex-col lg:flex-row items-center justify-between border border-[#1C2C4E] shadow-xl gap-4 shrink-0">

        <!-- ឈ្មោះទំព័រ (ប្តូរតាម Tab) -->
        <div class="flex items-center gap-3.5 w-full lg:w-auto">
            <div class="w-11 h-11 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20 flex items-center justify-center shadow-inner text-xl">
                @if(($activeTab ?? 'pd') == 'account')
                    💰
                @elseif(($activeTab ?? 'pd') == 'analytics')
                    📊
                @else
                    📦
                @endif
            </div>
            <div>
                <h3 class="font-black text-white text-base tracking-wide">
                    @if(($activeTab ?? 'pd') == 'account')
                        គណនីការងារ
                    @elseif(($activeTab ?? 'pd') == 'analytics')
                        វិភាគទិន្នន័យ
                    @else
                        តារាង PD
                    @endif
                </h3>
                <p class="text-xs text-slate-400 font-bold">
                    @if(($activeTab ?? 'pd') == 'account')
                        គ្រប់គ្រងគណនី និងចំណាយ
                    @elseif(($activeTab ?? 'pd') == 'analytics')
                        របាយការណ៍សង្ខេប និងការវិភាគ
                    @else
                        ដោះស្រាយ និងគ្រប់គ្រង Admin
                    @endif
                </p>
            </div>
        </div>

        <!-- Form Filter សម្រាប់ ថ្ងៃ/ខែ/ឆ្នាំ និង ប្រតិទិន -->
        <form id="filterForm" method="GET" action="{{ url('/finance') }}" class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full lg:w-auto justify-end">

            <!-- Hidden Inputs -->
            <input type="hidden" name="tab" value="{{ $activeTab ?? 'pd' }}">
            <input type="hidden" name="filter" id="filterInput" value="{{ $filterType ?? 'day' }}">

            <!-- ប៊ូតុងជ្រើសរើស ថ្ងៃ / ខែ / ឆ្នាំ -->
            <div class="flex bg-[#0B132B] p-1 rounded-xl border border-[#1C2C4E]">
                <button type="button" onclick="submitFilter('day')"
                        class="px-5 py-2 text-xs font-bold rounded-lg transition-all {{ ($filterType ?? 'day') == 'day' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    ថ្ងៃ
                </button>
                <button type="button" onclick="submitFilter('month')"
                        class="px-5 py-2 text-xs font-bold rounded-lg transition-all {{ ($filterType ?? 'day') == 'month' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    ខែ
                </button>
                <button type="button" onclick="submitFilter('year')"
                        class="px-5 py-2 text-xs font-bold rounded-lg transition-all {{ ($filterType ?? 'day') == 'year' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    ឆ្នាំ
                </button>
            </div>

            <!-- Date Picker Input -->
            <div class="relative w-full sm:w-48">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <input type="date" name="date" id="datePickerInput"
                       value="{{ $selectedDate ?? date('Y-m-d') }}"
                       onchange="document.getElementById('filterForm').submit()"
                       class="w-full pl-10 pr-4 py-2 bg-[#0B132B] border border-cyan-500/30 rounded-xl text-center text-cyan-400 font-bold text-xs outline-none cursor-pointer hover:border-cyan-500 transition-all shadow-[0_0_15px_rgba(6,182,212,0.1)] focus:ring-1 focus:ring-cyan-500">
            </div>

        </form>
    </div>

    <!-- 2. Main Content (បំបែកតាម Tab) -->
    @if(($activeTab ?? 'pd') == 'analytics')

        <!-- 🟡 ផ្ទាំងសម្រាប់ "វិភាគទិន្នន័យ" (Analytics Dashboard) 🟡 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- ប្រអប់ ចំណូលសរុប -->
            <div class="bg-[#15234b]/60 backdrop-blur-md rounded-2xl p-6 border border-[#1C2C4E] shadow-lg flex flex-col gap-2 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-cyan-500/10 rounded-full blur-2xl"></div>
                <span class="text-sm font-bold text-slate-400">ចំណូលសរុប (Total Revenue)</span>
                <span class="text-3xl font-black text-cyan-400">${{ number_format($totalRevenue ?? 0, 2) }}</span>
            </div>

            <!-- ប្រអប់ ចំនួនប្រតិបត្តិការសរុប -->
            <div class="bg-[#15234b]/60 backdrop-blur-md rounded-2xl p-6 border border-[#1C2C4E] shadow-lg flex flex-col gap-2 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl"></div>
                <span class="text-sm font-bold text-slate-400">វិក្កយបត្រសរុប (Total Orders)</span>
                <span class="text-3xl font-black text-indigo-400">{{ $totalOrders ?? 0 }} <span class="text-sm font-normal text-slate-400">ប្រតិបត្តិការ</span></span>
            </div>

            <!-- ប្រអប់ វិក្កយបត្រដែលបានបោះបង់ -->
            <div class="bg-[#15234b]/60 backdrop-blur-md rounded-2xl p-6 border border-[#1C2C4E] shadow-lg flex flex-col gap-2 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl"></div>
                <span class="text-sm font-bold text-slate-400">បានបោះបង់ (Canceled)</span>
                <span class="text-3xl font-black text-rose-400">{{ $canceledOrders ?? 0 }} <span class="text-sm font-normal text-slate-400">ប្រតិបត្តិការ</span></span>
            </div>

        </div>

    @else

        <!-- 🔵 / 🟢 ផ្ទាំងសម្រាប់ "តារាង PD" ឬ "គណនីការងារ" 🔵 / 🟢 -->
        @if(!isset($deliveries) || $deliveries->isEmpty())

            <!-- ផ្ទាំងបង្ហាញទិន្នន័យទទេរ (ពេលគ្មាន Data) -->
            <div class="flex-1 bg-[#15234b]/20 backdrop-blur-md rounded-3xl border border-[#1C2C4E] flex flex-col items-center justify-center p-8 text-center shadow-inner min-h-[400px]">
                <div class="w-24 h-24 bg-amber-500/10 rounded-full flex items-center justify-center border border-amber-500/20 mb-5 shadow-[0_0_20px_rgba(245,158,11,0.15)]">
                    <span class="text-5xl">🎁</span>
                </div>
                <h3 class="text-xl font-black text-slate-300 tracking-wide">មិនទាន់មានទិន្នន័យសម្រាប់លក្ខខណ្ឌនេះ!</h3>
                <p class="text-sm text-slate-500 mt-2 max-w-sm">រាល់ទិន្នន័យដែលបានបញ្ចូលរួចរាល់ វានឹងដំណើរការលោតបង្ហាញនៅទីនេះដោយស្វ័យប្រវត្តិ។</p>
            </div>

        @else

            <!-- តារាងបង្ហាញទិន្នន័យ (ពេលមាន Data) -->
            <div class="flex-1 bg-[#15234b]/40 backdrop-blur-md rounded-3xl border border-[#1C2C4E] shadow-xl overflow-hidden flex flex-col">
                <div class="overflow-x-auto w-full">
                    @if(($activeTab ?? 'pd') == 'account')

                        <!-- 🟢 តារាងសម្រាប់ "គណនីការងារ" 🟢 -->
                        <table class="w-full text-sm text-left text-slate-300">
                            <thead class="text-xs uppercase bg-[#0B132B]/80 text-slate-400 border-b border-[#1C2C4E]">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-bold">លេខកូដ (ID)</th>
                                    <th scope="col" class="px-6 py-4 font-bold">ការពិពណ៌នាចំណាយ</th>
                                    <th scope="col" class="px-6 py-4 font-bold">កាលបរិច្ឆេទ</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-right">ចំនួនទឹកប្រាក់</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#1C2C4E]/60">
                                @foreach($deliveries as $item)
                                <tr class="hover:bg-[#15234b]/60 transition-colors">
                                    <td class="px-6 py-4 font-bold text-white"><span class="text-indigo-400">#</span>{{ $item->id }}</td>
                                    <td class="px-6 py-4 font-medium text-slate-200">{{ $item->description ?? 'មិនមានការពិពណ៌នា' }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4 text-right font-black text-indigo-400">
                                        ${{ number_format($item->amount ?? 0, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @else

                        <!-- 🔵 តារាងសម្រាប់ "តារាង PD" 🔵 -->
                        <table class="w-full text-sm text-left text-slate-300">
                            <thead class="text-xs uppercase bg-[#0B132B]/80 text-slate-400 border-b border-[#1C2C4E]">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-bold">លេខកូដ/វិក្កយបត្រ</th>
                                    <th scope="col" class="px-6 py-4 font-bold">អតិថិជន</th>
                                    <th scope="col" class="px-6 py-4 font-bold">ស្ថានភាព</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-right">តម្លៃសរុប</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#1C2C4E]/60">
                                @foreach($deliveries as $item)
                                <tr class="hover:bg-[#15234b]/60 transition-colors">
                                    <td class="px-6 py-4 font-bold text-white flex items-center gap-2"><span class="text-cyan-400">#</span>{{ $item->id }}</td>
                                    <td class="px-6 py-4 font-medium text-slate-200">{{ $item->customer_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-sm">
                                            {{ $item->status ?? 'ជោគជ័យ' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-cyan-400">${{ number_format($item->total_amount ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @endif
                </div>
            </div>

        @endif

    @endif

</main>

    <!-- ========================================== -->
    <!-- 🔴 ៣. JAVASCRIPT និង Flatpickr JS 🔴 -->
    <!-- ========================================== -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // បើកដំណើរការប្រតិទិន (ការពារការលោតប្រអប់ស)
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#datePickerInput", {
                mode: "single",
                dateFormat: "Y-m-d",
                defaultDate: "today",
                theme: "dark"
            });
        });

        // មុខងារ Flyout Menu ស្តុក
        function toggleStockFlyout(event) {
            event.stopPropagation();
            const flyout = document.getElementById('stockFlyout');
            const isHidden = flyout.classList.contains('opacity-0');
            closeAllFlyouts();
            if (isHidden) {
                flyout.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-3');
                flyout.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
            }
        }

        // មុខងារប្តូរ Tab និងប្តូរឈ្មោះ
        function switchTab(tabKey, titleText, element = null) {
            const icons = { 'analytics': '📊', 'work_account': '💰', 'pd_table': '📦', 'stock_sale': '📈', 'stock_current': '🏬', 'stock_new': '📥', 'supplier': '🤝' };
            const subTitles = {
                'analytics': 'វិភាគនិងវាយតម្លៃលទ្ធផលទូទៅ',
                'work_account': 'គ្រប់គ្រងគណនីចំណាយនិងចំណូលបុគ្គលិក',
                'pd_table': 'ដោះស្រាយ និងគ្រប់គ្រង Admin',
                'stock_sale': 'របាយការណ៍ទិន្នន័យទំនិញដែលបានលក់ចេញ',
                'stock_current': 'ពិនិត្យចំនួនផលិតផលជាក់ស្តែងក្នុងឃ្លាំង',
                'stock_new': 'ទិន្នន័យទំនិញដែលទើបនាំចូលថ្មី',
                'supplier': 'ព័ត៌មានអ្នកផ្គត់ផ្គង់ និងការស្នើទិញទំនិញ'
            };

            document.getElementById('main-title').innerText = titleText;
            document.getElementById('sub-title').innerText = subTitles[tabKey] || '';
            document.getElementById('header-icon').innerText = icons[tabKey] || '📋';
            document.getElementById('empty-msg').innerText = `មិនទាន់មានទិន្នន័យ${titleText}ទេ!`;

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-[#15234b]', 'text-white', 'border-[#1c2c4e]', 'shadow-md');
                btn.classList.add('text-slate-400');
            });

            if (element) {
                element.classList.remove('text-slate-400');
                element.classList.add('bg-[#15234b]', 'text-white', 'border-[#1c2c4e]', 'shadow-md');
            } else {
                const stockBtn = document.getElementById('btn-stock');
                stockBtn.classList.remove('text-slate-400');
                stockBtn.classList.add('bg-[#15234b]', 'text-white', 'border-[#1c2c4e]', 'shadow-md');
            }
            closeAllFlyouts();
        }

        // មុខងារប្តូរពណ៌ ថ្ងៃ/ខែ/ឆ្នាំ
        function togglePeriod(btn) {
            document.querySelectorAll('.period-btn').forEach(b => {
                b.classList.remove('bg-indigo-600', 'text-white', 'shadow-md');
                b.classList.add('text-slate-400');
            });
            btn.classList.remove('text-slate-400');
            btn.classList.add('bg-indigo-600', 'text-white', 'shadow-md');
        }

        function closeAllFlyouts() {
            const flyout = document.getElementById('stockFlyout');
            flyout.classList.add('opacity-0', 'pointer-events-none', 'translate-x-3');
            flyout.classList.remove('opacity-100', 'pointer-events-auto', 'translate-x-0');
        }

        document.addEventListener('click', closeAllFlyouts);

       function submitFilter(type) {
        document.getElementById('filterInput').value = type;
        document.getElementById('filterForm').submit();
    }
    </script>
</body>
</html>
