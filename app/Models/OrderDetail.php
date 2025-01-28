<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['order_id', 'product_id', 'mosque_id','quantity','total_price','price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function mosque()
    {
        return $this->belongsTo(Mosque::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
