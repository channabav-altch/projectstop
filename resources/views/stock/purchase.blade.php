<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ស្តុកថ្មី & ទិញចូល - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        .modal-enter { animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes modalFadeIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    </style>
</head>
<body class="bg-gradient-to-br from-[#070b19] via-[#0B132B] to-[#070b19] text-slate-300 min-h-screen p-4 md:p-8">

    <div class="max-w-[1600px] mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-wrap md:flex-nowrap justify-between items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ url('/dashboard') }}" class="flex items-center justify-center w-12 h-12 bg-[#15234b] hover:bg-[#1C2C4E] text-slate-300 rounded-2xl border border-[#1C2C4E] shadow-lg transition-all">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
</a>
                <div class="flex items-center text-sm font-bold text-slate-500 bg-[#15234b]/40 px-6 py-3.5 rounded-2xl border border-[#1C2C4E]">
                    <span class="text-slate-300">ស្តុក</span>
                    <span class="mx-3 text-slate-600">/</span>
                    <span class="text-blue-400">ស្តុកថ្មី & ទិញចូល</span>
                </div>
            </div>

            <button onclick="openPurchaseModal()" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-2xl text-sm font-bold shadow-[0_5px_15px_rgba(79,70,229,0.4)] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                + បន្ថែមទិញចូលថ្មី
            </button>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-[#15234b]/80 backdrop-blur-md p-6 rounded-3xl border border-[#1C2C4E] shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-all"><span class="text-6xl">💸</span></div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">ចំណាយទិញចូលសរុប</p>
                <h2 class="text-3xl font-black text-white mt-2">${{ number_format($totalAmount ?? 0, 2) }}</h2>
                <p class="text-xs text-emerald-400 font-bold mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> ខែនេះ
                </p>
            </div>

            <div class="bg-[#15234b]/80 backdrop-blur-md p-6 rounded-3xl border border-[#1C2C4E] shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-all"><span class="text-6xl">📦</span></div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">បរិមាណទំនិញចូលស្តុក</p>
                <h2 class="text-3xl font-black text-white mt-2">{{ $totalQty ?? 0 }} <span class="text-sm font-normal text-slate-400">ឯកតា</span></h2>
            </div>

            <div class="bg-[#15234b]/80 backdrop-blur-md p-6 rounded-3xl border border-[#1C2C4E] shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-all"><span class="text-6xl">🧾</span></div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">ប្រតិបត្តិការទិញ</p>
                <h2 class="text-3xl font-black text-white mt-2">{{ isset($purchases) ? $purchases->count() : 0 }} <span class="text-sm text-slate-500 font-bold">ដង</span></h2>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 p-6 rounded-3xl border border-[#1C2C4E] shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-20"><span class="text-6xl">🚚</span></div>
                <p class="text-[11px] font-bold text-indigo-200 uppercase tracking-wider">អ្នកផ្គត់ផ្គង់ (Suppliers)</p>
                <h2 class="text-3xl font-black text-white mt-2">N/A <span class="text-sm text-indigo-300 font-bold">ក្រុមហ៊ុន</span></h2>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-[#15234b]/60 backdrop-blur-xl rounded-2xl p-4 flex flex-col xl:flex-row items-center justify-between border border-[#1C2C4E] shadow-lg gap-4 z-10 relative">
            <div class="relative w-full xl:w-[400px]">
                <form action="{{ url('/stock-purchase') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកវិក្កយបត្រ ឬអ្នកផ្គត់ផ្គង់..." class="w-full pl-10 pr-4 py-2.5 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-white font-bold text-sm outline-none focus:border-indigo-500 transition-all">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                </form>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto justify-end">
                <form action="{{ url('/stock-purchase') }}" method="GET" class="flex items-center gap-4">
                    <div>
                        <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="px-4 py-2 border border-[#1C2C4E] bg-[#0B132B] text-white rounded-lg cursor-pointer outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-[#1C2C4E] bg-[#0B132B] text-white rounded-lg cursor-pointer outline-none focus:border-indigo-500">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>ទាំងអស់</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>ទូទាត់រួច</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>ជំពាក់</option>
                        </select>
                    </div>
                    <button type="submit" class="hidden"></button>
                </form>
            </div>
        </div>

        <!-- Table Data -->
        <div class="bg-[#15234b]/50 backdrop-blur-md rounded-2xl border border-[#1C2C4E] overflow-hidden shadow-2xl block transition-all duration-300">
            <div class="overflow-x-auto hide-scroll">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-[#0B132B] border-b border-[#1C2C4E] text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-6 py-5">ថ្ងៃខែទិញ</th>
                            <th class="px-6 py-5">លេខវិក្កយបត្រ</th>
                            <th class="px-6 py-5">អ្នកផ្គត់ផ្គង់ / មុខទំនិញ</th>
                            <th class="px-6 py-5 text-center">បរិមាណ</th>
                            <th class="px-6 py-5 text-right">សរុបទឹកប្រាក់</th>
                            <th class="px-6 py-5 text-center">ស្ថានភាព</th>
                            <th class="px-4 py-3 text-right">សកម្មភាព (Action)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/10">
                        @if(isset($purchases) && $purchases->count() > 0)
                            @foreach($purchases as $item)
                            <tr class="hover:bg-slate-800/50 transition-colors">
                                <td class="py-4 px-6 text-sm text-slate-300">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d h:i A') }}
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-300">
                                    #{{ $item->invoice_no ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-6 font-bold text-white">
                                    {{ $item->product_name ?? 'មិនស្គាល់ទំនិញ' }}
                                    <div class="text-xs text-slate-400 font-normal mt-1">{{ $item->supplier ?? 'គ្មានឈ្មោះអ្នកផ្គត់ផ្គង់' }}</div>
                                </td>
                                <td class="py-4 px-6 text-sm font-black text-indigo-400 text-center">
                                    + {{ $item->qty }}
                                </td>
                                <td class="py-4 px-6 text-sm font-black text-rose-400 text-right">
                                    ${{ number_format(($item->unit_price ?? 0) * ($item->qty ?? 0), 2) }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="bg-indigo-500/20 text-indigo-400 px-3 py-1 rounded-full text-[11px] font-bold border border-indigo-500/20">ចូលស្តុក</span>
                                </td>
                                <td class="px-4 py-3 text-right flex items-center justify-end gap-2">
                                    <a href="{{ url('stock-purchase/' . $item->id . '/edit') }}" class="px-3 py-1 bg-indigo-500/20 text-[#5642F5] hover:bg-[#5642F5] hover:text-white rounded-lg text-xs font-bold transition-all border border-indigo-300">កែ</a>
                                    <form action="{{ url('stock-purchase/' . $item->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបទិន្នន័យទិញចូលនេះ និងកាត់ស្តុកវិញមែនទេ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-rose-500/20 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg text-xs font-bold transition-all border border-rose-500/50">លុប</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="py-20 text-center">
                                    <span class="text-4xl block mb-4 opacity-50">📭</span>
                                    <p class="text-slate-400 font-bold">មិនទាន់មានទិន្នន័យទិញចូលនៅឡើយទេ!</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal បញ្ចូលទិញចូលថ្មី -->
    <div id="purchaseModal" class="fixed inset-0 bg-[#04060d]/80 backdrop-blur-sm z-50 hidden flex items-center justify-center overflow-y-auto py-10 px-4">
        <div class="bg-white w-full max-w-[600px] rounded-[24px] shadow-2xl modal-enter relative flex flex-col overflow-hidden">

            <button type="button" onclick="closePurchaseModal()" class="absolute top-5 right-5 w-8 h-8 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full flex items-center justify-center transition-all z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="px-8 pt-8 pb-6 flex items-center gap-4 bg-slate-50 border-b border-slate-100">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-wide">បញ្ចូលស្តុកថ្មី</h2>
                    <p class="text-xs font-bold text-slate-400 mt-0.5">ទម្រង់កត់ត្រាការទិញទំនិញចូល</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ url('/stock-purchase') }}" method="POST">
                @csrf
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5">ថ្ងៃខែទិញចូល <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" id="modalDate" name="purchase_date" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-indigo-600 transition-all cursor-pointer">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">📅</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1.5">លេខវិក្កយបត្រ (Invoice)</label>
                            <input type="text" name="sku" placeholder="INV-..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-indigo-600 transition-all">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">អ្នកផ្គត់ផ្គង់ (Supplier) <span class="text-red-500">*</span></label>
                        <input type="text" name="supplier" required placeholder="ឈ្មោះក្រុមហ៊ុន ឬអ្នកលក់..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-indigo-600 transition-all">
                    </div>

                    <div class="bg-indigo-50/50 p-5 rounded-2xl border border-indigo-100">

                        <!-- 🟢 ប្រើ $products ?? [] ដើម្បីការពារ Error ១០០% 🟢 -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-indigo-700 mb-1.5">មុខទំនិញ / ផលិតផល <span class="text-red-500">*</span></label>
                            <select name="product_id" required class="w-full px-4 py-2.5 ...">
    <option value="">-- ជ្រើសរើសមុខទំនិញ --</option>

    <!-- 🟢 ត្រូវប្រាកដថាហៅឈ្មោះ $product->product_name -->
    @foreach($products ?? [] as $product)
        <option value="{{ $product->id }}">{{ $product->product_name }} ({{ $product->product_code }})</option>
    @endforeach
</select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-indigo-700 mb-1.5">បរិមាណ (Qty) <span class="text-red-500">*</span></label>
                                <input type="number" id="qtyInput" name="quantity" required min="1" placeholder="0" class="w-full px-4 py-2.5 bg-white border border-indigo-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-indigo-600 transition-all text-center" oninput="calculateTotal()">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-indigo-700 mb-1.5">តម្លៃដើមសរុប (Total Cost) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold">$</span>
                                    <input type="number" id="priceInput" name="total_price" required min="0" step="0.01" placeholder="0.00" class="w-full pl-8 pr-4 py-2.5 bg-white border border-indigo-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-indigo-600 transition-all" oninput="calculateTotal()">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-indigo-100 flex justify-between items-center">
                            <span class="text-sm font-bold text-indigo-800">សរុបទឹកប្រាក់ (Total):</span>
                            <span id="totalDisplay" class="text-2xl font-black text-rose-600">$0.00</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 px-8 py-5 bg-slate-50 border-t border-slate-100">
                    <button type="button" onclick="closePurchaseModal()" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 rounded-xl text-sm font-bold transition-all shadow-sm">បោះបង់</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-sm font-bold shadow-md transition-all">រក្សាទុកការទិញចូល</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if(document.getElementById("modalDate")) {
                flatpickr("#modalDate", { defaultDate: "today", dateFormat: "Y-m-d" });
            }
        });

        const modal = document.getElementById('purchaseModal');
        function openPurchaseModal() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closePurchaseModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function calculateTotal() {
            let total = parseFloat(document.getElementById('priceInput').value) || 0;
            document.getElementById('totalDisplay').innerText = '$' + total.toFixed(2);
        }
    </script>
</body>
</html>
