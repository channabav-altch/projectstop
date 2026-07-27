<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>បញ្ជីឈ្មោះអតិថិជន - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#070b19] text-slate-300 min-h-screen p-4 md:p-8">

    <div class="max-w-[1600px] mx-auto space-y-8">

        <!-- 🔴 Header ថ្មីដែលមានប៊ូតុងត្រឡប់ក្រោយ (Back Button) 🔴 -->
        <div class="flex flex-col sm:flex-row justify-between items-center bg-[#15234b]/80 p-6 rounded-3xl border border-[#1C2C4E] gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <!-- ប៊ូតុង Back -->
                <a href="javascript:history.back()" class="w-12 h-12 flex items-center justify-center bg-[#0B132B] hover:bg-slate-800 border border-[#1C2C4E] rounded-2xl text-slate-400 hover:text-white transition-all shadow-sm" title="ត្រឡប់ក្រោយ">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-xl md:text-2xl font-black text-white">👥 បញ្ជីឈ្មោះអតិថិជន</h1>
            </div>


            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-500 transition-all shadow-lg shadow-blue-500/20 whitespace-nowrap">
                + បន្ថែមអតិថិជន
            </button>
        </div>

        <!-- Customer Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($customers ?? [] as $cus)
            <div class="bg-[#15234b] rounded-3xl border border-[#1C2C4E] overflow-hidden shadow-xl">

                <!-- ពណ៌ Header ខាងលើ -->
                <div class="h-24 {{ $cus->type == 'VIP' ? 'bg-gradient-to-br from-amber-400 to-orange-600' : 'bg-gradient-to-br from-blue-600 to-indigo-800' }}"></div>

                <!-- កន្លែងបង្ហាញរូបភាព (កាត់មូលស្អាតជានិច្ច) -->
                <div class="flex justify-center -mt-12 relative z-10">
                    <div class="w-24 h-24 bg-[#0B132B] rounded-full border-[6px] border-[#15234b] flex items-center justify-center overflow-hidden shadow-lg">
                        @if($cus->image)
                            <img src="{{ asset('storage/' . $cus->image) }}" class="w-full h-full object-cover" alt="Profile">
                        @else
                            <span class="text-3xl">{{ $cus->type == 'VIP' ? '🌟' : '👤' }}</span>
                        @endif
                    </div>
                </div>

                <!-- ព័ត៌មានអតិថិជនខាងក្រោម -->
                <div class="p-6 text-center">
                    <h3 class="text-lg font-black text-white mb-2">{{ $cus->name }}</h3>

                    <!-- ប្រភេទ VIP ឬ ទូទៅ -->
                    <div class="mb-5">
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $cus->type == 'VIP' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                            {{ $cus->type == 'VIP' ? 'VIP' : 'GENERAL' }}
                        </span>
                    </div>

                    <!-- ប្រអប់លេខទូរស័ព្ទ និងទឹកប្រាក់ -->
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center justify-center gap-2 bg-[#0B132B] border border-[#1C2C4E] rounded-xl py-2 text-xs font-mono text-slate-300">
                            <span class="text-blue-400">📞</span> {{ $cus->phone ?? 'គ្មានលេខទូរស័ព្ទ' }}
                        </div>
                        <div class="flex items-center justify-center gap-2 bg-[#0B132B] border border-[#1C2C4E] rounded-xl py-2 text-xs font-mono font-bold text-emerald-400">
                            <span>💰</span> ${{ number_format($cus->total_spent, 2) }}
                        </div>
                    </div>

                    <!-- ប៊ូតុងសកម្មភាព -->
                    <div class="flex gap-2">
                        <button onclick="openViewModal({{ json_encode($cus) }})" class="flex-1 bg-[#0B132B] hover:bg-slate-700 border border-[#1C2C4E] text-slate-300 py-2.5 rounded-xl text-xs font-bold transition-all">
                            មើលលម្អិត
                        </button>
                        <button onclick="openEditModal({{ json_encode($cus) }})" class="w-11 h-11 flex items-center justify-center bg-[#0B132B] hover:bg-blue-600 border border-[#1C2C4E] text-amber-400 hover:text-white rounded-xl transition-all">
                            ✏️
                        </button>
                        <!-- ប៊ូតុងលុបអតិថិជន -->
<form action="{{ route('customers.destroy', $cus->id) ?? '#' }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបអតិថិជននេះមែនទេ?');" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="px-3 py-1.5 bg-rose-500/25 text-rose-500 hover:bg-rose-500 hover:text-white rounded-lg text-xs font-bold transition-all border border-rose-500/50">
        លុប
    </button>
</form>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-span-full py-10 text-center text-slate-500">មិនទាន់មានទិន្នន័យទេ!</div>
            @endforelse
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 1. MODAL: បន្ថែមអតិថិជនថ្មី -->
    <!-- ============================================== -->
    <div id="addModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 p-4">
        <div class="bg-[#0B132B] border border-[#1C2C4E] p-8 rounded-3xl w-full max-w-md">
            <h2 class="text-white font-black text-xl mb-6">➕ បន្ថែមអតិថិជនថ្មី</h2>
            <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <input type="file" name="image" accept="image/*" class="w-full p-3 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-500/20 file:text-blue-400 hover:file:bg-blue-500/30">
                    <input type="text" name="name" placeholder="ឈ្មោះអតិថិជន" required class="w-full p-4 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-white outline-none">
                    <input type="text" name="phone" placeholder="លេខទូរស័ព្ទ" class="w-full p-4 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-white outline-none">
                    <input type="number" step="0.01" name="total_spent" placeholder="ទឹកប្រាក់ទិញសរុប ($)" class="w-full p-4 mt-4 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-white outline-none">
                    <select name="type" class="w-full p-4 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-white outline-none">
                        <option value="ទូទៅ">អតិថិជនទូទៅ</option>
                        <option value="VIP">អតិថិជន VIP</option>
                    </select>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="w-full p-4 bg-slate-800 rounded-xl text-white font-bold">បោះបង់</button>
                    <button type="submit" class="w-full p-4 bg-blue-600 rounded-xl text-white font-bold">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. MODAL: កែប្រែអតិថិជន (Edit) -->
    <!-- ============================================== -->
    <div id="editModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 p-4">
        <div class="bg-[#0B132B] border border-[#1C2C4E] p-8 rounded-3xl w-full max-w-md">
            <h2 class="text-white font-black text-xl mb-6">✏️ កែប្រែព័ត៌មាន</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <input type="file" name="image" accept="image/*" class="w-full p-3 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-sm text-slate-400 file:bg-blue-500/20 file:text-blue-400">
                    <input type="text" id="edit_name" name="name" required class="w-full p-4 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-white">
                    <input type="text" id="edit_phone" name="phone" class="w-full p-4 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-white">
                    <input type="number" step="0.01" id="edit_total_spent" name="total_spent" placeholder="ទឹកប្រាក់ទិញសរុប ($)" class="w-full p-4 mt-4 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-white outline-none">
                    <select id="edit_type" name="type" class="w-full p-4 bg-[#15234b] border border-[#1C2C4E] rounded-xl text-white">
                        <option value="ទូទៅ">អតិថិជនទូទៅ</option>
                        <option value="VIP">អតិថិជន VIP</option>
                    </select>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="w-full p-4 bg-slate-800 rounded-xl text-white font-bold">បោះបង់</button>
                    <button type="submit" class="w-full p-4 bg-amber-600 rounded-xl text-white font-bold">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 3. MODAL: មើលលម្អិត (View Details) -->
    <!-- ============================================== -->
    <div id="viewModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 p-4">
        <div class="bg-[#0B132B] border border-[#1C2C4E] p-8 rounded-3xl w-full max-w-sm text-center shadow-2xl">
            <!-- រូបភាព -->
            <div class="w-32 h-32 mx-auto bg-[#15234b] rounded-full border-[6px] border-[#1C2C4E] overflow-hidden mb-4 shadow-lg flex items-center justify-center" id="view_image_container">
                <!-- លោតចូលតាម JS -->
            </div>

            <h2 id="view_name" class="text-white font-black text-2xl mb-2">ឈ្មោះ</h2>
            <span id="view_type" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider mb-6 inline-block">ប្រភេទ</span>

            <div class="bg-[#15234b] border border-[#1C2C4E] rounded-xl p-4 space-y-4 text-left">
                <div class="flex justify-between items-center border-b border-[#1C2C4E] pb-3">
                    <span class="text-slate-400 text-sm">📞 លេខទូរស័ព្ទ៖</span>
                    <span id="view_phone" class="text-white font-mono font-bold"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 text-sm">💰 ទិញសរុប៖</span>
                    <span id="view_spent" class="text-emerald-400 font-black text-lg"></span>
                </div>
            </div>

            <button onclick="document.getElementById('viewModal').classList.add('hidden')" class="w-full mt-6 p-4 bg-slate-800 hover:bg-slate-700 rounded-xl text-white font-bold transition-all">បិទផ្ទាំង</button>
        </div>
    </div>

    <!-- 🔴 JavaScript សម្រាប់បញ្ជាប៊ូតុង 🔴 -->
    <script>
        function openEditModal(customer) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('edit_name').value = customer.name;
            document.getElementById('edit_phone').value = customer.phone ?? '';
            document.getElementById('edit_type').value = customer.type;
            document.getElementById('editForm').action = `/customers/update/${customer.id}`;
            document.getElementById('edit_total_spent').value = customer.total_spent ?? 0;

            document.getElementById('editForm').action = `/customers/update/${customer.id}`;
        }

        function openViewModal(customer) {
            document.getElementById('viewModal').classList.remove('hidden');

            document.getElementById('view_name').innerText = customer.name;
            document.getElementById('view_phone').innerText = customer.phone ?? 'គ្មានលេខ';
            document.getElementById('view_spent').innerText = '$' + parseFloat(customer.total_spent).toFixed(2);

            let typeBadge = document.getElementById('view_type');
            typeBadge.innerText = customer.type === 'VIP' ? 'VIP Customer' : 'General Customer';
            typeBadge.className = customer.type === 'VIP'
                ? "px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-wider mb-6 inline-block bg-amber-500/20 text-amber-400 border border-amber-500/30"
                : "px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-wider mb-6 inline-block bg-blue-500/20 text-blue-400 border border-blue-500/30";

            let imgContainer = document.getElementById('view_image_container');
            if(customer.image) {
                imgContainer.innerHTML = `<img src="/storage/${customer.image}" class="w-full h-full object-cover">`;
            } else {
                let icon = customer.type === 'VIP' ? '🌟' : '👤';
                imgContainer.innerHTML = `<span class="text-5xl">${icon}</span>`;
            }
        }
    </script>
</body>
</html>
