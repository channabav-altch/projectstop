<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>កែប្រែវិក្កយបត្រ #{{ $order->invoice_no }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-xl bg-white border border-slate-200 rounded-2xl p-8 shadow-xl shadow-slate-200/50">
        <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
            <div class="w-12 h-12 bg-indigo-50 text-[#5642F5] flex items-center justify-center rounded-xl font-black text-xl">
                #
            </div>
            <div>
                <h2 class="text-xl font-black text-slate-700">កែប្រែវិក្កយបត្រ</h2>
                <p class="text-xs font-bold text-slate-400 mt-1">វិក្កយបត្រលេខ៖ {{ $order->invoice_no }}</p>
            </div>
        </div>

        <form action="{{ url('pos/update/' . $order->id) }}" method="POST">
            @csrf

            <div class="space-y-4 mb-8">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">ឈ្មោះអតិថិជន</label>
                        <input type="text" name="customer_name" value="{{ $order->customer_name }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:border-[#5642F5] focus:ring-1 focus:ring-[#5642F5] transition">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">លេខទូរស័ព្ទ</label>
                        <input type="text" name="phone" value="{{ $order->phone }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:border-[#5642F5] focus:ring-1 focus:ring-[#5642F5] transition">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">សេវាដឹកជញ្ជូន</label>
                        <input type="text" name="delivery_method" value="{{ $order->delivery_method }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:border-[#5642F5] focus:ring-1 focus:ring-[#5642F5] transition" placeholder="ឧ. VET, J&T...">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">ទីតាំង (ខេត្ត)</label>
                        <input type="text" name="province" value="{{ $order->province }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:border-[#5642F5] focus:ring-1 focus:ring-[#5642F5] transition" placeholder="ឧ. ភ្នំពេញ...">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1.5 block">ស្ថានភាពវិក្កយបត្រ (Status)</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-[#5642F5] focus:ring-1 focus:ring-[#5642F5] transition cursor-pointer appearance-none">
                        <option value="PAID" {{ $order->status == 'PAID' ? 'selected' : '' }}>✅ ទូទាត់រួច (PAID)</option>
                        <option value="PENDING" {{ $order->status == 'PENDING' ? 'selected' : '' }}>⏳ រង់ចាំ (PENDING)</option>
                        <option value="CANCELED" {{ $order->status == 'CANCELED' ? 'selected' : '' }}>❌ បោះបង់ (CANCELED)</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1.5 block">ចំណាំបន្ថែម</label>
                    <textarea name="note" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:border-[#5642F5] focus:ring-1 focus:ring-[#5642F5] transition">{{ $order->note }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="javascript:history.back()" class="flex-1 text-center py-3 rounded-xl border border-slate-200 text-slate-500 font-bold hover:bg-slate-50 transition">បោះបង់</a>
                <button type="submit" class="flex-1 text-center py-3 rounded-xl bg-[#5642F5] text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition">រក្សាទុកការកែប្រែ</button>
            </div>
        </form>
    </div>

</body>
</html>
