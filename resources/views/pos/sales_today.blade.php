<x-app-layout>
    <!-- Background ពណ៌ប្រផេះស្រាល ត្រជាក់ភ្នែក -->
    <div class="min-h-screen bg-[#F4F7FB] p-4 md:p-8 font-sans -m-4 md:-m-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- ================= HEADER ខាងលើ ================= -->
            <div class="bg-white rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between shadow-sm border border-slate-100 gap-4">
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <a href="{{ route('pos.index') }}" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-[18px] md:text-xl font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        ប្រព័ន្ធរបាយការណ៍លក់ (POS)
                    </h1>
                    <div class="hidden md:flex items-center gap-2 ml-4 px-3 py-1 bg-emerald-50 rounded-full border border-emerald-100">
                        <span class="text-xs font-semibold text-emerald-600">ព័ត៌មានលក់ថ្ងៃនេះ</span>
                        <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm shadow-rose-500/30">Live</span>
                    </div>
                </div>
                <a href="{{ route('pos.index') }}" class="w-full md:w-auto text-center bg-[#5642F5] hover:bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30 transition-all">
                    ទិញទំនិញ Live
                </a>
            </div>

            <!-- ================= FILTER BAR ================= -->
            <div class="bg-white rounded-2xl p-3 flex flex-col lg:flex-row items-center justify-between gap-4 shadow-sm border border-slate-100">

                <!-- មុខងារ Tab ចុចដំណើរការ -->
               @php
    // ចាប់យក status បច្ចុប្បន្ន ដើម្បីប្តូរពណ៌ប៊ូតុងឲ្យចំ
    $currentStatus = request('status', 'all');
@endphp

<div class="flex items-center gap-1 bg-white p-1 rounded-xl border border-slate-200 shadow-sm">

    <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
       class="px-4 py-2 rounded-lg text-[11px] font-black uppercase transition-all {{ request('status', 'all') == 'all' ? 'bg-[#5642F5] text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }}">
        ទាំងអស់
    </a>

    <a href="{{ request()->fullUrlWithQuery(['status' => 'paid']) }}"
       class="px-4 py-2 rounded-lg text-[11px] font-black uppercase transition-all {{ request('status') == 'paid' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }}">
        PAID
    </a>

    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
       class="px-4 py-2 rounded-lg text-[11px] font-black uppercase transition-all {{ request('status') == 'pending' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }}">
        PENDING
    </a>

    <a href="{{ request()->fullUrlWithQuery(['status' => 'CANCELED']) }}"
       class="px-4 py-2 rounded-lg text-[11px] font-black uppercase transition-all {{ request('status') == 'CANCELED' ? 'bg-rose-500 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50' }}">
        CANCELED
    </a>

</div>

                <!-- មុខងារស្វែងរក និងប៊ូតុង Print/PDF -->
                <div class="flex items-center gap-2 w-full lg:w-auto overflow-x-auto">
                    <form action="{{ route('pos.sales_today') }}" method="GET" class="flex items-center gap-3">

    <div class="relative">
        <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}" onchange="this.form.submit()" class="px-4 py-2 border border-slate-200 rounded-xl text-sm font-bold text-slate-600 outline-none focus:border-indigo-500 transition-all cursor-pointer shadow-sm">
    </div>

    <div class="relative">
        <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកឈ្មោះ ឬវិក្កយបត្រ..." class="pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 w-[250px] transition-all shadow-sm">
    </div>

    <button type="submit" class="hidden">Search</button>
</form>
                    <!-- 🔴 កែប៊ូតុងតារាងសរុបឲ្យទៅជា Link បែបនេះ 🔴 -->
<a href="{{ route('pos.summary') }}" class="bg-indigo-50 text-[#5642F5] px-4 py-2 rounded-xl text-sm font-bold border border-indigo-100 hover:bg-indigo-100 transition flex items-center gap-2">
    កាតសរុប
</a>
                    <!-- ប៊ូតុង Print (សម្រាប់ Print ចេញម៉ាស៊ីន) -->
