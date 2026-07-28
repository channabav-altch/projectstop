<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>កែប្រែទំនិញ - STOCK.PRO</title>
    <!-- នេះគឺជាកូដវេទមន្តដែលធ្វើឱ្យមានពណ៌ស្អាត (ហាមលុប ឬកាត់ចោលឱ្យសោះ) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gradient-to-tr from-rose-100 via-pink-50 to-orange-50 min-h-screen text-slate-800 m-0 p-0 antialiased selection:bg-violet-500 selection:text-white">

    <div class="max-w-4xl mx-auto p-6 mt-6 md:mt-10">

        <!-- Header ត្រឡប់ក្រោយ -->
        <div class="flex items-center gap-4 mb-8 bg-white/70 backdrop-blur-xl p-4 rounded-3xl border border-white shadow-sm">
            <a href="{{ route('stock.summary') }}" class="w-12 h-12 rounded-2xl bg-white border border-slate-100 hover:bg-slate-50 hover:scale-105 flex items-center justify-center text-slate-600 font-bold transition-all shadow-sm">
    ⬅
</a>
            <div>
                <h1 class="text-xl md:text-2xl font-extrabold bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent flex items-center gap-2">
                    ✏️ កែប្រែទិន្នន័យទំនិញ (EDIT PRODUCT)
                </h1>
                <p class="text-[11px] md:text-sm font-bold text-slate-400 mt-1 uppercase tracking-wider">ទិន្នន័យចាស់ត្រូវបានទាញមកបង្ហាញដោយស្វ័យប្រវត្តិ</p>
            </div>
        </div>

        <!-- ប្រអប់លោតសារ Error ពេលវាយខុស -->
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-600 px-5 py-4 rounded-2xl mb-6 shadow-sm">
                <ul class="list-disc list-inside font-bold text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ប្រអប់លោតសារជោគជ័យ -->
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-5 py-4 rounded-2xl mb-6 font-bold flex items-center gap-3 shadow-sm">
                <span class="text-xl">✅</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Form កែប្រែទិន្នន័យ -->
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white/80 backdrop-blur-xl p-6 md:p-8 rounded-[2rem] border border-white shadow-xl shadow-slate-200/50">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- ១. ឈ្មោះផលិតផល -->
                <div class="md:col-span-2 relative z-20">
                    <label class="block text-slate-600 text-sm font-bold mb-2">ឈ្មោះផលិតផល <span class="text-rose-500">*</span></label>
                    <input type="text" name="product_name" value="{{ $product->product_name }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-slate-800 font-bold focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 outline-none transition-all shadow-inner">
                </div>

                <!-- ២. រូបភាព -->
                <div class="relative z-20">
                    <label class="block text-slate-600 text-sm font-bold mb-2">រូបភាពផលិតផល (ជ្រើសរើសថ្មីបើចង់ដូរ)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-violet-100 file:text-violet-700 hover:file:bg-violet-200 cursor-pointer transition-all shadow-inner">
                    <!-- បង្ហាញរូបចាស់ឱ្យមើល -->
                    @if($product->image)
                        <div class="mt-3 inline-flex items-center gap-3 bg-white p-2 pr-4 rounded-xl border border-slate-100 shadow-sm">
                            <img src="{{ asset($product->image) }}" class="h-10 w-10 object-contain mix-blend-multiply rounded-md">
                            <span class="text-xs font-bold text-slate-400">រូបភាពបច្ចុប្បន្ន</span>
                        </div>
                    @endif
                </div>

                <!-- ៣. ប្រភេទ -->
                <div class="relative z-20">
                    <label class="block text-slate-600 text-sm font-bold mb-2">ប្រភេទ (Category) <span class="text-rose-500">*</span></label>
                    <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-slate-700 font-bold focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 outline-none transition-all shadow-inner">
                        <option value="BIOAQUA" {{ $product->category == 'BIOAQUA' ? 'selected' : '' }}>BIOAQUA</option>
                        <option value="DR+" {{ $product->category == 'DR+' ? 'selected' : '' }}>DR+</option>
                        <option value="ម៉ាស(MASK)" {{ $product->category == 'ម៉ាស(MASK)' ? 'selected' : '' }}>ម៉ាស(MASK)</option>
                        <option value="ផ្សេងៗ" {{ $product->category == 'ផ្សេងៗ' ? 'selected' : '' }}>ផ្សេងៗ</option>
                    </select>
                </div>

                <!-- ៤. លេខកូដទំនិញ -->
                <div class="md:col-span-2 relative z-20">
                    <label class="block text-slate-600 text-sm font-bold mb-2">លេខកូដទំនិញ (SKU/Barcode) <span class="text-rose-500">*</span></label>
                    <input type="text" name="product_code" value="{{ $product->product_code }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-slate-800 font-bold focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 outline-none transition-all shadow-inner">
                </div>

                <!-- ៥. តម្លៃទិញ & តម្លៃលក់ -->
                <div class="relative z-20">
                    <label class="block text-slate-600 text-sm font-bold mb-2">តម្លៃទិញចូល (Cost Price $) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="cost_price" value="{{ $product->cost_price }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-emerald-600 font-black focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 outline-none transition-all shadow-inner">
                </div>
                <div class="relative z-20">
                    <label class="block text-slate-600 text-sm font-bold mb-2">តម្លៃលក់ចេញ (Sale Price $) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="sale_price" value="{{ $product->sale_price }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-violet-600 font-black focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 outline-none transition-all shadow-inner">
                </div>

                <!-- ៦. ចំនួនស្តុក & ខ្នាត -->
                <div class="relative z-20">
                    <label class="block text-slate-600 text-sm font-bold mb-2">ចំនួនស្តុកបច្ចុប្បន្ន (Quantity)</label>
                    <input type="number" name="qty" value="{{ $product->qty }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-slate-800 font-bold focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 outline-none transition-all shadow-inner">
                </div>
                <div class="relative z-20">
                    <label class="block text-slate-600 text-sm font-bold mb-2">ខ្នាតឯកតា</label>
                    <select name="unit" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 text-slate-700 font-bold focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 outline-none transition-all shadow-inner">
                        <option value="ដប (Bottle)" {{ $product->unit == 'ដប (Bottle)' ? 'selected' : '' }}>ដប (Bottle)</option>
                        <option value="កំប៉ុង (Can)" {{ $product->unit == 'កំប៉ុង (Can)' ? 'selected' : '' }}>កំប៉ុង (Can)</option>
                        <option value="ប្រអប់ (Box)" {{ $product->unit == 'ប្រអប់ (Box)' ? 'selected' : '' }}>ប្រអប់ (Box)</option>
                        <option value="សន្លឹក (Piece)" {{ $product->unit == 'សន្លឹក (Piece)' ? 'selected' : '' }}>សន្លឹក (Piece)</option>
                    </select>
                </div>
                <div class="mb-6">
        <label class="block text-[13px] font-bold text-slate-700 mb-2">
            ស្ថានភាព (Status) <span class="text-rose-500">*</span>
        </label>
        <select name="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-[#5642F5]/20 focus:border-[#5642F5] transition-all bg-white outline-none">
            <option value="active" {{ (isset($product) && $product->status == 'active') ? 'selected' : '' }}>
                🟢 កំពុងលក់ (Active)
            </option>
            <option value="inactive" {{ (isset($product) && $product->status == 'inactive') ? 'selected' : '' }}>
                🔴 បិទការលក់ (Inactive)
            </option>
        </select>
    </div>

            </div>

            <!-- ប៊ូតុង Submit -->
            <div class="mt-8 pt-8 border-t border-slate-200/60">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-black text-lg py-4 px-4 rounded-2xl shadow-lg shadow-slate-800/20 hover:shadow-xl hover:shadow-slate-800/40 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <span class="text-2xl">💾</span> រក្សាទុកការកែប្រែ (SAVE CHANGES)
                </button>
            </div>
        </form>

    </div>
</body>
</html>
