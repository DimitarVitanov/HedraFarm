<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'municipality',
        'postal_code',
        'country',
        'additional_description',
        'payment_method',
        'status',
        'subtotal',
        'discount',
        'delivery_price',
        'total_price',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