<div class="flex gap-2 print:hidden mb-4 justify-end">
    <a href="{{ url('/pos/print') }}?date={{ request('date') }}&status={{ request('status') }}&search={{ request('search') }}"
       target="_blank"
       class="px-5 py-2.5 bg-slate-800 text-white font-bold text-xs rounded-xl hover:bg-slate-900 transition shadow-lg shadow-slate-200">
        Print
    </a>

    <a href="{{ url('/pos/print') }}?date={{ request('date') }}&status={{ request('status') }}&search={{ request('search') }}&format=pdf"
       target="_blank"
       class="px-5 py-2.5 bg-rose-50 text-rose-500 font-bold text-xs rounded-xl hover:bg-rose-500 hover:text-white transition border border-rose-100 shadow-sm">
        PDF
    </a>
</div>

{{-- <div id="pdf-content" class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">

    <div class="hidden print:block text-center mb-6 pb-4 border-b-2 border-[#5642F5]">
        <h1 class="text-2xl font-black text-[#1C2C4E] mb-1">របាយការណ៍លក់ប្រចាំថ្ងៃ (POS)</h1>
        <p class="text-sm text-slate-500">កាលបរិច្ឆេទ៖ {{ now()->format('d/m/Y') }}</p>
    </div>

    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-slate-200 text-slate-500">
                <th class="py-3">លេខវិក្កយបត្រ</th>
                <th class="py-3">ឈ្មោះអតិថិជន</th>
                <th class="py-3 text-center hide-on-print">សកម្មភាព</th> </tr>
        </thead>
        <tbody>
             </tbody>
    </table>

</div> --}}
                </div>
            </div>

            <div id="pdf-content" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-100 uppercase text-[10px] tracking-wider">
                <tr>
                    <th class="px-4 py-4">លេខវិក្កយបត្រ</th>
                    <th class="px-4 py-4">ឈ្មោះអតិថិជន</th>
                    <th class="px-4 py-4">លេខទូរស័ព្ទ</th>
                    <th class="px-4 py-4">ទីតាំង (ខេត្ត)</th>
                    {{-- <th class="px-4 py-4 text-center">ប្រភេទ</th> --}}
                    <th class="px-4 py-4">ម៉ោងទិញ</th>
                    <th class="px-4 py-4 text-right">ទឹកប្រាក់សរុប</th>
                    <th class="px-4 py-4 text-center">វិធីបង់ប្រាក់</th>
                    <th class="px-4 py-4 text-center">ស្ថានភាព</th>
                    <th class="px-4 py-4 text-center">សកម្មភាព</th>
                </tr>
            </thead>
            @foreach($orders as $order)
<tbody x-data="{ expanded: false }" class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">

    <tr>
        <td class="p-4 text-sm font-black text-[#5642F5]">#{{ $order->invoice_no }}</td>
        <td class="p-4 text-sm font-bold text-slate-700">{{ $order->customer_name }}</td>
        <td class="p-4 text-sm text-slate-600">{{ $order->phone }}</td>
        <td class="p-4 text-sm">
            @if($order->customer_type == 'walkin' || empty($order->province))
                <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-1 rounded-md text-[11px] font-black">
                    🛍️ ទិញផ្ទាល់
                </span>
            @else
                <span class="bg-indigo-50 text-[#5642F5] border border-indigo-100 px-2.5 py-1 rounded-md text-[11px] font-black">
                    📍 {{ $order->province }}
                </span>
            @endif
        </td>
        {{-- <td class="p-4 text-center text-sm">ប្រភេទអីវ៉ាន់</td> --}}
        <td class="p-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y h:i A') }}</td>
        <td class="p-4 text-sm font-black text-emerald-500">${{ number_format($order->total_amount, 2) }}</td>
        <td class="p-4 text-center text-[12px] font-bold">
    @if($order->payment_method == 'សាច់ប្រាក់' || $order->payment_method == 'Cash')
        <span class="text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded-md">💵 សាច់ប្រាក់</span>
    @elseif($order->payment_method == 'ធនាគារ' || $order->payment_method == 'ផ្ទេរប្រាក់' || $order->payment_method == 'Bank')
        <span class="text-blue-600 bg-blue-50 border border-blue-100 px-2 py-1 rounded-md">🏦 ធនាគារ</span>
    @else
        <span class="text-slate-600 bg-slate-50 border border-slate-200 px-2 py-1 rounded-md">{{ $order->payment_method ?? 'មិនបញ្ជាក់' }}</span>
    @endif
