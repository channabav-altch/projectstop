<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>កែប្រែទិន្នន័យលក់ចេញ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#0A1122] text-slate-200 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-lg bg-[#0D182E] border border-slate-700/50 rounded-2xl p-8 shadow-2xl">
        <div class="flex items-center gap-3 mb-6 border-b border-slate-700/50 pb-4">
            <div class="w-10 h-10 bg-indigo-500/20 text-indigo-400 flex items-center justify-center rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h2 class="text-xl font-black text-white">កែប្រែទិន្នន័យលក់ចេញ</h2>
        </div>

        <form action="{{ url('stock-sold/update/' . $item->id) }}" method="POST">
            @csrf

            <div class="space-y-5 mb-8">
                <div>
                    <label class="text-xs font-bold text-slate-400 mb-1.5 block">ឈ្មោះទំនិញ (មិនអាចកែបាន)</label>
                    <input type="text" value="{{ $item->product->product_name ?? $item->product->name ?? 'ទំនិញទូទៅ' }}" readonly class="w-full px-4 py-2.5 bg-slate-800/50 border border-slate-700 rounded-xl text-sm text-slate-500 outline-none cursor-not-allowed">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-300 mb-1.5 block">ចំនួន (QTY)</label>
                        <input type="number" name="qty" value="{{ $item->qty }}" required class="w-full px-4 py-2.5 bg-[#0A1122] border border-slate-600 rounded-xl text-sm text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-300 mb-1.5 block">តម្លៃសរុប ($)</label>
                        <input type="number" step="0.01" name="total" value="{{ $item->total }}" required class="w-full px-4 py-2.5 bg-[#0A1122] border border-slate-600 rounded-xl text-sm text-white outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="javascript:history.back()" class="flex-1 text-center py-3 rounded-xl border border-slate-600 text-slate-300 font-bold hover:bg-slate-800 transition">បោះបង់</a>
                <button type="submit" class="flex-1 text-center py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition">រក្សាទុកការកែប្រែ</button>
            </div>
        </form>
    </div>

</body>
</html>
