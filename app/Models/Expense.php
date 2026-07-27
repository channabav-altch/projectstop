<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    // 🔴 ត្រូវប្រាកដថាមានបន្ទាត់ Fillable នេះ ដើម្បីឲ្យ Model ព្រម Save ចូល DB 🔴
    protected $fillable = [
        'title',
        'description',
        'amount',
        'expense_date',
        'requester_name',
    ];
}
