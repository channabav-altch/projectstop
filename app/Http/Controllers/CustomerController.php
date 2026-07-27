<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            // ១. ព្យាយាមទាញទិន្នន័យពី Database
            $customers = Customer::orderBy('id', 'desc')->get();
            $totalCustomers = Customer::count();
            $vipCount = Customer::where('type', 'VIP')->count();

            $vipSpent = Customer::where('type', 'VIP')->sum('total_spent');
            $regularSpent = Customer::where('type', '!=', 'VIP')->sum('total_spent');
            $totalSpent = $vipSpent + $regularSpent;

        } catch (\Exception $e) {
            // ២. ប្រសិនបើ Database មិនទាន់មាន Column 'type' ឬ 'total_spent' ទេ ឱ្យវាស្មើ ០ សិន (ទប់កុំឱ្យគាំង)
            $customers = [];
            $totalCustomers = 0;
            $vipCount = 0;
            $vipSpent = 0;
            $regularSpent = 0;
            $totalSpent = 0;
        }

        // ៣. បញ្ជូនទិន្នន័យទៅកាន់ផ្ទាំង UI
        return view('customers.index', compact(
            'customers',
            'totalCustomers',
            'vipCount',
            'vipSpent',
            'regularSpent',
            'totalSpent'
        ));
    }
   public function store(Request $request)
    {
        // ១. ចាប់យកទិន្នន័យមក Validate (🔴 ត្រូវលុប 'type' ចោលពីទីនេះ 🔴)
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string',
            // 'type'  => 'nullable|string', // <-- លុបបន្ទាត់នេះចេញ
        ]);

        // ២. បើមាន Upload រូបភាព
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('customers', 'public');
        }

        // ៣. Save ចូល Database (លែង Error ព្រោះគ្មាន type ទៀតទេ)
        \App\Models\Customer::create($data);

        return back()->with('success', 'បានបន្ថែមអតិថិជនជោគជ័យ! 🎉');
    }

// ២. សម្រាប់ពេលកែប្រែព័ត៌មាន (Update) ដែលបងកំពុង Error
public function update(Request $request, $id)
{
    $customer = \App\Models\Customer::findOrFail($id);
    $data = $request->all();

    // 🎯 បន្ថែមកូដនេះ៖ បើអត់វាយបញ្ចូលលុយទេ ឱ្យវាស្មើ ០ ស្វ័យប្រវត្តិ
    $data['total_spent'] = $request->total_spent ?? 0;

    if ($request->hasFile('image')) {
        // លុបរូបចាស់ចោលសិន
        if ($customer->image) {
            Storage::disk('public')->delete($customer->image);
        }
        $data['image'] = $request->file('image')->store('customers', 'public');
    }

    $customer->update($data);

    return back()->with('success', 'បានកែប្រែទិន្នន័យជោគជ័យ!');
}

public function destroy($id)
{
    $customer = \DB::table('customers')->where('id', $id)->first();

    if ($customer) {
        \DB::table('customers')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'បានលុបអតិថិជនដោយជោគជ័យ!');
    }

    return redirect()->back()->with('error', 'រកមិនឃើញអតិថិជននេះទេ!');
}
}
