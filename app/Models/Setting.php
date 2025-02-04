<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Setting extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable =['id'];
    public $translatedAttributes = ['terms_and_conditions','privacy_policy'];

   
}
