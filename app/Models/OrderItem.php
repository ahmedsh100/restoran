<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'food_id', 'quantity', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function food()
    {
        return $this->belongsTo(\App\Models\Food\Food::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}
