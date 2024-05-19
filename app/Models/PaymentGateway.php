<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'payment_gateways';

    protected $guarded = [];
}
