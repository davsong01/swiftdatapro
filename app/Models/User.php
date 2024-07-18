<?php

namespace App\Models;

use App\Models\Transactions;
use App\Models\CustomerLevel;
use App\Models\AutomatedDeposit;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
        'product_blacklist' => 'array',
    ];

    public function customer_level(){
        return $this->belongsTo(CustomerLevel::class, 'level', 'id');
    }

    public function filter_accounts($payment_gateway_id)
    {
        return $this->hasMany(AutomatedDeposit::class, 'user_id')->where('payment_gateway_id', $payment_gateway_id)->get();
    }

    public function automated_accounts()
    {
        return $this->hasMany(AutomatedDeposit::class, 'user_id');
    }

    public function transactions()
    { 
        return $this->hasMany(Transactions::class, 'user_id');
    }


    // public function downlines()
    // {
    //     // dd($this);
    //     return $this->hasMany(User::class)->where('ref_by', $this->username)->orWhere('ref_by', $this->ref_code);
    // } 
    
}
