<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <title>របាយការណ៍សរុប - {{ $selectedDate }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Hanuman', serif; background-color: white; }
        .page-break { page-break-before: always; }
        @media print {
            @page { size: A4; margin: 10mm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .break-inside-avoid { page-break-inside: avoid; }
            * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .no-print-shadow {
            box-shadow: none !important;
        }
        }
        tfoot td { border-top: 2px solid #e2e8f0; background-color: #f8fafc; }
    </style>
</head>
<body class="text-slate-800 p-8 text-sm">

    <!-- ========================================== -->
    <!-- ក្បាលរបាយការណ៍ -->
    <!-- ========================================== -->
    <div class="text-center mb-6">
        <h1 class="text-2xl bg-[#00b4d8] font-bold text-[#5642F5] mb-2 font-['Hanuman'] tracking-wider">របាយការណ៍ប្រចាំថ្ងៃសរុប</h1>
        <p class="text-sm text-slate-500">កាលបរិច្ឆេទ៖ {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</p>
        <div class="w-full h-1 bg-[#5642F5] mt-4 mb-6 mx-auto rounded-full"></div>
    </div>

    <!-- ប្រអប់សរុបទាំង ៤ ខាងលើ -->
    <div class="grid grid-cols-4 gap-6 mb-8 break-inside-avoid">
        <div class="border border-slate-200 rounded-2xl p-4 text-center bg-slate-800">
            <p class="text-xs font-bold text-slate-500 mb-1">សរុបប្រាក់ចំណូល ($)</p>
            <p class="text-2xl font-black text-emerald-500">{{ number_format($totalRevenue, 2) }} $</p>
        </div>
        <div class="border border-slate-200 rounded-2xl p-4 text-center bg-blue-950">
            <p class="text-xs font-bold text-slate-500 mb-1">ស្ថានភាពវិក្កយបត្រ</p>
            <p class="text-2xl font-black text-amber-500 uppercase">{{ $status == 'all' ? 'ទាំងអស់' : $status }}</p>
        </div>
        <div class="border border-slate-200 rounded-2xl p-4 text-center bg-amber-800">
            <p class="text-xs font-bold text-slate-500 mb-1">វិក្កយបត្រសរុប</p>
            <p class="text-2xl font-black text-[#5642F5]">{{ $totalInvoices }}</p>
        </div>
        <div class="border border-rose-200 rounded-2xl p-4 text-center bg-rose-800">
            <p class="text-xs font-bold text-rose-500 mb-1">សរុបចំណាយ ($)</p>
            <p class="text-2xl font-black text-rose-600">{{ number_format($totalExpenses ?? 0, 2) }} $</p>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- បញ្ជីវិក្កយបត្រសរុបរួម (ទំព័រទី១) -->
    <!-- ========================================== -->
    <div class="mt-4 ">
        <h2 class="text-lg text-amber-500 bg-[#5642F5] font-bold mb-2 font-['Hanuman'] border-l-4 border-[#5642F5] pl-3">បញ្ជីវិក្កយបត្រសរុបថ្ងៃនេះ</h2>
        <table class="w-full text-left border-collapse border border-slate-200">
            <thead>
                <tr class="bg-slate-100 text-slate-600 text-[11px] uppercase">
                    <th class="p-3 border border-slate-200 w-8 text-center">#</th>
                    <th class="p-3 border border-slate-200 w-32">វិក្កយបត្រ</th>
                    <th class="p-3 border border-slate-200">អតិថិជន & ទីតាំង</th>
                    <th class="p-3 border border-slate-200">មុខទំនិញ</th>
                    <th class="p-3 border border-slate-200 text-center w-24">ថ្លៃដឹក</th>
                    <th class="p-3 border border-slate-200 text-right w-32">សរុប ($)</th>
                </tr>
            </thead>
            <tbody class="text-[12px]">
                @forelse($orders as $key => $order)
                <tr class="align-top">
                    <td class="p-3 border border-slate-200 font-bold text-center">{{ $key + 1 }}</td>
                    <td class="p-3 border border-slate-200 font-black text-slate-700">{{ $order->invoice_no }}</td>

                    <!-- 🟢 កន្លែងបង្ហាញឈ្មោះ លេខទូរស័ព្ទ ខេត្ត និងតំបន់ 🟢 -->
                    <td class="p-3 border border-slate-200">
                        <div class="font-bold text-slate-800 text-[13px]">{{ $order->customer_name ?? 'អតិថិជនទូទៅ' }}</div>
                        <div class="text-[11px] text-slate-600 font-medium">{{ $order->phone ?? 'គ្មានលេខទូរស័ព្ទ' }}</div>
                        <div class="text-[11px] text-slate-500 mt-1 flex items-start gap-1">
                            <span class="text-[12px]">📍</span>
                            <span>
                                {{ $order->province ?? 'មិនមានខេត្ត' }}
                                <!-- ចាប់យកតំបន់ បើមាន -->
                                @if(!empty($order->district))
                                    - {{ $order->district }}
                                @elseif(!empty($order->address))
                                    - {{ $order->address }}
                                @endif
                            </span>
                        </div>
                    </td>

                    <td class="p-3 border border-slate-200 text-[11px]">
                        <ul class="list-disc pl-3 text-slate-600">
                        @foreach($order->orderItems as $item)
                            <li>{{ $item->product?->product_name ?? 'ទំនិញ' }} <span class="font-bold text-[#5642F5]">({{ $item->qty }})</span></li>
                        @endforeach
                        </ul>
                    </td>
                    <td class="p-3 border border-slate-200 text-center font-bold text-orange-500">
                        {{ $order->delivery_fee > 0 ? number_format($order->delivery_fee, 2).' $' : '-' }}
                    </td>
                    <td class="p-3 border border-slate-200 text-right font-black text-emerald-600">
    {{ number_format($order->total_amount - $order->delivery_fee, 2) }} $
</td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-4 text-center text-slate-400">មិនមានវិក្កយបត្រទេ</td></tr>
                @endforelse
            </tbody>

            <!-- ជួរបូកសរុបខាងក្រោម (ត្រង់ជួរគ្នា ១០០%) -->
            <tfoot class="bg-slate-50">
                <tr>
                    <td colspan="3" class="p-3 border border-slate-200"></td>
                    <td class="p-3 border border-slate-200 text-right font-bold text-slate-700">សរុបប្រាក់ និងថ្លៃដឹក៖</td>
                    <td class="p-3 border border-slate-200 text-center font-black text-orange-600">{{ number_format($orders->sum('delivery_fee'), 2) }} $</td>
                    <td class="p-3 border border-slate-200 text-right font-black text-[#5642F5]">
    {{ number_format($totalRevenue - $orders->sum('delivery_fee'), 2) }} $
</td>
                </tr>
            </tfoot>
        </table>
<!-- ======================================================= -->
<!-- 🟢 ប្រអប់សរុបប្រាក់ និងថ្លៃដឹកជញ្ជូន (ប្រើ Variable ពិតប្រាកដ ១០០%) 🟢 -->
<!-- ======================================================= -->
<div class="mt-6 rounded-lg overflow-hidden border border-slate-700 bg-[#1C2434] text-slate-200 p-5 shadow-lg no-print-shadow break-inside-avoid">

    <!-- ចំណងជើងផ្នែកសរុប -->
    <div class="text-[#38BDF8] font-bold text-sm mb-3 flex items-center gap-1.5">
        <span>📊</span> សេចក្តីសរុបការលក់ និងការដឹកជញ្ជូន
    </div>

    <!-- ព័ត៌មានលម្អិតនៃតម្លៃ -->
    <div class="space-y-2.5 text-sm border-b border-dashed border-slate-600 pb-4 mb-4">

        <!-- សរុបតម្លៃទំនិញលក់បាន (យកសរុបរួម ដក ថ្លៃដឹក) -->
        <div class="flex justify-between items-center">
            <span class="text-slate-400">• សរុបតម្លៃទំនិញសុទ្ធ (Subtotal):</span>
            <span class="font-bold text-white text-base">
                {{ number_format($totalRevenue - $orders->sum('delivery_fee'), 2) }} $
            </span>
        </div>

        <!-- ថ្លៃដឹកជញ្ជូនសរុប -->
        <div class="flex justify-between items-center text-[#F87171]">
            <span class="font-bold flex items-center gap-1">
                🚚 ថ្លៃដឹកជញ្ជូនសរុប (Delivery Fee):
            </span>
            <span class="font-bold text-base">
                {{ number_format($orders->sum('delivery_fee'), 2) }} $
            </span>
        </div>
    </div>

    <!-- សរុបទឹកប្រាក់រួមចុងក្រោយ (Grand Total) -->
    <div class="bg-[#24303F] p-4 rounded-lg flex justify-between items-center shadow-inner">
        <span class="text-[#FBBF24] font-bold text-base flex items-center gap-1">
            💰 តម្លៃទំនិញសរុប និងថ្លៃដឹកសរុប:
        </span>
        <span class="text-[#FBBF24] font-bold text-2xl">
            {{ number_format($totalRevenue, 2) }} $
        </span>
    </div>
</div>
    </div>
<!-- ========================================== -->
<!-- ១. បញ្ជីតាមខេត្ត -->
<!-- ========================================== -->
@if(isset($provinceOrders) && $provinceOrders->count() > 0)
<div class="mt-8 break-inside-avoid page-break">
    <h2 class="text-base bg-blue-500 font-bold text-[#5642F5] mb-2 font-['Hanuman'] border-l-4 border-blue-500 pl-3">១. បញ្ជីផ្ញើតាមខេត្ត (Province)</h2>
    <table class="w-full text-left border-collapse border border-slate-200">
        <thead>
            <tr class="bg-blue-50 text-slate-600 text-[11px] uppercase">
                <th class="p-3 border border-slate-200 w-32">វិក្កយបត្រ</th>
                <th class="p-3 border border-slate-200">អតិថិជន (ទីតាំង & ទូរស័ព្ទ)</th>
                <th class="p-3 border border-slate-200 text-center w-24">ថ្លៃដឹក</th>
                <th class="p-3 border border-slate-200 text-right w-32">សរុបប្រាក់ ($)</th>
            </tr>
        </thead>
        <tbody class="text-[12px]">
            @foreach($provinceOrders as $order)
            <tr>
                <td class="p-3 border border-slate-200 font-black">#{{ $order->invoice_no }}</td>
                <td class="p-3 border border-slate-200">
                    <div class="font-bold">{{ $order->customer_name }} <span class="text-slate-500 font-normal">({{ $order->phone ?? 'គ្មានលេខ' }})</span></div>
                    <div class="text-[11px] text-slate-500 mt-0.5">📍 {{ $order->province }} {{ !empty($order->district) ? '- '.$order->district : (!empty($order->address) ? '- '.$order->address : '') }}</div>
                </td>
                <td class="p-3 border border-slate-200 text-center font-bold text-orange-500">
                    {{ $order->delivery_fee > 0 ? number_format($order->delivery_fee, 2).' $' : '-' }}
                </td>
                <!-- 🟢 កែប្រែ៖ បង្ហាញតែតម្លៃទំនិញសុទ្ធ (ដកថ្លៃដឹកចេញ) 🟢 -->
                <td class="p-3 border border-slate-200 text-right font-bold text-emerald-600">
                    {{ number_format($order->total_amount - $order->delivery_fee, 2) }} $
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-slate-50">
            <tr>
                <td colspan="2" class="p-3 border border-slate-200 text-right font-bold text-slate-700">សរុបតម្លៃទំនិញសុទ្ធ (ខេត្ត)៖</td>
                <td class="p-3 border border-slate-200 text-center font-black text-orange-600">{{ number_format($provinceOrders->sum('delivery_fee'), 2) }} $</td>
                <!-- 🟢 កែប្រែ៖ ផលបូកខាងក្រោមតារាង បង្ហាញតែតម្លៃទំនិញសុទ្ធ 🟢 -->
                <td class="p-3 border border-slate-200 text-right font-black text-blue-600">
                    {{ number_format($provinceOrders->sum('total_amount') - $provinceOrders->sum('delivery_fee'), 2) }} $
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-4 rounded-lg overflow-hidden border border-slate-700 bg-[#1C2434] text-slate-200 p-5 shadow-lg no-print-shadow break-inside-avoid">
        <div class="text-[#38BDF8] font-bold text-sm mb-3 flex items-center gap-1.5">
            <span>📊</span> សេចក្តីសរុបការលក់ - ផ្ញើតាមខេត្ត (Province)
        </div>
        <div class="space-y-2.5 text-sm border-b border-dashed border-slate-600 pb-4 mb-4">
            <div class="flex justify-between items-center">
                <span class="text-slate-400">• សរុបតម្លៃទំនិញសុទ្ធ (Subtotal):</span>
                <span class="font-bold text-white text-base">
                    {{ number_format($provinceOrders->sum('total_amount') - $provinceOrders->sum('delivery_fee'), 2) }} $
                </span>
            </div>
            <div class="flex justify-between items-center text-[#F87171]">
                <span class="font-bold flex items-center gap-1">
                    🚚 ថ្លៃដឹកជញ្ជូនសរុប (Delivery Fee):
                </span>
                <span class="font-bold text-base">
                    {{ number_format($provinceOrders->sum('delivery_fee'), 2) }} $
                </span>
            </div>
        </div>
        <div class="bg-[#24303F] p-4 rounded-lg flex justify-between items-center shadow-inner">
            <span class="text-[#FBBF24] font-bold text-base flex items-center gap-1">
                💰 តម្លៃទំនិញសរុប និងថ្លៃដឹកសរុប:
            </span>
            <span class="text-[#FBBF24] font-bold text-2xl">
                {{ number_format($provinceOrders->sum('total_amount'), 2) }} $
            </span>
        </div>
    </div>
</div>
@endif

<!-- ========================================== -->
<!-- ២. បញ្ជីភ្នំពេញ -->
<!-- ========================================== -->
@if(isset($ppOrders) && $ppOrders->count() > 0)
<div class="mt-8 break-inside-avoid page-break">
    <h2 class="text-base bg-emerald-500 font-bold text-[#5642F5] mb-2 font-['Hanuman'] border-l-4 border-sky-500 pl-3">២. បញ្ជីភ្នំពេញ / អ្នកដឹក (Phnom Penh)</h2>
    <table class="w-full text-left border-collapse border border-slate-200">
        <thead>
            <tr class="bg-sky-50 text-slate-600 text-[11px] uppercase">
                <th class="p-3 border border-slate-200 w-32">វិក្កយបត្រ</th>
                <th class="p-3 border border-slate-200">អតិថិជន (ទីតាំង & ទូរស័ព្ទ)</th>
                <th class="p-3 border border-slate-200 text-center w-24">ថ្លៃដឹក</th>
                <th class="p-3 border border-slate-200 text-right w-32">សរុបប្រាក់ ($)</th>
            </tr>
        </thead>
        <tbody class="text-[12px]">
            @foreach($ppOrders as $order)
            <tr>
                <td class="p-3 border border-slate-200 font-black">#{{ $order->invoice_no }}</td>
                <td class="p-3 border border-slate-200">
                    <div class="font-bold">{{ $order->customer_name }} <span class="text-slate-500 font-normal">({{ $order->phone ?? 'គ្មានលេខ' }})</span></div>
                    <div class="text-[11px] text-slate-500 mt-0.5">📍 {{ $order->province }} {{ !empty($order->district) ? '- '.$order->district : (!empty($order->address) ? '- '.$order->address : '') }}</div>
                </td>
                <td class="p-3 border border-slate-200 text-center font-bold text-orange-500">
                    {{ $order->delivery_fee > 0 ? number_format($order->delivery_fee, 2).' $' : '-' }}
                </td>
                <!-- 🟢 កែប្រែ៖ បង្ហាញតែតម្លៃទំនិញសុទ្ធ (ដកថ្លៃដឹកចេញ) 🟢 -->
                <td class="p-3 border border-slate-200 text-right font-bold text-emerald-600">
                    {{ number_format($order->total_amount - $order->delivery_fee, 2) }} $
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-slate-50">
            <tr>
                <td colspan="2" class="p-3 border border-slate-200 text-right font-bold text-slate-700">សរុបតម្លៃទំនិញសុទ្ធ (ភ្នំពេញ)៖</td>
                <td class="p-3 border border-slate-200 text-center font-black text-orange-600">{{ number_format($ppOrders->sum('delivery_fee'), 2) }} $</td>
                <!-- 🟢 កែប្រែ៖ ផលបូកខាងក្រោមតារាង បង្ហាញតែតម្លៃទំនិញសុទ្ធ 🟢 -->
                <td class="p-3 border border-slate-200 text-right font-black text-sky-600">
                    {{ number_format($ppOrders->sum('total_amount') - $ppOrders->sum('delivery_fee'), 2) }} $
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-4 rounded-lg overflow-hidden border border-slate-700 bg-[#1C2434] text-slate-200 p-5 shadow-lg no-print-shadow break-inside-avoid">
        <div class="text-[#38BDF8] font-bold text-sm mb-3 flex items-center gap-1.5">
            <span>📊</span> សេចក្តីសរុបការលក់ - ភ្នំពេញ / អ្នកដឹក (Phnom Penh)
        </div>
        <div class="space-y-2.5 text-sm border-b border-dashed border-slate-600 pb-4 mb-4">
            <div class="flex justify-between items-center">
                <span class="text-slate-400">• សរុបតម្លៃទំនិញសុទ្ធ (Subtotal):</span>
                <span class="font-bold text-white text-base">
                    {{ number_format($ppOrders->sum('total_amount') - $ppOrders->sum('delivery_fee'), 2) }} $
                </span>
            </div>
            <div class="flex justify-between items-center text-[#F87171]">
                <span class="font-bold flex items-center gap-1">
                    🚚 ថ្លៃដឹកជញ្ជូនសរុប (Delivery Fee):
                </span>
                <span class="font-bold text-base">
                    {{ number_format($ppOrders->sum('delivery_fee'), 2) }} $
                </span>
            </div>
        </div>
        <div class="bg-[#24303F] p-4 rounded-lg flex justify-between items-center shadow-inner">
            <span class="text-[#FBBF24] font-bold text-base flex items-center gap-1">
                💰 តម្លៃទំនិញសរុប និងថ្លៃដឹកសរុប:
            </span>
            <span class="text-[#FBBF24] font-bold text-2xl">
                {{ number_format($ppOrders->sum('total_amount'), 2) }} $
            </span>
        </div>
    </div>
</div>
@endif

<!-- ========================================== -->
<!-- ៣. បញ្ជីទិញផ្ទាល់ (Walk-in) -->
<!-- ========================================== -->
@if(isset($directOrders) && $directOrders->count() > 0)
<div class="mt-8 break-inside-avoid page-break">
    <h2 class="text-base bg-emerald-500 font-bold text-[#5642F5] mb-2 font-['Hanuman'] border-l-4 border-teal-500 pl-3">៣. បញ្ជីទិញផ្ទាល់ (Walk-in)</h2>
    <table class="w-full text-left border-collapse border border-slate-200">
        <thead>
            <tr class="bg-teal-50 text-slate-600 text-[11px] uppercase">
                <th class="p-3 border border-slate-200 w-32">វិក្កយបត្រ</th>
                <th class="p-3 border border-slate-200">អតិថិជន</th>
                <th class="p-3 border border-slate-200 text-center w-24">ថ្លៃដឹក</th>
                <th class="p-3 border border-slate-200 text-right w-32">សរុបប្រាក់ ($)</th>
            </tr>
        </thead>
        <tbody class="text-[12px]">
            @foreach($directOrders as $order)
            <tr>
                <td class="p-3 border border-slate-200 font-black">#{{ $order->invoice_no }}</td>
                <td class="p-3 border border-slate-200">
                    <div class="font-bold">{{ $order->customer_name ?? 'អតិថិជនទូទៅ' }} <span class="text-slate-500 font-normal">({{ $order->phone ?? '' }})</span></div>
                </td>
                <td class="p-3 border border-slate-200 text-center font-bold text-orange-500">
                    {{ $order->delivery_fee > 0 ? number_format($order->delivery_fee, 2).' $' : '-' }}
                </td>
                <!-- 🟢 កែប្រែ៖ បង្ហាញតែតម្លៃទំនិញសុទ្ធ (ដកថ្លៃដឹកចេញ) 🟢 -->
                <td class="p-3 border border-slate-200 text-right font-bold text-emerald-600">
                    {{ number_format($order->total_amount - $order->delivery_fee, 2) }} $
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-slate-50">
            <tr>
                <td colspan="2" class="p-3 border border-slate-200 text-right font-bold text-slate-700">សរុបតម្លៃទំនិញសុទ្ធ (ទិញផ្ទាល់)៖</td>
                <td class="p-3 border border-slate-200 text-center font-black text-orange-600">{{ number_format($directOrders->sum('delivery_fee'), 2) }} $</td>
                <!-- 🟢 កែប្រែ៖ ផលបូកខាងក្រោមតារាង បង្ហាញតែតម្លៃទំនិញសុទ្ធ 🟢 -->
                <td class="p-3 border border-slate-200 text-right font-black text-teal-600">
                    {{ number_format($directOrders->sum('total_amount') - $directOrders->sum('delivery_fee'), 2) }} $
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-4 rounded-lg overflow-hidden border border-slate-700 bg-[#1C2434] text-slate-200 p-5 shadow-lg no-print-shadow break-inside-avoid">
        <div class="text-[#38BDF8] font-bold text-sm mb-3 flex items-center gap-1.5">
            <span>📊</span> សេចក្តីសរុបការលក់ - ទិញផ្ទាល់ (Walk-in)
        </div>
        <div class="space-y-2.5 text-sm border-b border-dashed border-slate-600 pb-4 mb-4">
            <div class="flex justify-between items-center">
                <span class="text-slate-400">• សរុបតម្លៃទំនិញសុទ្ធ (Subtotal):</span>
                <span class="font-bold text-white text-base">
                    {{ number_format($directOrders->sum('total_amount') - $directOrders->sum('delivery_fee'), 2) }} $
                </span>
            </div>
            <div class="flex justify-between items-center text-[#F87171]">
                <span class="font-bold flex items-center gap-1">
                    🚚 ថ្លៃដឹកជញ្ជូនសរុប (Delivery Fee):
                </span>
                <span class="font-bold text-base">
                    {{ number_format($directOrders->sum('delivery_fee'), 2) }} $
                </span>
            </div>
        </div>
        <div class="bg-[#24303F] p-4 rounded-lg flex justify-between items-center shadow-inner">
            <span class="text-[#FBBF24] font-bold text-base flex items-center gap-1">
                💰 តម្លៃទំនិញសរុប និងថ្លៃដឹកសរុប:
            </span>
            <span class="text-[#FBBF24] font-bold text-2xl">
                {{ number_format($directOrders->sum('total_amount'), 2) }} $
            </span>
        </div>
    </div>
</div>
@endif
    <!-- ========================================== -->
    <!-- ៤. បញ្ជីចំណាយប្រចាំថ្ងៃ (កាត់ចូលទំព័រថ្មី) -->
    <!-- ========================================== -->
    @if(isset($expenses) && $expenses->count() > 0)
    <div class="mt-8 break-inside-avoid page-break">
        <h2 class="text-base bg-red-500 font-bold text-[#5642F5] mb-2 font-['Hanuman'] border-l-4 border-rose-500 pl-3">៤. បញ្ជីចំណាយប្រចាំថ្ងៃ (Expenses)</h2>
        <table class="w-full text-left border-collapse border border-slate-200">
            <thead>
                <tr class="bg-rose-50 text-slate-600 text-[11px] uppercase">
                    <th class="p-3 border border-slate-200 w-10 text-center">#</th>
                    <th class="p-3 border border-slate-200">ការបរិយាយចំណាយ</th>
                    <th class="p-3 border border-slate-200 text-right">ប្រាក់ចំណាយ ($)</th>
                </tr>
            </thead>
            <tbody class="text-[12px]">
                @foreach($expenses as $key => $expense)
                <tr>
                    <td class="p-3 border border-slate-200 font-bold text-center">{{ $key + 1 }}</td>
                    <td class="p-3 border border-slate-200">{{ $expense->title ?? $expense->description ?? 'ចំណាយទូទៅ' }}</td>
                    <td class="p-3 border border-slate-200 text-right font-bold text-rose-500">{{ number_format($expense->amount, 2) }} $</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="p-3 border border-slate-200 text-right font-bold text-slate-700">សរុបចំណាយទាំងអស់៖</td>
                    <td class="p-3 border border-slate-200 text-right font-black text-rose-600">{{ number_format($totalExpenses, 2) }} $</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    <!-- ========================================== -->
    <!-- ៥. បញ្ជីទំនិញលក់ចេញសរុប (កាត់ចូលទំព័រថ្មី) -->
    <!-- ========================================== -->
    @if(isset($itemsSold) && $itemsSold->count() > 0)
    <div class="mt-8 break-inside-avoid page-break">
        <h2 class="text-base bg-emerald-500 font-bold text-[#5642F5] mb-2 font-['Hanuman'] border-l-4 border-purple-500 pl-3">៥. បញ្ជីទំនិញលក់ចេញសរុប (Items Sold)</h2>
        <table class="w-full text-left border-collapse border border-slate-200">
            <thead>
                <tr class="bg-purple-50 text-slate-600 text-[11px] uppercase">
                    <th class="p-3 border border-slate-200">ឈ្មោះទំនិញ</th>
                    <th class="p-3 border border-slate-200 text-center">ចំនួន QTY</th>
                    <th class="p-3 border border-slate-200 text-right">ទឹកប្រាក់សរុប ($)</th>
                </tr>
            </thead>
            <tbody class="text-[12px]">
                @foreach($itemsSold as $item)
                <tr>
                    <td class="p-3 border border-slate-200">{{ $item->product->product_name ?? 'N/A' }}</td>
                    <td class="p-3 border border-slate-200 text-center font-bold">{{ $item->total_qty }}</td>
                    <td class="p-3 border border-slate-200 text-right font-bold text-emerald-600">{{ number_format($item->total_amount, 2) }} $</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="p-3 border border-slate-200 text-right font-bold text-slate-700">សរុបចំនួនទំនិញចេញ៖</td>
                    <td class="p-3 border border-slate-200 text-center font-black text-purple-600">{{ $itemsSold->sum('total_qty') }}</td>
                    <td class="p-3 border border-slate-200 text-right font-black text-[#5642F5]">{{ number_format($itemsSold->sum('total_amount'), 2) }} $</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    <!-- ========================================== -->
    <!-- 🟢 សរុបរួមចុងក្រោយ (កាត់ចូលទំព័រថ្មីចុងក្រោយ) 🟢 -->
    <!-- ========================================== -->
    <div class="mt-12 mb-8 bg-blue-500 break-inside-avoid page-break border-4 border-[#5642F5] rounded-xl p-6 bg-slate-50 shadow-sm">
        <h2 class="text-xl bg-slate-800 font-black text-amber-500 mb-4 text-center border-b-2 border-slate-200 pb-2">សរុបរួមប្រចាំថ្ងៃ (Grand Total)</h2>

        <div class="flex justify-between items-center mb-2">
            <span class="text-base font-bold text-emerald-600">ចំណូលពីការលក់សរុប (Total Revenue):</span>
            <span class="text-lg font-black text-emerald-600">{{ number_format($totalRevenue, 2) }} $</span>
        </div>

        <div class="flex justify-between items-center mb-2">
            <span class="text-base font-bold text-rose-600">ចំណាយសរុប (Total Expenses):</span>
            <span class="text-lg font-black text-rose-600"> - {{ number_format($totalExpenses ?? 0, 2) }} $</span>
        </div>

        <div class="flex justify-between items-center mt-4 pt-4 border-t-2 border-dashed border-slate-300">
            <span class="text-lg font-black text-[#5642F5] uppercase">ប្រាក់ចំណេញសុទ្ធ (Net Profit):</span>
            <span class="text-2xl font-black {{ ($totalRevenue - ($totalExpenses ?? 0)) >= 0 ? 'text-[#5642F5]' : 'text-red-600' }}">
                {{ number_format($totalRevenue - ($totalExpenses ?? 0), 2) }} $
            </span>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ប៊ូតុង និង Script -->
    <!-- ========================================== -->
    <div class="mt-12 text-center no-print">
        <button onclick="window.print()" class="px-8 py-3 bg-[#5642F5] text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">ព្រីនរបាយការណ៍</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('format') === 'pdf') {
                const printBtn = document.querySelector('.no-print');
                if(printBtn) printBtn.style.display = 'none';

                var opt = {
                    margin: 10,
                    filename: 'របាយការណ៍-{{ $selectedDate }}.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                    pagebreak: { mode: ['css', 'legacy'] }
                };

                html2pdf().set(opt).from(document.body).save().then(() => setTimeout(() => window.close(), 1500));
            } else {
                window.print();
            }
        }
    </script>
</body>
</html>
