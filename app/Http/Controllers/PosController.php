<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
// ឧទាហរណ៍៖ Model សម្រាប់រក្សាទុកការលក់ (បើបងមាន Model ផ្សេង អាចដូរឈ្មោះបាន)
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

use App\Models\Sale;      // ត្រូវបង្កើត Model Sale
use App\Models\SaleDetail; // ត្រូវបង្កើត Model SaleDetail

class PosController extends Controller
{
    // ========================================================
    // ១. មុខងារសម្រាប់ទាញទិន្នន័យមកបង្ហាញលើផ្ទាំង POS (GET)
    // ========================================================
    public function index()
    {
        // ទាញទំនិញទាំងអស់ពី PostgreSQL មកបង្ហាញ
        $products = Product::all();

        return view('pos.index', compact('products'));
    }

    // ========================================================
    // ២. មុខងារសម្រាប់ទទួលទិន្នន័យទូទាត់ប្រាក់ និង Save & កាត់ស្តុក (POST)
    // ========================================================
 public function store(Request $request)
{
    // ១. Validate ទិន្នន័យពី POS Form
    $validated = $request->validate([
        'invoice_no'     => 'required|string',
        'total_amount'   => 'required|numeric',
        'customer_type'  => 'nullable|string',
        'customer_name'  => 'nullable|string',
        'phone'          => 'nullable|string',
        'province'       => 'nullable|string',
        'address_detail' => 'nullable|string',
        'delivery_method'=> 'nullable|string',
        'delivery_fee'   => 'nullable|numeric',
        'note'           => 'nullable|string',
        'status'         => 'required|string',
        'payment_method' => 'nullable|string',
    ]);

    try {
        // ការពារពេលវិក្កយបត្រជាន់គ្នា ឱ្យវាថែមលេខកន្ទុយអូតូ
        $invNo = $validated['invoice_no'];
        if (\App\Models\Order::where('invoice_no', $invNo)->exists()) {
            $invNo = $invNo . '-' . rand(100, 999);
        }

        // ២. បញ្ចូលទិន្នន័យទៅក្នុងតារាង orders
        $order = new \App\Models\Order();
        $order->user_id         = auth()->id() ?? 1;
        $order->invoice_no      = $invNo;
        $order->total_amount    = $validated['total_amount'];
        $order->customer_type   = $validated['customer_type'] ?? 'walkin';
        $order->customer_name   = $validated['customer_name'] ?? 'អតិថិជនទិញផ្ទាល់';
        $order->phone           = $validated['phone'] ?? null;
        $order->province        = $validated['province'] ?? null;
        $order->address_detail  = $validated['address_detail'] ?? null;
        $order->delivery_method = $validated['delivery_method'] ?? 'VET';
        $order->delivery_fee    = $validated['delivery_fee'] ?? 0;
        $order->note            = $validated['note'] ?? null;
        $order->status          = $validated['status'];
        $order->payment_method  = $validated['payment_method'] ?? 'សាច់ប្រាក់';
        $order->save();

        // ៣. ដំណើរការកាត់ស្តុកទំនិញ និងកត់ត្រា order_items
        $cartItems = json_decode($request->cart_data, true);

        if ($cartItems && is_array($cartItems)) {
            foreach ($cartItems as $item) {

                $product = \App\Models\Product::find($item['id']);
                $sell_qty = $item['qty']; // ចំនួនដែលអតិថិជនទិញ

                if ($product) {
                    // ទាញយកទិន្នន័យកូនពី bundle_items ដោយប្រើ product_id ត្រឹមត្រូវ
                    $subItems = \DB::table('bundle_items')
                                   ->where('product_id', $product->id)
                                   ->orWhere('product_bundle_id', $product->id)
                                   ->get();

                    if ($subItems->count() > 0) {
                        // ប្រសិនបើវាជាទំនិញឈុត (Bundle) => កាត់ស្តុកកូនៗ
                        foreach ($subItems as $sub) {
                            $childId = $sub->product_id ?? $sub->item_id;
                            $childProduct = \App\Models\Product::find($childId);

                            if ($childProduct) {
                                $bundle_item_qty = $sub->qty ?? $sub->quantity ?? 1;
                                $qtyToDeduct = $bundle_item_qty * $sell_qty;

                                // កាត់ស្តុកកូនចេញ
                                $childProduct->decrement('qty', $qtyToDeduct);
                            }
                        }
                    } else {
                        // ប្រសិនបើវាជាទំនិញរាយធម្មតា => កាត់ស្តុកធម្មតា
                        $product->decrement('qty', $sell_qty);
                    }
                }

                // ៤. បង្កើតទិន្នន័យលម្អិតចូលតារាង order_items
                \App\Models\OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'qty'        => $sell_qty,
                    'unit_price' => $item['price'],
                    'total'      => $sell_qty * $item['price'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'ទូទាត់ប្រាក់ និងកាត់ស្តុកបានជោគជ័យ!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'បរាជ័យ! មានបញ្ហាក្នុងការទូទាត់៖ ' . $e->getMessage());
    }
}
    // 🔴 Function សម្រាប់ទំព័រព័ត៌មានលក់ថ្ងៃនេះ
    // 🔴 Function សម្រាប់ទំព័រព័ត៌មានលក់ថ្ងៃនេះ 🔴
    // 🔴 មុខងារទាញយករបាយការណ៍លក់ (អាច Filter តាមថ្ងៃបាន) 🔴
    // 🔴 មុខងារទាញយករបាយការណ៍លក់ (Filter តាមថ្ងៃ និង ស្វែងរក) 🔴
    // 🔴 មុខងារទាញយករបាយការណ៍លក់ (មានមុខងារ Filter Status ពេញលេញ) 🔴
    public function salesToday(Request $request)
    {
        // ១. ចាប់យក "ថ្ងៃខែ", "ពាក្យស្វែងរក", និង "ស្ថានភាព (Status)" ពី URL
        $selectedDate = $request->date ?? now()->timezone('Asia/Phnom_Penh')->format('Y-m-d');
        $search = $request->search;
        $status = $request->status ?? 'all';

        // ២. 🟢 ចាប់ផ្តើម Query ដោយភ្ជាប់ទំនិញកូនមកជាមួយស្រាប់ (with) និងត្រងយកតែថ្ងៃដែលជ្រើសរើស 🟢
        $query = \App\Models\Order::with(['orderItems.product'])->whereDate('created_at', $selectedDate);

        // ៣. លក្ខខណ្ឌស្វែងរក (Search) តាមឈ្មោះ លេខទូរស័ព្ទ ឬ លេខវិក្កយបត្រ
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%')
                  ->orWhere('invoice_no', 'LIKE', '%' . $search . '%');
            });
        }

        // ៤. លក្ខខណ្ឌតម្រងស្ថានភាព (Filter by Status ប៊ូតុងចុច)
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // ៥. ទាញយកទិន្នន័យចុងក្រោយ (បញ្ជាឲ្យកូដខាងលើដំណើរការរួមគ្នា)
        $orders = $query->latest()->get();

        // ៦. បញ្ជូនទិន្នន័យទៅកាន់ View (បញ្ជូនតែម្តងគត់កុំឲ្យ Error)
        return view('pos.sales_today', compact('orders', 'selectedDate'));
    }
    // 🔴 ឧទាហរណ៍ឈ្មោះមុខងារបើកទំព័រកាតសរុបរបស់បង
    public function summary()
    {
        // ១. គណនាទិន្នន័យ (បើបងចង់បានតែថ្ងៃនេះ អាចថែម ->whereDate('created_at', today()) ពីមុខ ->sum() បាន)
        $totalInvoices = \App\Models\Order::count();
        $totalDeliveryFee = \App\Models\Order::sum('delivery_fee');
        $grandTotal = \App\Models\Order::sum('total_amount');
        $totalProductRevenue = $grandTotal - $totalDeliveryFee;

        // 🔴 ចំណុចសំខាន់បំផុតទី១៖ ត្រូវប្រាកដថាមានពាក្យ compact នេះ ទើបទិន្នន័យហោះទៅ Blade 🔴
        // 🔴 ដាក់ពាក្យ pos. នៅពីមុខ 🔴
return view('pos.summary', compact('totalInvoices', 'totalDeliveryFee', 'totalProductRevenue'));
    }

public function checkout(Request $request)
{
    dd($request->all());
    // ១. ទទួលទិន្នន័យដែល JavaScript បោះមកឲ្យ
    $cartData = json_decode($request->cart_data, true);

    if (!$cartData || count($cartData) == 0) {
        return back()->with('error', 'មិនមានទំនិញក្នុងកន្ត្រកទេ!');
    }

    DB::beginTransaction();
        try {
            // ២. បង្កើតវិក្កយបត្រមេ (Order) ដោយប្រើក្បួនថ្មី (ធានាថាចូល Database ១០០%)
            $order = new Order();
            $order->user_id         = auth()->id() ?? 1;
            $order->invoice_no      = 'INV-' . time();
            $order->total_amount    = $request->grand_total ?? 0;
            $order->customer_type   = $request->customer_type ?? 'walkin';

            // បញ្ជូនទិន្នន័យចំៗចូលទៅ Column នីមួយៗ
            $order->customer_name   = $request->customer_name;
            $order->phone           = $request->phone;
            $order->province        = $request->province;
            $order->address_detail  = $request->address_detail;
            $order->delivery_method = $request->delivery_method;
            $order->delivery_fee    = $request->delivery_fee ?? 0;
            $order->note            = $request->note;
            $order->status          = $request->status ?? 'paid';

            $order->save(); // 👈 កន្លែងនេះហើយដែលបញ្ជាឲ្យ Save ចូល Database យ៉ាងពិតប្រាកដ!

            // ៣. Save ទំនិញលម្អិតចូល order_items និង កាត់ស្តុក (កូដនៅរក្សាដដែល)
            foreach ($cartData as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'qty'        => $item['qty'],
                    'unit_price' => $item['price'],
                ]);

                // កាត់ស្តុក
                Product::where('id', $item['id'])->decrement('qty', $item['qty']);
            }

