<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Variation;
use App\Models\WebSetting;
use App\Models\Notification;
use App\Models\Transactions;
use App\Models\RolePermission;

if (!function_exists("topProduct")) {
    function topProduct()
    {
        $transaction = Transactions::with('product')->whereNotNull('product_id')
        ->whereIn('buyer_status', ['COMPLETED', 'SUCCESSFUL'])
        ->addSelect(DB::raw('SUM(buyer_amount) as buyer_amount, COUNT(id) as count,product_id'))
        ->groupBy('product_id')
        // ->whereBetween('created_at', [$firstdayofmonth, Carbon::now()])
            // ->orderBy('id', 'DESC')
            ->first();
        return $transaction;
    }
}

if (!function_exists("getDownlines")) {
    function getDownlines($user)
    {
        $downlines = User::where('ref_by', $user->username)->orWhere('ref_code', $user->ref_code)->get();
        return $downlines;

    }
}

if (!function_exists("productAccess")) {
    function productAccess($user)
    {
        return !empty($user->product_access) ? $user->product_access : [];
    }
}
 // $downlines = ReferralEarning::where('customer_id', $customer)
        //     ->latest()
        //     ->groupBy('referred_customer_id')
        //     ->get(['*', DB::raw('sum(amount) as total')]);

if (!function_exists("specialVerifiableVariations")) {
    function specialVerifiableVariations()
    {
        return $specialVerifiableVariations = [
            'utme-no-mock' => 'profile_id',
            'utme-mock' => 'profile_id',
            'de' => 'profile_id'
        ];
    }
}

if (!function_exists("settings")) {
    function settings()
    {
        $settings = WebSetting::first();
        // $settings->favicon = 'assets/img/favicon/favicon.ico';
        $settings->currency = ' ₦';
        
        return $settings;
    }
}

if (!function_exists("getProductDiscount")) {
    function getProductDiscount($amount, $product_id, $quantity=1)
    {
        $response = [
            'type' => null,
            'discount_applied' => 0,
            'new_amount' => intval($amount) * $quantity,
            'display_value' =>  settings()->currency.($amount * $quantity),
        ];
        
        $level = auth()->user()->level;
        
        if(empty($level)){
            return $response;
        }

        $product = Product::where('id', $product_id)->first();
        $discount_type = $product->category->discount_type;
        
        // Check if resource has a category discount
        if($product->category->user_discount){
            $discount = $product->category->user_discount;
        }

        // Check if resource has product discount
        if ($product->user_discount) {
            $discount = $product->user_discount;
        }

        if(!empty($discount)){
            if ($discount_type == 'flat') {
                $type = 'flat';
                $new_amount = ($amount < $discount->price || $discount->price <= 0) ? ($amount * $quantity) : ($discount->price *  $quantity) ;
                $discount_applied = abs($amount - $discount->price);
                $display_amount = settings()->currency . number_format($new_amount);
            } else {
                $type = 'percentage';
                $discount_applied = $discount->price;
                $discount_applied = floor($discount_applied/100 * $amount);
                $n_amount = $amount - $discount_applied;
                $new_amount = ($amount < $n_amount || $n_amount <= 0) ? $amount * $quantity : $n_amount * $quantity;
                $display_amount = settings()->currency . intval($new_amount);
            }
        }else{
            return $response;
        }
        
        return [
            'type' => $type,
            'discount_applied' => $discount_applied * $quantity,
            'new_amount' => intval($new_amount),
            'display_value' => $display_amount
        ];
    }
}

if (!function_exists("getVariationDiscount")) {
    function getVariationDiscount($amount, $variation_id, $quantity = 1)
    {
        $response = [
            'type' => null,
            'discount_applied' => 0,
            'new_amount' => intval($amount) * $quantity,
            'display_value' =>  settings()->currency . ($amount * $quantity),
        ];

        $level = auth()->user()->level;

        if (empty($level)) {
            return $response;
        }
        
        $variation = Variation::where('id', $variation_id)->first();
        $discount_type = $variation->category->discount_type;
        
        // Check if resource has a category discount
        if ($variation->category->user_discount) {
            $discount = $variation->category->user_discount;
        }
        
        // if it has product discount use it
        if ($variation->product->user_discount) {
            $discount = $variation->product->user_discount;
        }

        // if it has its own discount, use it
        $individualdiscount = Discount::where(['variation_id' => $variation->id, 'customer_level' => $level])->first();

        $discount = !empty($individualdiscount) ? $individualdiscount : ($discount ?? NULL);
        
        if (!empty($discount)) {
            if ($discount_type == 'flat') {
                $type = 'flat';
                $new_amount = ($amount < $discount->price || $discount->price <= 0) ? ($amount * $quantity) : ($discount->price *  $quantity);
                $discount_applied = abs($amount - $discount->price);
                $display_amount = settings()->currency . number_format($new_amount);
            } else {
                $type = 'percentage';
                $discount_applied = $discount->price;
                $discount_applied = floor($discount_applied / 100 * $amount);
                $n_amount = $amount - $discount_applied;
                $new_amount = ($amount < $n_amount || $n_amount <= 0) ? $amount * $quantity : $n_amount * $quantity;
                $display_amount = settings()->currency . intval($new_amount);
            }
        } else {
            return $response;
        }
        
        return [
            'type' => $type,
            'discount_applied' => $discount_applied,
            'new_amount' => intval($new_amount),
            'display_value' => $display_amount
        ];
    }
}

if (!function_exists("notification1")) {
    function notification1()
    {
        $notification1 = Notification::where(['type' => 'popup', 'status' => 'active'])->first();
        return $notification1;
    }
}

if (!function_exists("notification2")) {
    function notification2()
    {
        $notification2 =  Notification::where(['type' => 'general', 'status' => 'active'])->first();
        return $notification2;
    }
}

if (!function_exists("notification2")) {
    function notification2()
    {
        $notification2 =  Notification::where(['type' => 'general', 'status' => 'active'])->first();
        return $notification2;
    }
}

if (!function_exists("notification")) {
    function notification()
    {
        $notification = Notification::where(['type' => 'announcement', 'status' => 'active'])->first();
        return $notification;
    }
}

if (!function_exists("notification_scroll")) {
    function notification_scroll()
    {
        $notification_scroll = Notification::where(['type' => 'scroll', 'status' => 'active'])->first();
        return $notification_scroll;
    }
}

if (!function_exists("activeCategories")) {
    function activeCategories()
    {
        $categories = Category::where('status','active')->orderBy('order', 'DESC')->get();
        return $categories;
    }
}


if (!function_exists("allowedPermissions")) {
    function allowedPermissions($user=null)
    {
        $user = !empty($user) ? $user : auth()->guard('admin')->user();
        $permissions = $user->permissions;
        $roles = $user->roleids;
        
        if (in_array(1, $roles)) {
            $permissions = RolePermission::pluck('route')->toArray();
            return $permissions;
        }else{
            return $permissions;
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

    if (!function_exists("walletBalance")) {
        function walletBalance($user)
        {
            return $user->wallets;
        }
    }

    if (!function_exists("referralBalance")) {
        function referralBalance($user)
        {
            return $user->cashback;
        }
    }
}