<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'web_settings';

    protected $casts = [
        'allowed_domain_emails_for_registration' => 'array',
        'allowed_email_notifications' => 'array',
        'extra_settings' => 'array',
        'transaction_restriction_settings' => 'array'
    ];
}
