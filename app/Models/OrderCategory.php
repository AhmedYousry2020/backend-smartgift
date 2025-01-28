<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCategory extends Model
{
    protected $fillable = ['order_id', 'category_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
