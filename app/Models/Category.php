<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date("M jS, Y", strtotime($value)),
        );
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function api()
    {
        return $this->hasMany(Product::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function user_discount()
    {
        return $this->hasOne(Discount::class)->where('category_id', $this->id)->where('customer_level', auth()->user()->level);
    }

    public function customer_level_price($level)
    {
        $price = null;
        $price = Discount::where(['category_id' => $this->id, 'customer_level' => $level])->value('price');
        return $price;
    }
}
