<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Product; // បងអាច import Model ផ្សេងៗដែលពាក់ព័ន្ធនឹងស្តុកនៅទីនេះ

class StockController extends Controller
{
    /**
     * មុខងារសម្រាប់បង្ហាញផ្ទាំង "ស្តុកថ្មី & ទិញចូល"
     */
    public function purchaseIndex()
    {
        // កន្លែងនេះបងអាចសរសេរកូដទាញទិន្នន័យពី Database មកបង្ហាញបាន
        // ឧទាហរណ៍៖ $purchases = \App\Models\Purchase::latest()->get();

        // បញ្ជូនទិន្នន័យទៅកាន់ទំព័រ Blade របស់ស្តុកថ្មី
        // ចំណាំ៖ សូមប្រាកដថាបងបានបង្កើត File ឈ្មោះ purchase.blade.php នៅក្នុង folder resources/views/stock/ រួចរាល់ហើយ
        return view('stock.purchase');

        // បើបងចង់បញ្ជូនទិន្នន័យទៅ View អាចសរសេរ:
        // return view('stock.purchase', compact('purchases'));
    }
    /**
     * មុខងារសម្រាប់បង្ហាញផ្ទាំង "អ្នកផ្គត់ផ្គង់" (Suppliers)
     */
    // កុំភ្លេច Import Model នៅខាងលើគេបង្អស់
    // use App\Models\Supplier;

    public function supplierIndex(\Illuminate\Http\Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');

        // ១. ទាញទិន្នន័យពី Database
        $query = \App\Models\Supplier::query();

        if ($status != 'all') {
            $query->where('status', $status);
        }
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")->orWhere('phone', 'LIKE', "%{$search}%");
        }

        $suppliers = $query->orderBy('id', 'desc')->get();

        // ២. បញ្ជូនទិន្នន័យ $suppliers ទៅឲ្យ View
        return view('stock.supplier', compact('status', 'search', 'suppliers'));
    }

    public function storeSupplier(\Illuminate\Http\Request $request)
    {
        // ១. ពិនិត្យថាទិន្នន័យវាយចូលត្រឹមត្រូវឬអត់
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // ២. បញ្ជាឲ្យ Save ចូល Database
        \App\Models\Supplier::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'note'    => $request->note,
            'status'  => 'active' // ដាក់ Defualt ជាសកម្ម
        ]);

        return redirect()->back()->with('success', 'បានបន្ថែមអ្នកផ្គត់ផ្គង់ថ្មីដោយជោគជ័យ!');
    }

    /**
     * មុខងារសម្រាប់ កែប្រែទិន្នន័យអ្នកផ្គត់ផ្គង់ចាស់
     */
    public function updateSupplier(\Illuminate\Http\Request $request, $id)
    {
        // ១. ស្វែងរកទិន្នន័យចាស់តាម ID
        $supplier = \App\Models\Supplier::findOrFail($id);

        // ២. ធ្វើការ Update ទិន្នន័យថ្មីចូល
        $supplier->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'note'    => $request->note,
            'status'  => $request->status, // អាចប្តូរ សកម្ម/ផ្អាក
        ]);

        return redirect()->back()->with('success', 'បានកែប្រែព័ត៌មានជោគជ័យ!');
    }

    // ========================================================
    // បងអាចបន្ថែមមុខងារផ្សេងៗទៀតដែលទាក់ទងនឹង "ស្តុក" នៅទីនេះ...
    // ========================================================

    /* ឧទាហរណ៍មុខងាររក្សាទុកស្តុកថ្មី៖
    public function storePurchase(Request $request)
    {
        // កូដ Save ទិន្នន័យ...
    }
    */

    /**
     * មុខងារសម្រាប់ Export របាយការណ៍ Admin ជា PDF
     */
    public function exportAdminReportPdf(\Illuminate\Http\Request $request)
    {
        // 1. ទាញយកទិន្នន័យដែលបងចង់បង្ហាញក្នុង PDF (ឧទាហរណ៍ យកទិន្នន័យ Admin ដូចលើអេក្រង់)
        $reports = [
            ['seller' => 'Admin', 'id' => '0000000', 'type' => 'ទូទៅ', 'qty' => '0 មុខ', 'total' => '$0.00']
        ];

        // 2. បង្កើត HTML View សាមញ្ញសម្រាប់ Print/PDF (ឬហៅ Blade View ផ្សេង)
        $html = '<h2 style="font-family: sans-serif;">របាយការណ៍ប្រព័ន្ធ (Admin Report)</h2>';
        $html .= '<table border="1" cellpadding="10" cellspacing="0" style="width:100%; font-family: sans-serif; border-collapse: collapse;">';
        $html .= '<tr style="background: #f2f2f2;"><th>តំណាងលក់ (SELLER)</th><th>ប្រភេទ</th><th>បរិមាណលក់</th><th>ទឹកប្រាក់សរុប</th></tr>';

        foreach($reports as $row) {
            $html .= '<tr><td>'.$row['seller'].' (ID: '.$row['id'].')</td><td>'.$row['type'].'</td><td>'.$row['qty'].'</td><td>'.$row['total'].'</td></tr>';
        }
        $html .= '</table>';

        // 3. បញ្ជាឲ្យ Browser បើកផ្ទាំង Print Preview ស្របពេលលោត Save as PDF
        return response($html)->header('Content-Type', 'text/html');
    }
