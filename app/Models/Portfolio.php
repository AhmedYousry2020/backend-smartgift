<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable = ['type']; // 'video' or 'images'

    public $translatedAttributes = ['title', 'description'];

    public function media()
    {
        return $this->hasMany(PortfolioMedia::class);
    }
}
