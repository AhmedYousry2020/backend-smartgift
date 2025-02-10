<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'status','total_price','order_type','order_code','order_for'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orderCategories()
    {
        return $this->hasMany(OrderCategory::class);
    }

    public function orderMedia()
    {
        return $this->hasMany(OrderMedia::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getFormattedTotalPriceAttribute($value)
    {
        $currency = app()->getLocale() === 'ar' ? 'دينار كويتي' : 'KWD';
        return number_format($this->total_price, 3) . ' ' . $currency;
    }


}
