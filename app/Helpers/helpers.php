<?php

use App\Models\WebSetting;

if (!function_exists("settings")) {
    function settings()
    {
        $settings = WebSetting::first();
        $settings->logo = 'assets/img/favicon/favicon.png';
        $settings->favicon = 'assets/img/favicon/favicon.ico';
        
        return $settings;
    }
}