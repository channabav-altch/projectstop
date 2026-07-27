<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    // 🟢 ២. មុខងារសម្រាប់ភ្ជាប់ទៅរកឈ្មោះទំនិញដើម (Dr+, Bio...) 🟢
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ត្រូវប្រាកដថាមានពាក្យទាំងនេះ ដើម្បីការពារការគាំងពី Laravel
    protected $fillable = [
        'combo_product_id',
        'product_id',
        'quantity',
        'product_bundle_id' // ទុកក្រែងលោបងមានប្រើថ្ងៃក្រោយ
    ];
}
