<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function items()
    {
        // ករណីទី១៖ បើទំនិញភ្ជាប់ផ្ទាល់ជាមួយអ្នកលក់ (មាន user_id ក្នុងតារាង order_items)
        // return $this->hasMany(OrderItem::class, 'user_id', 'id');

        // ករណីទី២៖ បើទំនិញភ្ជាប់តាមរយៈវិក្កយបត្រ (User -> Order -> OrderItem)
        return $this->hasManyThrough(
            OrderItem::class,
            Order::class,
            'user_id',    // Foreign key លើតារាង orders
            'order_id',   // Foreign key លើតារាង order_items
            'id',         // Local key លើតារាង users
            'id'          // Local key លើតារាង orders
        );
    }
}