public function stockSoldReport(\Illuminate\Http\Request $request)
{
    // 🟢 ១. ចាប់យកទិន្នន័យពី URL (ថ្ងៃខែ, ប្រភេទការមើល, និងការស្វែងរក)
    $date   = $request->input('date', date('Y-m-d'));
    $period = $request->input('period', 'daily'); // លំនាំដើមប្រចាំថ្ងៃ
    $search = $request->input('search');

    // 🟢 ២. ចាប់ផ្ដើម Query ទាញទិន្នន័យពី OrderItem
    $query = \App\Models\OrderItem::with(['product', 'order']);

    // 🟢 ៣. លក្ខខណ្ឌស្វែងរក (Search តាមឈ្មោះ ឬ វិក្កយបត្រ)
    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->whereHas('order', function($orderQ) use ($search) {
                $orderQ->where('invoice_no', 'LIKE', "%{$search}%");
            })->orWhereHas('product', function($productQ) use ($search) {
                $productQ->where('product_name', 'LIKE', "%{$search}%")
                         ->orWhere('name', 'LIKE', "%{$search}%");
            });
        });
    }

    // 🟢 ៤. លក្ខខណ្ឌច្រោះថ្ងៃខែ តាមប្រចាំថ្ងៃ/ខែ/ឆ្នាំ (Filter តាមរយៈ Order)
    $parsedDate = \Carbon\Carbon::parse($date);

    $query->whereHas('order', function($q) use ($period, $parsedDate, $date) {
        if ($period == 'monthly') {
            // ទាញយកពេញមួយខែ និងឆ្នាំ
            $q->whereMonth('created_at', $parsedDate->month)
              ->whereYear('created_at', $parsedDate->year);
        } elseif ($period == 'yearly') {
            // ទាញយកពេញមួយឆ្នាំ
            $q->whereYear('created_at', $parsedDate->year);
        } else {
            // លំនាំដើម (daily) ទាញយកតែ១ថ្ងៃ
            $q->whereDate('created_at', $date);
        }
    });

    // ទាញយកទិន្នន័យរៀបតាមលំដាប់ថ្មីមុន
    $soldItems = $query->latest()->get();

    // 🟢 ៥. គណនាចំនួនសរុប និងទឹកប្រាក់សរុប
    $totalQty = abs($soldItems->sum('qty'));
    $totalAmount = $soldItems->sum(function ($item) {
        return $item->qty * $item->price;
    });

    // 🟢 ៦. បញ្ជូនទិន្នន័យទៅ View (ប្រើ products.sold តាមដែលយើងធ្លាប់ជួសជុល Error មុននេះ)
    return view('products.sold', compact('soldItems', 'totalQty', 'totalAmount', 'search', 'date', 'period'));
}

    public function store(Request $request)
    {
        // ១. Validate ទិន្នន័យ
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        try {
            // ២. Save ចូលតារាង suppliers
            \App\Models\Supplier::create([
                'name'    => $validated['name'],
                'phone'   => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            // ៣. បញ្ជូនត្រឡប់ទៅទំព័រដើមវិញ ជាមួយសារជោគជ័យ
            return redirect()->route('stock.supplier.index')
                             ->with('success', 'បន្ថែមអ្នកផ្គត់ផ្គង់បានជោគជ័យ! 🎉');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'បរាជ័យ៖ ' . $e->getMessage());
        }
    }






}
