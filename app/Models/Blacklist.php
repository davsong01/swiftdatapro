<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blacklist extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'blacklist_mobiles';

    public function admin(){
        return $this->belongsTo(Admin::class, 'added_by');
    }
}
