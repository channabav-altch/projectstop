<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\AiVisionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockPurchaseController;

use App\Models\Order;
use App\Models\OrderItem;
Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    $todayRevenue = Order::whereDate('created_at', today())->sum('grand_total') ?? 0;
    $todayItemsSold = OrderItem::whereDate('created_at', today())->sum('qty') ?? 0;

    // 🔴 បន្ថែមកូដទាញយកចំនួនស្តុកសរុបដែលនៅសល់ 🔴
    $totalStock = \App\Models\Product::sum('stock_qty') ?? 0;

    // បញ្ជូនទិន្នន័យ $totalStock ទៅកាន់ View
    return view('dashboard', compact('todayRevenue', 'todayItemsSold', 'totalStock'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/audit', function () {
    // ទាញយកផលិតផលទាំងអស់មកបង្ហាញដើម្បីរាប់
    $products = Product::orderBy('id', 'desc')->get();
    return view('audit.index', compact('products'));
})->middleware(['auth'])->name('audit.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🟢 កូដថ្មី (បញ្ជាឲ្យរត់ទៅយកទិន្នន័យពី Controller មកបង្ហាញ)
Route::get('/dashboard', [\App\Http\Controllers\Controller::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');

    // សម្រាប់បង្ហាញផ្ទាំងកែប្រែ
Route::get('/products/{id}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');

// សម្រាប់ Save ទិន្នន័យដែលបានកែរួចទៅ Database
Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');

// សម្រាប់លុបទិន្នន័យ
Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');

// សម្រាប់បង្ហាញផ្ទាំងគិតស្តុក
Route::get('/stock-audit', [App\Http\Controllers\ProductController::class, 'stockAudit'])->name('stock.audit');

// សម្រាប់បញ្ជូនទិន្នន័យទៅ Save ក្នុង Database
Route::post('/stock-audit', [App\Http\Controllers\ProductController::class, 'updateStockAudit'])->name('stock.audit.update');

// សម្រាប់បង្ហាញផ្ទាំងគិតដកស្តុក
Route::get('/stock-deduct', [App\Http\Controllers\ProductController::class, 'stockDeduct'])->name('stock.deduct');

// សម្រាប់បញ្ជូនទិន្នន័យទៅកាត់ស្តុកក្នុង Database
Route::post('/stock-deduct', [App\Http\Controllers\ProductController::class, 'updateStockDeduct'])->name('stock.deduct.update');

// សម្រាប់បង្ហាញផ្ទាំងស្តុកក្នុងឃ្លាំងសរុប
Route::get('/stock-summary', [App\Http\Controllers\ProductController::class, 'stockSummary'])->name('stock.summary');

// សម្រាប់បង្ហាញផ្ទាំងចងកញ្ចប់ឈុត
Route::get('/bundle-create', [App\Http\Controllers\ProductController::class, 'bundleCreate'])->name('bundle.create');

// សម្រាប់រក្សាទុកឈុតថ្មីចូល Database
Route::post('/bundle-store', [App\Http\Controllers\ProductController::class, 'bundleStore'])->name('bundle.store');

// សម្រាប់បង្ហាញផ្ទាំង ស្តុកបានលក់ចេញ (Sold Stock)
// Route::get('/stock-sold', [App\Http\Controllers\ProductController::class, 'stockSold'])->name('stock.sold');
// ✅ ទុកតែមួយបន្ទាត់នេះគត់ សម្រាប់បញ្ជាទៅកាន់ ProductController ✅
// កែប្រែឈ្មោះ Controller ក្នុងនេះឱ្យត្រូវនឹងកន្លែងដែលបងដាក់កូដ
Route::get('/stock-sold', [\App\Http\Controllers\StockController::class, 'stockSoldReport'])->middleware(['auth', 'verified'])->name('stock.sold');
// សម្រាប់បង្ហាញផ្ទាំង ស្តុកបច្ចុប្បន្នជាក់ស្តែង (Actual Current Stock)
Route::get('/stock-current', [App\Http\Controllers\ProductController::class, 'stockCurrent'])->name('stock.current');

// សម្រាប់រក្សាទុកឈុតថ្មីទៅក្នុង Database
Route::post('/bundle-store', [App\Http\Controllers\ProductController::class, 'storeBundle'])->name('bundle.store');

Route::get('/pos', [App\Http\Controllers\ProductController::class, 'posIndex'])->name('pos.index');

Route::get('/pos', [PosController::class, 'index'])->name('pos.index');

// 🔴 ២. សម្រាប់ទទួលទិន្នន័យទូទាត់ប្រាក់ (POST) - បន្ថែមមួយជួរនេះ 🔴
Route::post('/pos', [PosController::class, 'store'])->name('pos.store');

// 🔴 Route ថ្មីសម្រាប់ទទួលទិន្នន័យ និង Save ចូល Database (POST)
Route::post('/pos/checkout', [App\Http\Controllers\PosController::class, 'store'])->name('pos.checkout');

// សម្រាប់បង្ហាញផ្ទាំងបញ្ជីឈ្មោះអតិថិជន
Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');

Route::post('/customers/store', [App\Http\Controllers\CustomerController::class, 'store'])->name('customers.store');
Route::put('/customers/update/{id}', [App\Http\Controllers\CustomerController::class, 'update'])->name('customers.update');
Route::delete('/customers/destroy/{id}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('customers.destroy');
// 🔴 ទ្វារសម្រាប់បង្ហាញទំព័រព័ត៌មានលក់ថ្ងៃនេះ (ទំព័រថ្មី)
Route::get('/pos/sales-today', [App\Http\Controllers\PosController::class, 'salesToday'])->name('pos.sales_today');

Route::get('/pos/summary', [App\Http\Controllers\PosController::class, 'summary'])->name('pos.summary');

// Route::get('/pos/edit/{id}', [App\Http\Controllers\PosController::class, 'edit'])->name('pos.edit');
Route::get('/pos/edit/{id}', [\App\Http\Controllers\PosController::class, 'editOrder']);
Route::post('/pos/update/{id}', [\App\Http\Controllers\PosController::class, 'updateOrder']);
Route::delete('/pos/destroy/{id}', [App\Http\Controllers\PosController::class, 'destroy'])->name('pos.destroy');
// Route សម្រាប់លុបវិក្កយបត្រ
Route::delete('/pos/delete/{id}', [\App\Http\Controllers\PosController::class, 'deleteOrder']);
// ថែមបន្ទាត់នេះមួយទៀត ដើម្បីឲ្យវាស្គាល់មុខងារទូទាត់ប្រាក់កែប្រែថ្មី (PUT method)
// Route::put('/pos/update/{id}', [App\Http\Controllers\PosController::class, 'update'])->name('pos.update');
Route::post('/pos/cancel/{id}', [\App\Http\Controllers\PosController::class, 'cancelOrder']);
// 🔴 កូដដែលត្រឹមត្រូវ ត្រូវតែមាន ->name('pos.checkout') នៅខាងចុង 🔴
Route::post('/checkout', [PosController::class, 'checkout'])->name('pos.checkout');

Route::get('/admin-report', [App\Http\Controllers\ReportController::class, 'adminReport'])->name('admin.report');

// បន្ថែម Route នេះ សម្រាប់ទំព័រស្តុកថ្មី និងទិញចូល
Route::get('/stock-purchase', [StockController::class, 'purchaseIndex'])->name('stock.purchase');
// Route សម្រាប់ទាញយកទំព័របង្ហាញ (មានស្រាប់)
Route::get('/stock-supplier', [App\Http\Controllers\StockController::class, 'supplierIndex'])->name('stock.supplier');

// 🔴 បន្ថែម Route នេះ សម្រាប់ទទួលទិន្នន័យពេលចុច Save (POST) 🔴
Route::post('/stock-supplier', [App\Http\Controllers\StockController::class, 'storeSupplier'])->name('stock.supplier.store');
Route::get('/finance', [App\Http\Controllers\ReportController::class, 'finance'])->name('finance.index');
// 🔴 បន្ថែម Route នេះ សម្រាប់កែប្រែទិន្នន័យ (PUT method)
Route::put('/stock-supplier/{id}', [App\Http\Controllers\StockController::class, 'updateSupplier'])->name('stock.supplier.update');

Route::get('/team-report', [App\Http\Controllers\ReportController::class, 'teamReport'])->name('team.report');

Route::post('/expenses/store', [App\Http\Controllers\ReportController::class, 'storeExpense'])->name('expense.store');

Route::get('/report/export', [App\Http\Controllers\ReportController::class, 'exportData'])->name('report.export');

// Route សម្រាប់កែប្រែ (Update) និង លុប (Delete) ការចំណាយ
// ត្រូវប្រាកដថាប្រើ /expenses/update/{id}
Route::put('/expense/update/{id}', [App\Http\Controllers\ReportController::class, 'updateExpense'])->name('expense.update');
Route::delete('/expense/delete/{id}', [App\Http\Controllers\ReportController::class, 'deleteExpense'])->name('expense.delete');

// ត្រូវប្រាកដថាមាន ->name('report.index') នៅចុងកូដ
Route::get('/team-report', [ReportController::class, 'index'])->name('report.index');

Route::get('/team-report', [\App\Http\Controllers\ReportController::class, 'index'])
    ->name('team-report'); // 👈 ដាក់ឈ្មោះនេះឲ្យត្រូវគ្នា

Route::post('/pos/ai-vision', [AiVisionController::class, 'extractInvoice']);

// កូដចាស់បងដាក់ពាក្យ 'index'
Route::get('/team-report', [ReportController::class, 'index']);

// 🔴 កូដថ្មី (ត្រូវកែទៅជាឈ្មោះ Function ពិតប្រាកដ)
Route::get('/team-report', [ReportController::class, 'teamReport']);

// 🔴 បន្ថែម Route សម្រាប់ទាញយករបាយការណ៍ជា PDF 🔴
Route::get('/admin-report/pdf', [App\Http\Controllers\StockController::class, 'exportAdminReportPdf'])->name('admin.report.pdf');
});

// // 🔴 កូដថ្មីសម្រាប់ទំព័រ របាយការណ៍ស្តុកលក់ចេញ 🔴
// Route::get('/stock-sold', function () {
//     // ១. ទាញទិន្នន័យទំនិញដែលបានលក់ចេញទាំងអស់ (ភ្ជាប់ជាមួយឈ្មោះ Product និង វិក្កយបត្រ Order)
//     // បើចង់បង្ហាញតែថ្ងៃនេះ អាចថែម ->whereDate('created_at', today()) ពីមុខ ->latest()
//     $soldItems = OrderItem::with(['product', 'order'])->latest()->get();

//     // ២. គណនាសរុប
//     $totalQty = $soldItems->sum('qty') ?? 0;

//     // គណនាទឹកប្រាក់សរុប (យកចំនួនគុណតម្លៃ)
//     $totalAmount = $soldItems->sum(function ($item) {
//         return $item->qty * $item->price;
//     }) ?? 0;

//     // ៣. បញ្ជូនទិន្នន័យទៅកាន់ឯកសារ sold.blade.php
//     return view('products.sold', compact('soldItems', 'totalQty', 'totalAmount'));
// })->middleware(['auth', 'verified'])->name('stock.sold');


// Route::get('/stock-sold', function (\Illuminate\Http\Request $request) {

//     // ១. ទាញយកទំនិញដែលលក់ចេញទាំងអស់ ភ្ជាប់ជាមួយ Product និង Order
//     $soldItems = \App\Models\OrderItem::with(['product', 'order'])->latest()->get();

//     // ២. គណនាចំនួនមុខទំនិញសរុប (បូកបញ្ជូល Column qty)
//     $totalQty = $soldItems->sum('qty');

//     // ៣. គណនាចំណូលសរុប (យក ចំនួនទំនិញ x តម្លៃរាយ)
//     $totalRevenue = $soldItems->sum(function ($item) {
//         return $item->qty * $item->unit_price;
//     });

//     // ៤. បញ្ជូនទិន្នន័យទៅកាន់ទំព័រ Blade
//     // (ចំណាំ៖ ប្តូរ 'stock-sold' ទៅជាឈ្មោះឯកសារ Blade របាយការណ៍នេះរបស់បង)
//     // 🔴 ប្តូរទៅរកក្នុង folder 'products' និង file ឈ្មោះ 'sold' 🔴
// return view('products.sold', compact('soldItems', 'totalQty', 'totalRevenue'));
// })->name('stock.sold');

// ២. ទំព័រ "លក់ចេញ" (Sold Items)
// Route::get('/stock-sold', function () {
//     $soldItems = \App\Models\OrderItem::with('order')->latest()->get();

//     // 🔴 ត្រូវប្រាកដថាវារត់ទៅរក products.sold 🔴
//     return view('products.sold', compact('soldItems'));
// })->name('stock.sold');

// ១. ទំព័រ "ទិញចូល" (Stock Purchase)
Route::get('/stock-purchase', function () {
    $purchases = \App\Models\StockMovement::latest()->get();
    $totalQty = $purchases->sum('qty');
    $totalAmount = $purchases->sum('total_amount');

    // 🔴 ត្រូវប្រាកដថាវារត់ទៅរក stock.purchase 🔴
    return view('stock.purchase', compact('purchases', 'totalQty', 'totalAmount'));
});



/// ======================================================================
// 🔴 មុខងារសម្រាប់ SAVE ទិន្នន័យពេលចុចប៊ូតុង "រក្សាទុកការទិញចូល" (POST)
// ======================================================================
Route::post('/stock-purchase', function (\Illuminate\Http\Request $request) {
    try {
        // ១. ស្វែងរកមុខទំនិញ (បើអត់ទាន់មានក្នុងស្តុកទេ ឲ្យវាបង្កើតថ្មីស្វ័យប្រវត្តិ)

        // 🔴 កែត្រង់នេះដាក់ពាក្យ 'product_name' 🔴
        $product = \App\Models\Product::where('product_name', $request->product_name)->first();

        if (!$product) {
            $product = new \App\Models\Product();

            // 🔴 កែត្រង់នេះមួយទៀតដាក់ពាក្យ 'product_name' 🔴
            $product->product_name = $request->product_name;

            $product->save();
        }

       // ២. កត់ត្រាការទិញចូលទៅក្នុងតារាង StockMovement
        $stock = new \App\Models\StockMovement();
        $stock->product_id = $product->id;
        $stock->user_id = 1;
        $stock->type = 'in';
        $stock->qty = $request->qty;

        // 🔴 ឥឡូវ Database យើងមាន Column ទាំងនេះហើយ អាច Save ចូលបានដោយសេរី 🔴
        $stock->invoice_no = $request->invoice_no;
        $stock->supplier   = $request->supplier;
        $stock->unit_price = $request->unit_price;

        // ថ្ងៃខែទិញចូល
        if ($request->purchase_date) {
            $stock->created_at = $request->purchase_date . ' ' . now()->format('H:i:s');
        }

        $stock->save();

        // ៣. ពេល Save រួច ឲ្យវា Refresh ទំព័រហ្នឹងឡើងវិញ
        return redirect()->back()->with('success', 'រក្សាទុកការទិញចូលបានជោគជ័យ!');

    } catch (\Exception $e) {
        // បើបងដាក់ឈ្មោះ Column ខុស វានឹងលោត Error ប្រាប់ចំៗនៅទីនេះ
        dd("មានបញ្ហាត្រង់កន្លែងណាមួយក្នុង Database៖ ", $e->getMessage());
    }
});

Route::get('/stock-purchase', function (\Illuminate\Http\Request $request) {

    $search = $request->input('search');
    $date = $request->input('date');

    $query = \App\Models\StockMovement::query();

    // ច្រោះតាម ថ្ងៃខែ (បើមានជ្រើសរើស)
    if ($date) {
        $query->whereDate('created_at', $date);
    }

    // ច្រោះតាម ការស្វែងរក (បើមានវាយអក្សរ)
    if ($search) {
        $query->where(function($q) use ($search) {
            // សូមដូរ 'product_name' ឲ្យត្រូវនឹង Column ក្នុងตារាង stock_movements របស់បង
            $q->where('product_name', 'like', "%{$search}%");
        });
    }

    $purchases = $query->latest()->get();

    $totalQty = $purchases->sum('qty');
    $totalAmount = $purchases->sum('total_amount');

    return view('stock.purchase', compact('purchases', 'totalQty', 'totalAmount', 'date', 'search'));

})->name('stock.purchase');


Route::get('/pos/sales-today', function () {

    // 🔴 យើងសាកល្បងទាញយកទិន្នន័យ [ទាំងអស់] តែម្តង ដោយមិនបាច់ខ្វល់ពីថ្ងៃខែសិន
    // ដើម្បីចង់ដឹងថាតើក្នុង Database យើងពិតជាមានទិន្នន័យហ្នឹងឬអត់?
    $orders = \App\Models\Order::latest()->get();

    return view('pos.sales_today', compact('orders'));

})->name('pos.sales_today');

Route::get('/pos/sales-today', function (\Illuminate\Http\Request $request) {

    $date = $request->input('date', now()->format('Y-m-d'));
    $search = $request->input('search');
    $status = $request->input('status', 'all'); // 🔴 ១. ចាប់យក status ពី URL (បើគ្មានទេ យក all)

    $query = \App\Models\Order::query();

    // លក្ខខណ្ឌច្រោះតាម "ថ្ងៃខែ"
    if ($date) {
        $query->whereDate('created_at', $date);
    }

    // លក្ខខណ្ឌច្រោះតាម "ការស្វែងរក"
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('invoice_no', 'like', "%{$search}%")
              ->orWhere('customer_name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    // 🔴 ២. លក្ខខណ្ឌច្រោះតាម "ស្ថានភាព (Status)"
    if ($status && $status !== 'all') {
        $query->where('status', $status);
    }

    $orders = $query->latest()->get();

    // 🔴 ៣. បញ្ជូន $status ទៅកាន់ Blade វិញ
    return view('pos.sales_today', compact('orders', 'date', 'search', 'status'));

})->name('pos.sales_today');

// ផ្លូវសម្រាប់បើកទំព័រទម្រង់ (Form) បន្ថែម Supplier ថ្មី
Route::get('/stock-supplier/create', function () {
    return view('stock.supplier-create');
})->name('stock.supplier.create');

Route::get('/stock-supplier', [\App\Http\Controllers\StockController::class, 'index'])
    ->name('stock.supplier'); // 👈 កន្ទុយឈ្មោះនេះសំខាន់ណាស់

// ផ្លូវសម្រាប់ Save ទិន្នន័យចូល Database
Route::post('/stock-supplier', [\App\Http\Controllers\StockController::class, 'store'])
    ->name('stock.supplier.store');

    Route::get('/expenses/{id}/edit', [\App\Http\Controllers\ReportController::class, 'editExpense'])->name('expenses.edit');
Route::put('/expenses/{id}', [\App\Http\Controllers\ReportController::class, 'updateExpense'])->name('expenses.update');
Route::delete('/expenses/{id}', [\App\Http\Controllers\ReportController::class, 'destroyExpense'])->name('expenses.destroy');

// 🟢 Route សម្រាប់កែប្រែ និង លុប ទិន្នន័យទំនិញលក់ចេញ 🟢
Route::get('/stock-sold/edit/{id}', [\App\Http\Controllers\Controller::class, 'editSoldItem'])->name('stock-sold.edit');
Route::delete('/stock-sold/delete/{id}', [\App\Http\Controllers\Controller::class, 'deleteSoldItem'])->name('stock-sold.delete');
// Route សម្រាប់ទទួលទិន្នន័យពី Form យកទៅ Save ចូល Database
Route::post('/stock-sold/update/{id}', [\App\Http\Controllers\Controller::class, 'updateSoldItem'])->name('stock-sold.update');

Route::get('/pos/print', [\App\Http\Controllers\PosController::class, 'printSales']);


// 🟢 Route សម្រាប់ឲ្យ AI អានវិក្កយបត្រ 🟢
Route::post('/extract-invoice', [App\Http\Controllers\AiVisionController::class, 'extractInvoice']);

Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

// Route សម្រាប់បង្ហាញ Form Edit (បងប្រហែលជាមានហើយ)
Route::get('/stock-purchase/{id}/edit', [App\Http\Controllers\StockPurchaseController::class, 'edit'])->name('stock-purchase.edit');

// 🟢 សូមបន្ថែម Route Update មួយនេះចូល (វាសំខាន់សម្រាប់ Form នេះ) 🟢
Route::put('/stock-purchase/{id}', [App\Http\Controllers\StockPurchaseController::class, 'update'])->name('stock-purchase.update');

// Route សម្រាប់លុប (បងប្រហែលជាមានហើយ)
Route::delete('/stock-purchase/{id}', [App\Http\Controllers\StockPurchaseController::class, 'destroy'])->name('stock-purchase.destroy');

// 🟢 សូមបន្ថែម ->name('stock-purchase.store') នៅចុងបញ្ចប់នៃ Route នេះ 🟢
Route::post('/stock-purchase', [App\Http\Controllers\StockPurchaseController::class, 'store'])->name('stock-purchase.store');


Route::get('/stock-purchase', [App\Http\Controllers\StockPurchaseController::class, 'index'])->name('stock-purchase.index');

// ផ្លូវសម្រាប់មើលរបាយការណ៍លម្អិតរបស់អ្នកលក់
Route::get('/admin-report/seller/{id}', [\App\Http\Controllers\ReportController::class, 'sellerDetail'])->name('report.seller_detail');

Route::get('/finance', [ReportController::class, 'finance']);

require __DIR__.'/auth.php';
