<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // ត្រូវតែមានទាំង product_code និង sku នៅទីនេះ
    protected $fillable = [
        'product_name', // ត្រូវប្រាកដថាមានពាក្យនេះ
        'product_code',
        'sku',
        'image',        // ត្រូវប្រាកដថាមានពាក្យនេះ
        'category',
        'cost_price',
        'sale_price',
        'qty',
        'unit',
        'status',
        'qty_cases',
        'qty_pieces'
    ];

    // ដាក់ក្នុងឯកសារ app/Models/Product.php
    public function bundleItems()
    {
        // ឧបមាថាបងមាន Table "bundle_items" សម្រាប់ចងទំនិញ
        return $this->hasMany(BundleItem::class, 'combo_product_id');
        return $this->hasMany(\App\Models\BundleItem::class, 'product_id');
        return $this->hasMany(BundleItem::class, 'product_id');
    }
}
