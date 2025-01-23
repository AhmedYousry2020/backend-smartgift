<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class CountryTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['name','nationality'];


    public function getNationalityAttribute($value)
    {
        if ($value)
        {
            return $value;
        }

        return $this->name;
    }
}
