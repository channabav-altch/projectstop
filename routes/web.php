<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AiVisionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockPurchaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [Controller::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Products Management
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Stock & Audit Management
    Route::get('/audit', function () {
        $products = Product::orderBy('id', 'desc')->get();
        return view('audit.index', compact('products'));
    })->name('audit.index');

    Route::get('/stock-audit', [ProductController::class, 'stockAudit'])->name('stock.audit');
    Route::post('/stock-audit', [ProductController::class, 'updateStockAudit'])->name('stock.audit.update');

    Route::get('/stock-deduct', [ProductController::class, 'stockDeduct'])->name('stock.deduct');
    Route::post('/stock-deduct', [ProductController::class, 'updateStockDeduct'])->name('stock.deduct.update');

    Route::get('/stock-summary', [ProductController::class, 'stockSummary'])->name('stock.summary');
    Route::get('/stock-current', [ProductController::class, 'stockCurrent'])->name('stock.current');

    // Bundle Management
    Route::get('/bundle-create', [ProductController::class, 'bundleCreate'])->name('bundle.create');
    Route::post('/bundle-store', [ProductController::class, 'storeBundle'])->name('bundle.store');

    // Stock Sold Report
    Route::get('/stock-sold', [StockController::class, 'stockSoldReport'])->name('stock.sold');
    Route::get('/stock-sold/edit/{id}', [Controller::class, 'editSoldItem'])->name('stock-sold.edit');
    Route::post('/stock-sold/update/{id}', [Controller::class, 'updateSoldItem'])->name('stock-sold.update');
    Route::delete('/stock-sold/delete/{id}', [Controller::class, 'deleteSoldItem'])->name('stock-sold.delete');

    // Stock Purchase Management
    Route::get('/stock-purchase', [StockPurchaseController::class, 'index'])->name('stock-purchase.index');
    Route::get('/stock-purchase/{id}/edit', [StockPurchaseController::class, 'edit'])->name('stock-purchase.edit');
    Route::put('/stock-purchase/{id}', [StockPurchaseController::class, 'update'])->name('stock-purchase.update');
    Route::delete('/stock-purchase/{id}', [StockPurchaseController::class, 'destroy'])->name('stock-purchase.destroy');
    Route::post('/stock-purchase', [StockPurchaseController::class, 'store'])->name('stock-purchase.store');

    // Stock Supplier Management
    Route::get('/stock-supplier', [StockController::class, 'index'])->name('stock.supplier');
    Route::get('/stock-supplier/create', function () {
        return view('stock.supplier-create');
    })->name('stock.supplier.create');
    Route::post('/stock-supplier', [StockController::class, 'store'])->name('stock.supplier.store');
    Route::put('/stock-supplier/{id}', [StockController::class, 'updateSupplier'])->name('stock.supplier.update');

    // POS System
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
    Route::post('/pos/checkout', [PosController::class, 'store'])->name('pos.checkout');
    Route::get('/checkout-page', [PosController::class, 'index'])->name('checkout.page');
    Route::get('/pos/summary', [PosController::class, 'summary'])->name('pos.summary');
    Route::get('/pos/print', [PosController::class, 'printSales']);

    // POS Sales Today & Order Management
    Route::get('/pos/sales-today', function (\Illuminate\Http\Request $request) {
        $date = $request->input('date', now()->format('Y-m-d'));
        $search = $request->input('search');
        $status = $request->input('status', 'all');

        $query = Order::query();

        if ($date) {
            $query->whereDate('created_at', $date);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->get();
        return view('pos.sales_today', compact('orders', 'date', 'search', 'status'));
    })->name('pos.sales_today');

    Route::get('/pos/edit/{id}', [PosController::class, 'editOrder']);
    Route::post('/pos/update/{id}', [PosController::class, 'updateOrder']);
    Route::delete('/pos/destroy/{id}', [PosController::class, 'destroy'])->name('pos.destroy');
    Route::delete('/pos/delete/{id}', [PosController::class, 'deleteOrder']);
    Route::post('/pos/cancel/{id}', [PosController::class, 'cancelOrder']);

    // Customers Management
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/update/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Reports & Finance & Expenses
    Route::get('/admin-report', [ReportController::class, 'adminReport'])->name('admin.report');
    Route::get('/admin-report/pdf', [StockController::class, 'exportAdminReportPdf'])->name('admin.report.pdf');
    Route::get('/admin-report/seller/{id}', [ReportController::class, 'sellerDetail'])->name('report.seller_detail');

    Route::get('/team-report', [ReportController::class, 'teamReport'])->name('report.index');
    Route::get('/finance', [ReportController::class, 'finance'])->name('finance.index');
    Route::get('/report/export', [ReportController::class, 'exportData'])->name('report.export');

    Route::post('/expenses/store', [ReportController::class, 'storeExpense'])->name('expense.store');
    Route::get('/expenses/{id}/edit', [ReportController::class, 'editExpense'])->name('expenses.edit');
    Route::put('/expenses/{id}', [ReportController::class, 'updateExpense'])->name('expenses.update');
    Route::delete('/expenses/{id}', [ReportController::class, 'destroyExpense'])->name('expenses.destroy');

    // AI Vision / Extract Invoice
    Route::post('/pos/ai-vision', [AiVisionController::class, 'extractInvoice']);
    Route::post('/extract-invoice', [AiVisionController::class, 'extractInvoice']);

});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze / Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