</td>
        <td class="px-4 py-4 text-center">
    @if(strtolower($order->status) == 'paid')
        <span class="px-3 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">ទូទាត់រួច (PAID)</span>

    @elseif(strtolower($order->status) == 'pending')
        <span class="px-3 py-1 text-xs font-semibold text-white bg-yellow-500 rounded-full">រង់ចាំ (PENDING)</span>

    @elseif(strtolower($order->status) == 'canceled')
        <span class="px-3 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">លុបចោល (CANCELED)</span>

    @else
        <span class="px-3 py-1 text-xs font-semibold text-white bg-gray-500 rounded-full">{{ $order->status }}</span>
    @endif
</td>
        {{-- <td class="p-4 text-sm"><span class="bg-amber-100 text-amber-700 px-2 py-1 rounded font-bold text-xs">PENDING</span></td> --}}

        <td class="p-4 flex items-center justify-end gap-2">

          <a href="{{ url('pos/edit/' . $order->id) }}" title="កែប្រែ"
   class="p-2 border border-slate-200 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all cursor-pointer">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
</a>

            <button @click="expanded = !expanded" type="button" title="មើលលម្អិត"
                    class="p-2 rounded-lg transition-all border border-slate-200 cursor-pointer"
                    :class="expanded ? 'bg-indigo-50 text-[#5642F5] border-indigo-200' : 'bg-white text-slate-500 hover:bg-slate-50'">
                <svg x-show="!expanded" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                <svg x-show="expanded" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
            </button>

        </td>
    </tr>

    <tr x-show="expanded" x-collapse x-cloak>
        <td colspan="10" class="p-0 bg-slate-50/80 border-t border-slate-100">

            <div id="receipt-card-{{ $order->id }}" class="m-4 bg-white rounded-2xl border border-slate-200 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 max-w-4xl mx-auto relative">

                <div class="action-buttons-row grid grid-cols-5 gap-3 mb-6">

                    <button @click="navigator.clipboard.writeText('{{ $order->invoice_no }}'); alert('បានចម្លងលេខវិក្កយបត្រ: {{ $order->invoice_no }}')" type="button" class="flex flex-col items-center justify-center py-2.5 rounded-xl border border-indigo-100 bg-indigo-50/50 text-indigo-500 hover:bg-indigo-500 hover:text-white hover:shadow-lg hover:shadow-indigo-500/30 transition-all">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        <span class="text-[10px] font-black uppercase tracking-wider">List ID</span>
                    </button>

                    <button onclick="copyOrderDetails('{{ $order->invoice_no }}', '{{ $order->customer_name }}', '{{ $order->total_amount }}')" type="button" class="flex flex-col items-center justify-center py-2.5 rounded-xl border border-emerald-100 bg-emerald-50/50 text-emerald-500 hover:bg-emerald-500 hover:text-white hover:shadow-lg hover:shadow-emerald-500/30 transition-all">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span class="text-[10px] font-black uppercase tracking-wider">Copy</span>
                    </button>

                    <button onclick="downloadReceiptImage('receipt-card-{{ $order->id }}', '{{ $order->invoice_no }}')" type="button" class="flex flex-col items-center justify-center py-2.5 rounded-xl border border-sky-100 bg-sky-50/50 text-sky-500 hover:bg-sky-500 hover:text-white hover:shadow-lg hover:shadow-sky-500/30 transition-all">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <span class="text-[10px] font-black uppercase tracking-wider">Image</span>
                    </button>

                    <form action="{{ url('pos/cancel/' . $order->id) }}" method="POST" class="w-full h-full" onsubmit="return confirm('តើអ្នកពិតជាចង់បោះបង់វិក្កយបត្រនេះមែនទេ? (ទំនិញនឹងត្រូវបូកចូលស្តុកវិញ)');">
                        @csrf
                        <button type="submit" class="w-full h-full flex flex-col items-center justify-center py-2.5 rounded-xl border border-amber-100 bg-amber-50/50 text-amber-500 hover:bg-amber-500 hover:text-white hover:shadow-lg hover:shadow-amber-500/30 transition-all">
                            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-wider">Cancel</span>
                        </button>
                    </form>

                    <form action="{{ url('pos/delete/' . $order->id) }}" method="POST" class="w-full h-full" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបវិក្កយបត្រនេះមែនទេ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full h-full flex flex-col items-center justify-center py-2.5 rounded-xl border border-rose-100 bg-rose-50/50 text-rose-500 hover:bg-rose-500 hover:text-white hover:shadow-lg hover:shadow-rose-500/30 transition-all">
                            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-wider">Delete</span>
                        </button>
                    </form>

                </div>

                <div class="flex justify-between items-end mb-3 px-1">
                    <h5 class="text-[11px] font-black text-slate-400">មុខទំនិញ ({{ $order->orderItems ? $order->orderItems->count() : 0 }})</h5>
                    <span class="text-[10px] font-black text-slate-400">តម្លៃសរុប</span>
                </div>

                <div class="space-y-2">
                    @if($order->orderItems && $order->orderItems->count() > 0)
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between p-3.5 border border-slate-100 rounded-xl hover:border-indigo-100 hover:shadow-sm transition-all bg-white group cursor-default">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-50 rounded-lg border border-slate-100 flex items-center justify-center group-hover:bg-indigo-50 transition-colors">
                                        <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <h6 class="text-[13px] font-black text-slate-700">{{ $item->product->product_name ?? 'ទំនិញទូទៅ' }}</h6>
                                        <p class="text-[11px] font-bold text-[#5642F5] mt-1">{{ $item->qty }} <span class="text-slate-400 text-[10px] mx-0.5">x</span> {{ number_format($item->unit_price ?? 0, 2) }}$</p>
                                    </div>
                                </div>
                                <div class="text-[15px] font-black text-slate-700">
                                    {{ number_format($item->total ?? ($item->qty * $item->unit_price), 2) }}$
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-slate-400 text-xs font-bold border border-dashed border-slate-200 rounded-xl">
                            មិនមានទិន្នន័យទំនិញទេ!
                        </div>
                    @endif
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 flex justify-between items-end px-1">
    <div>
        <p class="text-[10px] font-black text-slate-400 mb-1">ចំណាំអតិថិជន៖</p>
        <p class="text-[11px] font-bold text-slate-600 max-w-[250px]">{{ $order->note ?? 'មិនមានការបញ្ជាក់បន្ថែមទេ' }}</p>
    </div>

    @if($order->customer_type == 'walkin')
        <div class="text-right">
            <p class="text-[10px] font-black text-slate-400 mb-1 uppercase">សេវាដឹកជញ្ជូន</p>
            <p class="text-[12px] font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-md">🛍️ ទិញផ្ទាល់ (គ្មានសេវា)</p>
        </div>
    @elseif($order->delivery_fee > 0)
        <div class="text-right">
            <p class="text-[10px] font-black text-slate-400 mb-1 uppercase">សេវាដឹកជញ្ជូន</p>
            <p class="text-[14px] font-black text-rose-500">{{ number_format($order->delivery_fee, 2) }}$</p>
        </div>
    @endif
