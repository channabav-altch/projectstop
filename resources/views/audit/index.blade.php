<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ប្រតិបត្តិការគិតស្តុក - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* លាក់សញ្ញាឡើងចុះក្នុងប្រអប់លេខ (Number Input Arrows) */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-gradient-to-br from-[#070b19] via-[#0B132B] to-[#070b19] text-slate-300 m-0 p-0 antialiased min-h-screen">

    <div class="max-w-7xl mx-auto p-4 md:p-8">

        <!-- ប្រអប់លោតសារជោគជ័យ -->
        @if (session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 px-5 py-4 rounded-2xl mb-6 font-bold flex items-center gap-3 shadow-sm transition-all">
                <span class="text-xl">✅</span> {{ session('success') }}
            </div>
        @endif

        <!-- Form សម្រាប់ Submit ទិន្នន័យស្តុក -->
        <form action="{{ route('stock.audit.update') }}" method="POST">
            @csrf

            <!-- Header (ចំណងជើង និងប៊ូតុង Save) -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8 bg-[#15234b]/80 backdrop-blur-xl p-5 md:p-6 rounded-3xl border border-[#1C2C4E] shadow-xl">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') ?? '#' }}" class="w-12 h-12 rounded-2xl bg-[#0B132B] hover:bg-slate-800 border border-[#1C2C4E] flex items-center justify-center text-slate-400 font-bold transition-all shadow-sm">
                        ⬅
                    </a>
                    <div>
                        <h1 class="text-xl md:text-2xl font-extrabold text-white flex items-center gap-2">
                            📋 ប្រតិបត្តិការគិតស្តុក (Stock Audit)
                        </h1>
                        <p class="text-[11px] md:text-sm font-semibold text-cyan-400/70 mt-1 uppercase tracking-wider">ផ្ទៀងផ្ទាត់ចំនួនស្តុកក្នុងប្រព័ន្ធ និងចំនួនរាប់ជាក់ស្តែង</p>
                    </div>
                </div>

                <!-- ប៊ូតុង រក្សាទុកទិន្នន័យស្តុក -->
                <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-black text-sm md:text-base py-3.5 px-8 rounded-xl shadow-[0_0_20px_rgba(6,182,212,0.3)] hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    រក្សាទុកទិន្នន័យស្តុក
                </button>
            </div>

            <!-- តារាងទិន្នន័យ (Table) -->
            <div class="bg-[#15234b]/50 backdrop-blur-md rounded-3xl border border-[#1C2C4E] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#0B132B] border-b border-[#1C2C4E]">
                            <tr>
                                <th class="px-6 py-5 font-bold text-cyan-500 uppercase tracking-wider text-xs">លេខកូដ (SKU)</th>
                                <th class="px-6 py-5 font-bold text-cyan-500 uppercase tracking-wider text-xs">ឈ្មោះផលិតផល</th>
                                <th class="px-6 py-5 font-bold text-cyan-500 uppercase tracking-wider text-xs text-center">ស្តុកក្នុងប្រព័ន្ធ</th>
                                <th class="px-6 py-5 font-bold text-cyan-500 uppercase tracking-wider text-xs text-center w-56">រាប់ជាក់ស្តែង (ACTUAL)</th>
                                <th class="px-6 py-5 font-bold text-cyan-500 uppercase tracking-wider text-xs text-center">គម្លាត (DIFF)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1C2C4E]/50">
                            @forelse($products as $item)
                            <tr class="hover:bg-[#1C2C4E]/30 transition-colors">
                                <!-- លេខកូដ -->
                                <td class="px-6 py-4 font-mono text-slate-400">{{ $item->sku }}</td>

                                <!-- ឈ្មោះ -->
                                <td class="px-6 py-4 font-bold text-slate-200">
                                    {{ $item->product_name }}
                                </td>

                                <!-- ស្តុកប្រព័ន្ធ -->
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-[#0B132B] border border-[#1C2C4E] px-4 py-1.5 rounded-lg text-blue-400 font-bold shadow-inner">
                                        {{ $item->qty }}
                                    </span>
                                </td>

                                <!-- រាប់ជាក់ស្តែង (Input សម្រាប់វាយបញ្ចូលលេខ) -->
                                <!-- ប្រើ array name: stocks[ID_ទំនិញ] ដើម្បីងាយស្រួល Save ចូល Database ព្រមគ្នា -->
                                <td class="px-6 py-4 text-center">
                                    <input type="number" name="stocks[{{ $item->id }}]" value="{{ $item->qty }}"
                                           data-system="{{ $item->qty }}"
                                           class="actual-stock-input w-28 bg-[#0B132B] border border-[#1C2C4E] rounded-xl px-4 py-2 text-center text-white font-bold focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/30 outline-none transition-all shadow-inner">
                                </td>

                                <!-- គម្លាត (នឹងលោតដោយស្វ័យប្រវត្តិពេលវាយលេខខាងលើ) -->
                                <td class="px-6 py-4 text-center">
                                    <span class="diff-val font-black text-slate-400">0</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    📭 មិនទាន់មានទំនិញក្នុងស្តុកនៅឡើយទេ!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </form>
    </div>

    <!-- កូដ JS សម្រាប់គណនាគម្លាត (Diff) ស្វ័យប្រវត្តិពេលកំពុងវាយ -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.actual-stock-input');

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const systemStock = parseInt(this.getAttribute('data-system')) || 0;
                    const actualStock = parseInt(this.value) || 0;
                    const diff = actualStock - systemStock;

                    const diffSpan = this.closest('tr').querySelector('.diff-val');

                    // បង្ហាញសញ្ញា + បើលើស
                    diffSpan.innerText = diff > 0 ? '+' + diff : diff;

                    // លេងពណ៌ ក្រហម(ខ្វះ) បៃតង(លើស) ប្រផេះ(ស្មើ)
                    if (diff > 0) {
                        diffSpan.className = 'diff-val font-black text-emerald-400 drop-shadow-[0_0_5px_rgba(52,211,153,0.5)]';
                    } else if (diff < 0) {
                        diffSpan.className = 'diff-val font-black text-rose-500 drop-shadow-[0_0_5px_rgba(244,63,94,0.5)]';
                    } else {
                        diffSpan.className = 'diff-val font-black text-slate-400';
                    }
                });
            });
        });
    </script>
</body>
</html>
