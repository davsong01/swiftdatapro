<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Api extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['meta_data' => 'object'];
    
    public function products(){
        return $this->hasMany(Product::class);
    }
}