</div>

            </div>
        </td>
    </tr>
</tbody>
@endforeach
        </table>
    </div>
</div>

        </div>
    </div>



    <!-- ================= JAVASCRIPT សម្រាប់បញ្ជា TAB ================= -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function changeTab(element) {
            // ១. ទាញយកប៊ូតុង Tab ទាំងអស់
            let tabs = document.querySelectorAll('.tab-btn');

            // ២. ដកពណ៌ស្វាយចេញពីប៊ូតុងទាំងអស់ រួចប្តូរទៅជាពណ៌ប្រផេះ
            tabs.forEach(tab => {
                tab.className = "tab-btn text-slate-500 hover:text-slate-700 px-4 py-2 rounded-lg text-sm font-bold transition-all hover:bg-slate-200/50 whitespace-nowrap";
            });

            // ៣. ដាក់ពណ៌ស្វាយ (Active) ទៅឱ្យប៊ូតុងណាដែលបងទើបតែចុច
            element.className = "tab-btn bg-[#5642F5] text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-all whitespace-nowrap";

            // នៅថ្ងៃអនាគត បងអាចបន្ថែមមុខងារ Filter ទិន្នន័យត្រង់នេះបាន
            // console.log("អ្នកកំពុងមើល Tab:", element.innerText);
        }

        function downloadPDF() {
        // ចាប់យកប្រអប់ដែលមាន id="pdf-content"
        var element = document.getElementById('pdf-content');

        // ការពារក្រែងលោរកមិនឃើញ
        if(!element) {
            alert("រកមិនឃើញទិន្នន័យតារាងទេ! សូមប្រាកដថាបានដាក់ id='pdf-content'");
            return;
        }

        // កំណត់ទំហំ និងគុណភាព PDF
        var opt = {
            margin:       0.3,
            filename:     'POS_Report_' + new Date().getTime() + '.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 }, // Scale 2 ជួយឲ្យអក្សរច្បាស់ល្អមិនព្រាល
            jsPDF:        { unit: 'in', format: 'A4', orientation: 'landscape' } // ដាក់ Landscape (ផ្ដេក) ដើម្បីកុំឲ្យដាច់តារាង
        };

        // លាក់ប៊ូតុង Action (Edit/Delete) ជាបណ្តោះអាសន្នមុនពេលថត
        let actions = document.querySelectorAll('.hide-on-print');
        actions.forEach(el => el.style.display = 'none');

        // បញ្ជាឲ្យទាញយក
        html2pdf().set(opt).from(element).save().then(() => {
            // បង្ហាញប៊ូតុង Action មកវិញក្រោយទាញយករួច
            actions.forEach(el => el.style.display = '');
        });
    }
    // ១. មុខងារសម្រាប់ប៊ូតុង COPY
    function copyOrderDetails(invoiceNo, customerName, totalAmount) {
        // រៀបចំទម្រង់អក្សរសម្រាប់ Copy យកទៅផ្ញើភ្ញៀវ
        const textToCopy = `វិក្កយបត្រ: #${invoiceNo}\nអតិថិជន: ${customerName}\nសរុបទឹកប្រាក់: $${totalAmount}\n---\nសូមអរគុណ!`;

        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('✅ បានចម្លងទិន្នន័យសង្ខេបជោគជ័យ! លោកអ្នកអាច Paste ផ្ញើទៅភ្ញៀវបាន។');
        }).catch(err => {
            alert('មានបញ្ហាក្នុងការចម្លង!');
        });
    }

    // ២. មុខងារសម្រាប់ប៊ូតុង IMAGE (ទាញយកជារូបភាព)
    function downloadReceiptImage(cardId, invoiceNo) {
        const cardElement = document.getElementById(cardId);
        if(!cardElement) return;

        // កំណត់សម្គាល់ប៊ូតុង ដើម្បីលាក់វាសិន ពេលថតរូបកុំឲ្យជាប់ប៊ូតុង
        const actionRow = cardElement.querySelector('.action-buttons-row');
        if (actionRow) actionRow.style.display = 'none';

        // ដំណើរការថតរូប (Screenshot)
        html2canvas(cardElement, {
            scale: 2, // គុណភាពច្បាស់
            backgroundColor: "#ffffff",
            useCORS: true
        }).then(canvas => {
            // បង្កើតតំណភ្ជាប់ដើម្បីទាញយករូប
            let link = document.createElement('a');
            link.download = 'Receipt-' + invoiceNo + '.png'; // ឈ្មោះរូបភាព
            link.href = canvas.toDataURL('image/png');
            link.click();

            // បង្ហាញប៊ូតុងមកវិញ បន្ទាប់ពីថតរូបហើយ
            if (actionRow) actionRow.style.display = 'grid';
        });
    }
    </script>
   <style>
    @media print {
        /* លាក់មុខងារមិនចាំបាច់ (Sidebar, Header, ប៊ូតុង) */
        aside, nav, header, .print\:hidden, .hide-on-print {
            display: none !important;
        }

        /* ដោះសោរឲ្យទំព័រលាតសន្ធឹងពេញក្រដាសល្អ */
        body, html, main, #app, .flex-1 {
            background-color: white !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: auto !important;
            overflow: visible !important;
            position: static !important;
        }

        /* តម្រឹមតារាងឲ្យស្អាតពេល Print */
        #pdf-content {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
        }
    }
</style>
</x-app-layout>
