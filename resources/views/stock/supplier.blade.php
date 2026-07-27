<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>អ្នកផ្គត់ផ្គង់ - STOCK.PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .modal-enter { animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes modalFadeIn { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    </style>
</head>
<body class="bg-gradient-to-br from-[#070b19] via-[#0B132B] to-[#070b19] text-slate-300 min-h-screen p-4 md:p-8">

    <div class="max-w-[1600px] mx-auto space-y-6">

        <!-- 1. Header & Breadcrumb -->
        <div class="flex flex-wrap md:flex-nowrap justify-between items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="javascript:history.back()" class="flex items-center justify-center w-12 h-12 bg-[#15234b] hover:bg-[#1C2C4E] text-slate-300 rounded-2xl border border-[#1C2C4E] shadow-lg transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div class="flex items-center text-sm font-bold text-slate-500 bg-[#15234b]/40 px-6 py-3.5 rounded-2xl border border-[#1C2C4E]">
                    <span class="text-slate-300">ស្តុក</span>
                    <span class="mx-3 text-slate-600">/</span>
                    <span class="text-amber-400">អ្នកផ្គត់ផ្គង់ (Suppliers)</span>
                </div>
            </div>

            <a href="{{ route('stock.supplier.create') }}" class="flex items-center gap-2 bg-[#5642F5] hover:bg-indigo-600 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md cursor-pointer">
    <span>+ បន្ថែមអ្នកផ្គត់ផ្គង់ថ្មី</span>
</a>
        </div>

        <!-- 2. Search & Filter Bar -->
        <!-- 2. Search & Filter Bar (កែប្រែឲ្យដំណើរការ ១០០%) -->
        <form action="{{ route('stock.supplier') }}" method="GET" id="filterForm">
            <!-- ផ្ទុកទិន្នន័យលាក់មុខ ពេលចុចប៊ូតុង Filter -->
            <input type="hidden" name="status" id="filterStatus" value="{{ $status ?? 'all' }}">

            <div class="bg-[#15234b]/60 backdrop-blur-xl rounded-2xl p-4 flex flex-col xl:flex-row items-center justify-between border border-[#1C2C4E] shadow-lg gap-4 z-10 relative">

                <div class="relative w-full xl:w-[400px]">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500">🔍</span>
                    <!-- ថែម onchange ដើម្បីឲ្យពេលវាយអក្សររួច ចុច Enter វានឹងស្វែងរកដោយស្វ័យប្រវត្តិ -->
                    <input type="text" name="search" value="{{ $search ?? '' }}" onchange="document.getElementById('filterForm').submit()" placeholder="ស្វែងរកឈ្មោះក្រុមហ៊ុន ឬអ្នកផ្គត់ផ្គង់..." class="w-full pl-11 pr-4 py-3 bg-[#0B132B] border border-[#1C2C4E] rounded-xl text-sm text-white outline-none focus:border-amber-500 transition-all">
                </div>

                <div class="flex bg-[#0B132B] p-1.5 rounded-xl border border-[#1C2C4E]">
                    <!-- ប៊ូតុង ទាំងអស់ -->
                    <button type="button" onclick="setFilter('all')" class="px-5 py-2 rounded-lg text-xs font-bold transition-all {{ ($status ?? 'all') == 'all' ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">ទាំងអស់</button>
                    <!-- ប៊ូតុង សកម្ម -->
                    <button type="button" onclick="setFilter('active')" class="px-5 py-2 rounded-lg text-xs font-bold transition-all {{ ($status ?? '') == 'active' ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">🟢 សកម្ម</button>
                    <!-- ប៊ូតុង ផ្អាក -->
                    <button type="button" onclick="setFilter('inactive')" class="px-5 py-2 rounded-lg text-xs font-bold transition-all {{ ($status ?? '') == 'inactive' ? 'bg-slate-700 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">🔴 ផ្អាក</button>
                </div>

            </div>
        </form>

        <!-- 3. Data Table (បញ្ជីអ្នកផ្គត់ផ្គង់) -->
        <div class="bg-[#15234b]/50 backdrop-blur-md rounded-2xl border border-[#1C2C4E] overflow-hidden shadow-2xl block transition-all duration-300">
            <div class="overflow-x-auto hide-scroll">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-[#0B132B] border-b border-[#1C2C4E] text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-6 py-5">#</th>
                            <th class="px-6 py-5">ឈ្មោះក្រុមហ៊ុន / អ្នកផ្គត់ផ្គង់</th>
                            <th class="px-6 py-5">លេខទូរស័ព្ទ & អ៊ីមែល</th>
                            <th class="px-6 py-5">អាសយដ្ឋាន</th>
                            <th class="px-6 py-5 text-center">ប្រតិបត្តិការទិញ</th>
                            <th class="px-6 py-5 text-center">ស្ថានភាព</th>
                            <th class="px-6 py-5 text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1C2C4E]/50">

                        <!-- 🔴 ប្រើ Forelse ដើម្បី Loop បង្ហាញទិន្នន័យ 🔴 -->
                        @forelse($suppliers ?? [] as $index => $row)
                            <tr class="hover:bg-[#1C2C4E]/30 transition-all">
                                <td class="px-6 py-4 font-black text-white">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-amber-400">{{ $row->name }}</td>
                                <td class="px-6 py-4">{{ $row->phone }}</td>
                                <td class="px-6 py-4 text-xs">{{ $row->address ?? 'មិនមាន' }}</td>
                                <td class="px-6 py-4 text-center font-bold">0 ដង</td>
                                <td class="px-6 py-4 text-center">
                                    @if($row->status == 'active')
                                        <span class="bg-emerald-500/10 text-emerald-400 px-2 py-1 rounded text-[10px] font-bold">សកម្ម</span>
                                    @else
                                        <span class="bg-rose-500/10 text-rose-400 px-2 py-1 rounded text-[10px] font-bold">ផ្អាក</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" onclick="openEditModal({{ $row->id }}, '{{ addslashes($row->name) }}', '{{ $row->phone }}', '{{ addslashes($row->address) }}', '{{ addslashes($row->note) }}', '{{ $row->status }}')" class="text-blue-400 hover:text-white bg-blue-500/10 hover:bg-blue-600 p-2 rounded-lg transition-all">
    ✏️
</button>
                                </td>
                            </tr>
                        @empty
                            <!-- បើគ្មានទិន្នន័យ បង្ហាញរូប Folder ទទេរ -->
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-amber-500/10 rounded-full flex items-center justify-center border border-amber-500/20 mb-5">
                                            <span class="text-4xl opacity-80">🏢</span>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-300 tracking-wide mb-2">មិនទាន់មានអ្នកផ្គត់ផ្គង់ទេ!</h3>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- 🔴 MODAL FORM: កែប្រែព័ត៌មានអ្នកផ្គត់ផ្គង់ 🔴 -->
    <!-- ========================================== -->
    <div id="editSupplierModal" class="fixed inset-0 bg-[#04060d]/80 backdrop-blur-sm z-50 hidden flex items-center justify-center overflow-y-auto py-10 px-4">
        <div class="bg-white w-full max-w-[500px] rounded-[24px] shadow-2xl modal-enter relative flex flex-col">

            <!-- ប៊ូតុងខ្វែងបិទ -->
            <button type="button" onclick="closeEditModal()" class="absolute top-5 right-5 w-8 h-8 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full flex items-center justify-center transition-all z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Header -->
            <div class="px-8 pt-8 pb-4 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-wide">កែប្រែព័ត៌មាន</h2>
                    <p class="text-xs font-bold text-slate-400 mt-0.5">ធ្វើបច្ចុប្បន្នភាពទិន្នន័យអ្នកផ្គត់ផ្គង់</p>
                </div>
            </div>

            <!-- Form Body -->
            <form id="editSupplierForm" method="POST" action="" class="p-8 pt-2">
                @csrf
                @method('PUT') <!-- បញ្ជាក់ថាជាការ Update -->

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">ឈ្មោះក្រុមហ៊ុន / អ្នកផ្គត់ផ្គង់ <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="edit_phone" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">អាសយដ្ឋាន</label>
                        <input type="text" name="address" id="edit_address" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">ចំណាំ</label>
                        <textarea name="note" id="edit_note" rows="2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-blue-500 transition-all"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">ស្ថានភាព <span class="text-red-500">*</span></label>
                        <select name="status" id="edit_status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <option value="active">🟢 សកម្ម (Active)</option>
                            <option value="inactive">🔴 ផ្អាក (Inactive)</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="button" onclick="closeEditModal()" class="w-full py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-xl transition-all">បោះបង់</button>
                    <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-black text-sm rounded-xl shadow-[0_8px_20px_rgba(37,99,235,0.3)] transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // មុខងារបើក/បិទ Modal
        const modal = document.getElementById('supplierModal');
        function openSupplierModal() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeSupplierModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        // មុខងារបញ្ជា Filter ឲ្យ Auto-Submit ទៅកាន់ Server
        function setFilter(statusValue) {
            document.getElementById('filterStatus').value = statusValue; // កំណត់តម្លៃ status ថ្មី
            document.getElementById('filterForm').submit(); // បញ្ជូន Form (Refresh ទំព័រ)
        }

        // មុខងារបើក Modal កែប្រែ និងបញ្ចូនទិន្នន័យចូលប្រអប់
        function openEditModal(id, name, phone, address, note, status) {
            // ១. ដូរ URL របស់ Form ឲ្យត្រូវនឹង ID របស់អ្នកផ្គត់ផ្គង់ដែលចុច
            document.getElementById('editSupplierForm').action = '/stock-supplier/' + id;

            // ២. បោះទិន្នន័យចាស់ៗចូលទៅក្នុងប្រអប់ Input ឲ្យគេឃើញ
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_address').value = address || ''; // ប្រើ '' បើគ្មានទិន្នន័យ
            document.getElementById('edit_note').value = note || '';
            document.getElementById('edit_status').value = status;

            // ៣. បង្ហាញ Modal លើអេក្រង់
            const editModal = document.getElementById('editSupplierModal');
            editModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // មុខងារបិទ Modal កែប្រែ
        function closeEditModal() {
            document.getElementById('editSupplierModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</body>
</html>
