<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarnedTransactions extends Model
{
    use HasFactory;

    protected $table = 'earn_transactions';

    protected $fillable = ['user_id', 'ref_code', 'amount','type','description','sys_reference'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
