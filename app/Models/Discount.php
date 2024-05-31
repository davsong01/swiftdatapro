<?php

namespace App\Models;

use App\Models\CustomerLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customerlevel(){
        return $this->belongsTo(CustomerLevel::class, 'customer_level')->orderBy('order','asc');
    }
}
