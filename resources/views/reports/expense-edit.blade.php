<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កែប្រែចំណាយ</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Kantumruy Pro', sans-serif; } </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl p-8">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-800">
            <h1 class="text-xl font-black text-white flex items-center gap-3">✏️ កែប្រែព័ត៌មានចំណាយ</h1>
            <a href="{{ url('/team-report') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all">← ថយក្រោយ</a>
        </div>

        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="text-xs font-black text-slate-400 mb-2 block">បរិយាយ / ចំណងជើង</label>
                <input type="text" name="description" value="{{ old('description', $expense->description ?? $expense->title) }}"
                       class="w-full px-4 py-3.5 bg-slate-950 border border-slate-800 rounded-xl text-sm font-bold text-white outline-none focus:border-indigo-500" required>
            </div>

            <div>
                <label class="text-xs font-black text-slate-400 mb-2 block">ទឹកប្រាក់ ($)</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $expense->amount) }}"
                       class="w-full px-4 py-3.5 bg-slate-950 border border-slate-800 rounded-xl text-sm font-bold text-white outline-none focus:border-indigo-500" required>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-[#5642F5] hover:bg-indigo-600 text-white font-black text-sm rounded-xl transition-all shadow-xl shadow-indigo-600/30 cursor-pointer">
                    💾 រក្សាទុកការកែប្រែ
                </button>
            </div>
        </form>
    </div>
</body>
</html>
