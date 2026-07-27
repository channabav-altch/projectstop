<?php

namespace App\Http\Controllers;

// 🔴 បន្ថែមបន្ទាត់នេះ ដើម្បីប្រាប់ Laravel ពីទីតាំង BaseController 🔴
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Expense;
use App\Models\OrderItem;
use Carbon\Carbon;

class Controller extends BaseController
{
    public function index()
    {
        // ១. ទិន្នន័យទូទៅ (ចំណូល ចំណាយ សរុប)
        $todayRevenue = Order::sum('total_amount');
        $todayExpense = Expense::whereDate('expense_date', Carbon::today())->sum('amount');
        $totalOrders = Order::count();
        $expenses = Expense::latest()->take(5)->get();

        // ២. កូដសម្រាប់ក្រាហ្វិករបាយការណ៍ប្រចាំខែ (១២ ខែ)
        $monthlyData = [];
        $maxAmount = 1; // សម្រាប់គណនាភាគរយកម្ពស់សសរក្រាហ្វិក
        $hasData = false;

        for ($i = 1; $i <= 12; $i++) {
            $rev = Order::whereMonth('created_at', $i)->whereYear('created_at', Carbon::now()->year)->sum('total_amount');
            $exp = Expense::whereMonth('expense_date', $i)->whereYear('expense_date', Carbon::now()->year)->sum('amount');

            if ($rev > 0 || $exp > 0) $hasData = true;
            if ($rev > $maxAmount) $maxAmount = $rev;
            if ($exp > $maxAmount) $maxAmount = $exp;

            $monthlyData[] = [
                'revenue' => $rev,
                'expense' => $exp
            ];
        }

        // ៣. កូដសម្រាប់ Area Chart (ទាញយកទិន្នន័យ ៧ ថ្ងៃចុងក្រោយ)
        $chartDates = collect();
        $chartRevenues = collect();
        $chartExpenses = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('Y-m-d');

            $chartDates->push($date->format('d M'));

            // 🔴 បន្ថែម (float) ពីមុខ ដើម្បីបង្ខំឲ្យវាចេញជាលេខ (Number) ជានិច្ច
            $rev = (float) Order::whereDate('created_at', $dateString)->sum('total_amount');
            $chartRevenues->push($rev);

            $exp = (float) Expense::whereDate('expense_date', $dateString)->sum('amount');
            $chartExpenses->push($exp);
        }

        // ៤. បញ្ជូនអថេរទាំងអស់ទៅកាន់ Dashboard ក្នុងពេលតែមួយ
        return view('dashboard', compact(
            'todayRevenue', 'todayExpense', 'totalOrders', 'expenses',
            'monthlyData', 'maxAmount', 'hasData',
            'chartDates', 'chartRevenues', 'chartExpenses'
        ));
    }

//    // ២. មុខងាររបាយការណ៍ស្តុកលក់ចេញ (Sold Items)
//     public function stockSoldReport(\Illuminate\Http\Request $request)
//     {
//         $search = $request->input('search');
//         $date = $request->input('date');

//         // ចាប់ផ្ដើម Query ទាញទិន្នន័យពី OrderItem
//         $query = OrderItem::with(['product', 'order'])->latest();

//         // 🟢 លក្ខខណ្ឌទី១៖ ស្វែងរកតាមឈ្មោះ ឬវិក្កយបត្រ 🟢
//         if (!empty($search)) {
//             $query->where(function($q) use ($search) {
//                 $q->whereHas('order', function($orderQ) use ($search) {
//                     $orderQ->where('invoice_no', 'LIKE', "%{$search}%");
//                 })->orWhereHas('product', function($productQ) use ($search) {
//                     $productQ->where('product_name', 'LIKE', "%{$search}%")
//                              ->orWhere('name', 'LIKE', "%{$search}%");
//                 });
//             });
//         }

//         // 🟢 លក្ខខណ្ឌទី២៖ Filter តាមថ្ងៃខែ (កន្លែងដែលបងកំពុងជាប់គាំង) 🟢
//         if (!empty($date)) {
//             // បើជ្រើសរើសចន្លោះថ្ងៃ (Range: 2026-07-06 to 2026-07-08)
//             if (str_contains($date, ' to ')) {
//                 $dates = explode(' to ', $date);
//                 if(count($dates) == 2) {
//                     // ដោយសារ OrderItem អត់មាន created_at យើងត្រូវ Filter តាមរយៈតារាង Order មេរបស់វា
//                     $query->whereHas('order', function($q) use ($dates) {
//                         $q->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
//                     });
//                 }
//             } else {
//                 // បើជ្រើសរើសតែ ១ ថ្ងៃ
//                 $query->whereHas('order', function($q) use ($date) {
//                     $q->whereDate('created_at', $date);
//                 });
//             }
//         }

//         // ទាញទិន្នន័យចុងក្រោយមកបង្ហាញ
//         $soldItems = $query->get();

//         // គណនាសរុប
//         $totalQty = abs($soldItems->sum('qty'));
//         $totalAmount = $soldItems->sum('total');

//         return view('stock-sold', compact('soldItems', 'totalQty', 'totalAmount', 'search', 'date'));
//     }

    // ==========================================
    // មុខងារសម្រាប់ លុប (Delete) ទិន្នន័យលក់ចេញ
    // ==========================================
    public function deleteSoldItem($id)
    {
        $item = \App\Models\OrderItem::findOrFail($id);

        // លុបទិន្នន័យចោល
        $item->delete();

        // ត្រឡប់មកទំព័រដើមវិញ ជាមួយសារជោគជ័យ
        return redirect()->back()->with('success', 'ទិន្នន័យត្រូវបានលុបចោលដោយជោគជ័យ!');
    }

    // ==========================================
    // មុខងារសម្រាប់ កែប្រែ (Edit) ទិន្នន័យលក់ចេញ
    // ==========================================
    public function editSoldItem($id)
    {
        // ស្វែងរកទិន្នន័យដែលត្រូវកែប្រែ
        $item = \App\Models\OrderItem::with('product')->findOrFail($id);

        // បញ្ជូនទៅកាន់ទំព័រ Form កែប្រែ (បងត្រូវបង្កើត File នេះដោយខ្លួនឯង)
        // កូដចាស់៖ return view('stock-sold-edit', compact('item'));

    // 🟢 កូដថ្មី (ត្រូវកែទៅជាបែបនេះ) ៖
    return view('products.stock-sold-edit', compact('item'));
    }

    // ==========================================
    // មុខងារសម្រាប់ Update (រក្សាទុកការកែប្រែ)
    // ==========================================
    public function updateSoldItem(Request $request, $id)
    {
        // រកទិន្នន័យចាស់
        $item = \App\Models\OrderItem::findOrFail($id);

        // ផ្លាស់ប្តូរតម្លៃទៅតាមអ្វីដែលគេវាយបញ្ចូលថ្មី
        $item->qty = $request->input('qty');
        $item->total = $request->input('total');

        // រក្សាទុក (Save)
        $item->save();

        // ត្រឡប់ទៅទំព័ររបាយការណ៍វិញ ជាមួយសារប្រាប់ថាជោគជ័យ
        return redirect('stock-sold')->with('success', 'ទិន្នន័យត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    // ៣. មុខងារវិភាគទិន្នន័យ Admin
    public function adminAnalytics()
    {
        $sellerReports = Order::selectRaw('seller_name, COUNT(*) as total_orders, SUM(total_amount) as total_sales')
            ->groupBy('seller_name')
            ->get();

        return view('admin-report', compact('sellerReports'));
    }


}
