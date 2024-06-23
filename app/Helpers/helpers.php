<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Variation;
use App\Models\WebSetting;
use App\Models\Notification;
use App\Models\Transactions;
use App\Models\RolePermission;
use App\Models\EarnedTransactions;

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
            'display_value' =>  settings()->currency.(number_format($amount * $quantity)),
        ];
        
        $level = auth()->user()->level;
        
        if(empty($level)){
            return $response;
        }

        $product = Product::where('id', $product_id)->first();
        $discount_type = $product->category->discount_type;
        
        // Check if resource has a category discount
        // Bypass percentage discount type
        $byPassProducts = !empty(env('BY_PASS_PRODUCT_IDS')) ? explode(',',env('BY_PASS_PRODUCT_IDS')) : [];
        if ($discount_type == 'percentage' || in_array($product->id,$byPassProducts)) {
            if($product->category->user_discount){
                $discount = $product->category->user_discount;
            }

            // Check if resource has product discount
            if ($product->user_discount) {
                $discount = $product->user_discount;
            }
        }
        
        if(!empty($discount)){
            if ($discount_type == 'flat') {
                $type = 'flat';
                $new_amount = ($amount < $discount->price || $discount->price <= 0) ? ($amount * $quantity) : ($discount->price *  $quantity) ;
                $discount_applied = abs($amount - $discount->price);
                $display_amount = intval($new_amount);
            } else {
                $type = 'percentage';
                $discount_applied = $discount->price;
                $discount_applied = floor($discount_applied/100 * $amount);
                $n_amount = $amount - $discount_applied;
                $new_amount = ($amount < $n_amount || $n_amount <= 0) ? $amount * $quantity : $n_amount * $quantity;
                $display_amount = intval($new_amount);
            }
        }else{
            return $response;
        }
        
        return [
            'type' => $type,
            'discount_applied' => $discount_applied * $quantity,
            'new_amount' => $new_amount,
            'display_value' => settings()->currency . number_format($display_amount)
        ];
    }
}

if (!function_exists("getProductCharges")) {
    function getProductCharges($amount, $product_id, $quantity = 1)
    {
        $response = [
            'type' => null,
            'charge' => 0,
            'new_amount' => intval($amount) * $quantity,
            'display_value' =>  settings()->currency . (number_format($amount * $quantity)),
        ];

        $level = auth()->user()->level;

        if (empty($level)) {
            return $response;
        }

        $product = Product::where('id', $product_id)->first();
        $discount_type = $product->category->discount_type;

        if ($product->user_discount) {
            $discount = $product->user_discount;
        }
        
        if (!empty($discount)) {
            if ($discount_type == 'flat') {
                $type = 'flat';
                $new_amount = ($amount > $discount->price || $discount->price <= 0) ? ($amount * $quantity) : ($discount->price *  $quantity);
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
            'charge' => $discount_applied * $quantity,
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
            'display_value' =>  settings()->currency . (number_format($amount * $quantity)),
        ];
        $level = auth()->user()->level;

        if (empty($level)) {
            return $response;
        }
        
        $variation = Variation::where('id', $variation_id)->first();
        $discount_type = $variation->category->discount_type;
        
        // Check if resource has a category discount
        if($discount_type == 'percentage'){
            if ($variation->category->user_discount) {
                $discount = $variation->category->user_discount;
            }
            
            // if it has product discount use it
            if ($variation->product->user_discount) {
                $discount = $variation->product->user_discount;
            }
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
                $display_amount = settings()->currency . intval(number_format($new_amount));
            }
        } else {
            return $response;
        }
        // \Log::info(['discount' => $discount->price, 'system+price' => $variation->system_price, 'type' => $discount_type, 'new_amount' => $new_amount]);
        
        return [
            'type' => $type,
            'discount_applied' => $discount_applied,
            'new_amount' => intval($new_amount),
            'display_value' => $display_amount
        ];
    }
}

if (!function_exists("providerPrice")) {
    function providerPrice($product, $variation, $request){
        $provider_percentage_discount = $product->provider_percentage_discount;

        if ($product->has_variations == 'yes' && !empty($variation)) {
            if (!empty($provider_percentage_discount)) {
                $discount_off = $provider_percentage_discount / 100 * $variation->api_price;
                $src_price = ($variation->api_price - $discount_off);
                $src_price =  $src_price * $request['quantity'];
            } else {
                $src_price = 0;
            }
        } else {
            if(!empty($provider_percentage_discount)){
                $discount_off = $provider_percentage_discount / 100 * $request['unit_price'];
                $src_price = ($request['unit_price'] -  $discount_off );
                $src_price =  $src_price * $request['quantity'];
            }else{
                $src_price = 0;
            }
        }

        return $src_price;
    }
}

if (!function_exists("getAndUpdateReferralCommission")) {
    function getAndUpdateReferralCommission($user, $product, $variation, $transaction)
    {
        $earned = null;
        if(!empty(settings()->allow_transaction_refferal_system) && settings()->allow_transaction_refferal_system == 'yes'){
            // Get upline
            $upline = User::select('id', 'ref_by', 'cashback')->where('ref_code', $user->ref_by)->orwhere('username', $user->ref_by)->first();
            $referral_commission = $product->referral_commission;
            
            if(!$upline || empty($referral_commission)){
                return null;
            }

            $referral_commission = $referral_commission / 100 * $transaction->buyer_amount;

            if($referral_commission <= 0){
                return null;
            }
            
            // Update earning
            $descr = 'Referral Bonus of N' . $referral_commission . ' landed from ' . $user->username;

            // Update earnings
            $earned = EarnedTransactions::create([
                'user_id' => $upline->id,
                'ref_code' => $user->ref_code,
                'amount' =>  $referral_commission,
                'type' => 'credit',
                'sys_reference' => $transaction->sys_ref,
                'description' => $descr,
                'created_at' => Carbon::now(),
            ]);

            // Update cash back balance for balance
            $upline->update([
                'cashback' => $upline->cashback + $referral_commission,
            ]);

            $earned = $earned->amount;
        }

        return $earned;
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