<?php

namespace App\Models;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable = ['company_id','bottle_count','price','image'];
    public $translatedAttributes = ['name','description'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
