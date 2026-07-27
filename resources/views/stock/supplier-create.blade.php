<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>បន្ថែមអ្នកផ្គត់ផ្គង់ថ្មី</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6 selection:bg-indigo-500 selection:text-white">

    <div class="w-full max-w-xl bg-slate-900/90 border border-slate-800/80 rounded-3xl shadow-2xl p-8 backdrop-blur-xl">

        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-800/80">
            <h1 class="text-xl font-black text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                    ➕
                </div>
                បន្ថែមអ្នកផ្គត់ផ្គង់ថ្មី
            </h1>
            <a href="{{ url('/stock-supplier') }}"
               class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all border border-slate-700/50 flex items-center gap-1.5">
                ← ថយក្រោយ
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-2xl flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('stock.supplier.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="text-xs font-black text-slate-400 mb-2 block tracking-wide">
                    ឈ្មោះក្រុមហ៊ុន / អ្នកផ្គត់ផ្គង់ <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="បំពេញឈ្មោះអ្នកផ្គត់ផ្គង់..."
                       class="w-full px-4 py-3.5 bg-slate-950 border border-slate-800 rounded-xl text-sm font-bold text-white outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-600 shadow-inner" required>
                @error('name') <span class="text-rose-400 text-[11px] font-bold mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-xs font-black text-slate-400 mb-2 block tracking-wide">លេខទូរស័ព្ទ</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="បំពេញលេខទូរស័ព្ទ..."
                       class="w-full px-4 py-3.5 bg-slate-950 border border-slate-800 rounded-xl text-sm font-bold text-white outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-600 shadow-inner">
            </div>

            <div>
                <label class="text-xs font-black text-slate-400 mb-2 block tracking-wide">អាសយដ្ឋាន</label>
                <textarea name="address" rows="3" placeholder="បំពេញអាសយដ្ឋាន..."
                          class="w-full px-4 py-3.5 bg-slate-950 border border-slate-800 rounded-xl text-sm font-bold text-white outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-600 resize-none shadow-inner">{{ old('address') }}</textarea>
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="w-full py-4 bg-[#5642F5] hover:bg-indigo-600 text-white font-black text-sm rounded-xl transition-all shadow-xl shadow-indigo-600/30 cursor-pointer flex items-center justify-center gap-2">
                    <span>💾 រក្សាទុកទិន្នន័យ</span>
                </button>
            </div>

        </form>

    </div>

</body>
</html>
