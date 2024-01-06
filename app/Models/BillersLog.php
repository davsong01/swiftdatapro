<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillersLog extends Model
{
    use HasFactory;

    protected $table = 'billers_log';

    protected $guarded = [];
}
