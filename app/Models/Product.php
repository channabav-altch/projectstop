<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_name',
        'code',
        'product_code',
        'sku',
        'barcode',
        'price',
        'sale_price',
        'cost',
        'cost_price',
        'qty',
        'unit',
        'status',
        'qty_cases',
        'qty_pieces',
        'image',
        'category',
    ];

    /**
     * Relationship ទៅកាន់ BundleItem model ដោយប្រើ product_id
     */
    public function bundleItems()
    {
        return $this->hasMany(BundleItem::class, 'product_id');
    }
}
