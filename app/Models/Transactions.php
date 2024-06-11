<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use App\Models\CustomerLevel;
use App\Models\PaymentGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transactions extends Model
{
    use HasFactory;
    // public $timestamps = false;
    protected $table = 'final_transactions';

    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(User::class, 'buyer_email','email');
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

    public function provider()
    {
        return $this->belongsTo(PaymentGateway::class, 'wallet_funding_provider');
    }

    public function upgrade_level()
    {
        return $this->belongsTo(CustomerLevel::class, 'upgrade_level');
    }
}
