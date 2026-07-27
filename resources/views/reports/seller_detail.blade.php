<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>លម្អិតការលក់ - {{ $seller->name ?? 'Admin' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- បន្ថែម Font ដើម្បីឱ្យអក្សរមើលទៅ Professional -->
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; }
    </style>
</head>
<!-- 🟢 ១. Background ប្រណិត (Radial Gradient ជះពន្លឺពីលើកណ្ដាល) -->
<body class="bg-slate-950 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-800 via-slate-950 to-black text-slate-300 min-h-screen p-8 antialiased">

    <div class="max-w-[1200px] mx-auto space-y-6">

        <!-- 🟢 ២. កាតខាងលើ (Glassmorphism ថ្លាៗ មានស្រមោល) -->
        <div class="flex items-center justify-between bg-slate-900/40 backdrop-blur-2xl p-6 rounded-2xl border border-slate-700/50 shadow-2xl shadow-black/50">
            <div>
                <h1 class="text-2xl font-bold text-white mb-1">របាយការណ៍លម្អិត</h1>
                <p class="text-slate-400 text-sm">តំណាងលក់៖ <span class="text-blue-400 font-bold ml-1">{{ $seller->name ?? 'Admin' }}</span></p>
            </div>
            <!-- ប៊ូតុងថយក្រោយ Premium -->
            <a href="/admin-report" class="flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-600/50 transition-all shadow-lg hover:shadow-slate-700/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                ត្រឡប់ក្រោយ
            </a>
        </div>

        <!-- 🟢 ៣. កាតតារាង (Table) -->
        <!-- 🟢 ៣. កាតតារាង (Table) -->
        <div class="bg-slate-900/40 backdrop-blur-2xl rounded-2xl border border-slate-700/50 shadow-2xl shadow-black/50 overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300 whitespace-nowrap">
                <thead class="bg-slate-800/50 border-b border-slate-700/50 text-slate-400 font-bold uppercase text-[11px] tracking-widest">
                    <tr>
                        <th class="px-6 py-5">លេខវិក្កយបត្រ</th>
                        <!-- 🟢 បន្ថែមក្បាលតារាងថ្មី ៣ 🟢 -->
                        <th class="px-6 py-5">អតិថិជន</th>
                        <th class="px-6 py-5">លេខទូរស័ព្ទ</th>
                        <th class="px-6 py-5">ទីតាំង</th>

                        <th class="px-6 py-5">ថ្ងៃខែឆ្នាំលក់</th>
                        <th class="px-6 py-5 text-right">ទឹកប្រាក់សរុប</th>
                        <th class="px-6 py-5 text-center">ស្ថានភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($orders ?? [] as $order)
                        <!-- Hover ពណ៌ស្រទន់ -->
                        <tr class="hover:bg-slate-800/40 transition-colors duration-200">
                            <td class="px-6 py-4 font-bold text-white tracking-wide">
                                #{{ $order->invoice_no ?? $order->id }}
                            </td>

                            <!-- 🟢 បន្ថែមទិន្នន័យអតិថិជនថ្មី ៣ 🟢 -->
                            <td class="px-6 py-4 font-semibold text-blue-400">
                                {{ $order->customer_name ?? 'មិនមានឈ្មោះ' }}
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                {{ $order->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if(!empty($order->customer_address) || !empty($order->province))
                                    <span class="bg-slate-800 border border-slate-700 text-slate-300 px-2.5 py-1 rounded-md text-xs">
                                        {{ $order->customer_address ?? $order->province }}
                                    </span>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-slate-400">
                                {{ date('d-m-Y', strtotime($order->created_at)) }}
                                <span class="text-slate-500 ml-1">{{ date('h:i A', strtotime($order->created_at)) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-emerald-400 text-base">
                                ${{ number_format($order->total_amount ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $status = strtolower($order->status ?? 'paid');
                                @endphp

                                @if($status == 'paid')
                                    <span class="inline-flex items-center justify-center bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> ទូទាត់រួច
                                    </span>
                                @elseif($status == 'pending')
                                    <span class="inline-flex items-center justify-center bg-amber-500/10 text-amber-400 border border-amber-500/20 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> រង់ចាំ
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center bg-rose-500/10 text-rose-400 border border-rose-500/20 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> លុបចោល
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <!-- 🟢 កែ colspan ពី ៤ មក ៧ ព្រោះយើងថែម ៣ Column ទៀត 🟢 -->
                            <td colspan="7" class="px-6 py-24 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-800/50 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-400">មិនទាន់មានទិន្នន័យលក់ទេ!</h3>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- កន្លែងចុចទំព័រ (Pagination) -->
        <div class="mt-4">
            {{ $orders->links() ?? '' }}
        </div>
    </div>

</body>
</html>
