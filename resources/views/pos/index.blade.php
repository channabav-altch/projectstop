<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ប្រព័ន្ធបញ្ចេញលក់ (POS) - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #CBD5E1; border-radius: 10px; }
    .hide-scroll::-webkit-scrollbar { display: none; }
    .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

    /* ដាក់កូដ CSS ថ្មីរបស់អ្នកនៅត្រង់នេះ */
    .product-container {
      height: calc(100vh - 200px);
      overflow-y: auto;
      padding-right: 8px;
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
    }
    .product-card {
      display: flex;
      flex-direction: column;
      min-height: 180px;
      background-color: white;
      border-radius: 8px;
      padding: 10px;
    }
    .product-image {
      width: 100%;
      height: 120px;
      object-fit: contain;
      margin-bottom: 8px;
    }
</style>
</head>

<body class="text-slate-800 m-0 p-0 antialiased bg-[#0A1122] selection:text-white overflow-hidden">

    @if(session('success'))
        <div id="successAlert" class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg z-[999]">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div id="errorAlert" class="fixed top-4 right-4 bg-rose-500 text-white px-6 py-3 rounded-xl shadow-lg z-[999]">
            {{ session('error') }}
        </div>
    @endif

    <div x-data="{
        showCheckoutModal: {{ $errors->any() ? 'true' : 'false' }}, customerType: 'delivery',
        inputMode: 'pro',
        deliveryCurrency: 'USD',
        deliveryFee: '',
        pastedImage: null,
        isExtracting: false,

        cartTotal: 0,
        cartQty: 0,

        get grandTotal() {
            let total = parseFloat(this.cartTotal) || 0;
            let fee = (this.customerType === 'delivery') ? (parseFloat(this.deliveryFee) || 0) : 0;
            return (total + fee).toFixed(2);
        },
        async processImageWithAI() {
            if (!this.pastedImage) return;
            this.isExtracting = true;
            try {
                const response = await fetch('/pos/ai-vision', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']')?.content || ''
                    },
                    body: JSON.stringify({ image_base64: this.pastedImage })
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.error || 'មានបញ្ហានៅ Server');

                this.isExtracting = false;
                const nameInput = document.querySelector('input[name=\'customer_name\']');
                const phoneInput = document.querySelector('input[name=\'phone\']');
                if(nameInput) { nameInput.value = result.customer_name || ''; flashSuccess(nameInput); }
                if(phoneInput) { phoneInput.value = result.phone || ''; flashSuccess(phoneInput); }
            } catch (error) {
                this.isExtracting = false;
                alert('Error ពី Server: ' + error.message);
            }
        },
        handlePaste(event) {
            if(!this.showCheckoutModal || this.customerType !== 'delivery' || this.inputMode !== 'pro') return;
            let items = (event.clipboardData || event.originalEvent.clipboardData).items;
            for (let i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    let blob = items[i].getAsFile();
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.pastedImage = e.target.result;
                        document.getElementById('receiptImageBase64').value = e.target.result;
                        this.processImageWithAI();
                    };
                    reader.readAsDataURL(blob);
                }
            }
        }
    }"
    @cart-updated.window="cartTotal = $event.detail.total; cartQty = $event.detail.qty"
    @paste.window="handlePaste($event)">

        <header class="bg-[#0A1122] px-6 py-3 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] border-b border-slate-100 flex justify-between items-center z-40 relative">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') ?? '#' }}" class="p-2 text-slate-500 hover:bg-slate-100 hover:text-[#5642F5] rounded-xl transition-all active:scale-95">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-[#5642F5] font-black text-lg hidden sm:flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    ប្រព័ន្ធបញ្ចេញលក់ (POS)
                </h1>
            </div>
            <div class="flex items-center gap-4 flex-1 justify-center">
                <a href="{{ route('pos.sales_today') ?? '#' }}" class="w-full md:w-auto text-center bg-[#5642F5] hover:bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30 transition-all">
                    <span class="text-sm font-bold">លក់ថ្ងៃនេះ</span>
                    <span class="bg-rose-500/20 text-rose-500 text-[10px] font-bold px-2 py-0.5 rounded-full border border-rose-500/50">Live</span>
                </a>
            </div>
        </header>

        <div class="p-4 flex flex-col lg:flex-row gap-5 h-[calc(100vh-76px)]">

            <div class="flex-1 flex flex-col bg-[#0A1122] rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative z-10">
                <div class="p-4 border-b border-slate-100 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-4 bg-white z-20">
                    <div class="relative w-full xl:w-[320px] shrink-0">
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" id="searchInput" placeholder="ស្វែងរកឈ្មោះ ឬបាកូដ..." class="w-full bg-slate-50 border border-slate-200 rounded-full pl-10 pr-4 py-2.5 text-sm font-medium outline-none focus:bg-white focus:border-[#5642F5] transition-all">
                    </div>
                    <div class="flex items-center gap-2 overflow-x-auto hide-scroll w-full flex-nowrap pb-2 xl:pb-0" id="filterButtons">
                        <button data-filter="all" class="filter-btn shrink-0 active bg-slate-800 text-white px-5 py-2 rounded-full font-bold text-xs whitespace-nowrap shadow-md">ទាំងអស់</button>
                        <button data-filter="BIOAQUA" class="filter-btn shrink-0 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-5 py-2 rounded-full font-bold text-xs whitespace-nowrap">BIOAQUA</button>
                        <button data-filter="DR+" class="filter-btn shrink-0 bg-cyan-50 text-cyan-600 hover:bg-cyan-100 px-5 py-2 rounded-full font-bold text-xs whitespace-nowrap">DR+</button>
                        <button data-filter="ម៉ាស(MASK)" class="filter-btn shrink-0 bg-rose-50 text-rose-500 hover:bg-rose-100 px-5 py-2 rounded-full font-bold text-xs whitespace-nowrap">ម៉ាស(MASK)</button>
                        {{-- <button data-filter="ឈុត (Bundle)" class="filter-btn shrink-0 bg-amber-100 text-amber-800 border border-amber-300 hover:bg-amber-200 px-5 py-2 rounded-full font-bold text-xs whitespace-nowrap flex items-center gap-1.5">
                            <span>🎁</span> ទំនិញឈុត
                        </button> --}}
                        <button data-filter="ផ្សេងៗ" class="filter-btn shrink-0 bg-rose-50 text-rose-500 hover:bg-rose-100 px-5 py-2 rounded-full font-bold text-xs whitespace-nowrap">ផ្សេងៗ</button>
                    </div>
                </div>

               <div id="productGrid" class="flex-1 overflow-y-auto p-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 content-start custom-scrollbar bg-slate-50/30">

    @forelse($products ?? [] as $item)
        @php
            $isBundle = str_contains($item->category ?? '', 'ឈុត') || str_contains($item->category ?? '', 'ប៊ណ្ឌល') || str_contains(strtolower($item->category ?? ''), 'bundle');
            // ទាញយកចំនួនស្តុកដោយផ្ទៀងផ្ទាត់គ្រប់ Column ដែលអាចមានក្នុង Database
            $stockValue = $item->stock ?? $item->qty ?? $item->quantity ?? 0;
        @endphp

        <!-- 🟢 ប្រអប់កាតផលិតផល (កែសម្រួលកម្ពស់និង padding ឱ្យមានរបៀបរៀបរយល្អ) -->
        <div onclick="addToCart({{ $item->id }}, '{{ addslashes($item->product_name ?? $item->name) }}', {{ floatval($item->sale_price ?? $item->price ?? 0) }})"
             class="product-card group relative bg-white border border-slate-200 rounded-2xl p-3.5 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-indigo-300 cursor-pointer flex flex-col justify-between"
             style="min-height: 280px;"
             data-category="{{ $item->category ?? 'ទូទៅ' }}"
             data-search="{{ strtolower(($item->product_name ?? $item->name) . ' ' . ($item->sku ?? '')) }}">

            <!-- ស៊ុមរូបភាពផលិតផល -->
            <div class="card-img-wrapper relative w-full flex items-center justify-center mb-3 bg-slate-50 rounded-xl p-2 shrink-0" style="height: 130px;">
                <img src="{{ $item->image ? asset($item->image) : 'https://ui-avatars.com/api/?name=NI&background=E2E8F0&color=64748B&size=150' }}"
                     onerror="this.src='https://ui-avatars.com/api/?name=NI&background=E2E8F0&color=64748B&size=150'"
                     alt="Product Image"
                     class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
            </div>

            <!-- ព័ត៌មានឈ្មោះ តម្លៃ និងស្តុក -->
            <div class="flex flex-col flex-grow justify-between">
                <div class="mb-2">
                    <h3 class="font-extrabold text-slate-900 text-[13px] leading-tight group-hover:text-[#5642F5] transition-colors line-clamp-2">
                        {{ $item->product_name ?? $item->name }}
                    </h3>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 pt-2.5 mt-auto">
                    <span class="text-[11px] px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md font-bold">
                        ស្តុក: {{ $stockValue }}
                    </span>
                    <span class="text-[15px] font-black text-[#5642F5] tracking-tight whitespace-nowrap">
                        {{ number_format($item->sale_price ?? $item->price ?? 0, 2) }} <span class="text-xs">$</span>
                    </span>
                </div>
            </div>

            <!-- 🟢 ប៊ូតុង និងប្រអប់បង្ហាញកូនឈុត (Bundle Dropdown) -->
            @if($isBundle)
            <div class="absolute z-50 left-2.5 top-2.5 bundle-container" onclick="event.stopPropagation()">
                <button type="button" onclick="window.toggleBundle(this)" class="bg-orange-500 hover:bg-orange-600 text-white text-[10px] font-black px-2 py-1 rounded shadow-md flex items-center gap-1 cursor-pointer transition-all border border-orange-400 relative z-10">
                    📦 ឈុត
                </button>

                <div class="bundle-popup hidden absolute left-0 top-full mt-2 w-[280px] bg-[#161F33] border border-slate-700/50 rounded-xl shadow-2xl p-4 transition-all duration-300 z-50">
                    <div class="flex items-center justify-between mb-3 border-b border-slate-700/80 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-500 text-sm">📦</span>
                            <span class="text-[12px] font-black text-slate-100 uppercase tracking-wide">
                                កូនរបស់ឈុត (ID: {{ $item->id }})
                            </span>
                        </div>
                        <button type="button" onclick="this.closest('.bundle-popup').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 text-[14px] font-bold px-1 transition-colors">✕</button>
                    </div>

                    <ul class="space-y-2 overflow-y-auto max-h-[250px] hide-scroll pr-1">
                        @php
                            $subProducts = \DB::table('bundle_items')
                                               ->where('product_id', $item->id)
                                               ->orWhere('product_bundle_id', $item->id)
                                               ->get();
                        @endphp

                        @forelse($subProducts as $sub)
                            @php
                                $childId = $sub->product_id ?? $sub->item_id;
                                $realProduct = \DB::table('products')->where('id', $childId)->first();
                                $realName = $realProduct ? ($realProduct->product_name ?? $realProduct->name ?? 'មិនស្គាល់') : 'មិនស្គាល់ទំនិញ';
                                $unitName = $realProduct ? ($realProduct->unit ?? 'ឯកតា') : 'ឯកតា';
                                $qty = $sub->quantity ?? $sub->qty ?? 1;
                            @endphp

                            <li class="flex justify-between items-center gap-3 py-1.5 border-b border-slate-700/30 last:border-0">
                                <div class="flex items-start gap-2 flex-1">
                                    <span class="text-yellow-500 text-[12px] mt-0.5 font-black">▪</span>
                                    <span class="text-[11px] text-slate-200 font-bold leading-tight uppercase">{{ $realName }}</span>
                                </div>
                                <span class="bg-[#24304A] border border-slate-600/50 text-yellow-400 font-black px-2 py-1 rounded text-[10px] whitespace-nowrap shadow-sm">
                                    x{{ $qty }} {{ $unitName }}
                                </span>
                            </li>
                        @empty
                            <li class="text-center text-[11px] text-rose-400 py-2 font-bold bg-rose-500/10 rounded border border-rose-500/20">
                                មិនមានទិន្នន័យកូន សម្រាប់ ID: {{ $item->id }} ក្នុង Database ទេ!
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @endif

        </div>
    @empty
        <div id="js-empty-state" class="col-span-full py-24 text-center bg-white rounded-3xl border border-dashed border-slate-300">
            <span class="text-6xl mb-4 block opacity-50">📭</span>
            <p class="text-slate-500 text-lg font-bold">មិនទាន់មានទំនិញក្នុងស្តុកនៅឡើយទេ!</p>
        </div>
    @endforelse

</div>
                </div>

            <div class="w-full lg:w-[380px] bg-white rounded-3xl shadow-sm border border-slate-100 flex flex-col h-full overflow-hidden z-10">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h3 class="font-black text-base text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#5642F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        កន្ត្រកទំនិញ
                    </h3>
                    <span id="cartCountBadge" class="bg-indigo-50 text-[#5642F5] px-2.5 py-1 rounded-md text-xs font-black border border-indigo-100">0 មុខ</span>
                </div>

                <div id="cartItemsContainer" class="flex-1 overflow-y-auto p-3 space-y-2 bg-slate-50/50 custom-scrollbar"></div>

                <div class="p-4 bg-white border-t border-slate-100 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.03)] z-10">
                    <div class="flex justify-between items-center mb-4 bg-gradient-to-r from-[#5642F5] to-indigo-500 p-4 rounded-xl text-white shadow-lg shadow-indigo-200">
                        <span class="font-bold text-sm text-indigo-100">សរុបទឹកប្រាក់</span>
                        <span id="cartTotalDisplay" class="font-black text-3xl">0.00 $</span>
                    </div>
                    <button type="button" @click="if(cart.length > 0) { showCheckoutModal = true; } else { alert('សូមជ្រើសរើសទំនិញចូលកន្ត្រកជាមុនសិន!'); }" class="w-full bg-[#5642F5] hover:bg-indigo-600 text-white p-3.5 rounded-xl flex items-center justify-center gap-2 transition-all font-bold text-lg shadow-lg shadow-indigo-500/30">
                        ទូទាត់ប្រាក់
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showCheckoutModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="showCheckoutModal" @click="showCheckoutModal = false" x-transition.opacity.duration.300ms class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto pointer-events-none">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

                    <form id="checkoutForm" action="{{ route('pos.store') ?? '#' }}" method="POST" onsubmit="return prepareCheckoutData()"
                          x-show="showCheckoutModal"
                          x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                          x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                          class="relative transform overflow-visible rounded-[24px] bg-white text-left shadow-2xl transition-all w-full max-w-5xl pointer-events-auto flex flex-col max-h-[90vh] border border-slate-200">
                        @csrf
                        <input type="hidden" name="invoice_no" value="INV-{{ time() }}-{{ rand(100, 999) }}">

                        <input type="hidden" name="cart_data" id="cartDataInput">
                        <input type="hidden" name="grand_total" :value="grandTotal">
                        <input type="hidden" name="total_amount" :value="grandTotal">
                        <input type="hidden" name="customer_type" :value="customerType">
                        <input type="hidden" name="delivery_currency" :value="deliveryCurrency">
                        <input type="hidden" name="receipt_image_base64" id="receiptImageBase64">

                        <div class="flex items-center justify-between px-8 py-5 border-b border-slate-100 shrink-0 bg-white rounded-t-[24px]">
                            <div class="flex items-center gap-4">
                                <div :class="customerType === 'walkin' ? 'bg-emerald-500 shadow-emerald-500/30' : 'bg-[#5642F5] shadow-indigo-500/30'" class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-lg transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-slate-800 tracking-tight">បញ្ជាក់ការទូទាត់</h2>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-0.5">Checkout Processing</p>
                                </div>
                            </div>
                            <button type="button" @click="showCheckoutModal = false" class="text-slate-400 border border-slate-200 hover:text-rose-500 hover:bg-rose-50 hover:border-rose-200 p-2 rounded-full transition-all bg-white shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        @if ($errors->any())
                            <div class="px-8 py-4 bg-rose-50 border-b border-rose-100">
                                <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-lg relative">
                                    <strong class="font-bold">បរាជ័យ! ទិន្នន័យមានបញ្ហា៖</strong>
                                    <ul class="mt-2 list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col lg:flex-row flex-1 overflow-y-auto custom-scrollbar bg-white">
                            <div class="w-full lg:w-1/2 p-6 md:p-8 border-r border-slate-100 flex flex-col space-y-6 bg-[#F8FAFC]">

                                <div class="flex gap-3">
                                    <button type="button" @click="customerType = 'delivery'"
                                            :class="customerType === 'delivery' ? 'border-indigo-500 text-[#5642F5] bg-white shadow-sm ring-1 ring-indigo-500' : 'border-slate-200 text-slate-500 hover:bg-slate-100 bg-white'"
                                            class="flex-1 py-3 rounded-xl border text-sm font-bold transition-all flex items-center justify-center gap-2">
                                        <span class="text-rose-500 text-lg">📍</span> ផ្ញើតាមខេត្ត/ក្រុង
                                    </button>
                                    <button type="button" @click="customerType = 'walkin'; inputMode = 'manual';"
                                            :class="customerType === 'walkin' ? 'border-emerald-500 text-emerald-700 bg-white shadow-sm ring-1 ring-emerald-500' : 'border-slate-200 text-slate-500 hover:bg-slate-100 bg-white'"
                                            class="flex-1 py-3 rounded-xl border text-sm font-bold transition-all flex items-center justify-center gap-2">
                                        <span class="text-lg">🛍️</span> អតិថិជនទិញផ្ទាល់
                                    </button>
                                </div>

                                <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-sm font-black text-[#5642F5] flex items-center gap-2">⚡ បំពេញស្វ័យប្រវត្តិ</h3>
                                        <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-2 py-1 rounded-md border border-indigo-100">Credit: 314/314</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 mb-2">
                                        <button type="button" @click="inputMode = 'manual'" :class="inputMode === 'manual' ? 'bg-[#1C2C4E] border-[#1C2C4E] text-white shadow-md' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50'" class="py-2.5 text-xs font-bold border rounded-[10px] transition-all flex items-center justify-center gap-1.5">
                                            ✍️ បញ្ចូលដោយដៃ
                                        </button>
                                        <button type="button" @click="inputMode = 'pro'" :class="inputMode === 'pro' ? 'bg-indigo-50 border-[#5642F5] text-[#5642F5] shadow-sm ring-1 ring-indigo-500/20' : 'bg-white border-slate-200 text-slate-400 hover:bg-slate-50'" class="py-2.5 text-xs font-bold border rounded-[10px] flex justify-center items-center gap-1.5 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Pro data
                                        </button>
                                    </div>
                                    <div x-show="inputMode === 'pro'" x-collapse class="border border-dashed border-indigo-300 rounded-xl bg-indigo-50/30 p-6 text-center relative flex items-center justify-center min-h-[120px] mt-3">
                                        <template x-if="!pastedImage">
                                            <div>
                                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-[#5642F5] shadow-sm mx-auto mb-2 border border-slate-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <p class="text-xs font-bold text-slate-600">ចុច <span class="bg-white px-2 py-0.5 rounded shadow-sm border border-slate-200 text-[#5642F5] font-black text-[10px]">Ctrl + V</span> នៅទីនេះ</p>
                                            </div>
                                        </template>
                                        <template x-if="pastedImage">
                                            <div class="relative group w-full flex justify-center">
                                                <img :src="pastedImage" class="max-h-24 object-contain rounded-lg shadow-sm border border-slate-200">
                                                <button type="button" @click="pastedImage = null; document.getElementById('receiptImageBase64').value = ''" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full p-1 shadow-md hover:bg-rose-600 z-20"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="text-[11px] font-black text-slate-500 mb-1.5 block">ឈ្មោះអតិថិជន</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                                            <input type="text" id="customer_name" name="customer_name" placeholder="បញ្ចូលឈ្មោះ..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-[10px] text-sm outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition shadow-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-black text-slate-500 mb-1.5 block">លេខទូរស័ព្ទ</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></div>
                                            <input type="text" id="customer_phone" name="phone" placeholder="ឧ. 012 345 678..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-[10px] text-sm outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition shadow-sm">
                                        </div>
                                    </div>
                                </div>

                                <div x-show="customerType === 'delivery'" x-collapse class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[11px] font-black text-slate-500 mb-1.5 block">ខេត្ត/ក្រុង <span class="text-rose-500">*</span></label>
                                            <select id="province_select" name="province" :required="customerType === 'delivery'" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-[10px] text-sm font-bold text-slate-700 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition shadow-sm">
                                                <option value="">ជ្រើសរើស...</option>
                                                <option value="ភ្នំពេញ">ភ្នំពេញ</option>
<option value="បន្ទាយមានជ័យ">បន្ទាយមានជ័យ</option>
<option value="បាត់ដំបង">បាត់ដំបង</option>
<option value="កំពង់ចាម">កំពង់ចាម</option>
<option value="កំពង់ឆ្នាំង">កំពង់ឆ្នាំង</option>
<option value="កំពង់ស្ពឺ">កំពង់ស្ពឺ</option>
<option value="កំពង់ធំ">កំពង់ធំ</option>
<option value="កំពត">កំពត</option>
<option value="កណ្ដាល">កណ្ដាល</option>
<option value="កែប">កែប</option>
<option value="កោះកុង">កោះកុង</option>
<option value="ក្រចេះ">ក្រចេះ</option>
<option value="មណ្ឌលគិរី">មណ្ឌលគិរី</option>
<option value="ឧត្តរមានជ័យ">ឧត្តរមានជ័យ</option>
<option value="ប៉ៃលិន">ប៉ៃលិន</option>
<option value="ព្រះសីហនុ">ព្រះសីហនុ</option>
<option value="ព្រះវិហារ">ព្រះវិហារ</option>
<option value="ព្រៃវែង">ព្រៃវែង</option>
<option value="ពោធិ៍សាត់">ពោធិ៍សាត់</option>
<option value="រតនគិរី">រតនគិរី</option>
<option value="សៀមរាប">សៀមរាប</option>
<option value="ស្ទឹងត្រែង">ស្ទឹងត្រែង</option>
<option value="ស្វាយរៀង">ស្វាយរៀង</option>
<option value="តាកែវ">តាកែវ</option>
<option value="ត្បូងឃ្មុំ">ត្បូងឃ្មុំ</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[11px] font-black text-slate-500 mb-1.5 block">ទីតាំងលម្អិត</label>
                                            <input type="text" name="address_detail" placeholder="សរសេរទីតាំង..." class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-[10px] text-sm outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition shadow-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-black text-slate-500 mb-1.5 block">ដឹកតាមរយះ <span class="text-rose-500">*</span></label>
                                        <select name="delivery_method" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-[10px] text-sm font-bold text-slate-700 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition shadow-sm">
                                            <option value="VET">វីរៈបុណ្យចាំ (VET)</option>
                                            <option value="J&T">J&T Express</option>
                                            <option value="CAPITOL">កាពីតូល (Capitol)</option>
                                            <option value="DIRECT">ដឹកផ្ទាល់</option>
                                        </select>
                                    </div>
                                </div>

                                <div x-show="customerType === 'walkin'" x-collapse class="h-full">
                                    <div class="bg-emerald-50/50 border border-emerald-200/60 rounded-2xl p-6 w-full flex flex-col items-center justify-center text-center">
                                        <div class="w-10 h-10 bg-white rounded-full border border-emerald-200 text-emerald-50 flex items-center justify-center text-lg mb-3 font-black shadow-sm">i</div>
                                        <h3 class="text-emerald-700 font-black text-sm mb-1">អតិថិជនមកទិញផ្ទាល់ (Walk-in)</h3>
                                        <p class="text-emerald-600/80 text-[10px] font-bold">ប្រព័ន្ធនឹងមិនគិតសេវាដឹកជញ្ជូនឡើយ។</p>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 p-6 md:p-8 flex flex-col space-y-5 bg-white">
                                <div>
                                    <label class="text-[11px] font-black text-slate-500 mb-1.5 flex items-center gap-1">
                                        អ្នកលក់ (SELLER)
                                        <span x-show="customerType === 'delivery'" class="text-rose-500">*</span>
                                        <span x-show="customerType === 'walkin'" class="bg-slate-100 text-slate-400 px-1.5 py-0.5 rounded text-[9px]">(ជម្រើស)</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                        <input type="text" name="seller" :required="customerType === 'delivery'" placeholder="ស្វែងរកឈ្មោះតំណាងលក់..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-[10px] text-sm outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition shadow-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[11px] font-black text-slate-500 mb-1.5 block">កាលបរិច្ឆេទ <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <input type="datetime-local" name="checkout_date" value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-[10px] text-sm font-bold text-slate-700 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition shadow-sm" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[11px] font-black text-slate-500 mb-1.5 block">ការបង់ប្រាក់</label>
                                        <select name="payment_method" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-[10px] text-sm font-bold text-slate-700 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition shadow-sm cursor-pointer">
                                            <option value="cash">💵 សាច់ប្រាក់</option>
                                            <option value="transfer">📱 វេរប្រាក់</option>
                                            <option value="bank">🏦 ធនាគារ</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-black text-slate-500 mb-1.5 block">ស្ថានភាព</label>
                                        <select name="status" class="w-full px-4 py-2.5 bg-white border border-emerald-200 rounded-[10px] text-sm font-black text-emerald-600 outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 transition shadow-sm cursor-pointer">
                                            <option value="paid">✅ ទូទាត់រួចរាល់</option>
                                            <option value="pending">⏳ ជំពាក់</option>
                                        </select>
                                    </div>
                                </div>

                                <div x-show="customerType === 'delivery'" x-collapse>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <label class="text-[11px] font-black text-[#5642F5] block">ថ្លៃដឹកជញ្ជូន (DELIVERY FEE)</label>
                                        <div class="flex bg-slate-100 p-0.5 rounded border border-slate-200">
                                            <button type="button" @click="deliveryCurrency = 'USD'" :class="deliveryCurrency === 'USD' ? 'bg-white shadow-sm text-[#5642F5]' : 'text-slate-500'" class="px-2 py-0.5 text-[9px] font-black rounded-[4px] transition">USD</button>
                                            <button type="button" @click="deliveryCurrency = 'KHR'" :class="deliveryCurrency === 'KHR' ? 'bg-white shadow-sm text-[#5642F5]' : 'text-slate-500'" class="px-2 py-0.5 text-[9px] font-black rounded-[4px] transition">ខ្មែរ (៛)</button>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input type="number" id="delivery_fee" step="0.01" name="delivery_fee" x-model="deliveryFee" placeholder="0.00" class="w-full px-4 pr-16 py-2.5 bg-white border border-[#5642F5] rounded-[10px] text-sm font-bold text-[#5642F5] outline-none focus:ring-4 focus:ring-indigo-100 transition shadow-sm">
                                        <div class="absolute right-4 top-2.5 text-[#5642F5] font-black text-sm" x-text="deliveryCurrency === 'USD' ? '$ (USD)' : '៛ (KHR)'"></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[11px] font-black text-slate-500 mb-1.5 block">ចំណាំបញ្ជាក់ (NOTE)</label>
                                    <textarea name="note" rows="2" placeholder="វាយបញ្ចូលចំណាំ..." class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-[10px] text-sm outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition shadow-sm resize-none"></textarea>
                                </div>
                                <div class="mt-auto pt-4">
                                    <div class="bg-[#15234B] rounded-2xl p-6 text-white flex justify-between items-center shadow-xl">
                                        <div>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">ទំនិញសរុបទាំងអស់</p>
                                            <p class="text-2xl font-black"><span x-text="cartQty">0</span> <span class="text-xs font-normal text-slate-300">មុខ</span></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider mb-1">ទឹកប្រាក់សរុប (GRAND TOTAL)</p>
                                            <p class="text-4xl font-black text-emerald-400 tracking-tight"><span x-text="grandTotal">0.00</span> <span class="text-2xl text-emerald-400/80">$</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-8 py-5 border-t border-slate-100 bg-white flex items-center justify-between shrink-0 rounded-b-[24px]">
                            <label class="hidden md:flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="fast_mode" value="1" class="w-5 h-5 text-[#5642F5] border-slate-300 rounded focus:ring-[#5642F5] transition">
                                <span class="text-[11px] font-black text-slate-500">បញ្ជូនទិន្នន័យលឿន (Fast Mode)</span>
                            </label>

                            <div class="flex gap-3 w-full md:w-auto justify-between md:justify-end">
                                <button type="button" onclick="if(confirm('តើអ្នកពិតជាចង់សម្អាតទិន្នន័យមែនទេ?')){ document.getElementById('checkoutForm').reset(); document.querySelector('[x-data]').__x.$data.deliveryFee = ''; }" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-50 transition shadow-sm">
                                    សម្អាតទិន្នន័យ
                                </button>
                                <button type="submit" class="px-8 py-2.5 bg-[#5642F5] hover:bg-indigo-600 text-white font-black rounded-xl shadow-md transition-all active:scale-95">
                                    បញ្ជាក់ការទូទាត់
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let cart = [];
        let totalAmount = 0;

        function flashSuccess(element) {
            element.classList.add('border-emerald-500', 'ring-4', 'ring-emerald-100', 'bg-emerald-50/50', 'text-emerald-700');
            setTimeout(() => {
                element.classList.remove('border-emerald-500', 'ring-4', 'ring-emerald-100', 'bg-emerald-50/50', 'text-emerald-700');
            }, 2500);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const productCards = document.querySelectorAll('.product-card');
            const searchInput = document.getElementById('searchInput');

            const filterProducts = () => {
                let activeFilter = document.querySelector('.filter-btn.active')?.getAttribute('data-filter').toLowerCase() || 'all';
                if(activeFilter.includes('mask')) activeFilter = 'ម៉ាស';
                if(activeFilter.includes('bundle')) activeFilter = 'ឈុត';
                const searchTerm = searchInput.value.toLowerCase().trim();

                productCards.forEach(card => {
                    const category = (card.getAttribute('data-category') || '').toLowerCase().trim();
                    const searchData = (card.getAttribute('data-search') || '').toLowerCase();
                    const matchesCategory = (activeFilter === 'all' || category === activeFilter || searchData.includes(activeFilter));
                    const matchesSearch = searchData.includes(searchTerm);

                    card.style.display = (matchesCategory && matchesSearch) ? '' : 'none';
                });
            };

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => {
                        b.classList.remove('active', 'bg-slate-800', 'text-white', 'shadow-md', 'bg-indigo-50', 'text-indigo-600', 'bg-cyan-50', 'text-cyan-600', 'bg-rose-50', 'text-rose-500', 'bg-amber-100', 'text-amber-800');
                        b.classList.add('bg-slate-100', 'text-slate-600');
                    });
                    this.classList.add('active', 'shadow-md');
                    this.classList.remove('bg-slate-100', 'text-slate-600');

                    const filterVal = this.getAttribute('data-filter');
                    if(filterVal === 'all') this.classList.add('bg-slate-800', 'text-white');
                    else if(filterVal === 'BIOAQUA') this.classList.add('bg-indigo-50', 'text-indigo-600');
                    else if(filterVal === 'DR+') this.classList.add('bg-cyan-50', 'text-cyan-600');
                    else if(filterVal === 'ម៉ាស(MASK)') this.classList.add('bg-rose-50', 'text-rose-500');
                    else if(filterVal === 'ឈុត (Bundle)') this.classList.add('bg-amber-100', 'text-amber-800');

                    filterProducts();
                });
            });
            if(searchInput) searchInput.addEventListener('input', filterProducts);
        });

        function addToCart(id, name, price) {
            let numPrice = parseFloat(price) || 0;
            let existingItem = cart.find(item => item.id === id);
            if (existingItem) existingItem.qty += 1;
            else cart.push({ id: id, name: name, price: numPrice, qty: 1 });
            renderCart();
        }

        function updateQty(id, change) {
            let itemIndex = cart.findIndex(item => item.id === id);
            if (itemIndex > -1) {
                cart[itemIndex].qty += change;
                if (cart[itemIndex].qty <= 0) cart.splice(itemIndex, 1);
            }
            renderCart();
        }

        function updateItemTotal(id, newTotal) {
            let item = cart.find(item => item.id === id);
            if (item) {
                item.price = parseFloat(newTotal) / item.qty;
            }
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cartItemsContainer');
            const totalDisplay = document.getElementById('cartTotalDisplay');
            const countBadge = document.getElementById('cartCountBadge');

            if (cart.length === 0) {
                container.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-slate-300"><svg class="w-16 h-16 mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg><p class="text-sm font-bold text-slate-400">មិនទាន់មានទំនិញទេ</p></div>`;
                totalDisplay.innerText = "0.00 $";
                countBadge.innerText = "0 មុខ";
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { total: 0, qty: 0 } }));
                return;
            }
            let html = ''; totalAmount = 0; let totalItems = 0;
            cart.forEach(item => {
                let itemTotal = item.price * item.qty;
                totalAmount += itemTotal; totalItems += item.qty;
                html += `
                <div class="bg-white border border-slate-100 p-2.5 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center border border-slate-200 rounded-lg bg-slate-50">
                        <button type="button" onclick="updateQty(${item.id}, -1)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-slate-200 transition font-bold">-</button>
                        <span class="w-7 text-center text-xs font-black text-slate-700">${item.qty}</span>
                        <button type="button" onclick="updateQty(${item.id}, 1)" class="w-7 h-7 flex items-center justify-center text-[#5642F5] hover:bg-indigo-100 transition font-bold">+</button>
                    </div>
                    <div class="flex-1 px-3">
                        <h4 class="text-xs font-black text-slate-800 line-clamp-1">${item.name}</h4>
                        <span class="text-[10px] text-slate-400">${item.price.toFixed(2)}$ / មុខ</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" value="${itemTotal.toFixed(2)}" step="0.01" onchange="updateItemTotal(${item.id}, this.value)" class="w-16 text-right text-sm font-black text-emerald-600 bg-emerald-50 rounded px-1 outline-none focus:ring-1 ring-emerald-300">
                        <span class="text-emerald-600 font-black">$</span>
                        <button type="button" onclick="updateQty(${item.id}, -${item.qty})" class="text-slate-300 hover:text-rose-500 p-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>`;
            });
            container.innerHTML = html;
            totalDisplay.innerText = totalAmount.toFixed(2) + " $";
            countBadge.innerText = totalItems + " មុខ";

            window.dispatchEvent(new CustomEvent('cart-updated', { detail: { total: totalAmount, qty: totalItems } }));
        }
        document.addEventListener("DOMContentLoaded", renderCart);

        function prepareCheckoutData() {
            if (cart.length === 0) {
                alert('សូមជ្រើសរើសទំនិញចូលកន្ត្រកជាមុនសិន!');
                return false;
            }
            document.getElementById('cartDataInput').value = JSON.stringify(cart);
            return true;
        }

        setTimeout(function() {
            let alerts = document.querySelectorAll('#successAlert, #errorAlert');
            alerts.forEach(alert => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);


        // ប្រើ window.toggleBundle ដើម្បឲ្យវាស្គាល់គ្រប់ទីកន្លែង ទោះវេបសាយ Refresh អូតូក៏ដោយ
        window.toggleBundle = function(btn) {
            // ១. បិទប្រអប់ផ្សេងទៀតដែលកំពុងបើកចោល
            document.querySelectorAll('.bundle-popup').forEach(p => {
                p.classList.add('hidden');
            });

            // ២. បើកប្រអប់ដែលស្ថិតនៅជាប់ប៊ូតុងហ្នឹង
            let popup = btn.closest('.bundle-container').querySelector('.bundle-popup');
            if(popup) {
                popup.classList.remove('hidden');
            }
        };

        // ៣. ពេលយក Mouse ទៅចុចក្រៅប្រអប់ ឲ្យវាបិទប្រអប់វិញដោយស្វ័យប្រវត្តិ
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.bundle-container')) {
                document.querySelectorAll('.bundle-popup').forEach(p => p.classList.add('hidden'));
            }
        });

        $(document).ready(function() {

        // 🟢 មុខងារកណ្តាល សម្រាប់បាញ់រូបភាពទៅ AI 🟢
        function sendImageToAI(base64Image) {
            // ៣. ចាប់ផ្តើមបញ្ជូនទិន្នន័យ (AJAX)
                // កូដ AJAX ពេលបញ្ជូនរូបភាពទៅ Server ជោគជ័យ
$.ajax({
    url: '/extract-invoice', // ត្រូវប្រាកដថា Route នេះមានក្នុង web.php
    method: 'POST',
    data: {
        image_base64: base64Image,
        _token: '{{ csrf_token() }}'
    },
    success: function(response) {
        // 🟢 យកទិន្នន័យពី AI មកចាក់ចូលប្រអប់ Form ដោយស្វ័យប្រវត្តិ 🟢
        $('input[name="customer_name"]').val(response.customer_name);
        $('input[name="phone"]').val(response.phone);
        $('input[name="province"]').val(response.province);
        $('input[name="address_detail"]').val(response.address_detail);

        console.log("ទាញទិន្នន័យចូលប្រអប់:", response);
    },
    error: function(xhr) {
        alert("មានបញ្ហា៖ " + (xhr.responseJSON ? xhr.responseJSON.error : "មិនអាចអានទិន្នន័យបាន"));
    }
});
        }

        // ==========================================
        // ទី ១៖ ចាប់យករូបភាព ពេលដែលបងចុច Ctrl + V (Paste) លើអេក្រង់
        // ==========================================
        document.addEventListener('paste', function(e) {
            var items = (e.clipboardData || e.originalEvent.clipboardData).items;
            for (var index in items) {
                var item = items[index];
                if (item.kind === 'file' && item.type.includes('image/')) {
                    var blob = item.getAsFile();
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        // ពេលចាប់បានរូបភាព គឺបញ្ជូនទៅ AI ភ្លាមៗ
                        sendImageToAI(event.target.result);
                    };
                    reader.readAsDataURL(blob);
                }
            }
        });

        // ==========================================
        // ទី ២៖ ចាប់យករូបភាព ពេលចុចជ្រើសរើស File (បើបងមាន Input Type File)
        // (ជំនួស #file_upload_input ដោយ ID ពិតរបស់ Input បង)
        // ==========================================
        $('#file_upload_input').on('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(event) {
                sendImageToAI(event.target.result);
            };
            reader.readAsDataURL(file);
        });

    });

    document.addEventListener('DOMContentLoaded', function() {
    // ចាប់យកប៊ូតុងទាំងអស់
    const filterButtons = document.querySelectorAll('.filter-btn');
    // ចាប់យកកាតទំនិញទាំងអស់ (បងត្រូវប្រាកដថាកាតទំនិញមាន class ឈ្មោះ 'product-card')
    const productItems = document.querySelectorAll('.product-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // ១. ដកពណ៌ប៊ូតុងចាស់ចេញសិន (ដកពណ៌ background ខ្មៅ)
            filterButtons.forEach(btn => {
                btn.classList.remove('bg-slate-800', 'text-white', 'shadow-md');
            });

            // ២. ដាក់ពណ៌ខ្មៅ ឲ្យប៊ូតុងដែលយើងកំពុងចុច
            this.classList.add('bg-slate-800', 'text-white', 'shadow-md');

            // ៣. ចាប់យកឈ្មោះប្រភេទដែលយើងបានចុច (ឧ. ម៉ាស(MASK))
            const filterValue = this.getAttribute('data-filter');

            // ៤. ស្វែងរក និង បង្ហាញទំនិញ
            productItems.forEach(item => {
                // ចាប់យកឈ្មោះប្រភេទពីកាតទំនិញនីមួយៗ
                const itemCategory = item.getAttribute('data-category');

                if (filterValue === 'all') {
                    // បើចុច "ទាំងអស់" -> បង្ហាញទំនិញទាំងអស់
                    item.style.display = 'block';
                } else {
                    // បើឈ្មោះប្រភេទ ទំនិញ ដូចនឹង ប៊ូតុងដែលចុច -> បង្ហាញ
                    if (itemCategory === filterValue) {
                        item.style.display = 'block';
                    } else {
                        // បើខុសគ្នា -> លាក់វា
                        item.style.display = 'none';
                    }
                }
            });
        });
    });
});

