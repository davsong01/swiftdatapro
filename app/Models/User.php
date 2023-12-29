<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'ref_code',
        'ref_by',
        'mobile',
        'username',
        'pass',
        'wallets',
        'cashback',
        'block',
        'users_sid',
        'referral',
        'level',
        'last_login',
        'email',
        'sk_token',
        'password',
        'wema',
        'sterling',
        'security_pin',
        'acctname',
        'rolez',
        'fidelity',
        'reg_date',
        'status',
        'mtnsme_access',
        'mtncg_access',
        'glo_access',
        'airtel_access',
        'airtelcg_access',
        'mobile_access',
        'cable_access',
        'bills_access',
        'epin_access',
        'airtime_access',

        'susbscription',
        'webhook',
        'susbscription',
        'rating',
        'reserved_account',
        'middle_name',
        'last_name',
        'gender',
        'app_version',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'pass',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
}
