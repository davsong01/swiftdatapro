<?php

namespace App\Models;

use App\Models\API;
use App\Models\Api;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function variations()
    {
        return $this->hasMany(Variation::class)->orderBy('created_at', 'DESC')->where('api_id', $this->api_id);
    }

    public function api()
    {
        return $this->belongsTo(Api::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function customer_level_price($level)
    {
        $price = null;
        $price = Discount::where(['product_id' => $this->id, 'customer_level' => $level])->value('price');
        return $price;
    }

    public function user_discount()
    {
        return $this->hasOne(Discount::class)->where('product_id', $this->id)->where('customer_level', auth()->user()->level);
    }
    
}
