<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id','mosque_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }
}
