<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ស្តុកបច្ចុប្បន្នជាក់ស្តែង - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gradient-to-br from-[#070b19] via-[#0B132B] to-[#070b19] text-slate-300 m-0 p-0 antialiased min-h-screen">

    <div class="max-w-7xl mx-auto p-4 md:p-8 space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-[#15234b]/80 backdrop-blur-xl p-5 md:p-6 rounded-3xl border border-[#1C2C4E] shadow-xl">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') ?? '#' }}" class="w-12 h-12 rounded-2xl bg-[#0B132B] hover:bg-slate-800 border border-[#1C2C4E] flex items-center justify-center text-slate-400 font-bold transition-all shadow-sm">
                    ⬅
                </a>
                <div>
                    <h1 class="text-xl md:text-2xl font-extrabold text-white flex items-center gap-2">
                        📊 ស្តុកបច្ចុប្បន្នជាក់ស្តែង
                    </h1>
                    <p class="text-[11px] md:text-sm font-semibold text-indigo-400/70 mt-1 uppercase tracking-wider">តាមដានស្ថានភាពទំនិញក្នុងឃ្លាំង (ACTUAL CURRENT STOCK)</p>
                </div>
            </div>

            <!-- ប្រអប់ Search -->
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">🔍</span>
                <input type="text" id="searchInput" placeholder="ស្វែងរកឈ្មោះ ឬ SKU..."
                       class="w-full pl-11 pr-4 py-3 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-sm font-medium text-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 transition-all shadow-inner placeholder-slate-500">
            </div>
        </div>

        <!-- កាតសង្ខេប (Status Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- ស្តុកសរុបទាំងអស់ -->
            <div class="bg-gradient-to-br from-indigo-500/10 to-blue-500/10 border border-indigo-500/20 p-6 rounded-3xl flex items-center gap-5 shadow-lg shadow-indigo-500/5 hover:-translate-y-1 transition-transform">
                <div class="w-14 h-14 rounded-full bg-indigo-500/20 flex items-center justify-center text-2xl">📦</div>
                <div>
                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-1">ចំនួនទំនិញសរុប</p>
                    <h3 class="text-3xl font-black text-white">{{ number_format($totalQty ?? 0) }} <span class="text-sm font-medium text-slate-400">ឯកតា</span></h3>
                </div>
            </div>

            <!-- ស្តុកជិតអស់ -->
            <div class="bg-gradient-to-br from-amber-500/10 to-orange-500/10 border border-amber-500/20 p-6 rounded-3xl flex items-center gap-5 shadow-lg shadow-amber-500/5 hover:-translate-y-1 transition-transform">
                <div class="w-14 h-14 rounded-full bg-amber-500/20 flex items-center justify-center text-2xl animate-pulse">⚠️</div>
                <div>
                    <p class="text-xs font-bold text-amber-400 uppercase tracking-wider mb-1">ទំនិញស្តុកជិតអស់</p>
                    <h3 class="text-3xl font-black text-white">{{ $lowStock ?? 0 }} <span class="text-sm font-medium text-slate-400">មុខ</span></h3>
                </div>
            </div>

            <!-- អស់ស្តុក -->
            <div class="bg-gradient-to-br from-rose-500/10 to-red-500/10 border border-rose-500/20 p-6 rounded-3xl flex items-center gap-5 shadow-lg shadow-rose-500/5 hover:-translate-y-1 transition-transform">
                <div class="w-14 h-14 rounded-full bg-rose-500/20 flex items-center justify-center text-2xl">🚫</div>
                <div>
                    <p class="text-xs font-bold text-rose-400 uppercase tracking-wider mb-1">ទំនិញអស់ស្តុក</p>
                    <h3 class="text-3xl font-black text-rose-500">{{ $outOfStock ?? 0 }} <span class="text-sm font-medium text-slate-400">មុខ</span></h3>
                </div>
            </div>
        </div>

        <!-- តារាងទិន្នន័យ (Table) -->
        <div class="bg-[#15234b]/50 backdrop-blur-md rounded-3xl border border-[#1C2C4E] overflow-hidden shadow-2xl mt-6">
            <div class="overflow-x-auto hide-scroll">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#0B132B] border-b border-[#1C2C4E]">
                        <tr>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px] w-16">រូបភាព</th>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px]">ព័ត៌មានទំនិញ</th>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px]">ប្រភេទ</th>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px] text-right">តម្លៃទិញ</th>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px] text-right">តម្លៃលក់</th>
                            <th class="px-6 py-5 font-bold text-white uppercase tracking-wider text-[11px] text-center bg-indigo-500/10">ចំនួនកេស</th>
                            <th class="px-6 py-5 font-bold text-white uppercase tracking-wider text-[11px] text-center bg-indigo-500/10">ចំនួនរាយ</th>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px] text-center">ស្ថានភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1C2C4E]/50" id="tableBody">
                        @forelse($products as $item)
                        <tr class="hover:bg-[#1C2C4E]/30 transition-colors product-row" data-search="{{ strtolower($item->product_name . ' ' . $item->sku) }}">

                            <!-- រូបភាព -->
                            <td class="px-6 py-3">
                                <div class="w-12 h-12 rounded-xl bg-[#0B132B] border border-[#1C2C4E] overflow-hidden flex items-center justify-center p-1">
                                    @if($item->image)
                                        <img src="{{ asset($item->image) }}" class="w-full h-full object-contain mix-blend-screen" alt="img">
                                    @else
                                        <span class="text-xl opacity-50">📦</span>
                                    @endif
                                </div>
                            </td>

                            <!-- ព័ត៌មានទំនិញ -->
                            <td class="px-6 py-3">
                                <p class="font-bold text-slate-200 line-clamp-1">{{ $item->product_name }}</p>
                                <p class="text-[10px] font-mono text-slate-500 mt-0.5">SKU: {{ $item->sku }}</p>
                            </td>

                            <!-- ប្រភេទ -->
                            <td class="px-6 py-3">
                                <span class="bg-[#0B132B] border border-[#1C2C4E] px-3 py-1 rounded-md text-[10px] font-bold text-slate-400">
                                    {{ $item->category ?? 'ទូទៅ' }}
                                </span>
                            </td>

                            <!-- តម្លៃទិញ -->
                            <td class="px-6 py-3 text-right font-mono text-slate-400">
                                ${{ number_format($item->cost_price, 2) }}
                            </td>

                            <!-- តម្លៃលក់ -->
                            <td class="px-6 py-3 text-right font-mono font-bold text-indigo-400">
                                ${{ number_format($item->sale_price, 2) }}
                            </td>

                            @php
                                $cartonSize = isset($item->carton_size) && $item->carton_size > 0 ? $item->carton_size : 1;
                                $totalQty = $item->qty;
                                $cartons = floor($totalQty / $cartonSize);
                                $pieces = $totalQty % $cartonSize;
                            @endphp

                            <!-- ចំនួនកេស -->
                            <td class="px-6 py-3 text-center bg-indigo-500/5">
                                <span class="font-bold text-emerald-400 text-sm">{{ $cartons }} កេស</span>
                                <div class="text-[9px] text-slate-500">({{ $cartonSize }} ឯកតា/កេស)</div>
                            </td>

                            <!-- ចំនួនរាយ -->
                            <td class="px-6 py-3 text-center bg-indigo-500/5">
                                <span class="font-bold text-amber-400 text-sm">{{ $pieces }} រាយ</span>
                                <div class="text-[9px] text-slate-500">សរុបរួម: {{ $totalQty }}</div>
                            </td>

                            <!-- ស្ថានភាព -->
                            <td class="px-6 py-3 text-center">
                                @if($item->qty <= 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-500 text-[10px] font-black tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> អស់ស្តុក
                                    </span>
                                @elseif($item->qty <= 10)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[10px] font-black tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> ជិតអស់
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> គ្រប់គ្រាន់
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="8" class="px-6 py-16 text-center">
                                <span class="text-5xl block mb-4 opacity-50">📭</span>
                                <p class="text-slate-400 font-bold">មិនទាន់មានទិន្នន័យទំនិញនៅឡើយទេ!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- កូដសម្រាប់ Search -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.product-row');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                rows.forEach(row => {
                    const searchData = row.getAttribute('data-search');
                    if (searchData.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
