<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // ==========================================
    // មុខងាររបាយការណ៍ Admin
    // ==========================================
    public function adminReport(\Illuminate\Http\Request $request)
    {
        // ១. ចាប់យកទិន្នន័យពី Form (Filter)
        $date   = $request->input('date', date('Y-m-d'));
        $period = $request->input('period', ''); // ថ្ងៃនេះ, ខែនេះ
        $type   = $request->input('type', 'all'); // បោះដុំ, លក់រាយ
        $status = $request->input('status', 'all'); // មានលក់, គ្មានលក់
        $search = $request->input('search');

        // ២. រៀបចំថ្ងៃខែ បើគាត់ចុចប៊ូតុង "ថ្ងៃនេះ" ឬ "ខែនេះ"
        $parsedDate = \Carbon\Carbon::parse($date);
        if ($period == 'today') {
            $date = date('Y-m-d');
            $parsedDate = \Carbon\Carbon::parse($date);
        }

        $applyDateFilter = function($query) use ($period, $date, $parsedDate) {
        if ($period == 'month') {
            $query->whereMonth('created_at', $parsedDate->format('m'))
                  ->whereYear('created_at', $parsedDate->format('Y'));
        } else {
            $query->whereDate('created_at', $date);
        }
    };

   // Helper function សម្រាប់ទាញយកទិន្នន័យតាមតំបន់ និង Status
$getDataByRegionAndStatus = function($provinceCondition, $orderStatus) use ($applyDateFilter, $type) {
    $query = DB::table('orders');

    // ១. កំណត់លក្ខខណ្ឌតំបន់ (ភ្នំពេញ រួមទាំង Walk-in ផង)
    if ($provinceCondition == 'pp') {
        $query->where(function($q) {
            $q->where('province', 'like', '%ភ្នំពេញ%')
              ->orWhere('customer_type', 'walkin');
        });
    } elseif ($provinceCondition == 'prov') {
        $query->where('province', 'not like', '%ភ្នំពេញ%')
              ->where('customer_type', '!=', 'walkin')
              ->whereNotNull('province');
    }

    // ២. កំណត់លក្ខខណ្ឌ Status
    if ($orderStatus == 'paid') {
        $query->where('status', 'paid');
    } else {
        $query->where('status', '!=', 'paid');
    }

    // ៣. ប្រើប្រាស់ Date Filter ដែលមានស្រាប់
    $applyDateFilter($query);

    $orderIds = (clone $query)->pluck('id');
    $totalAmount = (clone $query)->sum('total_amount');
    $deliveryFee = (clone $query)->sum('delivery_fee');
    $orderCount = (clone $query)->count();

    // ៤. ទាញយក Item បែងចែកតាមប្រភេទលក់
    $items = DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->whereIn('order_items.order_id', $orderIds)
        ->selectRaw('products.product_name as product_name, order_items.unit_price as unit_price, SUM(order_items.qty) as qty')
        ->groupBy('products.product_name', 'order_items.unit_price')
        ->get();

    return [
        'orderIds'     => $orderIds,
        'totalAmount'  => $totalAmount,
        'deliveryFee'  => $deliveryFee,
        'orderCount'   => $orderCount,
        'items'        => $items
    ];
};

// =========================================================
    // គណនាទិន្នន័យសម្រាប់ផ្ទាំងពណ៌ស្វាយ (KPI Cards)
    // =========================================================

    // ១. បង្កើត Query ចេញពីតារាង orders
    $kpiQuery = DB::table('orders');

    // ២. ហៅប្រើ Function Filter ថ្ងៃខែដែលមានស្រាប់ក្នុងកូដរបស់បង
    $applyDateFilter($kpiQuery);

    // ៣. ពិនិត្យលក្ខខណ្ឌ Status (ដូចជា active ឬលក្ខខណ្ឌផ្សេងៗតាម URL)
    if ($status && $status !== 'all') {
        $kpiQuery->where('status', $status);
    }

    // ៤. គណនាផលបូកទឹកប្រាក់សរុប (ប្តូរ 'grand_total' ទៅជាឈ្មោះ Column ពិតក្នុង DB របស់បង)
    $totalRevenue = (clone $kpiQuery)->sum('total_amount');

   // ៥. គណនាបំបែកតាម លក់រាយ (គ្មាន customer_id) និង បោះដុំ (មាន customer_id)
    $retailRevenue = (clone $kpiQuery)->whereNull('customer_id')->sum('total_amount');
    $wholesaleRevenue = (clone $kpiQuery)->whereNotNull('customer_id')->sum('total_amount');

    // --- ក. ទិន្នន័យសរុបរួម (ភ្នំពេញ + ខេត្ត) ---
    // ១. បានទទួលប្រាក់ (Paid)
    $allPaid = [
        'pp' => $getDataByRegionAndStatus('pp', 'paid'),
        'prov' => $getDataByRegionAndStatus('prov', 'paid')
    ];
    $totalPaidAmount = $allPaid['pp']['totalAmount'] + $allPaid['prov']['totalAmount'];
    $totalPaidCount  = $allPaid['pp']['orderCount'] + $allPaid['prov']['orderCount'];
    $totalPaidDelivery = $allPaid['pp']['deliveryFee'] + $allPaid['prov']['deliveryFee'];

    // ២. មិនទាន់បានទទួលប្រាក់ (Unpaid)
    $allUnpaid = [
        'pp' => $getDataByRegionAndStatus('pp', 'unpaid'),
        'prov' => $getDataByRegionAndStatus('prov', 'unpaid')
    ];
    $totalUnpaidAmount = $allUnpaid['pp']['totalAmount'] + $allUnpaid['prov']['totalAmount'];
    $totalUnpaidCount  = $allUnpaid['pp']['orderCount'] + $allUnpaid['prov']['orderCount'];
    $totalUnpaidDelivery = $allUnpaid['pp']['deliveryFee'] + $allUnpaid['prov']['deliveryFee'];


    // --- ខ. ទិន្នន័យរាជធានីភ្នំពេញដាច់ដោយឡែក ---
    $ppPaid = $getDataByRegionAndStatus('pp', 'paid');
    $ppUnpaid = $getDataByRegionAndStatus('pp', 'unpaid');


    // --- គ. ទិន្នន័យតាមបណ្តាខេត្តដាច់ដោយឡែក ---
    $provPaid = $getDataByRegionAndStatus('prov', 'paid');
    $provUnpaid = $getDataByRegionAndStatus('prov', 'unpaid');


    // ១. ថ្លៃដឹកជញ្ជូនសរុបទាំងអស់
    $grandTotalDelivery = ($totalPaidDelivery ?? 0) + ($totalUnpaidDelivery ?? 0);

    // ២. ចំណូលសរុបរួមទាំងអស់ (ទំនិញ + ដឹក ដែលទាញបានពី Database)
    $finalGrandTotal = ($totalPaidAmount ?? 0) + ($totalUnpaidAmount ?? 0);

    // ៣. តម្លៃទំនិញសុទ្ធ (យកសរុបរួម ដកនឹងថ្លៃដឹកចេញ ដើម្បីបានតម្លៃទំនិញសុទ្ធ 21.75$)
    $grandTotalAmount = $finalGrandTotal - $grandTotalDelivery;
        // ១. ទិន្នន័យតំបន់ រាជធានីភ្នំពេញ
    $ppOrdersQuery = DB::table('orders')->whereIn('province', ['រាជធានីភ្នំពេញ', 'ទិញផ្ទាល់']);

    // ២. ទិន្នន័យតំបន់ តាមបណ្តាខេត្ត (មិនមែនភ្នំពេញ)
    $provOrdersQuery = DB::table('orders')->whereNotIn('province', ['រាជធានីភ្នំពេញ', 'ទិញផ្ទាល់']);

    // 🟢 កូដ Filter ថ្ងៃខែពេញលេញ៖
    if ($period == 'month') {
        $ppOrdersQuery->whereMonth('created_at', $parsedDate->format('m'))
                      ->whereYear('created_at', $parsedDate->format('Y'));

        $provOrdersQuery->whereMonth('created_at', $parsedDate->format('m'))
                        ->whereYear('created_at', $parsedDate->format('Y'));
    } else {
        $ppOrdersQuery->whereDate('created_at', $date);
        $provOrdersQuery->whereDate('created_at', $date);
    }

        // ៣. ទាញទិន្នន័យតំណាងលក់ និងគណនាពីតារាង orders តាមថ្ងៃខែ
        $query = \App\Models\User::query()
            ->addSelect([
                // 🔴 រាប់ចំនួនវិក្កយបត្រលក់បាន (តាមថ្ងៃដែលរើស)
                'sales_count' => DB::table('orders')
                    ->selectRaw('count(*)')
                    ->whereColumn('orders.user_id', 'users.id')
                    ->where(function ($q) use ($period, $date, $parsedDate) {
                        if ($period == 'month') {
                            // បើរើស "ខែនេះ"
                            $q->whereMonth('created_at', $parsedDate->format('m'))
                              ->whereYear('created_at', $parsedDate->format('Y'));
                        } else {
                            // បើរើសថ្ងៃជាក់លាក់ពីប្រតិទិន ឬ "ថ្ងៃនេះ"
                            $q->whereDate('created_at', $date);
                        }
                    }),

                // 🔴 បូកសរុបទឹកប្រាក់លក់បាន (តាមថ្ងៃដែលរើស)
                'total_sales' => DB::table('orders')
                    ->selectRaw('sum(total_amount)')
                    ->whereColumn('orders.user_id', 'users.id')
                    ->where(function ($q) use ($period, $date, $parsedDate) {
                        if ($period == 'month') {
                            $q->whereMonth('created_at', $parsedDate->format('m'))
                              ->whereYear('created_at', $parsedDate->format('Y'));
                        } else {
                            $q->whereDate('created_at', $date);
                        }
                    })
            ]);
        // ស្វែងរកតាមឈ្មោះ
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // 🟢 កូដសម្រាប់ត្រងយកប្រភេទ បោះដុំ ឬ លក់រាយ 🟢
        // បញ្ជាក់៖ បើនៅក្នុង Database (តារាង users) បងប្រើឈ្មោះ Column ផ្សេង (ឧទាហរណ៍៖ role ឬ type) សូមកែពាក្យ 'user_type' នេះ
        if ($type == 'wholesale') {
            $query->where('role', 'wholesale');
        } elseif ($type == 'retail') {
            $query->where('role', 'retail');
        }

        // 🟢 កែតម្រូវ៖ ប្រើ whereExists ដើម្បីកុំឱ្យ Error ជាមួយការកាត់ទំព័រ (Paginate) 🟢
        if ($status == 'active') {
            $query->whereExists(function ($q) use ($period, $date, $parsedDate) {
                $q->select(\Illuminate\Support\Facades\DB::raw(1))
                  ->from('orders')
                  ->whereColumn('orders.user_id', 'users.id')
                  ->when($period == 'month', function ($queryMonth) use ($parsedDate) {
                      $queryMonth->whereMonth('created_at', $parsedDate->format('m'))
                                 ->whereYear('created_at', $parsedDate->format('Y'));
                  }, function ($queryDate) use ($date) {
                      $queryDate->whereDate('created_at', $date);
                  });
            });
        } elseif ($status == 'inactive') {
            $query->whereNotExists(function ($q) use ($period, $date, $parsedDate) {
                $q->select(\Illuminate\Support\Facades\DB::raw(1))
                  ->from('orders')
                  ->whereColumn('orders.user_id', 'users.id')
                  ->when($period == 'month', function ($queryMonth) use ($parsedDate) {
                      $queryMonth->whereMonth('created_at', $parsedDate->format('m'))
                                 ->whereYear('created_at', $parsedDate->format('Y'));
                  }, function ($queryDate) use ($date) {
                      $queryDate->whereDate('created_at', $date);
                  });
            });
        }

        // ៤. ទាញយកទិន្នន័យ (ដាក់ Paginate ដើម្បីងាយស្រួលបែងចែកទំព័រខាងក្រោម)
        $reports = $query->paginate(15);
        // 🟢 ៥. បន្ថែមការទាញយកបញ្ជីទំនិញលម្អិត (Items) ឲ្យចំតាមថ្ងៃដែលបាន Filter សម្រាប់បង្ហាញនិង Print 🟢
        foreach ($reports as $manager) {
            if ($manager->sales_count <= 0) {
                $manager->items = collect();
                $manager->total_qty = 0;
            } else {
                // ទាញយក ID របស់ Order ដែលចំថ្ងៃនិងចំ User នោះ
                $orderQuery = DB::table('orders')->where('user_id', $manager->id);

                if ($period == 'month') {
                    $orderQuery->whereMonth('created_at', $parsedDate->format('m'))
                               ->whereYear('created_at', $parsedDate->format('Y'));
                } else {
                    $orderQuery->whereDate('created_at', $date);
                }

                $orderIds = $orderQuery->pluck('id');

                // ទាញយកបញ្ជីទំនិញមកបូកសរុបតាម Order ទាំងនោះ
                $items = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('order_items.order_id', $orderIds)
            // 🟢 ដូរមកប្រើ qty, unit_price និង product_name ឱ្យត្រូវនឹង Blade របស់បងបេះបិទ
            ->selectRaw('products.product_name as product_name, order_items.unit_price as unit_price, SUM(order_items.qty) as qty')
            ->groupBy('products.product_name', 'order_items.unit_price')
            ->get();

                $manager->items = $items;
                $manager->total_qty = $items->sum('qty');
            }
        }

       // ៥. បញ្ជូនទិន្នន័យទាំងអស់ (ទាំងតំបន់ និង តារាង Reports របស់បុគ្គលិក) ទៅកាន់ View តែមួយ
        return view('reports.admin', compact(
            'date', 'period', 'type', 'status', 'search',
            'totalPaidAmount', 'totalPaidCount', 'totalUnpaidAmount', 'totalUnpaidCount',
            'allPaid', 'allUnpaid', 'ppPaid', 'ppUnpaid', 'provPaid', 'provUnpaid',
            'grandTotalAmount', 'grandTotalDelivery', 'finalGrandTotal', 'totalRevenue',
        'retailRevenue',
        'wholesaleRevenue',
            'reports' // 👈 បន្ថែម biến នេះចូលទីនេះជាការស្រេច
        ));
        // return view('reports.admin', compact('reports', 'date', 'period', 'type', 'status', 'search'));
    }


    public function sellerDetail(Request $request, $id)
    {
        // ១. រកឈ្មោះអ្នកលក់
        $seller = \App\Models\User::findOrFail($id);

        // ២. ចាប់ផ្តើមទាញយកប្រវត្តិវិក្កយបត្រ (Orders) របស់អ្នកលក់តាម user_id
        $query = \Illuminate\Support\Facades\DB::table('orders')
                    ->where('user_id', $id);

        // 🟢 ៣. ឆែកមើលថាតើមានភ្ជាប់ថ្ងៃខែមកជាមួយដែរឬទេ? បើមាន គឺ Filter យកតែថ្ងៃនោះ!
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('created_at', $request->date);
        }

        // ៤. រៀបចំលំដាប់ និងទាញយកទិន្នន័យ (paginate)
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // រក្សា Parameter ថ្ងៃខែពេលប្តូរ Page (Pagination)
        $orders->appends($request->query());

        return view('reports.seller_detail', compact('seller', 'orders'));
    }

    // ==========================================
    // មុខងាររបាយការណ៍ហិរញ្ញវត្ថុ (Finance)
    // ==========================================
  public function finance(Request $request)
    {
        $activeTab    = $request->get('tab', 'pd');
        $filterType   = $request->get('filter', 'day');
        $selectedDate = $request->get('date', date('Y-m-d'));

        // ==========================================
        // 1. សម្រាប់ Tab "វិភាគទិន្នន័យ" (analytics)
        // ==========================================
        if ($activeTab === 'analytics') {

            // បង្កើត Query ថ្មីសម្រាប់ Analytics
            $analyticsQuery = Order::query();

            // Filter តាមកាលបរិច្ឆេទ
            if ($filterType === 'day') {
                $analyticsQuery->whereDate('created_at', $selectedDate);
            } elseif ($filterType === 'month') {
                $analyticsQuery->whereMonth('created_at', date('m', strtotime($selectedDate)))
                               ->whereYear('created_at', date('Y', strtotime($selectedDate)));
            } elseif ($filterType === 'year') {
                $analyticsQuery->whereYear('created_at', date('Y', strtotime($selectedDate)));
            }

            // គណនាទិន្នន័យសង្ខេប (ប្រើ method clone() របស់ Laravel)
            // 💡 ចំណាំ៖ ប្រសិនបើ Column ទឹកប្រាក់ក្នុង Table orders របស់បងឈ្មោះ 'amount' ឬ 'total' សូមប្តូរ 'total_price' ទៅតាមនោះ
            $totalRevenue   = $analyticsQuery->clone()->whereIn('status', ['paid', 'PAID', 'ជោគជ័យ'])->sum('total_amount');
            $totalOrders    = $analyticsQuery->clone()->count();
            $canceledOrders = $analyticsQuery->clone()->whereIn('status', ['canceled', 'CANCELED', 'បានបោះបង់'])->count();

            return view('reports.finance', compact(
                'activeTab',
                'filterType',
                'selectedDate',
                'totalRevenue',
                'totalOrders',
                'canceledOrders'
            ));
        }

        // ==========================================
        // 2. សម្រាប់ Tab "គណនីការងារ" (account) និង "តារាង PD" (pd)
        // ==========================================
        if ($activeTab === 'account') {
            $query = Expense::query();
        } else {
            $query = Order::query();
        }

        // Filter តាមកាលបរិច្ឆេទ
        if ($filterType === 'day') {
            $query->whereDate('created_at', $selectedDate);
        } elseif ($filterType === 'month') {
            $query->whereMonth('created_at', date('m', strtotime($selectedDate)))
                  ->whereYear('created_at', date('Y', strtotime($selectedDate)));
        } elseif ($filterType === 'year') {
            $query->whereYear('created_at', date('Y', strtotime($selectedDate)));
        }

        $deliveries = $query->latest()->get();

        return view('reports.finance', compact(
            'deliveries',
            'filterType',
            'selectedDate',
            'activeTab'
        ));
    }
    public function index()
{
    $request = request();
    // --- ផ្នែករបាយការណ៍លក់ ---
    $date   = $request->input('date', date('Y-m-d'));
    $type   = $request->input('type', 'all');
    $status = $request->input('status', 'all');
    $search = $request->input('search');

    $selectedDate = \Carbon\Carbon::parse($date)->format('Y-m-d');

    // ១. ទាញបញ្ជីបុគ្គលិក (User)
    $managersQuery = User::query();

    if ($type != 'all') {
        $managersQuery->where('role', $type);
    }
    if ($search) {
        $managersQuery->where('name', 'LIKE', "%{$search}%");
    }

    $managers = $managersQuery->get();

    $totalRevenue = 0;
    $totalCustomers = 0;

   // ២. បញ្ចូលទិន្នន័យលក់ និងទំនិញលម្អិតទៅឲ្យបុគ្គលិកម្នាក់ៗ
    foreach ($managers as $manager) {
        // រកវិក្កយបត្ររបស់បុគ្គលិកនេះ ក្នុងថ្ងៃហ្នឹង (PAID)
        $orders = Order::where('user_id', $manager->id)
                    ->whereDate('created_at', $selectedDate)
                    ->where('status', 'PAID')
                    ->get();

        // គណនាចំណូល និងចំនួនវិក្កយបត្រ
        $manager->total_sales = $orders->sum('total_amount');
        $manager->sales_count = $orders->count();

        if ($orders->isEmpty()) {
            $manager->items = collect();
            $manager->total_qty = 0;
        } else {
            $orderIds = $orders->pluck('id');

            // 🟢 ១. ទាញយក OrderItems ទាំងអស់ រួមទាំងភ្ជាប់មកជាមួយទិន្នន័យ Product (តាមរយៈ Relationship)
            $rawItems = OrderItem::with('product')
                            ->whereIn('order_id', $orderIds)
                            ->get();

            // 🟢 ២. ប្រើប្រាស់ Collection របស់ Laravel ដើម្បីបូកសរុប និងទាញយកឈ្មោះ
            $items = $rawItems->groupBy(function ($item) {
                return $item->product_id . '-' . $item->unit_price;
            })->map(function ($group) {
                $firstItem = $group->first();
                return (object) [
                    // ទាញយកឈ្មោះតាមរយៈ Relationship! (បើអត់មាន ដាក់ថា មិនមានឈ្មោះ)
                    'product_name' => $firstItem->product ? $firstItem->product->product_name : 'មិនមានឈ្មោះ',
                    'unit_price'   => $firstItem->unit_price,
                    'qty'          => $group->sum('qty')
                ];
            })->values();

            $manager->items = $items;
            $manager->total_qty = $items->sum('qty');
        }
    }

    // ៣. តម្រងស្ថានភាព (មានលក់ / គ្មានលក់) - ធ្វើក្រោយពេលគណនារួច
    if ($status == 'active') {
        $managers = $managers->filter(function ($manager) { return $manager->sales_count > 0; });
    } elseif ($status == 'inactive') {
        $managers = $managers->filter(function ($manager) { return $manager->sales_count == 0; });
    }

    // គណនាតួលេខសរុបរួម (Total រួម)
    $totalRevenue = $managers->sum('total_sales');
    $totalCustomers = $managers->sum('sales_count');

    // ⚠️ ចំណុចសំខាន់៖ តម្រូវឈ្មោះ Variable ទៅឲ្យត្រូវនឹង Blade កូដដែលបានឲ្យមុននេះ
    $reports = $managers;

    // --- ផ្នែកបញ្ជីចំណាយ (Modal) ---
    $expensePeriod = $request->input('expense_period', 'daily');
    $parsedDate = \Carbon\Carbon::parse($date);
    $expenseQuery = Expense::query();

    if ($expensePeriod === 'daily') {
        $expenseQuery->whereDate('expense_date', $parsedDate->format('Y-m-d'));
    } elseif ($expensePeriod === 'monthly') {
        $expenseQuery->whereMonth('expense_date', $parsedDate->format('m'))
                     ->whereYear('expense_date', $parsedDate->format('Y'));
    } elseif ($expensePeriod === 'yearly') {
        $expenseQuery->whereYear('expense_date', $parsedDate->format('Y'));
    }

    $expenses = $expenseQuery->orderBy('id', 'desc')->get();

    // ៤. បញ្ជូនទិន្នន័យទៅកាន់ View (ផ្លាស់ប្ដូរឈ្មោះ View ទៅតាមដែលបងកំពុងប្រើ)
    return view('reports.team', compact(
        'reports', 'date', 'type', 'status', 'search',
        'totalRevenue', 'totalCustomers', 'expenses', 'expensePeriod'
    ));
}
public function teamReport(\Illuminate\Http\Request $request)
{
    // 🟢 បង្ខំសម្អាត Cache លើ Server ដើម្បីឱ្យកូដ និងទិន្នន័យថ្មីដំណើរការភ្លាមៗ 🟢
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');

    $date = $request->input('date', $request->input('selectedDate', date('Y-m-d')));
    $type = $request->input('type', 'all');
    $status = $request->input('status', 'all');
    $search = $request->input('search');
    $expensePeriod = $request->input('expense_period', 'daily');

    // 🟢 ទាញយកទឹកប្រាក់សរុបផ្ទាល់ពី Table orders មកបង្ហាញ ១០០% 🟢
    $totalRevenue = \DB::table('orders')->sum('total_amount') ?? 0;

    $retailRevenue = $totalRevenue;
    $wholesaleRevenue = 0;

    // 🟢 ទាញយកទិន្នន័យ User និងទឹកប្រាក់លក់របស់ពួកគេ 🟢
    $userQuery = \App\Models\User::query()
        ->addSelect([
            'count_invoices' => \DB::table('orders')
                ->selectRaw('count(*)')
                ->whereColumn('orders.user_id', 'users.id'),

            'sum_total_sales' => \DB::table('orders')
                ->selectRaw('COALESCE(sum(total_amount), 0)')
                ->whereColumn('orders.user_id', 'users.id'),

            'sum_total_units' => \DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->selectRaw('COALESCE(sum(order_items.qty), 0)')
                ->whereColumn('orders.user_id', 'users.id')
        ]);

    if ($search) {
        $userQuery->where('name', 'LIKE', "%{$search}%");
    }

    if ($type && $type !== 'all') {
        $userQuery->where('role', $type);
    }

    $reports = $userQuery->paginate(15);

    // ទាញយកទិន្នន័យចំណាយ (Expenses)
    $expenses = \DB::table('expenses')->orderBy('id', 'desc')->get();

    return view('reports.team', compact(
        'reports',
        'date',
        'type',
        'status',
        'search',
        'totalRevenue',
        'retailRevenue',
        'wholesaleRevenue',
        'expenses',
        'expensePeriod'
    ))->with([
        'selectedDate'   => $date,
        'retailSales'    => $retailRevenue,
        'wholesaleSales' => $wholesaleRevenue,
        'teamReports'    => $reports,
        'totalSales'     => $totalRevenue
    ]);
}
    // ==========================================
    // មុខងារទាក់ទងនឹង ការចំណាយ (Expense)
    // ==========================================

   public function storeExpense(\Illuminate\Http\Request $request)
{
    // ១. Validate ទិន្នន័យ
    $request->validate([
        'description' => 'required|string|max:255',
        'amount'      => 'required|numeric|min:0',
    ]);

    // ២. រក្សាទុកក្នុង Database តាម Eloquent Model (គ្មាន title និង requester_name ទេ)
    \App\Models\Expense::create([
        'description'  => $request->description,
        'amount'       => $request->amount,
        'expense_date' => $request->expense_date ?? date('Y-m-d'),
    ]);

    // ៣. Redirect ត្រឡប់ទៅវិញ
    return redirect()->back()->with('success', 'បានរក្សាទុកការចំណាយជោគជ័យ!');
}

    // // កែប្រែចំណាយ
    // public function updateExpense(Request $request, $id)
    // {
    //     $expense = Expense::findOrFail($id);

    //     $expense->update([
    //         'description'    => $request->description,
    //         'amount'         => $request->amount,
    //         'requester_name' => $request->requester_name,
    //         'expense_date'   => $request->expense_date,
    //         'is_global'      => $request->has('is_global') ? 1 : 0,
    //         'specific_admin' => $request->specific_admin ? implode(', ', (array)$request->specific_admin) : null,
    //     ]);

    //     return redirect()->to(url()->previous() . '#show-expense-list')->with('success', 'បានកែប្រែការចំណាយដោយជោគជ័យ!');
    // }

    // លុបចំណាយ
    public function deleteExpense($id)
    {
        Expense::findOrFail($id)->delete();
        return redirect()->to(url()->previous() . '#show-expense-list')->with('success', 'បានលុបការចំណាយដោយជោគជ័យ!');
    }


    // ==========================================
    // មុខងារទាញយកទិន្នន័យ (Export CSV)
    // ==========================================
    public function exportData()
    {
        $fileName = 'team_report_' . date('Y-m-d') . '.csv';
        $expenses = Expense::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('លេខរៀង', 'បរិយាយ', 'ចំនួនទឹកប្រាក់', 'អ្នកស្នើ', 'កាលបរិច្ឆេទ');

        $callback = function() use($expenses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($expenses as $expense) {
                $row['លេខរៀង']  = $expense->id;
                $row['បរិយាយ']    = $expense->description;
                $row['ចំនួនទឹកប្រាក់']  = '$' . $expense->amount;
                $row['អ្នកស្នើ'] = $expense->requester_name;
                $row['កាលបរិច្ឆេទ'] = $expense->created_at;

                fputcsv($file, array($row['លេខរៀង'], $row['បរិយាយ'], $row['ចំនួនទឹកប្រាក់'], $row['អ្នកស្នើ'], $row['កាលបរិច្ឆេទ']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ១. បង្ហាញទំព័រ Form សម្រាប់កែប្រែ (Edit)
    public function editExpense($id)
    {
        $expense = \App\Models\Expense::findOrFail($id);
        return view('reports.expense-edit', compact('expense'));
    }

    // ២. រក្សាទុកការកែប្រែចូល Database (Update)
   public function updateExpense(\Illuminate\Http\Request $request, $id)
{
    // ១. Validate ទិន្នន័យ
    $request->validate([
        'description' => 'required|string|max:255',
        'amount'      => 'required|numeric|min:0',
    ]);

    // ២. ស្វែងរកទិន្នន័យចំណាយតាម ID
    $expense = \App\Models\Expense::findOrFail($id);

    // ៣. Update ចូល Database (ដក 'title' ចេញដាច់ខាត)
    $expense->update([
        'description'  => $request->description,
        'amount'       => $request->amount,
        'expense_date' => $request->expense_date ?? $expense->expense_date,
    ]);

    // ៤. Redirect ត្រឡប់ទៅវិញ
    return redirect(url('/team-report'))->with('success', 'បានកែប្រែការចំណាយបានជោគជ័យ!');
}

    // ៣. លុបទិន្នន័យ (Delete)
    public function destroyExpense($id)
    {
        $expense = \App\Models\Expense::findOrFail($id);
        $expense->delete();

        return redirect()->back()->with('success', 'លុបចំណាយបានជោគជ័យ! 🗑️');
    }





}
