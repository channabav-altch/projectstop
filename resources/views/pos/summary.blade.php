<x-app-layout>

    <div class="print:hidden min-h-screen bg-[#F4F7FB] p-6 md:p-10 font-sans">

        <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
            <a href="{{ route('pos.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-[#5642F5] transition font-bold text-sm bg-white px-5 py-2.5 rounded-xl shadow-sm border border-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                ត្រឡប់ទៅផ្ទាំង POS វិញ
            </a>

            <button onclick="window.print()" class="bg-[#1C2C4E] text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-800 transition shadow-md flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print របាយការណ៍
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-gradient-to-br from-[#5642F5] to-indigo-600 rounded-[20px] p-6 shadow-lg shadow-indigo-200 relative overflow-hidden transition-all hover:-translate-y-1">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-[12px] font-black text-indigo-100 uppercase tracking-wider">ចំណូលទំនិញ (PRODUCTS)</h3>
                            <p class="text-[11px] text-indigo-200 mt-1 font-medium">សរុបទឹកប្រាក់ថ្លៃទំនិញ</p>
                        </div>
                        <div class="p-2 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                    </div>
                    <h2 class="text-4xl font-black text-white">{{ number_format($totalProductRevenue ?? 0, 2) }} <span class="text-2xl text-indigo-200">$</span></h2>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#F97316] to-orange-500 rounded-[20px] p-6 shadow-lg shadow-orange-200 relative overflow-hidden transition-all hover:-translate-y-1">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-[12px] font-black text-orange-100 uppercase tracking-wider">ថ្លៃដឹកជញ្ជូន (DELIVERY)</h3>
                            <p class="text-[11px] text-orange-200 mt-1 font-medium">សរុបទឹកប្រាក់ថ្លៃដឹក</p>
                        </div>
                        <div class="p-2 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                    </div>
                    <h2 class="text-4xl font-black text-white">{{ number_format($totalDeliveryFee ?? 0, 2) }} <span class="text-2xl text-orange-200">$</span></h2>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-[20px] p-6 shadow-sm relative overflow-hidden transition-all hover:-translate-y-1">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-slate-50 rounded-full blur-xl"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-[12px] font-black text-slate-400 uppercase tracking-wider">អតិថិជន (CLIENTS)</h3>
                            <p class="text-[11px] text-slate-500 mt-1 font-bold">ចំនួនវិក្កយបត្រសរុប</p>
                        </div>
                        <div class="p-2 bg-emerald-50 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <h2 class="text-4xl font-black text-slate-800">{{ $totalInvoices ?? 0 }} <span class="text-sm text-slate-400 font-bold ml-1">វិក្កយបត្រ</span></h2>
                </div>
            </div>

        </div>
    </div>

    <div class="hidden print:block w-full bg-white text-slate-800 font-sans p-8">

        <div class="text-center mb-6 border-b-[3px] border-[#5642F5] pb-4 mt-4">
            <h1 class="text-2xl font-bold font-khmer mb-1 text-[#1C2C4E]">បញ្ជីវិក្កយបត្រប្រចាំថ្ងៃ</h1>
            <p class="text-xs text-slate-500">កាលបរិច្ឆេទ: {{ now()->timezone('Asia/Phnom_Penh')->format('d-M-Y') }}</p>
        </div>

        <div class="flex justify-between items-center bg-slate-50 border border-slate-200 p-4 rounded-xl mb-6">
            <div class="w-1/3 border-r border-slate-200">
                <p class="text-[11px] text-slate-500 font-bold mb-1">សរុបប្រាក់ទំនិញ</p>
                <p class="text-xl font-black text-emerald-600">{{ number_format($totalProductRevenue ?? 0, 2) }} $</p>
            </div>
            <div class="w-1/3 pl-6 border-r border-slate-200">
                <p class="text-[11px] text-slate-500 font-bold mb-1">ថ្លៃដឹកជញ្ជូនសរុប</p>
                <p class="text-xl font-black text-orange-500">{{ number_format($totalDeliveryFee ?? 0, 2) }} $</p>
            </div>
            <div class="w-1/3 pl-6">
                <p class="text-[11px] text-slate-500 font-bold mb-1">វិក្កយបត្រទាំងអស់</p>
                <p class="text-xl font-black text-[#5642F5]">{{ $totalInvoices ?? 0 }}</p>
            </div>
        </div>

        <table class="w-full text-left text-xs border-collapse border border-slate-200">
            <thead>
                <tr class="border-b-2 border-slate-200 text-slate-500 bg-slate-50">
                    <th class="py-3 px-2 w-8 font-bold border-r border-slate-200">#</th>
                    <th class="py-3 px-2 font-bold border-r border-slate-200">វិក្កយបត្រ & កាលបរិច្ឆេទ</th>
                    <th class="py-3 px-2 font-bold border-r border-slate-200">អតិថិជន & ទំនាក់ទំនង</th>
                    <th class="py-3 px-2 w-[35%] font-bold border-r border-slate-200">មុខទំនិញ</th>
                    <th class="py-3 px-2 text-center font-bold border-r border-slate-200">ថ្លៃដឹក</th>
                    <th class="py-3 px-2 text-right font-bold">សរុប ($)</th>
                </tr>
            </thead>
            <tbody class="text-slate-700">
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <svg class="w-16 h-16 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-sm font-bold text-slate-400">មិនទាន់មានទិន្នន័យលម្អិតសម្រាប់ Print ទេ</p>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <style>
        @media print {
            /* បង្ខំឱ្យពណ៌ Background និងអក្សរចេញមកច្បាស់ ១០០% */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* ដោះសោរ Layout របស់ Laravel កុំឱ្យវារាំងស្ទះទំព័រ Print (នេះជាវ៉ាក់សាំងការពារទំព័រស) */
            body, html, main, #app, .flex-1, .font-sans {
                background-color: white !important;
                height: auto !important;
                overflow: visible !important;
                position: static !important;
            }

            /* លាក់ Navbar ឬ Sidebar (ប្រសិនបើមាន) */
            header, nav, aside {
                display: none !important;
            }

            /* កំណត់ទំហំក្រដាស A4 */
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</x-app-layout>
