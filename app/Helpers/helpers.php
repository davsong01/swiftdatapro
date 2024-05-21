<?php

use App\Models\WebSetting;

if (!function_exists("settings")) {
    function settings()
    {
        $settings = WebSetting::first();
        // $settings->logo = 'assets/img/favicon/favicon.png';
        // $settings->favicon = 'assets/img/favicon/favicon.ico';
        $settings->currency = ' ₦';
        
        return $settings;
    }
}


if (!function_exists("allowedPermissions")) {
    function allowedPermissions($user=null)
    {
        if(empty($user)){
            return auth()->guard('admin')->user()->permissions;
        }else{
            return $user->permissions;
        }
    }
}


if (!function_exists("hasAccess")) {
    function hasAccess($route)
    {
        $routes = auth()->guard('admin')->user()->permissions;
        $ids = auth()->guard('admin')->user()->roleids;
        
        if (in_array($route, $routes) || in_array(1, $ids)) {
            return true;
        } else {
            return false;
        }
    }

    if (!function_exists("getUniqueElements")) {
        function getUniqueElements()
        {
            return [
                'phone',
                'meter_number',
                'iuc_number',
                'account_id'
            ];
        }
    }

    if (!function_exists("verifiableUniqueElements")) {
        function verifiableUniqueElements()
        {
            return ['meter_number', 'iuc_number', 'profile_id'];
        }
    }
}