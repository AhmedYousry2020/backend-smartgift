<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Mosque extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable = ['lat','lng','address','city_id','is_high_need','category_id'];
    public $translatedAttributes = ['name'];

    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_mosque');
    }

    public function category(){
        return $this->belongsTo(City::class, 'category_id');
    }
}
