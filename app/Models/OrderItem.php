<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Requirement: order_items table design
    protected $fillable = ['product_id', 'quantity', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}