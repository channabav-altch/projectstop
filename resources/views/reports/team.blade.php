<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>របាយការណ៍រួម - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        .flatpickr-calendar { background: #0B132B !important; border: 1px solid #1C2C4E !important; box-shadow: 0 10px 40px rgba(0,0,0,0.5) !important; }
        .flatpickr-day.selected { background: #4f46e5 !important; border-color: #4f46e5 !important; }
        .modal-enter { animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes modalFadeIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    </style>
</head>
<body class="bg-gradient-to-br from-[#070b19] via-[#0B132B] to-[#04060d] text-slate-300 min-h-screen p-4 md:p-8 flex flex-col items-center relative">

    <!-- 🔴 បង្កើត Form ធំមួយក្តោបយកផ្ទាំងទាំងមូល -->
    <form action="{{ url()->current() }}" method="GET" id="reportForm" class="w-full max-w-[1600px] space-y-6 relative z-10">

        <!-- Header -->
        <div class="bg-[#15234b]/60 backdrop-blur-xl rounded-2xl p-6 border border-[#1C2C4E] shadow-xl flex flex-col gap-6">
            <div class="flex flex-wrap lg:flex-nowrap items-start lg:items-center justify-between gap-6">

                <!-- ខាងឆ្វេង -->
                <div class="flex items-center gap-4">
                    <a href="{{ url('/dashboard') }}" class="w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-slate-300 font-bold transition-all border border-slate-700/50 cursor-pointer">
                        &lt;
                    </a>
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-500/30">📦</div>
                    <div>
                        <h2 class="text-2xl font-black text-white tracking-wide">របាយការណ៍រួម</h2>
                        <p class="text-sm text-slate-400 font-bold mt-0.5">ទិន្នន័យការលក់របស់ក្រុមគ្រប់គ្រង</p>
                    </div>
                </div>

                <!-- ខាងស្តាំ (ប៊ូតុងភ្នែក និង ប៊ូតុងចំណាយ) -->
                <div class="flex flex-wrap items-center gap-4 w-full lg:w-auto justify-end">
                    <!-- ប៊ូតុងលាក់/បង្ហាញទិន្នន័យ (បិទ/បើកភ្នែក) -->
                    <button type="button" onclick="toggleDataVisibility()" id="btnToggleVisibility" class="flex items-center gap-2 bg-[#1C2C4E] text-slate-300 px-5 py-2.5 rounded-lg hover:bg-indigo-600 hover:text-white font-bold transition border border-slate-700 shadow-sm">
                        <svg id="eyeIconClosed" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        <svg id="eyeIconOpen" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <span id="visibilityText">លាក់ទិន្នន័យ</span>
                    </button>

                    <!-- ប៊ូតុងចំណាយប្រចាំថ្ងៃ -->
                    <button type="button" onclick="openExpenseModal()" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-600/30 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        ចំណាយប្រចាំថ្ងៃ
                    </button>
                </div>
            </div>

            <!-- ប្រអប់ប្រតិទិន -->
            <div class="flex items-center">
                <div class="relative w-64">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold text-sm pointer-events-none">ថ្ងៃទី</span>
                    <input type="text" id="datePickerInput" name="date" value="{{ $date ?? date('Y-m-d') }}" class="w-full pl-14 pr-10 py-2.5 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-white font-bold text-sm outline-none cursor-pointer hover:border-indigo-500 transition-all focus:border-indigo-500 shadow-inner">
                </div>
            </div>
        </div>

        <!-- ផ្ទាំងកាត (Stats Cards) -->
        <div id="statsContainer" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-violet-600 to-purple-600 p-6 rounded-3xl shadow-[0_10px_30px_rgba(124,58,237,0.3)] text-white relative overflow-hidden">
                <!-- ១. ចំណូលសរុប (TOTAL REVENUE) -->
<p class="text-[11px] font-bold text-white/80 uppercase tracking-wider">ចំណូលសរុប (TOTAL REVENUE)</p>
<h2 class="text-4xl font-black mt-1 sensitive-data" data-value="{{ number_format($totalRevenue ?? 0, 2) }}">
    $ {{ number_format($totalRevenue ?? 0, 2) }}
</h2>

<div class="flex gap-4 mt-6">
    <!-- ២. លក់រាយ (RETAIL) -->
    <div class="bg-white/10 p-3.5 rounded-xl w-full border border-white/20">
        <p class="text-[10px] font-bold text-white/80 uppercase">លក់រាយ (RETAIL)</p>
        <p class="font-black text-lg mt-0.5 sensitive-data" data-value="{{ number_format($retailRevenue ?? 0, 2) }}">
            $ {{ number_format($retailRevenue ?? 0, 2) }}
        </p>
    </div>

    <!-- ៣. លក់ដុំ (WHOLESALE) -->
    <div class="bg-white/10 p-3.5 rounded-xl w-full border border-white/20">
        <p class="text-[10px] font-bold text-white/80 uppercase">លក់ដុំ (WHOLESALE)</p>
        <p class="font-black text-lg mt-0.5 sensitive-data" data-value="{{ number_format($wholesaleRevenue ?? 0, 2) }}">
            $ {{ number_format($wholesaleRevenue ?? 0, 2) }}
        </p>
    </div>
</div>
            </div>

            <div class="bg-[#15234b]/80 backdrop-blur-md p-6 rounded-3xl border border-[#1C2C4E] shadow-xl relative">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">អតិថិជនទិញទំនិញ</p>
                <h2 class="text-3xl font-black text-white mt-1">{{ $totalCustomers ?? 0 }} <span class="text-sm font-bold text-slate-500">នាក់</span></h2>
                <div class="flex gap-4 mt-8">
                    <div class="bg-[#0B132B] p-3 rounded-xl w-full border border-[#1C2C4E]">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">អតិថិជនរាយ</p>
                        <p class="font-black text-white text-lg mt-0.5">{{ $retailCustomers ?? 0 }} <span class="text-[10px] text-slate-500">នាក់</span></p>
                    </div>
                    <div class="bg-[#0B132B] p-3 rounded-xl w-full border border-[#1C2C4E]">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">អតិថិជនដុំ</p>
                        <p class="font-black text-white text-lg mt-0.5">{{ $wholesaleCustomers ?? 0 }} <span class="text-[10px] text-slate-500">នាក់</span></p>
                    </div>
                </div>
            </div>

            <div class="bg-[#15234b]/80 backdrop-blur-md p-6 rounded-3xl border border-[#1C2C4E] shadow-xl">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-4">ស្ថិតិអ្នកគ្រប់គ្រង</p>
                <div class="space-y-3">
                    <div class="flex justify-between items-center bg-[#0B132B] p-3.5 rounded-xl border border-[#1C2C4E]">
                        <span class="text-xs font-bold text-slate-300">អ្នកគ្រប់គ្រងសរុប</span>
                        <!-- 🟢 ដូរពី $managers ទៅ $reports -->
                        <span class="font-black text-white text-base">{{ count($reports ?? []) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-2 pb-1">
            <!-- 🟢 ដូរពី $managers ទៅ $reports ដូចគ្នា -->
            <h3 class="text-xl font-black text-white flex items-center gap-2">បញ្ជីអ្នកគ្រប់គ្រង <span class="text-slate-500 text-base font-bold bg-[#15234b] px-3 py-0.5 rounded-lg border border-[#1C2C4E]">({{ count($reports ?? []) }})</span></h3>
        </div>

        <!-- 🔴 ផ្នែក Filter តារាង 🔴 -->
        <div class="bg-[#15234b]/60 backdrop-blur-xl rounded-2xl p-4 flex flex-col xl:flex-row items-center justify-between border border-[#1C2C4E] shadow-xl gap-4 mt-2">

            <!-- ប្រអប់ Search -->
            <div class="relative w-full xl:w-80">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">🔍</span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="ស្វែងរកឈ្មោះ..." class="w-full pl-11 pr-4 py-2.5 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-sm text-white font-bold outline-none focus:border-indigo-500 transition-all shadow-inner">
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" name="type" id="filterType" value="{{ $type ?? 'all' }}">
            <input type="hidden" name="status" id="filterStatus" value="{{ $status ?? 'all' }}">

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full xl:w-auto justify-end">
                <div class="flex bg-[#0B132B] p-1.5 rounded-xl border border-[#1C2C4E]">
                    <button type="button" onclick="setFilter('type', 'all')" class="px-5 py-2 {{ ($type ?? 'all') == 'all' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-white' }} rounded-lg text-xs font-bold transition-all duration-300">ទាំងអស់</button>
                    <button type="button" onclick="setFilter('type', 'wholesale')" class="px-5 py-2 {{ ($type ?? '') == 'wholesale' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-white' }} rounded-lg text-xs font-bold transition-all duration-300">បោះដុំ</button>
                    <button type="button" onclick="setFilter('type', 'retail')" class="px-5 py-2 {{ ($type ?? '') == 'retail' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-white' }} rounded-lg text-xs font-bold transition-all duration-300">លក់រាយ</button>
                </div>

                <div class="flex bg-[#0B132B] p-1.5 rounded-xl border border-[#1C2C4E]">
                    <button type="button" onclick="setFilter('status', 'all')" class="px-5 py-2 {{ ($status ?? 'all') == 'all' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-white' }} rounded-lg text-xs font-bold transition-all duration-300">ទាំងអស់</button>
                    <button type="button" onclick="setFilter('status', 'active')" class="px-5 py-2 {{ ($status ?? '') == 'active' ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-white' }} rounded-lg text-xs font-bold transition-all duration-300">មានលក់</button>
                </div>

                <a href="{{ route('report.export') ?? '#' }}" class="px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-400 hover:to-blue-500 active:scale-95 text-white rounded-xl text-xs font-bold shadow-[0_5px_15px_rgba(79,70,229,0.4)] flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    កំណត់របាយការណ៍ (Export)
                </a>
            </div>
        </div>

        <!-- តារាងទិន្នន័យ -->
        <div class="bg-[#15234b]/60 backdrop-blur-xl rounded-2xl border border-[#1C2C4E] overflow-hidden shadow-2xl">
            <div class="overflow-x-auto hide-scroll">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-[#0B132B] border-b border-[#1C2C4E] text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                        <tr>
                            <th class="px-6 py-5">#</th>
                            <th class="px-6 py-5">អ្នកគ្រប់គ្រង</th>
                            <th class="px-6 py-5 text-center">ប្រភេទ</th>
                            <th class="px-6 py-5 text-center">តំណាងលក់សរុប</th>
                            <th class="px-6 py-5 text-center">ចំនួនលក់ (UNITS)</th>
                            <td class="px-6 py-4 text-center">
    <span class="font-bold text-white">{{ $manager->total_customers ?? 0 }}</span> <span class="text-slate-500 text-xs">អតិថិជនសរុប</span>
</td>
                            <th class="px-6 py-5 text-right">ចំណូល</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1C2C4E]/50">
    @forelse($reports ?? [] as $key => $manager)
        <tr class="border-b border-[#1C2C4E]/80 hover:bg-white/[0.02] transition-all duration-300 group">

            <!-- ១. លំដាប់ (#) -->
            <td class="px-6 py-4 text-sm font-bold text-slate-500 group-hover:text-slate-300 transition-colors">
                #{{ $loop->iteration }}
            </td>

            <!-- ២. រូបតំណាង (Avatar) + ឈ្មោះអ្នកលក់ -->
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#5642F5] to-purple-500 flex items-center justify-center text-white font-black text-xs shadow-lg shadow-[#5642F5]/30">
                        {{ mb_substr($manager->name ?? 'U', 0, 1) }}
                    </div>
                    <span class="text-sm font-bold text-slate-200 group-hover:text-white transition-colors">
                        {{ $manager->name ?? 'មិនស្គាល់' }}
                    </span>
                </div>
            </td>

            <!-- ៣. ប្រភេទ/Role (បោះដុំ ឬ លក់រាយ) -->
            <td class="px-6 py-4 text-center text-sm">
                @if(strtolower($manager->role ?? '') == 'wholesale')
                    <span class="bg-amber-500/20 text-amber-400 border border-amber-500/30 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm shadow-amber-500/10">
                        បោះដុំ
                    </span>
                @else
                    <span class="bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm shadow-indigo-500/10">
                        លក់រាយ
                    </span>
                @endif
            </td>

            <!-- ៤. ចំនួនវិក្កយបត្រ (count_invoices) -->
            <td class="px-6 py-4 text-center text-sm font-bold text-slate-300">
                <span class="text-sky-400 text-base">{{ number_format($manager->count_invoices ?? 0) }}</span>
                <span class="text-slate-500 text-xs ml-1">វិក្កយបត្រ</span>
            </td>

            <!-- ៥. ចំនួនទំនិញ (sum_total_units) -->
            <td class="px-6 py-4 text-center text-sm font-bold text-slate-300">
                <span class="text-orange-400 text-base">{{ number_format($manager->sum_total_units ?? 0) }}</span>
                <span class="text-slate-500 text-xs ml-1">ឯកតា</span>
            </td>

            <!-- ៦. ចំនួនអតិថិជន -->
            <td class="px-6 py-4 text-center text-sm font-bold text-slate-300">
                <span class="text-slate-300 text-base">0</span>
                <span class="text-slate-500 text-xs ml-1">នាក់</span>
            </td>

            <!-- ៧. ទឹកប្រាក់លក់សរុប (sum_total_sales) -->
            <td class="px-6 py-4 text-right">
                <span class="inline-block text-emerald-400 font-black text-sm bg-emerald-500/10 px-3 py-1.5 rounded-lg border border-emerald-500/20 shadow-sm shadow-emerald-500/10 sensitive-data"
                      data-value="$ {{ number_format($manager->sum_total_sales ?? 0, 2) }}">
                    $ {{ number_format($manager->sum_total_sales ?? 0, 2) }}
                </span>
            </td>

        </tr>
    @empty
        <!-- បង្ហាញពេលគ្មានទិន្នន័យ -->
        <tr>
            <td colspan="7" class="px-6 py-16 text-center text-slate-500 text-sm font-bold">
                📂 មិនទាន់មានទិន្នន័យលក់សម្រាប់ថ្ងៃនេះទេ!
            </td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </form>

    <!-- ========================================== -->
    <!-- 🔴 ផ្ទាំង MODAL ចំណាយពណ៌ស -->
    <!-- ========================================== -->
    <div id="expenseModal" class="fixed inset-0 bg-[#04060d]/80 backdrop-blur-sm z-50 hidden flex items-center justify-center overflow-y-auto py-10 px-4">
        <div class="bg-white w-full max-w-[420px] rounded-[24px] shadow-2xl modal-enter relative flex flex-col">

            <!-- ប៊ូតុងខ្វែងបិទ (Close) -->
            <button type="button" onclick="closeExpenseModal()" class="absolute top-5 right-5 w-8 h-8 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full flex items-center justify-center transition-all z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Header -->
            <div class="px-6 pt-6 pb-4 flex items-center gap-4 border-b border-transparent">
                <div class="w-12 h-12 bg-[#E11D48] rounded-2xl flex items-center justify-center text-white text-3xl font-light shadow-lg shadow-rose-500/30 shrink-0">+</div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-wide">គណនីចំណាយ</h2>
                    <p class="text-[11px] font-bold text-slate-400 mt-0.5">គ្រប់គ្រងការចំណាយប្រចាំថ្ងៃ</p>
                </div>
            </div>

            <!-- តម្រង ថ្ងៃ/ខែ/ឆ្នាំ -->
            <div class="px-6 pt-2">
                <div class="flex items-center justify-between gap-1 mb-4 bg-slate-50 p-1 rounded-xl">
                    <button type="button" onclick="filterExpense('daily')" class="flex-1 py-2 {{ ($expensePeriod ?? 'daily') == 'daily' ? 'text-indigo-600 bg-white shadow-sm border border-slate-200/60' : 'text-slate-400 border border-transparent hover:text-slate-600' }} rounded-lg text-[11px] font-bold transition-all">ប្រចាំថ្ងៃ</button>
                    <button type="button" onclick="filterExpense('monthly')" class="flex-1 py-2 {{ ($expensePeriod ?? '') == 'monthly' ? 'text-indigo-600 bg-white shadow-sm border border-slate-200/60' : 'text-slate-400 border border-transparent hover:text-slate-600' }} rounded-lg text-[11px] font-bold transition-all">ប្រចាំខែ</button>
                    <button type="button" onclick="filterExpense('yearly')" class="flex-1 py-2 {{ ($expensePeriod ?? '') == 'yearly' ? 'text-indigo-600 bg-white shadow-sm border border-slate-200/60' : 'text-slate-400 border border-transparent hover:text-slate-600' }} rounded-lg text-[11px] font-bold transition-all">ប្រចាំឆ្នាំ</button>
                    <button type="button" onclick="document.getElementById('modalDatePicker')._flatpickr.open()" class="flex-1 py-2 text-slate-400 border border-transparent hover:text-slate-600 rounded-lg text-[11px] font-bold transition-all">កំណត់ថ្ងៃ</button>
                </div>

                <div class="relative mb-4">
                    <input type="text" id="modalDatePicker" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-700 font-bold text-sm outline-none cursor-pointer" value="{{ $date ?? date('d/m/Y') }}" readonly>
                    <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </span>
                </div>
            </div>

            <!-- Tabs បញ្ចូល និង បញ្ជី -->
            <div class="flex px-6 border-b border-slate-200 gap-5">
                <button type="button" onclick="switchModalTab('form')" id="tab-form" class="pb-3 border-b-2 border-[#E11D48] text-[#E11D48] font-black text-xs transition-all">បញ្ចូលចំណាយថ្មី</button>
                <button type="button" onclick="switchModalTab('list')" id="tab-list" class="pb-3 border-b-2 border-transparent text-slate-400 hover:text-slate-600 font-bold text-xs flex items-center gap-2 transition-all">
                    បញ្ជីចំណាយ <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full text-[10px]">{{ count($expenses ?? []) }}</span>
                </button>
            </div>

            <!-- ផ្ទាំង Form បញ្ចូលទិន្នន័យ -->
            <div id="modal-content-form" class="bg-white p-6 rounded-b-[24px]">
                <form action="{{ route('expense.store') ?? '#' }}" method="POST" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-4">
                    @csrf

                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-black text-slate-500">1</div>
                        <h3 class="font-black text-slate-700 text-sm">ការចំណាយ</h3>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">បរិយាយ / មូលហេតុ <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="description" required placeholder="ឧ. ចាក់សាំង, ទិញសម្ភារៈ..." class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-[#E11D48] transition-all">
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">ចំនួនទឹកប្រាក់ <span class="text-[#E11D48]">*</span></label>
                        <div class="relative">
                            <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full pl-4 pr-20 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-[#E11D48] transition-all">
                            <span class="absolute right-4 top-2.5 text-slate-800 font-black text-xs bg-transparent">$ (USD)</span>
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">ឈ្មោះអ្នកស្នើប្រាក់</label>
                        <input type="text" name="requester_name" value="TSM" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-black text-slate-800 outline-none focus:border-[#E11D48] transition-all">
                    </div>

                    <!-- កាត់កងចំណាយ -->
                    <div class="bg-indigo-50/40 p-4 rounded-xl border border-indigo-100 mt-1">
                        <p class="text-[10px] font-bold text-indigo-700 mb-3">កាត់កងចំណាយទៅលើអ្នកគ្រប់គ្រងណាខ្លះ?</p>
                        <label class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-xl cursor-pointer mb-2">
                            <input type="checkbox" name="is_global" value="1" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                រួមទាំងអស់ (Global Expense)
                            </span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-xl cursor-pointer mb-2">
                            <input type="checkbox" name="specific_admin[]" value="TSM" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-bold text-slate-600">TSM</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-xl cursor-pointer">
                            <input type="checkbox" name="specific_admin[]" value="App Mall" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-bold text-slate-600">App Mall</span>
                        </label>
                    </div>

                    <button type="button" class="w-full mt-2 py-3 border-2 border-dashed border-slate-300 text-slate-500 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all">
                        + បន្ថែមចំណាយមួយទៀត
                    </button>

                    <button type="submit" class="w-full mt-2 py-3.5 bg-[#E11D48] hover:bg-rose-700 active:scale-[0.98] text-white font-black text-sm rounded-xl shadow-[0_8px_20px_rgba(225,29,72,0.3)] transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        រក្សាទុកការចំណាយ
                    </button>
                </form>
            </div>

           <!-- ផ្ទាំងបញ្ជីចំណាយ (លាក់ទុកសិន) -->
<div id="modal-content-list" class="bg-white p-6 rounded-b-[24px] hidden max-h-[400px] overflow-y-auto">
    <div class="space-y-3">
        @forelse($expenses ?? [] as $item)
        <div class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 transition-all border border-slate-100 rounded-2xl mb-3 group">
            <div>
                <h4 class="text-sm font-black text-slate-800">{{ $item->description ?? $item->title }}</h4>
                <span class="px-2 py-0.5 bg-slate-200 text-slate-600 text-[10px] font-bold rounded-md mt-1 inline-block">👤 {{ $item->requester_name ?? 'N/A' }}</span>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm font-black text-rose-500">- ${{ number_format($item->amount ?? 0, 2) }}</span>

                <!-- 🟢 ផ្នែកប៊ូតុង កែប្រែ និង លុប (វានឹងលេចឡើងរាងច្បាស់ពេលយក Mouse ទៅដាក់ពីលើ) -->
                <div class="flex items-center gap-1.5 pl-3 border-l border-slate-200 opacity-60 group-hover:opacity-100 transition-opacity">

                    <!-- ប៊ូតុងកែប្រែ -->
                    <a href="{{ route('expenses.edit', $item->id) ?? '#' }}" class="p-2 bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white rounded-lg transition-all" title="កែប្រែ">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>

                    <!-- ប៊ូតុងលុប -->
                    <form action="{{ route('expenses.destroy', $item->id) ?? '#' }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបចំណាយនេះមែនទេ? ទិន្នន័យមិនអាចទាញមកវិញបានទេ។');" class="m-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg transition-all" title="លុប">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>

                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-6 text-slate-400 text-xs font-bold">
            មិនទាន់មានទិន្នន័យចំណាយនៅឡើយទេ!
        </div>
        @endforelse
    </div>
</div>
        </div>
    </div>

    <!-- 🔴 JAVASCRIPT សរុប 🔴 -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // ១. មុខងារបិទ/បើកភ្នែកលាក់ទិន្នន័យលុយ
        let isDataHidden = false;
        function toggleDataVisibility() {
            isDataHidden = !isDataHidden;
            const sensitiveElements = document.querySelectorAll('.sensitive-data');
            const btnText = document.getElementById('visibilityText');
            const eyeOpen = document.getElementById('eyeIconOpen');
            const eyeClosed = document.getElementById('eyeIconClosed');

            if (isDataHidden) {
                sensitiveElements.forEach(el => { el.innerText = '*** $'; });
                btnText.innerText = 'បង្ហាញទិន្នន័យ';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                sensitiveElements.forEach(el => { el.innerText = el.getAttribute('data-value'); });
                btnText.innerText = 'លាក់ទិន្នន័យ';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }

        // ២. មុខងារបញ្ជា Filter ឲ្យ Submit ស្វ័យប្រវត្តិ
        function setFilter(name, value) {
            if(name === 'type') {
                document.getElementById('filterType').value = value;
            } else if (name === 'status') {
                document.getElementById('filterStatus').value = value;
            }
            document.getElementById('reportForm').submit();
        }

        // ៣. ប្រអប់រើសថ្ងៃខែ
        document.addEventListener("DOMContentLoaded", function() {
            if(document.getElementById("datePickerInput")) {
                flatpickr("#datePickerInput", {
                    mode: "single",
                    dateFormat: "Y-m-d",
                    defaultDate: "{{ $date ?? date('Y-m-d') }}",
                    theme: "dark",
                    onChange: function(selectedDates, dateStr, instance) {
                        document.getElementById('reportForm').submit();
                    }
                });
            }
        });

        // ៤. មុខងារបញ្ជា Modal ចំណាយ
        function openExpenseModal() {
            const modal = document.getElementById('expenseModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeExpenseModal() {
            const modal = document.getElementById('expenseModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function switchModalTab(tabName) {
            const tabForm = document.getElementById('tab-form');
            const tabList = document.getElementById('tab-list');
            const contentForm = document.getElementById('modal-content-form');
            const contentList = document.getElementById('modal-content-list');

            if (tabName === 'form') {
                tabForm.classList.add('border-[#E11D48]', 'text-[#E11D48]');
                tabForm.classList.remove('border-transparent', 'text-slate-400');
                tabList.classList.add('border-transparent', 'text-slate-400');
                tabList.classList.remove('border-[#E11D48]', 'text-[#E11D48]');
                contentForm.classList.remove('hidden');
                contentList.classList.add('hidden');
            } else {
                tabList.classList.add('border-[#E11D48]', 'text-[#E11D48]');
                tabList.classList.remove('border-transparent', 'text-slate-400');
                tabForm.classList.add('border-transparent', 'text-slate-400');
                tabForm.classList.remove('border-[#E11D48]', 'text-[#E11D48]');
                contentList.classList.remove('hidden');
                contentForm.classList.add('hidden');
            }
        }

        // ដាក់ប្រតិទិនក្នុង Modal ចំណាយ
        document.addEventListener("DOMContentLoaded", function() {
            if(document.getElementById("modalDatePicker")) {
                flatpickr("#modalDatePicker", {
                    mode: "single",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    defaultDate: "{{ $date ?? date('Y-m-d') }}",
                    onChange: function(selectedDates, dateStr, instance) {
                        let currentUrl = new URL(window.location.href);
                        currentUrl.searchParams.set('date', dateStr);
                        currentUrl.searchParams.set('expense_period', 'daily');
                        currentUrl.hash = 'expense-list';
                        window.location.href = currentUrl.toString();
                    }
                });
            }
        });

        function filterExpense(period) {
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('expense_period', period);
            currentUrl.hash = 'expense-list';
            window.location.href = currentUrl.toString();
        }

        document.addEventListener("DOMContentLoaded", function() {
            if (window.location.hash === '#expense-list') {
                openExpenseModal();
                switchModalTab('list');
                history.replaceState(null, null, window.location.pathname + window.location.search);
            }
        });
    </script>
</body>
</html>
