<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarnedTransactions extends Model
{
    use HasFactory;

    protected $table = 'earn_transactions';
    public $timestamps = false;

    protected $fillable = ['user_id', 'ref_code', 'amount','type','description','created_at'];
}
