<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StockPurchaseController extends Controller
{
    // ... អាចមាន function ផ្សេងៗដូចជា index ឬ create នៅទីនេះ ...

    // 🟢 ១. ពេលបញ្ចូលស្តុកទិញចូលថ្មី (វាទៅបូកថែមស្តុកចាស់) 🟢
  public function store(Request $request)
    {
        // ១. កត់ត្រាចូលប្រវត្តិទិញ
        \DB::table('stock_movements')->insert([
            // 🟢 បន្ថែមបន្ទាត់ user_id នេះចូល (វាចាប់យក ID អ្នកដែលកំពុង Login ស្វ័យប្រវត្តិ) 🟢
            'user_id'    => auth()->id() ?? 1,

            'product_id' => $request->product_id,
            'qty'        => $request->quantity,
            'unit_price' => $request->total_price ?? 0,
            'supplier'   => $request->supplier,
            'invoice_no' => $request->sku,
            'type'       => 'in',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ២. កូដបូកស្តុក (ដែលយើងបានជជែកគ្នាមុននេះ)
        \DB::table('products')
            ->where('id', $request->product_id)
            ->increment('qty', $request->quantity);

        return redirect()->back()->with('success', 'បានទិញចូល និងបូកស្តុកដោយជោគជ័យ!');
    }
    // 🟢 ២. មុខងារសម្រាប់ទាញទិន្នន័យទៅបង្ហាញក្នុង Form Edit 🟢
    public function edit($id)
    {
        $purchase = DB::table('stock_movements')->where('id', $id)->first();

        if (!$purchase) {
            return redirect()->back()->with('error', 'រកមិនឃើញទិន្នន័យសម្រាប់ធ្វើការកែសម្រួលទេ!');
        }

        return view('stock.edit', compact('purchase'));
    }

    // 🟢 ៣. ពេលកែប្រែចំនួន (វានឹងទូទាត់គម្លាតចំនួនចាស់ និងថ្មីដោយស្វ័យប្រវត្តិ) 🟢
    public function update(Request $request, $id)
    {
        $purchase = DB::table('stock_movements')->where('id', $id)->first();
        if (!$purchase) return redirect()->back()->with('error', 'រកមិនឃើញទិន្នន័យទេ!');

        // គណនាតម្លៃរាយថ្មី
        $unit_price = 0;
        if ($request->quantity > 0) {
            $unit_price = $request->total_price / $request->quantity;
        }

        // ១. គណនាគម្លាតស្តុក (ចំនួនថ្មី ដក ចំនួនចាស់)
        // ឧទាហរណ៍៖ ទិញចាស់ ១០, បងកែដាក់ ១៥ => គម្លាតគឺ +៥ (វាត្រូវទៅបូកថែម៥ ក្នុងតារាង Products)
        $stock_diff = $request->quantity - $purchase->qty;

        // ២. អាប់ដេតប្រវត្តិទិញចូល (stock_movements)
        DB::table('stock_movements')->where('id', $id)->update([
            'invoice_no' => $request->sku,
            'qty'        => $request->quantity,
            'unit_price' => $unit_price,
            'updated_at' => now(),
        ]);

        // ៣. បូកថែម ឬ ដកចេញ ពីស្តុកសរុប (ដោយផ្អែកលើគម្លាត $stock_diff)
        DB::table('products')->where('id', $purchase->product_id)->increment('qty', $stock_diff);

        return redirect()->back()->with('success', 'ទិន្នន័យត្រូវបានកែប្រែ និងទូទាត់ស្តុកដោយជោគជ័យ!');
    }

    // 🟢 ៤. ពេលលុបប្រវត្តិទិញចូល (វាទៅដកចំនួននេះចេញពីស្តុកចាស់វិញ) 🟢
    public function destroy($id)
    {
        $purchase = DB::table('stock_movements')->where('id', $id)->first();

        if ($purchase) {
            // ១. ដកចំនួនទំនិញនេះ ចេញពីស្តុកសរុបវិញ (ព្រោះយើងលុបវិក្កយបត្រទិញចូលហ្នឹងចោល)
            DB::table('products')->where('id', $purchase->product_id)->decrement('qty', $purchase->qty);

            // ២. លុបទិន្នន័យពីប្រវត្តិ (stock_movements)
            DB::table('stock_movements')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'បានលុបទិន្នន័យ និងដកស្តុកចេញវិញជោគជ័យ!');
        }

        return redirect()->back()->with('error', 'រកមិនឃើញទិន្នន័យនេះទេ!');
    }

//  public function index()
// {
//     $purchases = \DB::table('stock_movements')->orderBy('id', 'desc')->get();

//     // ១. ត្រូវមានបន្ទាត់នេះដើម្បីទាញទំនិញ យកទៅបង្ហាញក្នុង Dropdown
//     $products = \DB::table('products')->orderBy('product_name', 'asc')->get();

//     // ២. កុំភ្លេចបញ្ជូន 'products' ទៅ compact
//     return view('stock.purchase', compact('purchases', 'products'));
// }

// 🟢 ១. លុបពាក្យ Request $request ចេញ ទុកវង់ក្រចកទទេ 🟢
    public function index()
    {
        // 🟢 ២. ប្រើពាក្យ request(...) ជំនួសវិញ ដើម្បីចាប់យកតម្លៃពី Filter 🟢
        $search = request('search');
        $date = request('date');
        $status = request('status');

        // ៣. បង្កើត Query ដើម្បីទាញទិន្នន័យទិញចូល
        $query = \DB::table('stock_movements')
            ->leftJoin('products', 'stock_movements.product_id', '=', 'products.id')
            ->select('stock_movements.*', 'products.product_name')
            ->orderBy('stock_movements.id', 'desc');

        // ៤. លក្ខខណ្ឌកាត់ត្រង ស្វែងរកអត្ថបទ (Search Filter)
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('stock_movements.invoice_no', 'LIKE', "%{$search}%")
                  ->orWhere('stock_movements.supplier', 'LIKE', "%{$search}%")
                  ->orWhere('products.product_name', 'LIKE', "%{$search}%");
            });
        }

        // ៥. លក្ខខណ្ឌកាត់ត្រង តាមថ្ងៃខែ (Date Filter)
        if (!empty($date)) {
            $query->whereDate('stock_movements.created_at', $date);
        }

        // ៦. ទាញយកទិន្នន័យដែលបានកាត់ត្រងរួច
        $purchases = $query->get();

        // ៧. គណនា "ចំណាយទិញចូលសរុប" និង "បរិមាណទំនិញ"
        $totalQty = $purchases->sum('qty');
        $totalAmount = $purchases->sum(function($item) {
            return ($item->qty ?? 0) * ($item->unit_price ?? 0);
        });

        // ៨. ទាញទំនិញសម្រាប់បង្ហាញក្នុងប្រអប់ Dropdown
        $products = \DB::table('products')->orderBy('product_name', 'asc')->get();

        // ៩. បញ្ជូនទិន្នន័យទាំងអស់ទៅកាន់ View
        return view('stock.purchase', compact('purchases', 'products', 'totalQty', 'totalAmount'));
    }
}
