<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto p-8 bg-slate-800/80 backdrop-blur-xl text-white rounded-3xl shadow-[0_0_40px_rgba(0,0,0,0.3)] border border-slate-700/50">

            <div class="flex items-center justify-between mb-8 pb-5 border-b border-slate-700/50">
                <h2 class="text-2xl font-extrabold flex items-center gap-3">
                    <span class="text-2xl drop-shadow-md">📝</span>
                    <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                        កែសម្រួលទិន្នន័យស្តុកចូល
                    </span>
                </h2>
                <a href="{{ url('/stock-purchase') }}" class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 rounded-lg text-sm font-bold transition-all border border-slate-600">
    ថយក្រោយ
</a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-400 rounded-r-xl shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-500/10 border-l-4 border-rose-500 text-rose-400 rounded-r-xl shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            @if(isset($purchase) && $purchase)
                <form action="{{ route('stock-purchase.update', $purchase->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <!-- 🟢 ដូរ Value មកប្រើ invoice_no 🟢 -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2 tracking-wide">លេខកូដ / លេខវិក្កយបត្រ (SKU / Ref)</label>
                            <input type="text" name="sku" value="{{ $purchase->invoice_no ?? '' }}" class="w-full px-4 py-3.5 bg-slate-900/50 border border-slate-600 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 text-slate-100 transition-all shadow-inner placeholder-slate-500" placeholder="បញ្ចូលលេខកូដទីនេះ..." required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- 🟢 ដូរ Value មកប្រើ qty 🟢 -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-2 tracking-wide">ចំនួន (Quantity)</label>
                                <input type="number" step="any" name="quantity" value="{{ $purchase->qty ?? '' }}" class="w-full px-4 py-3.5 bg-slate-900/50 border border-slate-600 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 text-slate-100 transition-all shadow-inner" required>
                            </div>

                            <!-- 🟢 គណនាតម្លៃសរុប (qty * unit_price) ដើម្បីបង្ហាញក្នុង Form 🟢 -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-2 tracking-wide">តម្លៃសរុប (Total Price)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-slate-400">$</span>
                                    <input type="number" step="any" name="total_price" value="{{ isset($purchase->qty, $purchase->unit_price) ? ($purchase->qty * $purchase->unit_price) : '' }}" class="w-full pl-8 pr-4 py-3.5 bg-slate-900/50 border border-slate-600 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 text-slate-100 transition-all shadow-inner" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-8 mt-4 border-t border-slate-700/50">
                        <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold rounded-xl text-sm transition-all transform hover:-translate-y-1 shadow-[0_4px_14px_0_rgba(107,33,168,0.39)] hover:shadow-[0_6px_20px_rgba(107,33,168,0.23)] flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            រក្សាទុកការកែប្រែ
                        </button>
                    </div>
                </form>
            @else
                <div class="p-6 bg-rose-500/10 border border-rose-500 text-rose-300 rounded-2xl text-center shadow-inner">
                    រកមិនឃើញទិន្នន័យសម្រាប់ធ្វើការកែសម្រួលទេ។ សូមពិនិត្យមើលលេខ ID ឡើងវិញ!
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
