<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // អនុញ្ញាតឲ្យប្រព័ន្ធ Save ទិន្នន័យចូលទៅកាន់កូឡោនទាំងនេះបាន
    protected $fillable = [
        'name',
        'phone',
        'address',
        'note',
        'status'
    ];
}
