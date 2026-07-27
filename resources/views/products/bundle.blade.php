<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>បង្កើតកញ្ចប់ឈុតថ្មី - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gradient-to-br from-[#070b19] via-[#0B132B] to-[#070b19] text-slate-300 min-h-screen p-4 md:p-6 lg:p-8">

    <!-- 🎯 Form ក្តោបទាំងមូល សម្រាប់បញ្ជូនទិន្នន័យ (enctype សម្រាប់បញ្ជូនរូបភាព) -->
    <form action="{{ route('bundle.store') }}" method="POST" enctype="multipart/form-data" class="max-w-[1600px] mx-auto" id="bundleForm">
        @csrf

        <!-- ចំណងជើងធំ និងប៊ូតុងត្រឡប់ក្រោយ -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') ?? '#' }}" class="w-10 h-10 rounded-xl bg-[#15234b]/60 border border-[#1C2C4E] flex items-center justify-center text-slate-400 hover:text-white transition-all shadow-sm hover:bg-[#1C2C4E]">
                    ⬅
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-black text-white flex items-center gap-2">
                        <span class="text-cyan-400">🎁</span> បង្កើតកញ្ចប់ឈុតថ្មី
                    </h2>
                    <p class="text-slate-400 text-xs mt-1 uppercase tracking-wider">បំពេញព័ត៌មានខាងឆ្វេង និងជ្រើសរើសទំនិញខាងស្តាំ</p>
                </div>
            </div>

            <!-- ប៊ូតុង Save ធំនៅខាងស្តាំលើ (ជម្រើសទី២) -->
            <button type="submit" class="hidden md:flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-cyan-500/30 transition-all transform hover:-translate-y-1">
                💾 រក្សាទុកឈុតថ្មី
            </button>
        </div>

        <!-- គ្រោងឆ្អឹង ៣ ជួរ (3 Columns Layout) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ជួរទី១៖ ប្រអប់បញ្ចូលព័ត៌មានឈុត (Bundle Details) -->
            <div class="bg-[#15234b]/60 backdrop-blur-md border border-cyan-500/30 rounded-3xl p-5 md:p-6 flex flex-col shadow-[0_0_20px_rgba(6,182,212,0.1)] h-fit relative">

                <h3 class="font-bold text-white flex items-center gap-2 mb-6 pb-4 border-b border-[#1C2C4E]">
                    <span class="text-cyan-400">📝</span> ព័ត៌មានឈុត (Bundle Details)
                </h3>

                <div class="space-y-5">
                    <!-- ឈ្មោះឈុត -->
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-2">ឈ្មោះឈុត <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="ឧ. ឈុតថែរក្សាស្បែកមុខ..."
                               class="w-full px-4 py-3 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all">
                    </div>

                    <!-- លេខកូដ (SKU) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-2">លេខកូដឈុត (SKU)</label>
                        <input type="text" name="sku" placeholder="ឧ. BUNDLE-001"
                               class="w-full px-4 py-3 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all">
                    </div>

                    <!-- តម្លៃលក់ -->
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-2">តម្លៃលក់ចេញ ($) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="sale_price" required placeholder="0.00"
                               class="w-full px-4 py-3 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-sm text-cyan-400 font-bold focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all">
                    </div>

                    <!-- រូបភាពបញ្ជូល (Image Upload) ជាមួយ Preview ស្អាតៗ -->
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-2">រូបភាពឈុត (Image)</label>

                        <!-- កន្លែងចុចរើសរូប -->
                        <div class="relative w-full h-32 bg-[#0B132B] border-2 border-dashed border-[#1C2C4E] rounded-xl hover:border-cyan-500/50 transition-all flex items-center justify-center overflow-hidden group cursor-pointer" onclick="document.getElementById('imageInput').click()">

                            <!-- រូបដែលបានរើសនឹងលោតមកទីនេះ -->
                            <img id="imagePreview" src="#" class="w-full h-full object-cover hidden absolute inset-0 z-10 mix-blend-screen">

                            <!-- ទីធ្លាពេលអត់ទាន់រើសរូប -->
                            <div id="uploadPlaceholder" class="text-center z-0 group-hover:scale-110 transition-transform">
                                <span class="text-2xl block mb-1">📸</span>
                                <span class="text-[10px] font-bold text-slate-500">ចុចទីនេះដើម្បីជ្រើសរើសរូបភាព</span>
                            </div>

                            <!-- Input File (លាក់បាំង) -->
                            <input type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(event)" class="hidden">
                        </div>
                    </div>

                    <!-- ប៊ូតុង Save (សម្រាប់ទូរស័ព្ទដៃ) -->
                    <button type="submit" class="w-full md:hidden flex items-center justify-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-6 py-3.5 rounded-xl font-bold shadow-lg mt-4">
                        💾 រក្សាទុកឈុតថ្មី
                    </button>
                </div>
            </div>

            <!-- ជួរទី២៖ បញ្ជីទំនិញក្នុងស្តុក (Available Products) -->
            <div class="bg-[#15234b]/60 backdrop-blur-md border border-[#1C2C4E] rounded-3xl p-5 md:p-6 flex flex-col h-[650px] shadow-2xl">
                <div class="flex flex-col gap-4 mb-5">
                    <h3 class="font-bold text-white flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="text-indigo-400">📦</span> បញ្ជីទំនិញក្នុងស្តុក</span>
                    </h3>
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">🔍</span>
                        <input type="text" id="searchProduct" placeholder="ស្វែងរកទំនិញ..."
                               class="w-full pl-11 pr-4 py-3 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-inner">
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto hide-scroll pr-2 space-y-3" id="availableProductsList">

                    <!-- ឧទាហរណ៍ទំនិញ (ពេលបង Loop ទាញពី Database) -->
                    <div class="product-item flex items-center justify-between p-3.5 bg-[#0B132B] border border-[#1C2C4E] rounded-2xl hover:border-cyan-500/50 transition-all group" data-search="bio mask ម៉ាស">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-[#15234b] flex items-center justify-center font-black text-cyan-400 border border-[#1C2C4E]">
                                BIO
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-200">Bio Mask</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">SKU: BIO-001 | ទុន: <span class="font-bold text-slate-300">$0.77</span></p>
                            </div>
                        </div>
                        <!-- ប៊ូតុងបញ្ជូនទិន្នន័យទៅ JS -->
                        <button type="button" onclick="addBundleItem(1, 'Bio Mask', 0.77, 'BIO')" class="w-9 h-9 rounded-xl bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500 hover:text-white flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>

                    <div class="product-item flex items-center justify-between p-3.5 bg-[#0B132B] border border-[#1C2C4E] rounded-2xl hover:border-cyan-500/50 transition-all group" data-search="dr+ coca">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-[#15234b] flex items-center justify-center font-black text-blue-400 border border-[#1C2C4E]">
                                DR
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-200">Dr+ Coca</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">SKU: DR-055 | ទុន: <span class="font-bold text-slate-300">$0.70</span></p>
                            </div>
                        </div>
                        <button type="button" onclick="addBundleItem(2, 'Dr+ Coca', 0.70, 'DR')" class="w-9 h-9 rounded-xl bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500 hover:text-white flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>

                </div>
            </div>

            <!-- ជួរទី៣៖ ទំនិញក្នុងឈុតដែលបានរើស (Selected Bundle Items) -->
            <div class="bg-[#15234b]/60 backdrop-blur-md border border-[#1C2C4E] rounded-3xl p-5 md:p-6 flex flex-col h-[650px] shadow-[0_0_30px_rgba(0,0,0,0.5)]">

                <h3 class="font-bold text-white flex items-center justify-between mb-5">
                    <span class="flex items-center gap-2"><span class="text-cyan-400">🛒</span> ទំនិញក្នុងឈុត</span>
                    <span class="bg-cyan-500 text-white text-[11px] px-2.5 py-1 rounded-lg font-black" id="selectedCountBadge">
                        0 មុខ
                    </span>
                </h3>

                <div class="flex-1 overflow-y-auto hide-scroll pr-2 space-y-3" id="bundleItemsContainer">
                    <!-- ពេលអត់ទំនិញ -->
                    <div id="emptyBundle" class="h-full flex flex-col items-center justify-center text-slate-500">
                        <span class="text-5xl mb-3 opacity-50 block">📥</span>
                        <p class="text-sm font-bold text-center">មិនទាន់មានទំនិញទេ<br><span class="text-[11px] font-normal mt-1">សូមចុចសញ្ញា + ពីប្រអប់កណ្តាល</span></p>
                    </div>
                </div>

                <!-- ផ្នែកតម្លៃដើមសរុប -->
                <div class="mt-4 pt-5 border-t border-[#1C2C4E] flex items-center justify-between bg-[#0B132B] p-5 rounded-2xl shadow-inner">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">តម្លៃទុនសរុបនៃឈុត៖</span>
                    <span class="text-2xl font-black text-cyan-400">$<span id="totalCostDisplay">0.00</span></span>

                    <!-- តម្លៃនេះនឹងបញ្ជូនទៅ Database ពេលចុច Save -->
                    <input type="hidden" name="total_cost" id="hiddenTotalCost" value="0">
                </div>

            </div>

        </div>
    </form>

    <!-- វេទមន្ត JS សម្រាប់បញ្ជាភាពរលូន -->
    <script>
        // ១. មុខងារ Preview រូបភាព
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('uploadPlaceholder');

            reader.onload = function() {
                preview.src = reader.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }

            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        // ២. មុខងារទាញទំនិញចូលឈុត (Cart Logic)
        let bundleItems = [];

        function addBundleItem(id, name, price, shortText) {
            const container = document.getElementById('bundleItemsContainer');
            const emptyState = document.getElementById('emptyBundle');

            // ឆែកមើលក្រែងលោមានហើយ
            const existingItem = bundleItems.find(item => item.id === id);

            if (existingItem) {
                // បើមានហើយ បូកចំនួន +១
                const qtyInput = document.getElementById(`qty_${id}`);
                qtyInput.value = parseInt(qtyInput.value) + 1;
                updateTotalCost();
                return;
            }

            if (emptyState) emptyState.style.display = 'none';

            bundleItems.push({ id: id, name: name, price: parseFloat(price) });

            // ⚠️ ចំណុចសំខាន់៖ ខ្ញុំបានដាក់ <input type="hidden"> ដើម្បីឱ្យ Form ស្គាល់ទំនិញពេលចុច Save
            const itemHtml = `
                <div id="bundle_item_${id}" class="flex items-center justify-between p-3 bg-[#0B132B] border border-cyan-500/20 rounded-2xl relative overflow-hidden">

                    <!-- Hidden Inputs សម្រាប់បញ្ជូនទៅ Laravel Backend -->
                    <input type="hidden" name="items[${id}][product_id]" value="${id}">

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#15234b] flex items-center justify-center font-black text-cyan-400 text-xs border border-[#1C2C4E]">
                            ${shortText}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-200">${name}</h4>
                            <p class="text-[10px] text-slate-400">ទុន: <span class="text-cyan-400">$${price}</span></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Input បញ្ចូលចំនួន ទំនិញក្នុងមួយឈុត -->
                        <div class="flex items-center bg-[#15234b] border border-[#1C2C4E] rounded-lg p-1">
                            <input type="number" name="items[${id}][qty]" id="qty_${id}" value="1" min="1" onchange="updateTotalCost()" onkeyup="updateTotalCost()" class="w-10 bg-transparent text-center text-xs font-bold text-white focus:outline-none">
                        </div>

                        <!-- ប៊ូតុងលុប -->
                        <button type="button" onclick="removeBundleItem(${id})" class="text-rose-500 hover:bg-rose-500/20 p-2 rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', itemHtml);
            updateCount();
            updateTotalCost();
        }

        function removeBundleItem(id) {
            document.getElementById(`bundle_item_${id}`).remove();
            bundleItems = bundleItems.filter(item => item.id !== id);

            if (bundleItems.length === 0) {
                document.getElementById('emptyBundle').style.display = 'flex';
            }

            updateCount();
            updateTotalCost();
        }

        function updateCount() {
            document.getElementById('selectedCountBadge').innerText = bundleItems.length + " មុខ";
        }

        function updateTotalCost() {
            let totalCost = 0;
            bundleItems.forEach(item => {
                const qtyInput = document.getElementById(`qty_${item.id}`);
                const qty = qtyInput ? parseInt(qtyInput.value) || 0 : 0;
                totalCost += (item.price * qty);
            });

            document.getElementById('totalCostDisplay').innerText = totalCost.toFixed(2);
            document.getElementById('hiddenTotalCost').value = totalCost.toFixed(2);
        }

        // ៣. មុខងារ Search
        document.getElementById('searchProduct').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const items = document.querySelectorAll('.product-item');

            items.forEach(item => {
                if (item.getAttribute('data-search').includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