            DB::commit();

            return redirect()->route('pos.index')->with('success', 'ការទូទាត់ប្រាក់ទទួលបានជោគជ័យ!');

    } catch (\Exception $e) {
        DB::rollBack();

        // 🔴 ដាក់កូដនេះ ដើម្បីឲ្យវាបង្ហាញ Error ពណ៌ខ្មៅនៅលើអេក្រង់ឲ្យយើងឃើញច្បាស់ 🔴
        dd('បញ្ហាគឺ៖ ' . $e->getMessage(), 'ត្រង់បន្ទាត់ទី៖ ' . $e->getLine());

        // return back()->with('error', 'មានបញ្ហា'); // (បិទអាចាស់នេះសិន)
    }
}

// 🔴 មុខងារសម្រាប់លុបវិក្កយបត្រ (Delete)
    public function destroy($id)
    {
        try {
            $order = \App\Models\Order::findOrFail($id);

            // លុបទំនិញលម្អិតក្នុងវិក្កយបត្រនោះចោលសិន (ដើម្បីកុំឲ្យសល់សំរាមក្នុង DB)
            \App\Models\OrderItem::where('order_id', $id)->delete();

            // លុបវិក្កយបត្រមេ
            $order->delete();

            return redirect()->back()->with('success', 'វិក្កយបត្រត្រូវបានលុបដោយជោគជ័យ!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'មានបញ្ហាក្នុងការលុប៖ ' . $e->getMessage());
        }
    }

   // 🔴 បើកទំព័រកែប្រែ
    public function edit($id)
    {
        $order = \App\Models\Order::findOrFail($id);

        // បញ្ជាឲ្យវាបើកទំព័រ edit.blade.php ដែលយើងទើបបង្កើតអម្បាញ់មិញ
        return view('pos.edit', compact('order'));
    }

    // 🔴 មុខងារ Save ទុកពេលកែប្រែរួច
    public function update(Request $request, $id)
    {
        try {
            $order = \App\Models\Order::findOrFail($id);

            // ទាញយកទិន្នន័យពី Form Edit មក Update
            $order->customer_name   = $request->customer_name;
            $order->phone           = $request->phone;
            $order->province        = $request->province;
            $order->delivery_method = $request->delivery_method;
            $order->delivery_fee    = $request->delivery_fee ?? 0;
            $order->note            = $request->note;
            $order->status          = $request->status;

            $order->save();

            // ពេលកែប្រែជោគជ័យ ឲ្យលោតមកទំព័ររបាយការណ៍វិញ
            return redirect()->route('pos.sales_today')->with('success', 'វិក្កយបត្រត្រូវបានកែប្រែដោយជោគជ័យ!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'មានបញ្ហាក្នុងការកែប្រែ៖ ' . $e->getMessage());
        }
    }

    // 🔴 នេះជាឧទាហរណ៍មុខងារដែលបើកទំព័ររបាយការណ៍សរុប (បងត្រូវរកមើលឈ្មោះមុខងារពិតប្រាកដរបស់បង)
    public function dashboard()
    {
        // ១. គណនាទិន្នន័យពី Database ផ្ទាល់ៗ
        $totalInvoices = \App\Models\Order::count(); // រាប់ចំនួនវិក្កយបត្រ
        $totalDeliveryFee = \App\Models\Order::sum('delivery_fee'); // បូកថ្លៃដឹកទាំងអស់
        $grandTotal = \App\Models\Order::sum('total_amount'); // បូកប្រាក់សរុបទាំងអស់

        // ២. រកចំណូលផលិតផលសុទ្ធ (យកសរុបរួម ដកថ្លៃដឹកចេញ)
        $totalProductRevenue = $grandTotal - $totalDeliveryFee;

        // ៣. 🔴 ចំណុចសំខាន់៖ ត្រូវបញ្ជូនអថេរទាំង ៣ នេះទៅកាន់ View 🔴
        // (សូមប្រាកដថាឈ្មោះ view 'pos.dashboard' នេះត្រូវគ្នានឹងឈ្មោះឯកសារ Blade របស់បង)
        return view('pos.dashboard', compact('totalInvoices', 'totalDeliveryFee', 'totalProductRevenue'));
    }

// ទាញទិន្នន័យវិក្កយបត្រយកទៅបង្ហាញលើ Form
    public function editOrder($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        return view('pos.edit', compact('order'));
    }

    // ទទួលទិន្នន័យពី Form យកមក Save បញ្ចូល Database វិញ
    public function updateOrder(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);

        // កែប្រែព័ត៌មានអតិថិជន និងការដឹកជញ្ជូន
        $order->customer_name = $request->input('customer_name');
        $order->phone = $request->input('phone');
        $order->delivery_method = $request->input('delivery_method');
        $order->province = $request->input('province');
        $order->note = $request->input('note');

        // 🟢 កូដថ្មី៖ ចាប់យក Status ពី Form យកមក Save បញ្ចូល Database
        $order->status = $request->input('status');

        $order->save();

        return redirect('pos/sales-today')->with('success', 'កែប្រែព័ត៌មាន និងស្ថានភាពវិក្កយបត្របានជោគជ័យ!');
    }

    // មុខងារសម្រាប់លុបវិក្កយបត្រ
    public function deleteOrder($id)
    {
        $order = \App\Models\Order::findOrFail($id);

        // ១. ត្រូវលុបទំនិញកូនៗដែលមានក្នុងវិក្កយបត្រនេះចោលសិន (ដើម្បីកុំឲ្យទើស Foreign Key)
        \App\Models\OrderItem::where('order_id', $id)->delete();

        // ២. បន្ទាប់មកទើបលុបវិក្កយបត្រមេចោល
        $order->delete();

        return redirect()->back()->with('success', 'បានលុបវិក្កយបត្រចេញពីប្រព័ន្ធដោយជោគជ័យ!');
    }

    public function cancelOrder($id)
{
    try {
        // ចាប់ផ្តើម Transaction (បើ Error ពាក់កណ្តាលទី វានឹងមិនកាត់ស្តុកញ៉េញ៉ៃទេ)
        \Illuminate\Support\Facades\DB::beginTransaction();

        // ១. រកមើលវិក្កយបត្រនោះ
        $order = \App\Models\Order::findOrFail($id);

        // ការពារកុំឲ្យបូកស្តុកផ្ទួនគ្នា (ប្រើ strtoupper ដើម្បីការពារខុសអក្សរតូច/ធំ)
        if (strtoupper($order->status) === 'CANCELED') {
            return redirect()->back()->with('error', 'វិក្កយបត្រនេះត្រូវបានបោះបង់រួចហើយ!');
        }

        // ២. ទាញទំនិញដែលបានលក់ក្នុងវិក្កយបត្រនេះ ដើម្បីយកទៅបូកស្តុកវិញ
        $orderItems = \App\Models\OrderItem::where('order_id', $id)->get();

        foreach ($orderItems as $item) {
            $product = \App\Models\Product::find($item->product_id);

            if ($product) {
                // ឆែកមើលតើជាទំនិញ "ឈុត" ឬទេ?
                if ($product->category === 'ឈុត (Bundle)' || $product->category === 'ឈុត') {
                    $subItems = $product->bundleItems;
                    if ($subItems) {
                        foreach ($subItems as $sub) {
                            $childProduct = \App\Models\Product::find($sub->product_id);
                            if ($childProduct) {
                                // រូបមន្ត៖ ចំនួនកូន x ចំនួនឈុតដែលបានលក់
                                $qtyToReturn = $sub->quantity * $item->qty;
                                $childProduct->increment('qty', $qtyToReturn);
                            }
                        }
                    }
                } else {
                    // បើមិនមែនឈុតទេ គឺបូកស្តុកទំនិញរាយធម្មតា
                    $product->increment('qty', $item->qty);
                }
            }
        }

        // ៣. ប្តូរស្ថានភាពវិក្កយបត្រទៅជា CANCELED
        $order->status = 'CANCELED';
        $order->save();

        // បញ្ជាក់ថាជោគជ័យ
        \Illuminate\Support\Facades\DB::commit();

        return redirect()->back()->with('success', 'វិក្កយបត្រត្រូវបានបោះបង់ និងស្តុកត្រូវបានបូកបញ្ចូលវិញជោគជ័យ!');

    } catch (\Exception $e) {
        // បើមាន Error ទាញទិន្នន័យត្រឡប់ក្រោយវិញ (Rollback)
        \Illuminate\Support\Facades\DB::rollBack();
        return redirect()->back()->with('error', 'មានបញ្ហាពេលបោះបង់វិក្កយបត្រ៖ ' . $e->getMessage());
    }
}

    public function printSales(Request $request)
    {
        // ==========================================
        // ផ្នែកទី១៖ កំណត់ថ្ងៃខែ និងលក្ខខណ្ឌស្វែងរក
        // ==========================================
        $selectedDate = $request->date ?? now()->timezone('Asia/Phnom_Penh')->format('Y-m-d');
        $status = $request->status ?? 'all';
        $search = $request->search;

        // ទាញទិន្នន័យការលក់ (Orders) និងកូនទំនិញ
        $query = \App\Models\Order::with(['orderItems.product'])->whereDate('created_at', $selectedDate);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%')
                  ->orWhere('invoice_no', 'LIKE', '%' . $search . '%');
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->get();

        // គណនាលុយសរុប និងចំនួនវិក្កយបត្រ
        $totalRevenue = $orders->sum('total_amount');
        $totalInvoices = $orders->count();

        // ទាញទិន្នន័យចំណាយ (Expenses) ក្នុងថ្ងៃនោះ
        $expenses = \App\Models\Expense::whereDate('created_at', $selectedDate)->get();
        $totalExpenses = $expenses->sum('amount');

        // ==========================================
        // ផ្នែកទី២៖ បំបែកបញ្ជីតាមទីតាំង (Column 'province')
        // ==========================================

        // ក. យកតែអ្នកដែលបងវាយពាក្យថា "ភ្នំពេញ"
        $ppOrders = $orders->where('province', 'ភ្នំពេញ');

        // ខ. យកអ្នកខេត្ត (លុបចោលភ្នំពេញ និង លុបចោលអ្នកអត់ដាក់ឈ្មោះខេត្ត)
        $provinceOrders = $orders->whereNotIn('province', ['ភ្នំពេញ', '', null]);

        // គ. ទិញផ្ទាល់ (គឺអ្នកដែលបងមិនបានវាយបញ្ចូលឈ្មោះខេត្ត ឬទទេរ)
        $directOrders = $orders->whereIn('province', ['', null]);

        // ==========================================
        // ផ្នែកទី៣៖ បញ្ជីទំនិញចេញសរុប និង ស្តុកបច្ចុប្បន្ន
        // ==========================================

        // បញ្ជីទំនិញចេញសរុប (ទាញយកតែទំនិញដែលស្ថិតក្នុងវិក្កយបត្រថ្ងៃនេះ ដែលបាន Filter រួច)
        $itemsSold = \App\Models\OrderItem::with('product')
            ->whereIn('order_id', $orders->pluck('id'))
            ->selectRaw('product_id, SUM(qty) as total_qty, SUM(total) as total_amount')
            ->groupBy('product_id')
            ->get();

        // បញ្ជីទំនិញស្តុកសរុបទាំងអស់ (ស្តុកបច្ចុប្បន្ន)
        $currentStocks = \App\Models\Product::all();

        // ==========================================
        // ផ្នែកទី៤៖ បញ្ជូនទិន្នន័យទាំងអស់ទៅកាន់ View 'pos.print'
        // ==========================================
        return view('pos.print', compact(
            'orders', 'selectedDate', 'totalRevenue', 'totalInvoices', 'status', 'expenses', 'totalExpenses',
            'provinceOrders', 'ppOrders', 'directOrders', 'itemsSold', 'currentStocks'
        ));
    }


public function processAi(Request $request)
{
    try {

        if (empty($apiKey)) {
            return response()->json(['error' => 'មិនទាន់បានកំណត់ GEMINI_API_KEY ទេ!'], 500);
        }

        // ២. រៀបចំ URL ប្រើ Model gemini-1.5-flash
      // ទាញយកពី env() ដោយផ្ទាល់ ឬពី config() បើ env ស្មើ null
$apiKey = env('GEMINI_API_KEY') ?: config('services.gemini.key');

if (empty($apiKey)) {
    return response()->json(['error' => 'សូមពិនិត្យមើល GEMINI_API_KEY ក្នុង .env ឡើងវិញ!'], 500);
}
        // ៣. ទាញយក Prompt ដែលផ្ញើមក ឬប្រើប្រាស់ Prompt ដើមបើគ្មាន
        $promptText = $request->input('prompt', 'វិភាគទិន្នន័យស្តុក');

        // ៤. ផ្ញើ Request ទៅ Google (មាន withoutVerifying សម្រាប់ Localhost)
        $response = Http::withoutVerifying()
            ->timeout(60)
            ->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $promptText]
                        ]
                    ]
                ]
            ]);

        // ៥. បើកាលណាជោគជ័យ បញ្ជូនទិន្នន័យទៅ Frontend
        if ($response->successful()) {
            return response()->json($response->json());
        }

        // ៦. បង្ហាញ Error លម្អិតប្រសិនបើ Google បដិសេធ
        $errorDetail = $response->json();
        $errorMessage = $errorDetail['error']['message'] ?? 'មានបញ្ហាទាក់ទង Google Gemini API';
        return response()->json(['error' => 'កំហុសពី Google: ' . $errorMessage], 500);

    } catch (\Exception $e) {
        return response()->json(['error' => 'មានបញ្ហាកូដ: ' . $e->getMessage()], 500);
    }
}


}
