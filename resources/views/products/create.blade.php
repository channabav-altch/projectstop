<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between bg-[#111C38] p-6 rounded-2xl border border-[#1C2C4E]">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="p-2.5 rounded-xl bg-[#0B132B] text-slate-400 hover:text-white border border-[#1C2C4E] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-lg font-black text-white">បញ្ចូលទំនិញថ្មីចូលស្តុក (INPUT PRODUCT)</h1>
                    <p class="text-[11px] text-emerald-400 font-semibold mt-0.5">● PostgreSQL Engine Ready</p>
                </div>
            </div>
        </div>

        <!-- 🟢 កូដសម្រាប់បង្ហាញ Error ដែល Laravel លាក់ទុក 🟢 -->
@if ($errors->any())
    <div class="mb-5 p-4 bg-rose-500/10 border-l-4 border-rose-500 text-rose-500 rounded-r-xl">
        <div class="font-bold mb-2">មានបញ្ហាមួយចំនួនទាមទារឲ្យអ្នកកែតម្រូវ៖</div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <!-- Form Card -->
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 md:col-span-2">
                    <label class="text-xs font-bold text-slate-300">ឈ្មោះផលិតផល <span class="text-rose-500">*</span></label>
                    <input type="text" name="product_name" required placeholder="ឧ. Coca-Cola កំប៉ុង 330ml" class="w-full bg-[#0B132B] border border-[#1C2C4E] rounded-xl px-4 py-3 text-white focus:border-emerald-500 text-sm">
                </div>
                <!-- ២. ប្រភេទ & លេខកូដ (២ ជួរ) -->
                <div class="mb-5">
    <label class="block text-slate-300 text-sm font-bold mb-2">រូបភាពផលិតផល</label>
    <input type="file" name="image" accept="image/*" class="w-full bg-[#0B132B] border border-[#1C2C4E] rounded-xl px-4 py-2 text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 transition cursor-pointer">
</div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
            <!-- ផ្នែកប្រភេទ (Category) អាចវាយបញ្ចូលថ្មីបាន -->
            <div>
                <label class="block text-slate-300 text-sm font-bold mb-2">ប្រភេទ (Category) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <!-- ប្តូរពី <select> មកប្រើ <input> ជាមួយ list="category-options" -->
                    <input type="text" list="category-options" name="category" required
                           class="w-full bg-[#0B132B] border border-[#1C2C4E] rounded-xl px-4 py-2.5 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition shadow-inner placeholder-slate-500"
                           placeholder="រើស ឬវាយបញ្ចូលប្រភេទថ្មី...">

                    <!-- បញ្ជីជម្រើសដែលនឹងលោតទម្លាក់ចុះមកក្រោម -->
                    <datalist id="category-options">
                        <option value="ទូទៅ (General)">
                        <option value="BIOAQUA">
                        <option value="DR+">
                        <option value="ម៉ាស(MASK)">
                        <option value="ផ្សេងៗ">
                    </datalist>

                    <!-- Icon សញ្ញាព្រួញចុះក្រោម ដើម្បីឱ្យមើលទៅដូច Dropdown -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

        </div>

            <div>
                <label class="block text-slate-300 text-sm font-bold mb-2">លេខកូដទំនិញ (SKU/Barcode) <span class="text-rose-500">*</span></label>
                <input type="text" name="product_code" required
                       class="w-full bg-[#0B132B] border border-[#1C2C4E] rounded-xl px-4 py-2.5 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition shadow-inner"
                       placeholder="ឧ. COCA-01">
            </div>

                <!-- ៣. តម្លៃទិញ & តម្លៃលក់ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="relative z-20"> <!-- ដាក់ relative z-20 ដើម្បីកុំឱ្យគេបាំង -->
                <label class="block text-slate-300 text-sm font-bold mb-2 cursor-pointer">តម្លៃទិញចូល (Cost Price $) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" name="cost_price" required
                       class="w-full bg-[#0B132B] border border-[#1C2C4E] rounded-xl px-4 py-2.5 text-emerald-400 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition shadow-inner"
                       placeholder="0.00" value="{{ old('cost_price') }}">
            </div>

            <div class="relative z-20">
                <label class="block text-slate-300 text-sm font-bold mb-2 cursor-pointer">តម្លៃលក់ចេញ (Sale Price $) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" name="sale_price" required
                       class="w-full bg-[#0B132B] border border-[#1C2C4E] rounded-xl px-4 py-2.5 text-emerald-400 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition shadow-inner"
                       placeholder="0.00" value="{{ old('sale_price') }}">
            </div>
        </div>

        <!-- ៤. ចំនួនស្តុក (Quantity) -->
        <div class="mb-6 relative z-20"> <!-- ដាក់ relative z-20 ដូចគ្នា -->
            <label class="block text-slate-300 text-sm font-bold mb-2 cursor-pointer">ចំនួនស្តុកបច្ចុប្បន្ន (Quantity)</label>
            <input type="number" name="qty" required
                   class="w-full bg-[#0B132B] border border-[#1C2C4E] rounded-xl px-4 py-2.5 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition shadow-inner"
                   placeholder="ឧ. 10" value="{{ old('qty') }}">
        </div>
            </div>
            <!-- ៤. ប្រអប់ព័ត៌មានស្តុក (កេស & រាយ) -->
        <div class="p-5 bg-[#0B132B]/60 rounded-xl border border-[#1C2C4E]/40 mb-6">
            <h3 class="text-cyan-400 font-bold text-sm mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                កំណត់បរិមាណស្តុក
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div>
                    <label class="block text-slate-300 text-[13px] font-bold mb-2">ខ្នាតឯកតា</label>
                    <div class="relative">
                        <select name="unit" class="w-full bg-[#111C38] border border-[#1C2C4E] rounded-lg px-3 py-2 text-white focus:border-cyan-500 outline-none appearance-none shadow-inner text-sm">
                            <option value="កំប៉ុង (Can)">កំប៉ុង (Can)</option>
                            <option value="ដប (Bottle)">ដប (Bottle)</option>
                            <option value="សន្លឹក (Piece)">សន្លឹក (Piece)</option>
                            <option value="ប្រអប់ (Box)">ប្រអប់ (Box)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-slate-300 text-[13px] font-bold mb-2">ចំនួនកេស</label>
                    <input type="number" name="qty_cases"
                           class="w-full bg-[#111C38] border border-[#1C2C4E] rounded-lg px-3 py-2 text-center text-white focus:border-cyan-500 outline-none transition shadow-inner text-sm"
                           >
                </div>

                <!-- 🟢 ប្រអប់ចំនួនក្នុង ១កេស (Carton Size) 🟢 -->
<div>
    <label class="block text-xs font-bold text-slate-300 mb-1.5">ចំនួនក្នុង ១កេស <span class="text-rose-500">*</span></label>
    <div class="relative">
        <input type="number"
               name="carton_size"
               id="carton_size"
               value="1"
               min="1"
               required
               placeholder="ឧ. 24"
               class="w-full bg-[#0B132B] border border-[#1C2C4E] rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600">
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <span class="text-xs text-slate-500">ឯកតា/កេស</span>
        </div>
    </div>
</div>
                <div>
                    <label class="block text-slate-300 text-[13px] font-bold mb-2">ចំនួនរាយ (ដប/សន្លឹក)</label>
                    <input type="number" name="qty_pieces"
                           class="w-full bg-[#111C38] border border-[#1C2C4E] rounded-lg px-3 py-2 text-center text-white focus:border-cyan-500 outline-none transition shadow-inner text-sm"
                           >
                </div>
            </div>
        </div>

            <div class="pt-4 border-t border-[#1C2C4E] flex justify-end gap-4">
                <button type="submit" class="px-8 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 active:scale-95 transition">
                    ➕ រក្សាទុកចូលស្តុក
                </button>
            </div>
        </form>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ចាប់យកប្រអប់ទាំង ៤ មកធ្វើការ
        const inputCartons = document.querySelector('input[name="cartons"]');
        const inputCartonSize = document.querySelector('input[name="carton_size"]');
        const inputPieces = document.querySelector('input[name="pieces"]');
        const inputTotalQty = document.querySelector('input[name="quantity"]'); // ប្រអប់សរុបខាងលើ

        // មុខងារគណនាសរុប
        function calculateTotal() {
            let cartons = parseInt(inputCartons.value) || 0;
            let size = parseInt(inputCartonSize.value) || 1;
            let pieces = parseInt(inputPieces.value) || 0;

            let total = (cartons * size) + pieces;

            // បាញ់លេខសរុបទៅបង្ហាញក្នុងប្រអប់ Quantity ខាងលើ
            if(total > 0) {
                inputTotalQty.value = total;
            }
        }

        // ពេលវាយបញ្ចូលក្នុងប្រអប់ទាំង៣ នេះ ឲ្យវាគណនាភ្លាមៗ
        if(inputCartons) inputCartons.addEventListener('input', calculateTotal);
        if(inputCartonSize) inputCartonSize.addEventListener('input', calculateTotal);
        if(inputPieces) inputPieces.addEventListener('input', calculateTotal);
    });
</script>
</x-app-layout>
