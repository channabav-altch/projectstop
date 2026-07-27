<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    use HasFactory;

    // ប្រាប់ឈ្មោះ Table ឱ្យច្បាស់លាស់
    protected $table = 'bundle_items';

    // បញ្ជី Column ដែលអនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យបាន
    protected $fillable = [
        'product_id',
        'item_id',
        'product_bundle_id',
        'quantity',
        'price',
    ];

    /**
     * Relationship ភ្ជាប់ទៅកាន់ Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
