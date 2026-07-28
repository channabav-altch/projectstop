<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>របាយការណ៍លក់ Admin - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />

    <!-- 🔴 Library សម្រាប់ប្រតិទិន (Flatpickr) 🔴 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        .smooth-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .flatpickr-calendar { background: #0B132B !important; border: 1px solid #1C2C4E !important; box-shadow: 0 10px 40px rgba(0,0,0,0.5) !important; }
        .flatpickr-day.selected { background: #06b6d4 !important; border-color: #06b6d4 !important; }

        /* កូដ CSS សម្រាប់ពេល Print */
        @media print {
            body { background: white !important; color: black !important; }
            #adminReportForm, .hide-on-print, a, button { display: none !important; }
            #listViewContainer { border: none !important; box-shadow: none !important; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd !important; color: black !important; padding: 8px; }
            th { background: #f3f4f6 !important; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#070b19] via-[#0B132B] to-[#070b19] text-slate-300 min-h-screen p-4 md:p-8">

    <div class="max-w-[1600px] mx-auto space-y-5 print:hidden">

        <!-- Header / Back Button -->
        <div class="flex items-center gap-3 mb-6 hide-on-print">
            <a href="/dashboard" class="flex items-center justify-center w-12 h-12 bg-[#15234b] hover:bg-[#1C2C4E] text-slate-300 rounded-2xl border border-[#1C2C4E] shadow-lg transition-all" title="ត្រឡប់ក្រោយ">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex items-center text-sm font-bold text-slate-500 bg-[#15234b]/40 px-6 py-4 rounded-2xl border border-[#1C2C4E]">
                <span class="text-slate-300">View (ទិដ្ឋភាព)</span>
                <span class="mx-3 text-slate-600">/</span>
                <span class="text-slate-400">System</span>
                <span class="mx-3 text-slate-600">/</span>
                <span class="text-blue-400">Analytics</span>
            </div>
        </div>

        <!-- 🔴 បង្កើត Form ធំមួយក្តោបយក Filter ទាំងអស់ 🔴 -->
        <form action="{{ url()->current() }}" method="GET" id="adminReportForm" class="space-y-4">

            <!-- ផ្ទុកទិន្នន័យលាក់មុខ ពេលចុចប៊ូតុង Filter -->
            <input type="hidden" name="type" id="filterType" value="{{ $type ?? 'all' }}">
            <input type="hidden" name="status" id="filterStatus" value="{{ $status ?? 'all' }}">
            <input type="hidden" name="period" id="filterPeriod" value="{{ $period ?? '' }}">

            <!-- ១. Top Action Bar (ប្រតិទិន, ថ្ងៃនេះ, Print) -->
            <div class="bg-[#15234b]/80 backdrop-blur-xl rounded-2xl p-4 flex flex-col xl:flex-row items-center justify-between border border-[#1C2C4E] shadow-lg gap-4 z-20 relative">

                <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                    <!-- ប្រអប់ប្រតិទិន -->
                    <div class="relative w-full sm:w-56">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="text" id="datePickerInput" name="date" value="{{ $date ?? date('Y-m-d') }}" class="w-full pl-12 pr-4 py-2.5 bg-[#0B132B] border border-cyan-500/30 rounded-xl text-center text-cyan-400 font-bold tracking-wider outline-none transition-all cursor-pointer hover:border-cyan-500" placeholder="ជ្រើសរើសថ្ងៃ">
                    </div>

                    <!-- ប៊ូតុង ថ្ងៃនេះ និង ខែនេះ -->
                    <div class="flex bg-[#0B132B] p-1 rounded-xl border border-[#1C2C4E]">
                        <button type="button" onclick="setAdminFilter('period', 'today')" class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all {{ ($period ?? '') == 'today' ? 'bg-cyan-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">ថ្ងៃនេះ</button>
                        <button type="button" onclick="setAdminFilter('period', 'month')" class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all {{ ($period ?? '') == 'month' ? 'bg-cyan-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">ខែនេះ</button>
                    </div>
                </div>



                <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto justify-end">
                    <button type="button" onclick="toggleViewData()" class="flex items-center gap-2 px-4 py-2 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-200">
                <span id="eyeIcon">👁️</span>
                <span id="btnText">បង្ហាញទិន្នន័យ</span>
            </button>
                    <button type="button" onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-200">🖨️ Print</button>
                    <button type="button" onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-200">PDF</button>
</div>
            </div>

            <div id="targetDataContainer" class="hidden mt-5 pt-5 border-t border-slate-700/30 transition-all duration-300">

    <!-- ចាប់ផ្តើម Card ពណ៌ស្វាយ -->
    <div class="max-w-md bg-[#8B5CF6] rounded-3xl p-6 text-white shadow-lg font-sans">
        <div class="mb-6">
            <p class="text-xs font-bold text-white/80 uppercase mb-1 font-['Hanuman'] tracking-wide">
                ចំណូលសរុប (TOTAL REVENUE)
            </p>
            <!-- ប្រើ number_format ដើម្បីកាត់ក្បៀសខ្ទង់រយ ខ្ទង់ពាន់ ឱ្យស្អាត -->
            <h2 class="text-[40px] leading-none font-extrabold">
                {{ number_format($totalRevenue ?? 0, 2) }} $
            </h2>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- លក់រាយ (RETAIL) -->
            <div class="bg-white/10 border border-white/20 rounded-xl p-4">
                <p class="text-[11px] font-bold text-white/80 uppercase mb-1 font-['Hanuman'] tracking-wide">
                    លក់រាយ (RETAIL)
                </p>
                <h3 class="text-2xl font-bold">
                    {{ number_format($retailRevenue ?? 0, 2) }} $
                </h3>
            </div>

            <!-- បោះដុំ (WHOLESALE) -->
            <div class="bg-white/10 border border-white/20 rounded-xl p-4">
                <p class="text-[11px] font-bold text-white/80 uppercase mb-1 font-['Hanuman'] tracking-wide">
                    បោះដុំ (WHOLESALE)
                </p>
                <h3 class="text-2xl font-bold">
                    {{ number_format($wholesaleRevenue ?? 0, 2) }} $
                </h3>
            </div>
        </div>
    </div>
    <!-- បញ្ចប់ Card ពណ៌ស្វាយ -->

</div>

            <!-- ២. Search & Filter Bar (ស្វែងរក, តម្រងប្រភេទ/ស្ថានភាព) -->
            <div class="bg-[#15234b]/80 backdrop-blur-xl rounded-2xl p-4 flex flex-col xl:flex-row items-center justify-between border border-[#1C2C4E] shadow-lg gap-4 z-10 relative">

                <!-- ប្រអប់ស្វែងរក -->
                <div class="relative w-full xl:w-[400px]">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">🔍</span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="ស្វែងរកឈ្មោះ / អត្តលេខ..." class="w-full pl-11 pr-4 py-3 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-sm text-white outline-none focus:border-blue-500 transition-all">
                </div>
                <div class="flex flex-wrap xl:flex-nowrap items-center gap-3 w-full xl:w-auto justify-end">
                    <!-- តម្រង ទាំងអស់ / បោះដុំ / លក់រាយ -->
                    <div class="flex bg-[#0B132B] p-1.5 rounded-xl border border-[#1C2C4E]">
                        <button type="button" onclick="setAdminFilter('type', 'all')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ ($type ?? 'all') == 'all' ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">ទាំងអស់</button>
                        <button type="button" onclick="setAdminFilter('type', 'wholesale')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ ($type ?? '') == 'wholesale' ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">បោះដុំ</button>
                        <button type="button" onclick="setAdminFilter('type', 'retail')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ ($type ?? '') == 'retail' ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">លក់រាយ</button>
                    </div>

                    <!-- តម្រង ស្ថានភាព -->
                    <div class="flex bg-[#0B132B] p-1.5 rounded-xl border border-[#1C2C4E]">
                        <button type="button" onclick="setAdminFilter('status', 'all')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ ($status ?? 'all') == 'all' ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">All</button>
                        <button type="button" onclick="setAdminFilter('status', 'active')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ ($status ?? '') == 'active' ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:text-white' }}"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> មានលក់</button>
                        <button type="button" onclick="setAdminFilter('status', 'inactive')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ ($status ?? '') == 'inactive' ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:text-white' }}"><span class="w-2 h-2 rounded-full bg-slate-500"></span> គ្មានលក់</button>
                    </div>

                    <!-- ប៊ូតុងប្តូរ View (List / Grid) -->
                    <div class="flex bg-[#0B132B] p-1.5 rounded-xl border border-[#1C2C4E]">
                        <button type="button" id="btnListView" onclick="switchView('list')" class="p-2 bg-blue-600 text-white shadow-md shadow-blue-500/30 rounded-lg transition-all" title="បញ្ជី (List)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <button type="button" id="btnGridView" onclick="switchView('grid')" class="p-2 text-slate-400 hover:text-white transition-all rounded-lg" title="ក្រឡា (Grid)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- ទិដ្ឋភាពបញ្ជី (List View) -->
        <div id="listViewContainer" class="bg-[#15234b]/50 backdrop-blur-md rounded-2xl border border-[#1C2C4E] overflow-hidden shadow-2xl block transition-all duration-300">
            <div id="reportContainer" class="overflow-x-auto hide-scroll">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-[#0B132B] border-b border-[#1C2C4E] text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-6 py-5">#</th>
                            <th class="px-6 py-5">តំណាងលក់ (SELLER)</th>
                            <th class="px-6 py-5">ប្រភេទ</th>
                            <th class="px-6 py-5 text-center">បរិមាណលក់</th>
                            <th class="px-6 py-5 text-right">ចំណូលសរុប</th>
                            <th class="px-6 py-5 text-center hide-on-print">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1C2C4E]/50">
                        @forelse($reports ?? [] as $index => $report)
                            <tr class="hover:bg-[#1C2C4E]/30 transition-all">
                                <td class="px-6 py-4 font-black text-white">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white shadow-lg hide-on-print">
                                            {{ mb_substr($report->name ?? 'U', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-white text-base">{{ $report->name ?? 'មិនមានឈ្មោះ' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if(($report->role ?? '') == 'wholesale')
                                        <span class="bg-purple-500/10 text-purple-400 border border-purple-500/20 px-3 py-1 rounded text-[10px] font-bold">បោះដុំ</span>
                                    @elseif(($report->role ?? '') == 'retail')
                                        <span class="bg-orange-500/10 text-orange-400 border border-orange-500/20 px-3 py-1 rounded text-[10px] font-bold">លក់រាយ</span>
                                    @else
                                        <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-1 rounded text-[10px] font-bold">ទូទៅ</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-black">{{ $report->sales_count ?? 0 }} វិក្កយបត្រ</td>
                                <td class="px-6 py-4 text-right font-black text-emerald-400">${{ number_format($report->total_sales ?? 0, 2) }}</td>
                                <td class="px-6 py-4 text-center hide-on-print">
                                    <a href="{{ route('report.seller_detail', $report->id) }}?{{ http_build_query(request()->query()) }}" class="inline-block text-blue-400 hover:text-white bg-blue-500/10 hover:bg-blue-600 p-2 rounded-lg transition-all" title="មើលលម្អិត">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <span class="text-6xl mb-4 block opacity-50">📂</span>
                                    <h3 class="text-xl font-bold text-slate-400">មិនទាន់មានទិន្នន័យរបាយការណ៍ទេ!</h3>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($sellerReports) && count($sellerReports) > 0 && method_exists($sellerReports, 'links'))
                <div class="p-4 bg-[#0B132B] border-t border-[#1C2C4E] hide-on-print">
                    {{ $sellerReports->links() }}
                </div>
            @endif
        </div>

        <!-- ទិដ្ឋភាពក្រឡា (Grid View) -->
        <div id="gridViewContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 transition-all duration-300">
            @forelse($sellerReports ?? [] as $report)
                <div class="bg-[#15234b] border border-[#1C2C4E] rounded-3xl p-6 shadow-xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 bg-blue-500/20 text-blue-400 text-[10px] font-black px-3 py-1 rounded-bl-xl uppercase">ទូទៅ</div>
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white text-2xl shadow-[0_0_15px_rgba(99,102,241,0.4)] mb-4 border-4 border-[#0B132B]">
                            {{ mb_substr($report->seller_name ?? 'U', 0, 2) }}
                        </div>
                        <h3 class="font-black text-white text-lg">{{ $report->seller_name ?? 'មិនមានឈ្មោះ' }}</h3>
                        <p class="text-xs text-slate-400 font-mono mb-4">{{ $report->total_orders ?? 0 }} វិក្កយបត្រ</p>
                        <div class="w-full bg-[#0B132B] rounded-xl p-3 border border-[#1C2C4E] flex justify-between items-center">
                            <span class="text-xs text-slate-500">ចំណូលសរុប</span>
                            <span class="text-emerald-400 font-black">${{ number_format($report->total_sales ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-[#15234b]/30 rounded-3xl border border-[#1C2C4E] border-dashed">
                    <span class="text-6xl mb-4 block opacity-50">📂</span>
                    <h3 class="text-xl font-bold text-slate-400">មិនទាន់មានទិន្នន័យទេ!</h3>
                </div>
            @endforelse
        </div>
    </div>

    <!-- =================================================== -->
    <!-- 🟢 ផ្នែករបាយការណ៍សម្រាប់ PRINT (ទម្រង់ថ្មី ធំទូលាយស្អាត) 🟢 -->
    <!-- =================================================== -->
    @if(isset($reports) && count($reports) > 0)
    <div id="reportContainer" class="hidden print:block w-full p-4 bg-white text-slate-800 font-['Hanuman']" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">

        <!-- ក្បាលទំព័រ (Header) -->
        <div class="text-center mb-6 ">
            <div class="text-xs text-slate-400 font-sans tracking-wide mb-1">Sales_Report_today</div>
            <h1 class="text-2xl font-bold text-indigo-900 font-['Khmer_MEF2'] border border-slate-200 rounded-xl p-4 shadow-sm ">របាយការណ៍លក់សរុប</h1>
            <div class="w-full border-b-2 border-indigo-600 mt-3"></div>
        </div>

        <!-- តារាងរបាយការណ៍មេ (ប្រើ table-fixed ដើម្បីកំណត់ទំហំជួរឈរបានច្បាស់) -->
        <table class="w-full text-left border-collapse table-fixed">
            <thead>
                <tr class="bg-slate-100 text-slate-600 text-[12px] font-bold uppercase border-b border-slate-200">
                    <th class="p-3 w-[5%] text-center">#</th>
                    <th class="p-3 text-slate-700 w-[50%]">តំណាងលក់ និងទំនិញលម្អិត</th> <!-- ឲ្យទំហំ ៥០% នៃក្រដាស -->
                    <th class="p-3 text-center w-[10%]">ប្រភេទ</th>
                    <th class="p-3 text-center w-[10%]">បរិមាណរួម</th>
                    <th class="p-3 text-center w-[10%]">អតិថិជន</th>
                    <th class="p-3 text-right w-[15%]">ចំណូល</th>
                </tr>
            </thead>
            <tbody class="text-[13px] divide-y divide-slate-200">

        @foreach($reports as $key => $report)
        <!-- ជួរទី ១៖ ព័ត៌មានមេរបស់បុគ្គលិក -->
        <tr class="align-top border-t border-slate-200 bg-white">
            <td class="p-3 text-center font-bold text-slate-500">{{ $key + 1 }}</td>
            <td class="p-3">
                <div class="font-black text-slate-800 text-[15px]">
                    {{ $report->name ?? 'មិនមានឈ្មោះ' }}
                </div>
                <div class="text-[11px] text-slate-400 font-sans">
                    ID: {{ $report->id ?? '-' }}
                </div>
            </td>
            <td class="p-3 text-center">
                @if(($report->role ?? '') == 'wholesale')
                    <span class="bg-purple-50 text-purple-600 px-2 py-1 rounded text-[11px] font-bold">បោះដុំ</span>
                @elseif(($report->role ?? '') == 'retail')
                    <span class="bg-orange-50 text-orange-600 px-2 py-1 rounded text-[11px] font-bold">លក់រាយ</span>
                @else
                    <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-[11px] font-bold">ទូទៅ</span>
                @endif
            </td>
            <td class="p-3 text-center text-slate-600 font-bold">
                {{ $report->total_qty ?? '-' }}
            </td>
            <td class="p-3 text-center font-bold text-slate-700">
                {{ $report->sales_count ?? '0' }} នាក់
            </td>
            <td class="p-3 text-right">
                <div class="font-black text-emerald-600 text-[15px]">
                    {{ number_format($report->total_sales ?? 0, 2) }} $
                </div>
            </td>
        </tr>

       <!-- ជួរទី ២៖ បញ្ជីទំនិញលម្អិត -->
        @if(($report->sales_count ?? 0) > 0 && isset($report->items) && count($report->items) > 0)
        <tr class="bg-slate-50/50">
            <td colspan="6" class="p-3 pt-0">
                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-sm w-full">
                    <div class="text-[11px] font-bold text-slate-500 mb-2 border-b border-slate-100 pb-1">
                        បញ្ជីទំនិញលម្អិត (គិតជាដប)
                    </div>
                    <div class="grid grid-cols-2 gap-x-8 gap-y-2 w-full">
                        @foreach($report->items as $item)
                        <div class="flex justify-between items-start border-b border-dashed border-slate-200 pb-1.5 w-full">
    <!-- 🟢 ឈ្មោះ -->
    <span class="text-slate-700 text-[11px] font-medium leading-tight flex-1 pr-2 break-words">
        • {{ $item->product_name }}
    </span>

    <!-- 🟢 ចំនួន និងតម្លៃ -->
    <div class="text-right shrink-0 whitespace-nowrap">
        <span class="font-bold text-slate-800 text-[11px]">{{ $item->qty }} ដប</span>
        <span class="text-[10px] text-slate-400 font-sans mx-1">({{ number_format($item->unit_price, 2) }}$)</span>
        <span class="font-black text-emerald-600 text-[11px]">
            {{ number_format($item->qty * $item->unit_price, 2) }} $
        </span>
    </div>
</div>
                        @endforeach
                    </div>
                </div>
            </td>
        </tr>
        @endif
        @endforeach

    </tbody>
        </table>

      <h2 class="text-center bg-blue-500 font-bold text-lg mb-6 text-indigo-900 print:break-before-page">របាយការណ៍ស្ថិតតាមតំបន់</h2>

<!-- 🌟 ១. តំបន់ទិន្នន័យរួម (ភ្នំពេញ + ខេត្ត) -->
<div class="text-blue-600 bg-orange-500 text-white p-3 rounded-t-lg font-bold mb-0">
    📊 តំបន់ទិន្នន័យរួម (ភ្នំពេញ + ខេត្ត)
</div>
<div class="border rounded-b-lg p-4 mb-2 bg-white shadow-sm">
    <!-- ទំនិញបានទទួលប្រាក់ -->
    <div class="mb-4 border-b pb-4">
        <h4 class="text-green-600 font-bold mb-2">✔ ទំនិញបានទទួលប្រាក់</h4>
        <div class="flex justify-between bg-gray-50 p-2 rounded mb-2">
            <span>លក់រាយ</span>
            <span class="font-bold text-green-700">{{ number_format($totalPaidAmount, 2) }} $ ({{ $totalPaidCount }} នាក់)</span>
        </div>
        <!-- បញ្ជីទំនិញរួម Paid -->
        <table class="w-full text-xs">
            <tr class="text-gray-500 border-b">
                <th class="text-left py-1">ឈ្មោះទំនិញសរុប</th>
                <th class="text-center">ចំនួន</th>
                <th class="text-right">សរុបទឹកប្រាក់</th>
            </tr>
            @foreach(collect($allPaid['pp']['items'])->merge($allPaid['prov']['items']) as $item)
            <tr class="border-b border-dashed">
                <td class="py-1">{{ $item->product_name }}</td>
                <td class="text-center">{{ $item->qty }} ដប</td>
                <td class="text-right">{{ number_format($item->unit_price * $item->qty, 2) }} $</td>
            </tr>
            @endforeach
        </table>
    </div>

    <!-- ទំនិញមិនទាន់បានទទួលប្រាក់ -->
    <div>
        <h4 class="text-orange-600 font-bold mb-2">⏳ ទំនិញមិនទាន់បានទទួលប្រាក់</h4>
        <div class="flex justify-between bg-gray-50 p-2 rounded mb-2">
            <span>លក់រាយ</span>
            <span class="font-bold text-orange-600">{{ number_format($totalUnpaidAmount, 2) }} $ ({{ $totalUnpaidCount }} នាក់)</span>
        </div>
        <table class="w-full text-xs">
            <tr class="text-gray-500 border-b">
                <th class="text-left py-1">ឈ្មោះទំនិញសរុប</th>
                <th class="text-center">ចំនួន</th>
                <th class="text-right">សរុបទឹកប្រាក់</th>
            </tr>
            @foreach(collect($allUnpaid['pp']['items'])->merge($allUnpaid['prov']['items']) as $item)
            <tr class="border-b border-dashed">
                <td class="py-1">{{ $item->product_name }}</td>
                <td class="text-center">{{ $item->qty }} ដប</td>
                <td class="text-right">{{ number_format($item->unit_price * $item->qty, 2) }} $</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<!-- 📊 ប្រអប់សរុបប្រចាំតំបន់រួម -->
<div class="bg-slate-800 text-white p-4 rounded-b-lg mb-6 shadow-md text-sm">
    <div class="font-bold text-blue-300 mb-2">= បូកសរុប (ទំនិញបានទទួលប្រាក់ + មិនទាន់ទូទាត់)</div>
    <div class="flex justify-between py-1 border-b border-slate-700">
        <span>• លក់រាយ (តម្លៃទំនិញសុទ្ធ):</span>
        <span class="font-semibold">{{ number_format($grandTotalAmount, 2) }} $ (សរុប {{ $totalPaidCount + $totalUnpaidCount }} នាក់)</span>
    </div>
    <div class="flex justify-between py-1 border-b border-slate-700 text-orange-400">
        <span>◻ ថ្លៃដឹកជញ្ជូនសរុប:</span>
        <span class="font-semibold">{{ number_format($grandTotalDelivery ?? 0, 2) }} $</span>
    </div>
    <div class="flex justify-between pt-2 text-base font-bold text-green-400">
        <span>💰 ចំណូលសរុប (ទំនិញ + ថ្លៃដឹក):</span>
        <span>{{ number_format($finalGrandTotal, 2) }} $</span>
    </div>
</div>


<!-- 🌟 ២. រាជធានីភ្នំពេញ -->
<div class="bg-orange-500 text-blue-600 p-3 rounded-t-lg font-bold mb-0 print:break-before-page">
    📌 តំបន់ទីកន្លែង: រាជធានីភ្នំពេញ + ទិញផ្ទាល់
</div>
<div class="border rounded-b-lg p-4 mb-2 bg-white shadow-sm">
    <!-- បានទទួលប្រាក់ ភ្នំពេញ -->
    <div class="mb-4 border-b pb-4">
        <h4 class="text-green-600 font-bold mb-2">✔ ទំនិញបានទទួលប្រាក់</h4>
        <div class="flex justify-between bg-gray-50 p-2 rounded mb-2">
            <span>លក់រាយ</span>
            <span class="font-bold">{{ number_format($ppPaid['totalAmount'], 2) }} $ ({{ $ppPaid['orderCount'] }} នាក់)</span>
        </div>
        @foreach($ppPaid['items'] as $item)
        <div class="flex justify-between text-xs py-1 border-b border-dashed">
            <span>{{ $item->product_name }}</span>
            <span>{{ $item->qty }} ដប - {{ number_format($item->unit_price * $item->qty, 2) }} $</span>
        </div>
        @endforeach
    </div>

    <!-- មិនទាន់បានទទួលប្រាក់ ភ្នំពេញ -->
    <div>
        <h4 class="text-orange-600 font-bold mb-2">⏳ ទំនិញមិនទាន់បានទទួលប្រាក់</h4>
        <div class="flex justify-between bg-gray-50 p-2 rounded mb-2">
            <span>លក់រាយ</span>
            <span class="font-bold">{{ number_format($ppUnpaid['totalAmount'], 2) }} $ ({{ $ppUnpaid['orderCount'] }} នាក់)</span>
        </div>
        @foreach($ppUnpaid['items'] as $item)
        <div class="flex justify-between text-xs py-1 border-b border-dashed">
            <span>{{ $item->product_name }}</span>
            <span>{{ $item->qty }} ដប - {{ number_format($item->unit_price * $item->qty, 2) }} $</span>
        </div>
        @endforeach
    </div>
</div>

<!-- 📊 ប្រអប់សរុបប្រចាំតំបន់ ភ្នំពេញ -->
<div class="bg-slate-800 text-white p-4 rounded-b-lg mb-6 shadow-md text-sm">
    <div class="font-bold text-blue-300 mb-2">= បូកសរុប (ភ្នំពេញ)</div>
    <div class="flex justify-between py-1 border-b border-slate-700">
        <span>• លក់រាយ (តម្លៃទំនិញសុទ្ធ):</span>
        <span class="font-semibold">{{ number_format(($ppPaid['totalAmount'] ?? 0) + ($ppUnpaid['totalAmount'] ?? 0) - (($ppPaid['deliveryFee'] ?? 0) + ($ppUnpaid['deliveryFee'] ?? 0)), 2) }} $ (សរុប {{ $ppPaid['orderCount'] + $ppUnpaid['orderCount'] }} នាក់)</span>
    </div>
    <div class="flex justify-between py-1 border-b border-slate-700 text-orange-400">
        <span>◻ ថ្លៃដឹកជញ្ជូនសរុប:</span>
        <span class="font-semibold">{{ number_format(($ppPaid['deliveryFee'] ?? 0) + ($ppUnpaid['deliveryFee'] ?? 0), 2) }} $</span>
    </div>
    <div class="flex justify-between pt-2 text-base font-bold text-green-400">
        <span>💰 ចំណូលសរុប (ទំនិញ + ថ្លៃដឹក):</span>
        <span>{{ number_format(($ppPaid['totalAmount'] ?? 0) + ($ppUnpaid['totalAmount'] ?? 0), 2) }} $</span>
    </div>
</div>


<!-- 🌟 ៣. តាមបណ្តាខេត្ត -->
<div class="bg-orange-500 text-blue-600 p-3 rounded-t-lg font-bold mb-0 print:break-before-page">
    📌 តំបន់ទីកន្លែង: តាមបណ្តាខេត្ត
</div>
<div class="border rounded-b-lg p-4 mb-2 bg-white shadow-sm">
    <!-- បានទទួលប្រាក់ ខេត្ត -->
    <div class="mb-4 border-b pb-4">
        <h4 class="text-green-600 font-bold mb-2">✔ ទំនិញបានទទួលប្រាក់</h4>
        <div class="flex justify-between bg-gray-50 p-2 rounded mb-2">
            <span>លក់រាយ</span>
            <span class="font-bold">{{ number_format($provPaid['totalAmount'], 2) }} $ ({{ $provPaid['orderCount'] }} នាក់)</span>
        </div>
        @foreach($provPaid['items'] as $item)
        <div class="flex justify-between text-xs py-1 border-b border-dashed">
            <span>{{ $item->product_name }}</span>
            <span>{{ $item->qty }} ដប - {{ number_format($item->unit_price * $item->qty, 2) }} $</span>
        </div>
        @endforeach
    </div>

    <!-- មិនទាន់បានទទួលប្រាក់ ខេត្ត -->
    <div>
        <h4 class="text-orange-600 font-bold mb-2">⏳ ទំនិញមិនទាន់បានទទួលប្រាក់</h4>
        <div class="flex justify-between bg-gray-50 p-2 rounded mb-2">
            <span>លក់រាយ</span>
            <span class="font-bold">{{ number_format($provUnpaid['totalAmount'], 2) }} $ ({{ $provUnpaid['orderCount'] }} នាក់)</span>
        </div>
        @foreach($provUnpaid['items'] as $item)
        <div class="flex justify-between text-xs py-1 border-b border-dashed">
            <span>{{ $item->product_name }}</span>
            <span>{{ $item->qty }} ដប - {{ number_format($item->unit_price * $item->qty, 2) }} $</span>
        </div>
        @endforeach
    </div>
</div>

<!-- 📊 ប្រអប់សរុបប្រចាំតំបន់ តាមបណ្តាខេត្ត -->
<div class="bg-slate-800 text-white p-4 rounded-b-lg mb-6 shadow-md text-sm">
    <div class="font-bold text-blue-300 mb-2">= បូកសរុប (ខេត្ត)</div>
    <div class="flex justify-between py-1 border-b border-slate-700">
        <span>• លក់រាយ (តម្លៃទំនិញសុទ្ធ):</span>
        <span class="font-semibold">{{ number_format(($provPaid['totalAmount'] ?? 0) + ($provUnpaid['totalAmount'] ?? 0) - (($provPaid['deliveryFee'] ?? 0) + ($provUnpaid['deliveryFee'] ?? 0)), 2) }} $ (សរុប {{ $provPaid['orderCount'] + $provUnpaid['orderCount'] }} នាក់)</span>
    </div>
    <div class="flex justify-between py-1 border-b border-slate-700 text-orange-400">
        <span>◻ ថ្លៃដឹកជញ្ជូនសរុប:</span>
        <span class="font-semibold">{{ number_format(($provPaid['deliveryFee'] ?? 0) + ($provUnpaid['deliveryFee'] ?? 0), 2) }} $</span>
    </div>
    <div class="flex justify-between pt-2 text-base font-bold text-green-400">
        <span>💰 ចំណូលសរុប (ទំនិញ + ថ្លៃដឹក):</span>
        <span>{{ number_format(($provPaid['totalAmount'] ?? 0) + ($provUnpaid['totalAmount'] ?? 0), 2) }} $</span>
    </div>
</div>

<!-- 🟤 ៤. ផ្នែកសរុបខាងក្រោមបង្អស់ (Grand Total Summary) -->
<div class="bg-slate-900 text-white p-5 rounded-lg mt-6 shadow-lg print:break-before-page text-sm">
    <div class="font-bold text-blue-300 mb-2">= បូកសរុបទាំងអស់ (ទំនិញបានទទួលប្រាក់ និងមិនទាន់បានទទួលប្រាក់)</div>

    <div class="flex justify-between py-1">
        <span>• លក់ដុំ (តម្លៃទំនិញសុទ្ធ):</span>
        <span>0.00 $ (សរុប 0 នាក់)</span>
    </div>

    <div class="flex justify-between py-1 border-b border-slate-700">
        <span>• លក់រាយ (តម្លៃទំនិញសុទ្ធ):</span>
        <span class="font-semibold">{{ number_format($grandTotalAmount, 2) }} $ (សរុប {{ $totalPaidCount + $totalUnpaidCount }} នាក់)</span>
    </div>

    <div class="flex justify-between py-1 border-b border-slate-700 text-orange-400">
        <span>📦 ថ្លៃដឹកជញ្ជូន (សេវា) សរុប:</span>
        <span class="font-semibold">{{ number_format($grandTotalDelivery ?? 0, 2) }} $</span>
    </div>

    <div class="flex justify-between pt-3 text-base font-bold text-emerald-400 mt-2 bg-slate-800 p-3 rounded">
        <span>💰 ចំណូលសរុបរួមទាំងអស់ (ទំនិញ + ថ្លៃដឹក):</span>
        <span>{{ number_format($finalGrandTotal, 2) }} $</span>
    </div>
</div>
    </div>
    @endif

    <!-- 🔴 Script សម្រាប់ប្រតិទិន និងបញ្ជាប៊ូតុង 🔴 -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- ១. ភ្ជាប់ Library សម្រាប់បង្កើត PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function setAdminFilter(name, value) {
            if(name === 'type') { document.getElementById('filterType').value = value; }
            if(name === 'status') { document.getElementById('filterStatus').value = value; }
            if(name === 'period') { document.getElementById('filterPeriod').value = value; }
            document.getElementById('adminReportForm').submit();
        }

        document.addEventListener("DOMContentLoaded", function() {
            if(document.getElementById("datePickerInput")) {
                flatpickr("#datePickerInput", {
                    mode: "single",
                    dateFormat: "Y-m-d",
                    defaultDate: "{{ $date ?? date('Y-m-d') }}",
                    theme: "dark",
                    onChange: function(selectedDates, dateStr, instance) {
                        document.getElementById('filterPeriod').value = '';
                        document.getElementById('adminReportForm').submit();
                    }
                });
            }
        });

        function switchView(viewType) {
            const listView = document.getElementById('listViewContainer');
            const gridView = document.getElementById('gridViewContainer');
            const btnList = document.getElementById('btnListView');
            const btnGrid = document.getElementById('btnGridView');

            if(viewType === 'grid') {
                listView.classList.remove('block'); listView.classList.add('hidden');
                gridView.classList.remove('hidden'); gridView.classList.add('grid');
                btnGrid.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/30'); btnGrid.classList.remove('text-slate-400');
                btnList.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/30'); btnList.classList.add('text-slate-400');
            } else {
                gridView.classList.remove('grid'); gridView.classList.add('hidden');
                listView.classList.remove('hidden'); listView.classList.add('block');
                btnList.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/30'); btnList.classList.remove('text-slate-400');
                btnGrid.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/30'); btnGrid.classList.add('text-slate-400');
            }
        }

        function downloadDirectPDF() {
    // យក Container របាយការណ៍លម្អិតដែលមាន id="reportContainer"
    var element = document.getElementById('reportContainer');

    var opt = {
        margin:       0.3,
        filename:     'Admin-Report.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' } // អាចប្ដូរជា landscape បើចង់បានក្រដាសបណ្តោយទូលាយ
    };


    html2pdf().set(opt).from(element).save();
}

function toggleViewData() {
    var dataContainer = document.getElementById('targetDataContainer');
    var eyeIcon = document.getElementById('eyeIcon');
    var btnText = document.getElementById('btnText');

    if (dataContainer) {
        // បិទ ឬ បើក ផ្ទាំងពណ៌ស្វាយ
        dataContainer.classList.toggle('hidden');

        // កែប្រែទម្រង់ប៊ូតុងទៅតាមស្ថានភាពជាក់ស្តែង (បើក/បិទ)
        if (dataContainer.classList.contains('hidden')) {
            eyeIcon.innerText = '👁️';
            btnText.innerText = 'បង្ហាញទិន្នន័យ';
        } else {
            eyeIcon.innerText = '🙈'; // ឬប្រើរូបភ្នែកឆ្នូតសញ្ញាខ្វែងក៏បាន
            btnText.innerText = 'លាក់ទិន្នន័យ';
        }
    }
}
    </script>
</body>
</html>
