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

    public function getImagePathAttribute(){

        if (!$this->image) {
            return asset('uploads/product_images/default.png'); // Return a default image
        }
        return asset( $this->image);

   }
   public function getPriceAttribute($value)
   {
       $currency = app()->getLocale() === 'ar' ? 'دينار كويتي' : 'KWD';
       return number_format($value, 2) . ' ' . $currency;
   }

}
