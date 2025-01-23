<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Mosque extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable = ['lat','lng','address','city_id'];
    public $translatedAttributes = ['name'];

    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }
}
