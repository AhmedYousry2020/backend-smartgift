<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioMedia extends Model
{
    use HasFactory;

    protected $fillable = ['portfolio_id', 'media_path'];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
