<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // 🎯 បន្ថែមបន្ទាត់នេះ ដើម្បីអនុញ្ញាតឱ្យបញ្ជូនទិន្នន័យចូល Database បាន
   protected $fillable = ['name', 'image', 'phone', 'type', 'total_spent'];
}