function processAiImage(base64Image) {
    console.log("កំពុងវិភាគរូបភាពជាមួយ AI...");
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch('/extract-invoice', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ image_base64: base64Image })
    })
    .then(response => response.json())
    .then(res => {
        console.log("ទាញទិន្នន័យជោគជ័យ:", res);

        if (res.success && res.data) {
            let info = res.data;

            setTimeout(() => {
                // 💡 ក្បាច់ពិសេសទម្លុះ Alpine.js / Livewire / React
                const forceFill = (selector, value) => {
                    if (value === null || value === undefined || value === "") return;

                    let elements = document.querySelectorAll(selector);

                    elements.forEach(el => {
                        // ១. ទម្លុះដោយប្រើ Native HTML Input Setter (បំបែកការចាក់សោរ)
                        let nativeSetter = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, "value").set;
                        if (nativeSetter) {
                            nativeSetter.call(el, value);
                        } else {
                            el.value = value;
                        }

                        // ២. បង្ខំសរសេរជា Attribute បន្ថែម
                        el.setAttribute('value', value);

                        // ៣. បាញ់សញ្ញាប្រាប់ Framework ឱ្យទទួលស្គាល់
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                        el.dispatchEvent(new Event('change', { bubbles: true }));

                        // ៤. លោតពណ៌បៃតងបញ្ជាក់ភាពជោគជ័យ
                        el.style.border = "2px solid #00C853";
                        el.style.backgroundColor = "#e8f5e9"; // ថែមពណ៌ផ្ទៃបន្តិច
                    });
                };

                // ចាប់ផ្តើមបញ្ចូលទិន្នន័យ
                forceFill('input[name="customer_name"], #customer_name', info.customer_name);
                forceFill('input[name="phone"], input[name="customer_phone"], #customer_phone', info.phone);
                forceFill('input[name="delivery_fee"], #delivery_fee', info.delivery_fee);

            }, 500);

        } else {
            alert('មានបញ្ហា៖ ' + (res.error || 'មិនមានទិន្នន័យ'));
        }
    })
    .catch(error => {
        console.error("មានកំហុស:", error);
    });
}
    </script>
</body>
</html>
