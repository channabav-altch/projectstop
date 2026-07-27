<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // 🔴 បន្ថែម 'unit_price' និង 'total' ចូលក្នុង array នេះ 🔴
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'unit_price',
        'total'
    ];

    // ========================================================
    // 🔴 បន្ថែមទំនាក់ទំនងទី១៖ ប្រាប់ថា OrderItem នេះជារបស់ Product មួយណា
    // ========================================================
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ========================================================
    // 🔴 បន្ថែមទំនាក់ទំនងទី២៖ ប្រាប់ថា OrderItem នេះជារបស់ Order មួយណា
    // (ដើម្បីកុំឲ្យ Error ពេលទាញយក 'order' នៅក្នុង web.php របស់បង)
    // ========================================================
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
