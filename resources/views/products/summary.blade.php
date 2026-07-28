<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ស្តុកក្នុងឃ្លាំងសរុប - STOCK.PRO</title>
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
                        🎁 ស្តុកក្នុងឃ្លាំងសរុប (INVENTORY SUMMARY)
                    </h1>
                    <p class="text-[11px] md:text-sm font-semibold text-teal-400/70 mt-1 uppercase tracking-wider">របាយការណ៍ទិន្នន័យទំនិញក្នុងប្រព័ន្ធ</p>
                </div>
            </div>

            <!-- ប្រអប់ Search -->
            <div class="relative w-full md:w-[300px]">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">🔍</span>
                <input type="text" id="searchInput" placeholder="ស្វែងរកឈ្មោះ ឬ SKU..."
                       class="w-full pl-11 pr-4 py-3 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-sm font-medium text-white focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/30 transition-all shadow-inner">
            </div>
        </div>

        <!-- កាតសង្ខេបទិន្នន័យ (Summary Cards ដូចក្នុងរូបភាព) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-gradient-to-br from-indigo-500/10 to-purple-500/10 border border-indigo-500/20 p-6 rounded-3xl flex items-center gap-5 shadow-lg">
                <div class="w-14 h-14 rounded-full bg-indigo-500/20 flex items-center justify-center text-2xl">📓</div>
                <div>
                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1">មុខទំនិញសរុប (SKUS)</p>
                    <h3 class="text-3xl font-black text-white">{{ isset($products) ? $products->count() : 0 }} <span class="text-sm font-medium text-slate-400">មុខ</span></h3>
                </div>
            </div>

            <div class="bg-gradient-to-br from-teal-500/10 to-emerald-500/10 border border-teal-500/20 p-6 rounded-3xl flex items-center gap-5 shadow-lg">
                <div class="w-14 h-14 rounded-full bg-teal-500/20 flex items-center justify-center text-2xl">📦</div>
                <div>
                    <p class="text-[10px] font-bold text-teal-400 uppercase tracking-wider mb-1">ឯកតាស្តុកសរុប</p>
                    <h3 class="text-3xl font-black text-white">{{ isset($products) ? number_format($products->sum('qty')) : 0 }} <span class="text-sm font-medium text-slate-400">ឯកតា</span></h3>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-500/10 to-orange-500/10 border border-amber-500/20 p-6 rounded-3xl flex items-center gap-5 shadow-lg">
                <div class="w-14 h-14 rounded-full bg-amber-500/20 flex items-center justify-center text-2xl">💰</div>
                <div>
                    <p class="text-[10px] font-bold text-amber-400 uppercase tracking-wider mb-1">តម្លៃទុនសរុបក្នុងឃ្លាំង</p>
                    <h3 class="text-3xl font-black text-amber-400">
                        @php
                            $totalValue = 0;
                            if(isset($products)){
                                foreach($products as $p) {
                                    $totalValue += ($p->cost_price * $p->qty);
                                }
                            }
                        @endphp
                        ${{ number_format($totalValue, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- តារាងទិន្នន័យ (Table) ដែលបានបន្ថែម Action ថ្មី -->
        <div class="bg-[#15234b]/50 backdrop-blur-md rounded-3xl border border-[#1C2C4E] overflow-hidden shadow-2xl mt-6">
            <div class="overflow-x-auto hide-scroll">

                @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center justify-between">
            <span class="font-bold">{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-emerald-400 hover:text-emerald-300">✖</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl flex items-center justify-between">
            <span class="font-bold">{{ session('error') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-rose-400 hover:text-rose-300">✖</button>
        </div>
    @endif
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#0B132B] border-b border-[#1C2C4E]">
                        <tr>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px] w-16">រូបភាព</th>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px]">ព័ត៌មានទំនិញ</th>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px]">ប្រភេទ</th>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px] text-center">តម្លៃទិញ</th>
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px] text-center">តម្លៃលក់</th>
                            <th class="px-6 py-5 font-bold text-teal-400 uppercase tracking-wider text-[11px] text-center bg-teal-500/10">ស្តុកបច្ចុប្បន្ន</th>
                            <th class="px-6 py-5 font-bold text-amber-400 uppercase tracking-wider text-[11px] text-right">តម្លៃទុនសរុប</th>

                            <!-- 🎯 នេះហើយ! ជួរឈរថ្មីសម្រាប់ "សកម្មភាព (Edit/Delete)" -->
                            <th class="px-6 py-5 font-bold text-slate-400 uppercase tracking-wider text-[11px] text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1C2C4E]/50" id="tableBody">
                        @if(isset($products) && $products->count() > 0)
                            @foreach($products as $item)
                            <tr class="hover:bg-[#1C2C4E]/30 transition-colors product-row" data-search="{{ strtolower($item->product_name . ' ' . $item->sku) }}">

                                <td class="px-6 py-3">
                                    <div class="w-12 h-12 rounded-xl bg-[#0B132B] border border-[#1C2C4E] overflow-hidden flex items-center justify-center p-1">
                                        @if($item->image)
                                        <img src="{{ $item->image ? asset($item->image) : 'https://ui-avatars.com/api/?name=NI&background=E2E8F0&color=64748B&size=150' }}"
     onerror="this.src='https://ui-avatars.com/api/?name=NI&background=E2E8F0&color=64748B&size=150'"
     alt="Product Image"
     class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl opacity-50">📦</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-3">
                                    <p class="font-bold text-slate-200 line-clamp-1">{{ $item->product_name }}</p>
                                    <p class="text-[10px] font-mono text-slate-500 mt-0.5">SKU: {{ $item->product_code }}</p>
                                </td>

                                <td class="px-6 py-3">
                                    <span class="bg-[#0B132B] border border-[#1C2C4E] px-3 py-1 rounded-md text-[10px] font-bold text-slate-400">
                                        {{ $item->category ?? 'ទូទៅ' }}
                                    </span>
                                </td>

                                <td class="px-6 py-3 text-center font-mono text-slate-400">
                                    ${{ number_format($item->cost_price, 2) }}
                                </td>

                                <td class="px-6 py-3 text-center font-mono text-indigo-400 font-bold">
                                    ${{ number_format($item->sale_price, 2) }}
                                </td>

                                <td class="px-6 py-3 text-center bg-teal-500/5">
                                    <span class="font-black text-lg text-teal-400">
                                        {{ $item->qty }}
                                    </span>
                                </td>

                                <td class="px-6 py-3 text-right font-mono font-bold text-amber-400">
                                    ${{ number_format($item->cost_price * $item->qty, 2) }}
                                </td>

                                <!-- 🎯 កន្លែងបញ្ជាប៊ូតុង Update និង Delete យ៉ាងប្រណីត -->
                                <td class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- ប៊ូតុង Update (Edit) ពណ៌ខៀវ -->
                                        <a href="{{ url('products/'.$item->id.'/edit') }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="កែប្រែទិន្នន័យ">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <!-- ប៊ូតុង Delete ពណ៌ក្រហម -->
                                        <form action="{{ url('/products/' . ($item->id ?? $item->product_id)) }}" method="POST" class="inline-block" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបទំនិញនេះមែនទេ?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition-colors border border-rose-500/20">
            🗑️
        </button>
    </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr id="emptyRow">
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <span class="text-5xl block mb-4 opacity-50">📭</span>
                                    <p class="text-slate-400 font-bold">មិនទាន់មានទិន្នន័យទំនិញនៅឡើយទេ!</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script សម្រាប់មុខងារ Search ដើរធម្មតា -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.product-row');

            if(searchInput) {
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
            }
        });
    </script>
</body>
</html>
