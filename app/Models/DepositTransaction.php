<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transactions;
use App\Models\PaymentGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepositTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'sys_ref', 'sys_ref');
    }
}
