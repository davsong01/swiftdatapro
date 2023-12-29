<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    public $timestamps = false;

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
        'ip_address'
    ];
}
