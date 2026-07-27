<x-app-layout>
    {{-- មុខងារការពារ Error៖ បើ Backend អត់បញ្ជូនទិន្នន័យមក វានឹងទាញយកដោយខ្លួនឯង --}}
    @php
        $totalEarning = $totalEarning ?? 0;
        $totalProducts = $totalProducts ?? \App\Models\Product::count();
        $totalStock = $totalStock ?? \App\Models\Product::sum('qty');
        $lowStock = $lowStock ?? \App\Models\Product::where('qty', '<', 10)->count();
        $percentage = $percentage ?? ($totalProducts > 0 ? 100 : 0);
        $monthlySales = $monthlySales ?? [];
    @endphp

    <div class="min-h-screen bg-[#0A1122] p-2 md:p-4 font-sans -m-2 md:-m-4">

        <div class="max-w-7xl mx-auto space-y-6">

            <div class="flex justify-between items-center mb-2">
                <h1 class="text-[22px] font-semibold text-white">Dashboard</h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-gradient-to-br from-indigo-900/90 via-slate-900 to-slate-900 border border-indigo-500/30 rounded-xl p-5 shadow-2xl relative overflow-hidden group hover:border-indigo-500/60 transition-all flex flex-col justify-between h-28">
                    <div class="absolute -right-6 -top-6 w-28 h-28 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>

                    <div class="flex items-center justify-between mb-1 relative z-10">
                        <span class="text-[10px] font-black text-indigo-300 tracking-wider uppercase">ចំណូលសរុប (REVENUE)</span>
                        <div class="w-8 h-8 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-black text-xs shadow-md">
                            $
                        </div>
                    </div>

                    <div class="relative z-10">
                        <h2 class="text-2xl font-black text-white tracking-tight">${{ number_format($todayRevenue ?? 0, 2) }}</h2>
                        <span class="text-[9px] font-bold text-emerald-400 flex items-center gap-1 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> ធ្វើបច្ចុប្បន្នភាពថ្ងៃនេះ
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_rgba(0,0,0,0.04)] flex flex-col justify-between h-28">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-400 text-sm font-medium">មុខទំនិញសរុប</span>
                        <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div class="text-slate-700 text-3xl font-normal">{{ $totalProducts }}</div>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_rgba(0,0,0,0.04)] flex flex-col justify-between h-28">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-400 text-sm font-medium">ចំនួនស្តុកសរុប</span>
                        <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-800">
                        {{ number_format($totalStock ?? 0) }}
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_rgba(0,0,0,0.04)] flex flex-col justify-between h-28">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-400 text-sm font-medium">ទំនិញជិតអស់</span>
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="text-rose-500 text-3xl font-normal">{{ $lowStock }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] h-[280px] flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <span class="text-slate-500 font-medium">របាយការណ៍ប្រចាំខែ (ឆ្នាំ {{ date('Y') }})</span>
        <div class="flex gap-3">
            <div class="flex items-center gap-1"><span class="w-2 h-2 bg-[#1C325B] rounded-full"></span><span class="text-[10px] text-slate-500">ចំណូល</span></div>
            <div class="flex items-center gap-1"><span class="w-2 h-2 bg-[#F59E0B] rounded-full"></span><span class="text-[10px] text-slate-500">ចំណាយ</span></div>
        </div>
    </div>

    @if($hasData ?? false)
        <div class="flex-1 border-b border-slate-200 relative flex items-end justify-between px-2 md:px-6 pb-0">
            <div class="absolute w-full h-full flex flex-col justify-between top-0 left-0">
                <div class="border-t border-slate-100 w-full h-1/4"></div>
                <div class="border-t border-slate-100 w-full h-1/4"></div>
                <div class="border-t border-slate-100 w-full h-1/4"></div>
                <div class="border-t border-slate-100 w-full h-1/4"></div>
            </div>

            @foreach($monthlyData as $data)
            @php
                $revHeight = $maxAmount > 0 ? ($data['revenue'] / $maxAmount) * 100 : 0;
                $expHeight = $maxAmount > 0 ? ($data['expense'] / $maxAmount) * 100 : 0;
            @endphp

            <div class="relative z-10 flex items-end gap-1 w-[6%] h-full group cursor-pointer" title="ចំណូល: ${{ number_format($data['revenue'], 2) }} | ចំណាយ: ${{ number_format($data['expense'], 2) }}">

                <div class="w-1/2 bg-[#1C325B] rounded-t-sm transition-all group-hover:opacity-80" style="height: {{ $revHeight }}%;"></div>

                <div class="w-1/2 bg-[#F59E0B] rounded-t-sm transition-all group-hover:opacity-80" style="height: {{ $expHeight }}%;"></div>

            </div>
            @endforeach
        </div>
        <div class="flex justify-between px-1 mt-2 text-[9px] text-slate-400 font-bold uppercase">
            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
        </div>
    @else
        <div class="flex-1 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl">
            <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="text-slate-400 text-sm font-medium">មិនទាន់មានទិន្នន័យលក់នៅឡើយទេ</span>
        </div>
    @endif
