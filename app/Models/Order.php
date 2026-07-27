<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 🔴 ត្រូវប្រាកដថា មានឈ្មោះទាំងនេះ ១០០% ទើបវាព្រម Save ចូល Database 🔴
    protected $fillable = [
        'user_id',
        'invoice_no',
        'total_amount',
        'customer_type',
        'customer_name',   // បើអត់មានបន្ទាត់នេះ វាអត់ Save ឈ្មោះឲ្យទេ
        'phone',           // បើអត់មានបន្ទាត់នេះ វាអត់ Save លេខទូរស័ព្ទឲ្យទេ
        'province',
        'address_detail',
        'delivery_method',
        'delivery_fee',
        'note',
        'status'
    ];

    // ដាក់ក្នុងឯកសារ app/Models/Order.php
    public function orderItems()
    {
        // ភ្ជាប់ពីវិក្កយបត្រ ទៅកាន់ទំនិញរាយដែលភ្ញៀវបានទិញ
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
