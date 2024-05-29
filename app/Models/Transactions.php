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
    protected $appends = ['type'];
    protected $table = 'final_transactions';

    protected $fillable =
    [
        'mobile_number',
        'buyer_email',
        'buyer_username',
        'buyer_service',
        'buyer_amount',
        'buyer_prebal',
        'buyer_postbal',
        'buyer_descr',
        'buyer_status',
        'buyer_date',
        'timestring',
        'charge_from',
        'user_type',
        'sys_ref',
        'api_ref',
        'api_report',
        'src_price',
        'profit',
        'databyte',
        'app_version',
        'domain_name',
        'ip_address',
        'post_fields',
        'provider',
        'extra',
        'charges',
        'failure_reason',
        'api_response',
        'buyer_quantity',
    ];

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

    public function getTypeAttribute()
    {
        return $this->wallet->type ?? 'new';
    }

    public function upgrade_level()
    {
        return $this->belongsTo(CustomerLevel::class, 'upgrade_level');
    }
}