</div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

                        <div class="md:col-span-3 bg-white rounded-xl p-5 shadow-[0_2px_10px_rgba(0,0,0,0.04)] h-52 relative flex flex-col">
    <div class="space-y-1 mb-2 relative z-10">
        <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span> <span class="text-[10px] text-slate-500">ចំណូលពីការលក់ (៧ថ្ងៃចុងក្រោយ)</span></div>
        <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#1C325B]"></span> <span class="text-[10px] text-slate-500">ការចំណាយ (៧ថ្ងៃចុងក្រោយ)</span></div>
    </div>

    <div id="areaChart" class="w-full flex-1 -ml-3 -mb-3"></div>
</div>

                        <div class="md:col-span-2 bg-white rounded-xl p-5 shadow-[0_2px_10px_rgba(0,0,0,0.04)] h-52 flex flex-col justify-center">

    <div class="flex justify-between items-center mb-4 px-1">
        <span class="text-xs font-black text-[#1C325B] uppercase">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
    </div>

    <div class="grid grid-cols-7 gap-1 text-center text-[10px] text-slate-400 font-bold mb-2">
        <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
    </div>

    <div class="grid grid-cols-7 gap-y-1.5 gap-x-1 text-center text-[11px] font-bold text-slate-700">
        @php
            $now = \Carbon\Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();
            $startOfWeek = $startOfMonth->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
            $today = $now->day;
        @endphp

        {{-- ប្រអប់ទទេរសម្រាប់ថ្ងៃដើមខែ --}}
        @for($i = 0; $i < $startOfWeek; $i++)
            <div></div>
        @endfor

        {{-- លោតលេខថ្ងៃនៃខែបច្ចុប្បន្ន --}}
        @for($day = 1; $day <= $endOfMonth->day; $day++)
            @if($day == $today)
                <div class="bg-[#1C325B] text-white rounded-md shadow-sm flex items-center justify-center h-5 w-6 mx-auto">
                    {{ $day }}
                </div>
            @else
                <div class="flex items-center justify-center h-5 w-6 mx-auto hover:bg-slate-100 rounded-md cursor-pointer transition-all">
                    {{ $day }}
                </div>
            @endif
        @endfor
    </div>
</div>

                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_rgba(0,0,0,0.04)] h-full flex flex-col items-center">

                    <div class="mt-4 mb-8">
                        <div class="w-36 h-36 rounded-full flex items-center justify-center relative shadow-inner" style="background: conic-gradient(#F59E0B 0% {{ $percentage }}%, #1C325B {{ $percentage }}% 100%);">
                            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-2xl font-semibold text-slate-700">{{ $percentage }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full space-y-3 flex-1 mt-4">
                        <div class="text-center text-xs font-bold text-slate-400 mb-2 uppercase">ទំនិញទើបបញ្ចូលថ្មី</div>
                        @forelse(\App\Models\Product::latest()->take(3)->get() as $item)
                            <div class="border-b border-slate-100 pb-2 text-center text-sm text-slate-600 font-medium truncate">
                                {{ $item->product_name }}
                            </div>
                        @empty
                            <div class="text-center text-xs text-slate-400 italic mt-4">... ទទេស្អាត ...</div>
                        @endforelse
                    </div>

                    {{-- <a href="{{ route('products.create') ?? '#' }}" class="mt-6 w-full text-center bg-[#F59E0B] hover:bg-orange-500 text-white py-2.5 rounded-full text-xs font-bold shadow-md shadow-orange-500/20 transition">
                        ➕ បញ្ចូលទំនិញថ្មី
                    </a> --}}

                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: [{
                    name: 'ចំណូល',
                    data: @json($chartRevenues) // ទិន្នន័យចំណូលពី Controller
                }, {
                    name: 'ចំណាយ',
                    data: @json($chartExpenses) // ទិន្នន័យចំណាយពី Controller
                }],
                chart: {
                    type: 'area',
                    height: '100%',
                    toolbar: { show: false }, // លាក់ប៊ូតុង Menu ផ្សេងៗ
                    fontFamily: 'inherit'
                },
                colors: ['#F59E0B', '#1C325B'], // ពណ៌ទឹកក្រូច និង ខៀវក្រម៉ៅ
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.6,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false }, // លាក់លេខលើក្រាហ្វិក
                stroke: { curve: 'smooth', width: 2.5 }, // ធ្វើឱ្យខ្សែកោងរលោងស្អាត
                xaxis: {
                    categories: @json($chartDates), // ឈ្មោះថ្ងៃពី Controller
                    labels: { show: false },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    tooltip: { enabled: false }
                },
                yaxis: { show: false },
                grid: { show: false },
                legend: { show: false }, // លាក់ Legend ព្រោះយើងបានធ្វើដោយខ្លួនឯងខាងលើហើយ
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return "$" + val.toFixed(2);
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#areaChart"), options);
            chart.render();
        });
    </script>
</x-app-layout>
