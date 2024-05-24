<?php

namespace App\Models;

use App\Models\User;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Transactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerLevel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customers()
    {
        return $this->hasMany(User::class, 'customer_level');
    }

    public function variation_price($variation_id)
    {
        $price = null;
        $price = Discount::where('variation_id', $variation_id)->value('price');
        return $price;
    }

    public function product_price($product_id)
    {
        $price = null;
        $price = Discount::where('variation_id', $product_id)->whereNull('variation_id')->value('price');
        return $price;
    }

    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'upgrade_level');
    }
}
