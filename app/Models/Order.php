<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'status','total_price','order_type'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orderCategories()
    {
        return $this->hasMany(OrderCategory::class);
    }
}
