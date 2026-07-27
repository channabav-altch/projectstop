<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>គិតដកស្តុក - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-gradient-to-br from-[#1a0b13] via-[#0B132B] to-[#1a0b13] text-slate-300 m-0 p-0 antialiased min-h-screen">

    <div class="max-w-7xl mx-auto p-4 md:p-8">

        @if (session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 px-5 py-4 rounded-2xl mb-6 font-bold flex items-center gap-3 shadow-sm">
                <span class="text-xl">✅</span> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('stock.deduct.update') }}" method="POST">
            @csrf

            <!-- Header -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8 bg-[#15234b]/80 backdrop-blur-xl p-5 md:p-6 rounded-3xl border border-rose-900/50 shadow-xl shadow-rose-900/10">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') ?? '#' }}" class="w-12 h-12 rounded-2xl bg-[#0B132B] hover:bg-rose-950 border border-rose-900/50 flex items-center justify-center text-slate-400 hover:text-rose-400 font-bold transition-all shadow-sm">
                        ⬅
                    </a>
                    <div>
                        <h1 class="text-xl md:text-2xl font-extrabold text-white flex items-center gap-2">
                            🗑️ ប្រតិបត្តិការគិតដកស្តុក (Stock Deduction)
                        </h1>
                        <p class="text-[11px] md:text-sm font-semibold text-rose-400/70 mt-1 uppercase tracking-wider">កាត់ដកស្តុកទំនិញដែលខូច បាត់បង់ ឬប្រើប្រាស់ផ្ទៃក្នុង</p>
                    </div>
                </div>

                <!-- ប៊ូតុង រក្សាទុក -->
                <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white font-black text-sm md:text-base py-3.5 px-8 rounded-xl shadow-[0_0_20px_rgba(244,63,94,0.3)] hover:shadow-[0_0_30px_rgba(244,63,94,0.5)] hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    រក្សាទុកការកាត់ដក
                </button>
            </div>

            <!-- តារាងទិន្នន័យ -->
            <div class="bg-[#15234b]/50 backdrop-blur-md rounded-3xl border border-rose-900/30 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#0B132B] border-b border-[#1C2C4E]">
                            <tr>
                                <th class="px-6 py-5 font-bold text-rose-400 uppercase tracking-wider text-xs">លេខកូដ (SKU)</th>
                                <th class="px-6 py-5 font-bold text-rose-400 uppercase tracking-wider text-xs">ឈ្មោះផលិតផល</th>
                                <th class="px-6 py-5 font-bold text-rose-400 uppercase tracking-wider text-xs text-center">ស្តុកបច្ចុប្បន្ន</th>
                                <th class="px-6 py-5 font-bold text-rose-400 uppercase tracking-wider text-xs text-center w-56">ចំនួនត្រូវដក (DEDUCT)</th>
                                <th class="px-6 py-5 font-bold text-rose-400 uppercase tracking-wider text-xs text-center">ស្តុកសល់ (REMAINING)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1C2C4E]/50">
                            @forelse($products as $item)
                            <tr class="hover:bg-rose-900/20 transition-colors">
                                <td class="px-6 py-4 font-mono text-slate-400">{{ $item->sku }}</td>
                                <td class="px-6 py-4 font-bold text-slate-200">{{ $item->product_name }}</td>

                                <!-- ស្តុកបច្ចុប្បន្ន -->
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-[#0B132B] border border-[#1C2C4E] px-4 py-1.5 rounded-lg text-emerald-400 font-bold shadow-inner">
                                        {{ $item->qty }}
                                    </span>
                                </td>

                                <!-- បញ្ចូលចំនួនត្រូវដក -->
                                <td class="px-6 py-4 text-center relative">
                                    <span class="absolute inset-y-0 left-8 flex items-center text-rose-500 font-bold">-</span>
                                    <input type="number" name="deduct_stocks[{{ $item->id }}]" value="" min="0" max="{{ $item->qty }}"
                                           data-current="{{ $item->qty }}"
                                           placeholder="0"
                                           class="deduct-input w-28 bg-[#0B132B] border border-rose-900/50 rounded-xl pl-6 pr-3 py-2 text-center text-white font-bold focus:border-rose-500 focus:ring-2 focus:ring-rose-500/30 outline-none transition-all shadow-inner placeholder-slate-600">
                                </td>

                                <!-- ស្តុកសល់ -->
                                <td class="px-6 py-4 text-center">
                                    <span class="remain-val font-black text-slate-400">{{ $item->qty }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    📭 មិនមានទំនិញក្នុងស្តុកដើម្បីកាត់ដកទេ!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </form>
    </div>

    <!-- គណនាស្តុកសល់ដោយស្វ័យប្រវត្តិ -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.deduct-input');

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const currentStock = parseInt(this.getAttribute('data-current')) || 0;
                    let deductStock = parseInt(this.value) || 0;

                    // ការពារកុំឱ្យវាយលេខដកលើសស្តុកដែលមាន
                    if(deductStock > currentStock) {
                        this.value = currentStock;
                        deductStock = currentStock;
                    }
                    if(deductStock < 0) {
                        this.value = 0;
                        deductStock = 0;
                    }

                    const remain = currentStock - deductStock;
                    const remainSpan = this.closest('tr').querySelector('.remain-val');

                    remainSpan.innerText = remain;

                    // លេងពណ៌ ពេលមានការកាត់ដក
                    if (deductStock > 0) {
                        remainSpan.className = 'remain-val font-black text-orange-400 drop-shadow-[0_0_5px_rgba(251,146,60,0.5)]';
                    } else {
                        remainSpan.className = 'remain-val font-black text-slate-400';
                    }
                });
            });
        });
    </script>
</body>
</html>
