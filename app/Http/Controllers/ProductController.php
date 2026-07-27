<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    // ១. បង្ហាញផ្ទាំង Form
    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // ១. ការពារកុំឲ្យ Error ទំព័រខ្មៅពេលអត់បញ្ជូនទិន្នន័យមក
        $request->validate([
            'product_name' => 'required',
            'sale_price' => 'required|numeric',
            'product_code' => 'required|unique:products,product_code'
        ], [
            // ប្ដូរសារ Error ទៅជាភាសាខ្មែរឲ្យងាយយល់
            'product_code.unique' => 'លេខកូដទំនិញនេះមានរួចហើយ សូមប្រើលេខកូដផ្សេង!',
            'product_code.required' => 'សូមបញ្ចូលលេខកូដទំនិញ!'
        ]);
        // ២. រក្សាទុកទំនិញចូល Database
        $product = new Product();
        $product->product_name = $request->product_name;

        // 🟢 ចាប់យក SKU (បើគ្មាន វាបង្កើតថ្មី)
        $product->product_code = $request->product_code ?? $request->sku ?? 'PRD-' . time();

        // -------------------------------------------------------------------
        // 🟢 ផ្នែកគណនាចំនួនសរុប (កេស និង រាយ) 🟢
        // ចាប់យកទិន្នន័យដែលវាយបញ្ចូលពី Form
        $cartonSize = $request->input('carton_size', 1); // ចំនួនក្នុង១កេស (បើទទេ ស្មើ 1)
        $inputCartons = $request->input('cartons', 0);   // ចំនួនកេស
        $inputPieces = $request->input('pieces', 0);     // ចំនួនរាយ

        // គណនារកចំនួនសរុប: (កេស * ក្នុង១កេស) + រាយ
        $calculatedQty = ($inputCartons * $cartonSize) + $inputPieces;

        // បើបានវាយបញ្ជូលកេស/រាយ វានឹងយកចំនួនដែលគណនាឃើញ តែបើអត់ទេ វាយកចំនួនក្នុងប្រអប់ Quantity ខាងលើ
        $fallbackQty = $request->quantity ?? $request->qty ?? 0;
        $product->qty = $calculatedQty > 0 ? $calculatedQty : $fallbackQty;

        $product->carton_size = $cartonSize; // Save ចំនួនក្នុង១កេស ចូល Database
        // -------------------------------------------------------------------

        $product->cost_price = $request->cost_price ?? $request->total_cost ?? 0;
        $product->sale_price = $request->sale_price ?? 0;

        // 🟢 ចាប់យក Category ពី Form
        $product->category   = $request->category ?? 'General';

        // ៣. បញ្ចូលរូបភាព (បើមាន Upload)
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('uploads/products'), $imageName);
            $product->image = 'uploads/products/' . $imageName;
        }

        $product->save(); // 🟢 Save បង្កើត ID មេ

        // ៤. កូដចាប់កូនៗ (បើទំនិញនេះជាប្រភេទ Bundle វានឹងដំណើរការកូដនេះ)
        $items = $request->input('items', []);
        if (!empty($items) && is_array($items)) {
            foreach ($items as $item) {
                if(isset($item['product_id'])) {
                    \DB::table('bundle_items')->insert([
                        'combo_product_id'  => $product->id,
                        'product_bundle_id' => $product->id,
                        'product_id'        => $item['product_id'],
                        'quantity'          => $item['qty'] ?? 1,
                        'created_at'        => now(),
                        'updated_at'        => now()
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'បានបញ្ចូលទំនិញ និងទិន្នន័យស្តុកដោយជោគជ័យ ១០០%!');
    }
    // ១. ទាញទិន្នន័យទៅបង្ហាញលើផ្ទាំងកែប្រែ
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // ១. ចាប់យកទិន្នន័យមក Validate (🔴 ត្រូវលុប 'sku' ចោលពីទីនេះ 🔴)
        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|unique:products,product_code,' . $id,
            'category'     => 'nullable|string',
            'cost_price'   => 'required|numeric',
            'sale_price'   => 'required|numeric',
            'qty'          => 'required|integer',
            'unit'         => 'nullable|string',
            'qty_cases'    => 'nullable|integer',
            'qty_pieces'   => 'nullable|integer',
            // 'sku'       => 'nullable|string', // <-- លុបបន្ទាត់នេះចេញ
        ]);

        // ២. បើមាន Upload រូបភាពថ្មី
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = 'uploads/products/' . $imageName;
        }

        // ៣. Update ចូល Database (លែង Error ព្រោះគ្មាន sku ទៀតទេ)
        $product->update($data);

        return redirect()->back()->with('success', 'កែប្រែទិន្នន័យបានជោគជ័យ! ✅');
    }

    // ៣. លុបទំនិញចោល
   public function destroy($id)
    {
        $product = Product::findOrFail($id);

        try {
            // ១. ព្យាយាមលុបទិន្នន័យ
            $product->delete();

            // ២. បើលុបបានជោគជ័យ សឹមលុបរូបភាពចេញពី Folder
            if($product->image && file_exists(public_path($product->image))){
                unlink(public_path($product->image));
            }

            return redirect()->back()->with('success', 'លុបទំនិញបានជោគជ័យ! ✅');

        } catch (\Illuminate\Database\QueryException $e) {

            // ៣. បើលោត Error លេខ 23503 (ជាប់ Foreign Key វិក្កយបត្រចាស់)
            if($e->getCode() == "23503"){
                return redirect()->back()->with('error', 'មិនអាចលុបទំនិញនេះបានទេ ព្រោះវាធ្លាប់បានលក់ចេញ (ជាប់ក្នុងប្រវត្តិវិក្កយបត្រ)! ❌');
            }

            // Error ផ្សេងៗទៀត
            return redirect()->back()->with('error', 'មានបញ្ហាក្នុងការលុបទិន្នន័យ៖ ' . $e->getMessage());
        }
    }
    // ១. បង្ហាញផ្ទាំងគិតស្តុក
    public function stockAudit()
    {
        $products = Product::orderBy('product_name', 'asc')->get();
        return view('products.audit', compact('products'));
        // ចំណាំ៖ ឈ្មោះ File view របស់បងអាចជា 'stock_audit' ឬ 'audit' អាស្រ័យលើបងដាក់
    }

    // ២. រក្សាទុកចំនួនស្តុកថ្មី (Save Multiple Stocks)
    public function updateStockAudit(Request $request)
    {
        // ត្រួតពិនិត្យមើលថាតើមានទិន្នន័យបញ្ជូនមកឬអត់
        if ($request->has('stocks')) {
            foreach ($request->stocks as $id => $actual_qty) {
                // Update ចូល Database តាម ID ទំនិញនីមួយៗ
                if ($actual_qty !== null) {
                    Product::where('id', $id)->update(['qty' => $actual_qty]);
                }
            }
            return redirect()->back()->with('success', 'រក្សាទុកទិន្នន័យស្តុកបានជោគជ័យ! ✅');
        }
        return redirect()->back()->withErrors(['error' => 'មិនមានទិន្នន័យត្រូវរក្សាទុកទេ!']);
    }

    // ១. បង្ហាញផ្ទាំងគិតដកស្តុក
    public function stockDeduct()
    {
        // ទាញទិន្នន័យទំនិញដែលមានស្តុកធំជាង 0 មកបង្ហាញ
        $products = Product::where('qty', '>', 0)->orderBy('product_name', 'asc')->get();
        return view('products.deduct', compact('products'));
    }

    // ២. រក្សាទុកការកាត់ដកស្តុក
    public function updateStockDeduct(Request $request)
    {
        if ($request->has('deduct_stocks')) {
            foreach ($request->deduct_stocks as $id => $deduct_qty) {
                // កាត់ស្តុកលុះត្រាតែមានការវាយបញ្ចូលលេខធំជាង 0
                if ($deduct_qty > 0) {
                    $product = Product::find($id);
                    if ($product) {
                        // យកស្តុកចាស់ ដកចំនួនដែលវាយបញ្ចូល (ការពារកុំឱ្យស្តុកធ្លាក់ក្រោម 0)
                        $new_qty = max(0, $product->qty - $deduct_qty);
                        $product->update(['qty' => $new_qty]);
                    }
                }
            }
            return redirect()->back()->with('success', 'កាត់ដកស្តុកបានជោគជ័យ! 🗑️');
        }
        return redirect()->back()->withErrors(['error' => 'មិនមានទិន្នន័យត្រូវកាត់ដកទេ!']);
    }

    // បង្ហាញផ្ទាំងស្តុកក្នុងឃ្លាំងសរុប
    public function stockSummary()
    {
        // ទាញទិន្នន័យទំនិញទាំងអស់ រៀបតាមអក្ខរក្រម
        $products = Product::orderBy('product_name', 'asc')->get();

        // គណនាទិន្នន័យសរុបសម្រាប់បង្ហាញលើកាត
        $totalProducts = $products->count(); // ចំនួនមុខទំនិញ
        $totalQty = $products->sum('qty');   // ចំនួនទំនិញសរុបគិតជាដុំ

        // គណនាតម្លៃដើមសរុបក្នុងឃ្លាំង (ចំនួនស្តុក x តម្លៃទិញចូល)
        $totalValue = $products->sum(function ($product) {
            return $product->qty * $product->cost_price;
        });

        return view('products.summary', compact('products', 'totalProducts', 'totalQty', 'totalValue'));
    }

    // ១. បង្ហាញផ្ទាំងចងកញ្ចប់ឈុត
    public function bundleCreate()
    {
        // ទាញទំនិញរាយទាំងអស់មកបង្ហាញ ដើម្បីឱ្យយើងរើសចូលឈុត
        $products = Product::orderBy('product_name', 'asc')->get();
        return view('products.bundle', compact('products'));
    }

    // ២. រក្សាទុកឈុតថ្មី
    public function bundleStore(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|unique:products,product_code', // លេខកូដឈុតកុំឱ្យជាន់គ្នា
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sale_price'   => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['sku'] = $request->product_code;
        $data['category'] = 'ឈុត (Bundle)'; // កំណត់ប្រភេទជាឈុតដោយស្វ័យប្រវត្តិ
        $data['unit'] = 'ឈុត (Set)'; // ខ្នាតជាឈុត

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = 'uploads/products/' . $imageName;
        }

        Product::create($data);

        return redirect()->back()->with('success', 'ចងកញ្ចប់ឈុតថ្មីបានជោគជ័យ! 🎁');
    }

   public function stockSold(\Illuminate\Http\Request $request)
    {
        $search = $request->input('search');
        $date = $request->input('date');

        // 🟢 ប្ដូរមកប្រើ OrderItem វិញ ព្រោះជាតារាងទិន្នន័យលក់ពិតប្រាកដរបស់បង 🟢
        $query = \App\Models\OrderItem::with(['order', 'product']);

        // លក្ខខណ្ឌកាត់ត្រងតាមថ្ងៃខែ (ប្រតិទិន)
        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }

        // លក្ខខណ្ឌស្វែងរក (Search)
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereHas('order', function($orderQuery) use ($search) {
                    $orderQuery->where('invoice_no', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('product', function($productQuery) use ($search) {
                    $productQuery->where('product_name', 'LIKE', "%{$search}%")
                                 ->orWhere('name', 'LIKE', "%{$search}%");
                });
            });
        }

        $soldItems = $query->orderBy('created_at', 'desc')->get();

        return view('products.sold', compact('soldItems'));
    }

    // បង្ហាញផ្ទាំងស្តុកបច្ចុប្បន្នជាក់ស្តែង
    public function stockCurrent()
    {
        // ទាញទំនិញទាំងអស់
        $products = Product::orderBy('product_name', 'asc')->get();

        // គណនាទិន្នន័យសម្រាប់កាតដាស់តឿន
        $totalQty = $products->sum('qty'); // ស្តុកសរុបទាំងអស់
        $lowStock = $products->where('qty', '>', 0)->where('qty', '<=', 10)->count(); // ស្តុកជិតអស់ (ក្រោមឬស្មើ ១០)
        $outOfStock = $products->where('qty', '<=', 0)->count(); // ទំនិញដែលអស់ស្តុករលីង

        return view('products.current_stock', compact('products', 'totalQty', 'lowStock', 'outOfStock'));
    }



    // ... កូដចាស់ៗរបស់បង ...

    // មុខងារសម្រាប់រក្សាទុកកញ្ចប់ឈុត (Bundle) ចូលទៅក្នុង Database
    // មុខងារសម្រាប់រក្សាទុកកញ្ចប់ឈុត (Bundle) ចូលទៅក្នុង Database
    public function storeBundle(Request $request)
    {
        try {
            $product = new \App\Models\Product();

            $product->product_name = $request->name;

            // កំណត់ SKU
            $product->sku = $request->sku ? $request->sku : 'BND-' . rand(10000, 99999);

            // 🎯 នេះជាបន្ទាត់ដែលថែមថ្មី ដើម្បីដោះស្រាយ Error (យក SKU ទៅដាក់ក្នុង product_code)
            $product->product_code = $product->sku;

            $product->sale_price = $request->sale_price;
            $product->cost_price = $request->total_cost ?? 0;

            $product->category = 'ឈុត (Bundle)';
            $product->unit = 'ឈុត';
            $product->qty = 1;

            // បញ្ចូលរូបភាព
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/products'), $filename);
                $product->image = 'uploads/products/' . $filename;
            }

            // បញ្ជាឱ្យ Save ចូល Database
            $product->save();

            // Save រួច រុញទៅផ្ទាំង POS វិញ
            return redirect()->route('pos.index')->with('success', 'កញ្ចប់ឈុតត្រូវបានបង្កើតដោយជោគជ័យ!');

        } catch (\Exception $e) {
            // បើមាន Error អ្វីទៀត វានឹងលោតប្រាប់នៅទីនេះ
            dd('មានបញ្ហាពេល Save ចូល Database របស់អ្នកហើយ៖ ', $e->getMessage());
        }
    }

    public function posIndex()
    {
        // ទាញយកទំនិញទាំងអស់ពី Database មកបង្ហាញ មិនមានលក្ខខណ្ឌកាត់ចោល
        $products = \App\Models\Product::orderBy('id', 'desc')->get();

        return view('pos.index', compact('products'));
    }


}
