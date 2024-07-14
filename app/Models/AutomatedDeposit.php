<?php

namespace App\Models;

use App\Models\DepositTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AutomatedDeposit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    public function deposit_transactions()
    {
        return $this->hasMany(DepositTransaction::class, 'account_number', 'account_number');
    }
}
