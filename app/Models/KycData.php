<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycData extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['verification_data' => 'object'];

    public function kyclogs(){
        return $this->hasMany(KycLog::class, 'kyc_data_id');
    }
}
