<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ស្តុកបានលក់ចេញ - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />

    <!-- បន្ថែម Library សម្រាប់ប្រតិទិន (Flatpickr) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- ប្រើ Theme ពណ៌ងងឹតសម្រាប់ប្រតិទិន -->
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        /* កែទម្រង់ប្រតិទិនឱ្យស៊ីពណ៌ជាមួយប្រព័ន្ធយើង */
        .flatpickr-calendar.dark {
            background: #0B132B;
            border: 1px solid #1C2C4E;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#070b19] via-[#0B132B] to-[#070b19] text-slate-300 m-0 p-0 antialiased min-h-screen">

    <div class="max-w-7xl mx-auto p-4 md:p-8 space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-[#15234b]/80 backdrop-blur-xl p-5 md:p-6 rounded-3xl border border-[#1C2C4E] shadow-xl relative z-20">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') ?? '#' }}" class="w-12 h-12 rounded-2xl bg-[#0B132B] hover:bg-slate-800 border border-[#1C2C4E] flex items-center justify-center text-slate-400 font-bold transition-all shadow-sm">
                    ⬅
                </a>
                <div>
                    <h1 class="text-xl md:text-2xl font-extrabold text-white flex items-center gap-2">
                        💸 របាយការណ៍ស្តុកបានលក់ចេញ
                    </h1>
                    <p class="text-[11px] md:text-sm font-semibold text-cyan-400/70 mt-1 uppercase tracking-wider">ប្រវត្តិទំនិញដែលបានបញ្ចេញលក់ (SOLD ITEMS)</p>
                </div>
            </div>

            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-3">

                <!-- ប្រអប់ស្វែងរក -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកវិក្កយបត្រ ឬឈ្មោះ..."
                           class="bg-[#0D182E] border border-slate-700 text-slate-200 text-sm rounded-lg outline-none focus:border-emerald-500 block w-full pl-10 p-2.5">
                </div>
                <!-- 🟢 ផ្នែកប៊ូតុង រើស ប្រចាំថ្ងៃ/ខែ/ឆ្នាំ 🟢 -->
<div class="flex items-center gap-1 bg-[#0D182E] border border-slate-700 p-1 rounded-lg mr-3">
    <button type="button" onclick="filterPeriod('daily')" class="px-4 py-1.5 text-xs font-bold rounded-md transition-all {{ ($period ?? 'daily') == 'daily' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
        ប្រចាំថ្ងៃ
    </button>
    <button type="button" onclick="filterPeriod('monthly')" class="px-4 py-1.5 text-xs font-bold rounded-md transition-all {{ ($period ?? '') == 'monthly' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
        ប្រចាំខែ
    </button>
    <button type="button" onclick="filterPeriod('yearly')" class="px-4 py-1.5 text-xs font-bold rounded-md transition-all {{ ($period ?? '') == 'yearly' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
        ប្រចាំឆ្នាំ
    </button>
</div>

               <!-- 🟢 ត្រូវមាន Form នេះដើម្បីបញ្ជូនថ្ងៃខែថ្មីទៅ Controller 🟢 -->
<form action="{{ url()->current() }}" method="GET" id="filterForm">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>

        <!-- ប្រអប់ Input របស់បង -->
        <input type="text" name="date" id="datePicker" value="{{ request('date', date('Y-m-d')) }}" autocomplete="off" placeholder="ជ្រើសរើសថ្ងៃខែ..."
               class="bg-[#0D182E] border border-slate-700 text-slate-200 text-sm rounded-lg outline-none focus:border-emerald-500 block w-full pl-10 p-2.5 cursor-pointer">
    </div>
</form>
                {{-- <!-- ប៊ូតុងដើម្បីជម្រះថ្ងៃខែ (Clear Filter) - ងាយស្រួលមើលទិន្នន័យទាំងអស់វិញ -->
                @if(request('date') || request('search'))
                    <a href="{{ url()->current() }}" class="text-xs bg-rose-500/10 text-rose-400 border border-rose-500/30 px-3 py-2.5 rounded-lg hover:bg-rose-500 hover:text-white transition-all">ជម្រះ</a>
                @endif --}}

                <button type="submit" class="hidden">Search</button>
            </form>
        </div>

        @php
            $realTotalQty = 0;
            $realTotalAmount = 0;

            // បូកទិន្នន័យទំនិញទាំងអស់ដែលទាញបានមកបង្ហាញក្នុងតារាង
            if(isset($soldItems) && count($soldItems) > 0) {
                foreach($soldItems as $item) {
                    $realTotalQty += abs((float)($item->qty ?? 0));
                    $realTotalAmount += (float)($item->total ?? 0);
                }
            }
        @endphp

        <!-- កាតបង្ហាញសរុប -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-[#0D182E] border border-slate-700/50 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 text-xl border border-emerald-500/30">
                    📦
                </div>
                <div>
                    <p class="text-[11px] font-bold text-emerald-400/80 mb-1">ចំនួនទំនិញលក់ចេញសរុប</p>
                    <h3 class="text-2xl font-black text-white flex items-baseline gap-2">
                        {{ number_format($realTotalQty) }} <span class="text-sm font-normal text-slate-400">ឯកតា</span>
                    </h3>
                </div>
            </div>

            <div class="bg-[#0D182E] border border-slate-700/50 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center text-cyan-400 text-xl border border-cyan-500/30">
                    💵
                </div>
                <div>
                    <p class="text-[11px] font-bold text-cyan-400/80 mb-1">ចំណូលពីការលក់សរុប</p>
                    <h3 class="text-2xl font-black text-cyan-400 tracking-tight">
                        ${{ number_format($realTotalAmount, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- តារាងទិន្នន័យ (Table) -->
        <div class="bg-[#15234b]/50 backdrop-blur-md rounded-3xl border border-[#1C2C4E] overflow-hidden shadow-2xl mt-6 relative z-10">
            <div class="overflow-x-auto hide-scroll">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#0D182E] border-b border-slate-700/50">
                        <tr>
                            <th class="p-4 text-left text-[11px] font-black text-slate-400 uppercase">លេខវិក្កយបត្រ</th>
                            <th class="p-4 text-left text-[11px] font-black text-slate-400 uppercase">ថ្ងៃខែឆ្នាំ</th>
                            <th class="p-4 text-left text-[11px] font-black text-slate-400 uppercase">ទំនិញ (ITEM)</th>
                            <th class="p-4 text-center text-[11px] font-black text-slate-400 uppercase">ចំនួន (QTY)</th>
                            <th class="p-4 text-right text-[11px] font-black text-slate-400 uppercase">តម្លៃសរុប</th>
                            <th class="p-4 text-center text-[11px] font-black text-slate-400 uppercase">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($soldItems as $item)
                            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30 transition-colors">
                                <td class="p-4 text-sm text-slate-300">#{{ $item->order->invoice_no ?? 'N/A' }}</td>
                                <td class="p-4 text-sm text-slate-300">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y h:i A') }}</td>
                                <td class="p-4 text-sm text-slate-300 font-bold">{{ $item->product->product_name ?? $item->product->name ?? 'ទំនិញទូទៅ' }}</td>
                                <td class="p-4 text-sm text-slate-300 text-center">{{ $item->qty }}</td>
                                <td class="p-4 text-sm font-black text-cyan-400 text-right">${{ number_format($item->total, 2) }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ url('stock-sold/edit/' . $item->id) }}"
                                           class="p-2 bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500 hover:text-white rounded-lg transition-all" title="កែប្រែ">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <form action="{{ url('stock-sold/delete/' . $item->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('តើអ្នកពិតជាចង់លុបទិន្នន័យនេះមែនទេ? ការលុបនេះមិនអាចទាញមកវិញបានទេ។');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-all" title="លុបចោល">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500 font-bold">
                                    មិនមានទិន្នន័យលក់ចេញទេសម្រាប់កាលបរិច្ឆេទនេះ!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script សម្រាប់បញ្ជាប្រតិទិន -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // 🟢 ចាប់យកថ្ងៃខែពី URL បើអត់មានទេ គឺយកថ្ងៃបច្ចុប្បន្ន (date('Y-m-d')) ជាលំនាំដើម 🟢
    let selectedDate = "{{ request('date', date('Y-m-d')) }}";

    flatpickr("#datePicker", {
        mode: "single",
        dateFormat: "Y-m-d",
        defaultDate: selectedDate, // 🟢 ប្រាប់ Flatpickr ឱ្យយកថ្ងៃបច្ចុប្បន្ន ឬថ្ងៃចាស់មកបង្ហាញ 🟢

        onClose: function(selectedDates, dateStr, instance) {
            // 🟢 បញ្ជាឱ្យ Submit Form តែនៅពេលដែលថ្ងៃខែត្រូវបានផ្លាស់ប្ដូរថ្មី 🟢
            let previousDate = "{{ request('date') }}";
            if (dateStr !== "" && dateStr !== previousDate) {
                instance.element.closest('form').submit();
            }
        }
    });
});

function filterPeriod(periodType) {
        // ចាប់យក URL បច្ចុប្បន្ន
        let currentUrl = new URL(window.location.href);

        // បន្ថែមឬដូរតម្លៃ period ក្នុង URL
        currentUrl.searchParams.set('period', periodType);

        // Refresh វេបសាយជាមួយ URL ថ្មី
        window.location.href = currentUrl.toString();
    }
    </script>
</body>
</html>
