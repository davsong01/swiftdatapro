<?php

namespace App\Models;

use App\Models\Api;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use App\Models\CustomerLevel;
use App\Models\PaymentGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transactions extends Model
{
    use HasFactory;
    // public $timestamps = false;
    protected $table = 'final_transactions';
    protected $appends = ['total_amount'];

    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function api()
    {
        return $this->belongsTo(Api::class, 'provider_id');
    }

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'wallet_funding_provider');
    }

    public function upgrade_level()
    {
        return $this->belongsTo(CustomerLevel::class, 'upgrade_level');
    }

    public function gettotalAmountAttribute()
    {
        $array = ['DEPOSIT'];

        if(!in_array($this->buyer_service, $array)){
            $charges = $this->charges ?? $this->provider_charge;
            return $this->buyer_amount + $charges;
        }else{
            return $this->buyer_amount;
        }
    }
}
