<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'mosque_id', 'status','total_price'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function mosques()
    {
        return $this->belongsToMany(Mosque::class, 'order_mosques');
    }
}
