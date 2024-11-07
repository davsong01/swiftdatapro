<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionStatusChangeTrack extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function resolved_by(){
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }
}
