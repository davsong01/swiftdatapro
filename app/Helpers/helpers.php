<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\KycData;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Variation;
use App\Models\WebSetting;
use App\Models\Notification;
use App\Models\Transactions;
use App\Models\RolePermission;
use App\Models\AutomatedDeposit;
use App\Models\DepositTransaction;
use App\Models\EarnedTransactions;
use Illuminate\Support\Facades\DB;

if (!function_exists('sendNativeMail')) {
    function sendNativeMail($to, $subject, $body)
    {
        $settings = WebSetting::first();
        
        $from = $settings->admin_notification_email;
        
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n" .
            'Reply-To: ' . $from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $mail = mail($to, $subject, $body, $headers);
        $mail = mail($to, $subject, $body, $headers);

        try {
            return mail($to, $subject, $body, $headers);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}

if (!function_exists("emailNotificationOptions")) {
    function emailNotificationOptions()
    {
        return [
            'Essential Emails (Verification link, password reset, pin reset)' => 'essential-email', // verification link, password reset, pin reset, welcome email
            'Login Notification Emails' => 'login-email',
            'Transaction Notification Emails' => 'transaction-email',
            'Admin Notification on registration' => 'admin-registration-notification-email',
            'Graphics Transaction Notification Email' => 'graphics-registration-notification-email',
            'Airtime to Cash Admin Notification Email' => 'airtime-to-cash-notification',
            'Customer Review Email' => 'customer-review-email',
        ];
    }
}

if (!function_exists("mask")) {
    function mask($word, $a = 2, $b = 9, $c = 9, $d = 10)
    {
        return substr_replace($word, "*******", $a, $b) . substr($word, $c, $d);
    }
}

if (!function_exists("topProduct")) {
    function topProduct()
    {
        $transaction = Transactions::with('product')
        ->whereNotNull('product_id')
        ->whereIn('buyer_status', ['COMPLETED', 'SUCCESSFUL'])
            ->whereBetween('created_at', [Carbon::today(), Carbon::now()])
            ->select(DB::raw('product_id, provider_id, variation_id, SUM(buyer_amount) as total_buyer_amount, SUM(profit) as total_profit, COUNT(id) as count'))
            ->groupBy('product_id', 'provider_id', 'variation_id')
            ->orderBy('total_buyer_amount', 'DESC')
            ->first();
        
        return $transaction;
    }
}

if (!function_exists("getBanks")) {
    function getBanks()
    {
        return AutomatedDeposit::select('bank_code', 'bank_name')->distinct()->get();
    }
}

if (!function_exists("getDownlines")) {
    function getDownlines($user)
    {
        $downlines = User::where('ref_by', $user->username)->orWhere('ref_code', $user->ref_code)->get();
        return $downlines;

    }
}

if (!function_exists("productBlacklist")) {
    function productBlacklist($user)
    {
        return !empty($user->product_blacklist) ? $user->product_blacklist : [];
    }
}

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
        $discount_type = $product->discount_type ?? $product->category->discount_type;
        
        // Check if resource has a category discount
        // Bypass discount type
        $byPassProducts = !empty(env('BY_PASS_PRODUCT_IDS')) ? explode(',',env('BY_PASS_PRODUCT_IDS')) : [];
        
        if ($discount_type == 'percentage' || !in_array($product->id, $byPassProducts)) {
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
        $discount_type = $product->discount_type ?? $product->category->discount_type;

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
                $display_amount = settings()->currency . number_format(intval($new_amount));
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
        $discount_type = $variation->product->discount_type ?? $variation->category->discount_type;
        
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

        // \Log::info(['id' => $variation->id, 'discount' => $discount, 'sy_price' => $individualdiscount]);
        
        if (!empty($discount)) {
            $type = $discount_type;
            if ($discount_type == 'flat') {
                $new_amount = ($amount < $discount->price || $discount->price <= 0) ? ($amount * $quantity) : ($discount->price *  $quantity);
                $discount_applied = abs($amount - $discount->price);
                $display_amount = settings()->currency . number_format($new_amount);
            } else {
                $discount_applied = $discount->price;
                $discount_applied = floor($discount_applied / 100 * $amount);
                $n_amount = $amount - $discount_applied;
                $new_amount = ($amount < $n_amount || $n_amount <= 0) ? $amount * $quantity : $n_amount * $quantity;
                $display_amount = settings()->currency . number_format(intval($new_amount));
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

if (!function_exists("getSourcePriceAndCalculateProfit")) {
    function getSourcePriceAndCalculateProfit($product, $variation, $request){
        $provider_percentage_discount = $product->provider_percentage_discount;
        if ($product->has_variations == 'yes' && !empty($variation) && $variation->fixed_price == 'yes') {
            // If product has variation and variation fixed price == yes, use variation->api_price
            $src_price = $variation->api_price;
            $profit = $request['total_amount'] - $src_price;
            
            if(!empty($src_price)){
                if (!empty($provider_percentage_discount)) {
                    $discount_off = $provider_percentage_discount / 100 * $request['unit_price'];
                    $src_price = ($request['unit_price'] - $discount_off);
                    $src_price =  $src_price;

                    // Profit is amount charged - provider charge 
                    $profit = $request['total_amount'] - $src_price;
                } else {
                    $src_price = 0;
                }
            }

        } else {

            if(!empty($provider_percentage_discount)){
                $discount_off = $provider_percentage_discount / 100 * $request['unit_price'];
                $src_price = ($request['unit_price'] - $discount_off );
                $src_price =  $src_price;

                // Profit is amount charged - provider charge 
                $profit = $request['total_amount'] - $src_price;
            }else{
                $src_price = $product->system_price;
                $profit = $request['total_amount'] - $src_price;
            }
        }

        $profit = $profit * $request['quantity'];

        return [
            'source_price' => $src_price ?? 0,
            'profit' => $profit ?? 0
        ];
    }
}


// if (!function_exists("getSourcePriceAndCalculateProfit")) {
//     function getSourcePriceAndCalculateProfit($product, $variation, $request)
//     {
//         $provider_percentage_discount = $product->provider_percentage_discount;
//         if ($product->has_variations == 'yes' && !empty($variation) && $variation->fixed_price == 'yes') {
//             // If product has variation and variation fixed price == yes, use variation->api_price
//             $src_price = $variation->api_price;
//             $profit = $request['total_amount'] - $src_price;

//             if (empty($src_price)) {
//                 if (!empty($provider_percentage_discount)) {
//                     $discount_off = $provider_percentage_discount / 100 * $request['unit_price'];
//                     $src_price = ($request['unit_price'] - $discount_off);
//                     $src_price =  $src_price;

//                     // Profit is amount charged - provider charge 
//                     $profit = $request['total_amount'] - $src_price;
//                 } else {
//                     $src_price = 0;
//                 }
//             }
//         } else {

//             if (!empty($provider_percentage_discount)) {
//                 $discount_off = $provider_percentage_discount / 100 * $request['unit_price'];
//                 $src_price = ($request['unit_price'] - $discount_off);
//                 $src_price =  $src_price;

//                 // Profit is amount charged - provider charge 
//                 $profit = $request['total_amount'] - $src_price;
//             } else {
//                 $src_price = $product->system_price;
//                 $profit = $request['total_amount'] - $src_price;
//             }
//         }

//         $profit = $profit * $request['quantity'];

//         return [
//             'source_price' => $src_price,
//             'profit' => $profit
//         ];
//     }
// }

if (!function_exists("getAndUpdateReferralCommission")) {
    function getAndUpdateReferralCommission($type, $user, $product, $transaction)
    { 
        $earned = null;
        $commission_settings = settings()->refferal_commission_settings;

        if($type === 'transaction'){
            if (!empty($commission_settings['allow_transaction_refferal']) && $commission_settings['allow_transaction_refferal']== 'yes') {
                $upline = User::where('ref_code', $user->ref_by)
                    ->orWhere('username', $user->ref_by)
                    ->lockForUpdate()
                    ->first(['id', 'ref_by', 'cashback']);

                $referral_commission = $product->referral_commission ?? ($commission_settings['transaction_refferal_commission_percent'] ?? null);
                
                if (!$upline || empty($referral_commission)) {
                    return null;
                }

                $referral_commission = $referral_commission / 100 * $transaction->buyer_amount;

                if ($referral_commission <= 0) {
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

                // Update cash back balance
                $upline->update([
                    'cashback' => $upline->cashback + $referral_commission,
                ]);

                $earned = $earned->amount;
            }
        }

        // if ($type === 'upgrrade') {
        //     if (!empty($commission_settings['allow_transaction_refferal']) && $commission_settings['allow_transaction_refferal'] == 'yes') {
        //         $upline = User::where('ref_code', $user->ref_by)
        //             ->orWhere('username', $user->ref_by)
        //             ->lockForUpdate()
        //             ->first(['id', 'ref_by', 'cashback']);

        //         $referral_commission = $product->referral_commission ?? ($commission_settings['transaction_refferal_commission_percent'] ?? null);

        //         if (!$upline || empty($referral_commission)) {
        //             return null;
        //         }

        //         $referral_commission = $referral_commission / 100 * $transaction->buyer_amount;

        //         if ($referral_commission <= 0) {
        //             return null;
        //         }

        //         // Update earning
        //         $descr = 'Referral Bonus of N' . $referral_commission . ' landed from ' . $user->username;

        //         // Update earnings
        //         $earned = EarnedTransactions::create([
        //             'user_id' => $upline->id,
        //             'ref_code' => $user->ref_code,
        //             'amount' =>  $referral_commission,
        //             'type' => 'credit',
        //             'sys_reference' => $transaction->sys_ref,
        //             'description' => $descr,
        //             'created_at' => Carbon::now(),
        //         ]);

        //         // Update cash back balance
        //         $upline->update([
        //             'cashback' => $upline->cashback + $referral_commission,
        //         ]);

        //         $earned = $earned->amount;
        //     }
        // }

        if ($type === 'first-deposit') {
            if (!empty($commission_settings['allow_first_deposit_refferal']) && $commission_settings['allow_first_deposit_refferal'] == 'yes') {
                $allTimeFunding = DepositTransaction::where('user_id', auth()->user()->id)->where('user_service', 'DEPOSIT')->where('user_status', 'SUCCESSFUL')->count();

                if ($allTimeFunding > 0) {
                    return null;
                }
                
                $upline = User::where('ref_code', $user->ref_by)
                    ->orWhere('username', $user->ref_by)
                    ->lockForUpdate()
                    ->first(['id', 'ref_by', 'cashback']);

                $referral_commission = $commission_settings['first_deposit_refferal_commission_percent'] ?? null;

                if (!$upline || empty($referral_commission)) {
                    return null;
                }

                $referral_commission = $referral_commission / 100 * $transaction->buyer_amount;

                if ($referral_commission <= 0) {
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

                // Update cash back balance
                $upline->update([
                    'cashback' => $upline->cashback + $referral_commission,
                ]);

                $earned = $earned->amount;
            }
        }

        if ($type === 'level-upgrade') {
            if (!empty($commission_settings['allow_first_deposit_refferal']) && $commission_settings['allow_first_deposit_refferal'] == 'yes') {
                $upline = User::where('ref_code', $user->ref_by)
                    ->orWhere('username', $user->ref_by)
                    ->lockForUpdate()
                    ->first(['id', 'ref_by', 'cashback']);

                $referral_commission = $commission_settings['first_deposit_refferal_commission_percent'] ?? null;

                if (!$upline || empty($referral_commission)) {
                    return null;
                }

                $referral_commission = $referral_commission / 100 * $transaction->buyer_amount;

                if ($referral_commission <= 0) {
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

                // Update cash back balance
                $upline->update([
                    'cashback' => $upline->cashback + $referral_commission,
                ]);

                $earned = $earned->amount;
            }
        }
        return $earned;
    }
}


if (!function_exists("popupNotification")) {
    function popupNotification()
    {
        $notification1 = Notification::where(['type' => 'popup', 'status' => 'active'])->first();
        return $notification1;
    }
}

if (!function_exists("scrollNotification")) {
    function scrollNotification()
    {
        $notification2 = Notification::where(['type' => 'scroll', 'status' => 'active'])->get();
        $content = '';

        foreach($notification2 as $notify){
            $content .= strip_tags($notify->content) . '. ';
        }
        
        return [
            'notifications' => $notification2,
            'content' => $content,
        ];
    }
}

if (!function_exists("generalNotifications")) {
    function generalNotifications()
    {
        $notification2 =  Notification::where(['type' => 'general', 'status' => 'active'])->get();
        return $notification2;
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
    function allowedPermissions($user=null){
        $user = !empty($user) ? $user : auth()->guard('admin')->user();
        $permissions = $user->permissions ?? [];
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
        if (Auth::guard('admin')->check()) {
            $routes = auth()->guard('admin')->user()->permissions;
            $ids = auth()->guard('admin')->user()->roleids;
        }else{
            return false;
        }
        
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
                'account_id',
                'billersCode'
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

    if (!function_exists("buyerStatusColors")) {
        function buyerStatusColors($transaction){
            if ($transaction->buyer_status == 'FAILED') {
                $color = 'red';
                $bg = 'danger';
            } elseif ($transaction->buyer_status == 'initiated') {
                $color = '#FDAC41';
                $bg = 'dark';
            } elseif ($transaction->buyer_status == 'ATTENTION-REQUIRED') {
                $color = '#FDAC41';
                $bg = 'warning';
            } elseif (in_array($transaction->buyer_status, ['ADMIN-REFUND'])) {
                $color = 'black';
                $bg = 'dark';
            } else {
                $color = 'green';
                $bg = 'success';
            }

            return [
                'color' => $color,
                'bg' => $bg,
            ];
        }
    }

    // if (!function_exists("customerStatistics")) {
    //     function customerStatistics()
    //     {
    //         $today = Carbon::today();
    //         $startOfWeek = Carbon::now()->startOfWeek(); // Start of current week (Sunday)
    //         $startOfMonth = Carbon::today()->startOfMonth(); // Start of current month

    //         // Query logic
    //         $query = Transactions::with('customer:id,full_name,middle_name,last_name')
    //         ->whereNotIn('buyer_service', ['KYC-VERIFICATION-CHARGE', 'DEPOSIT'])
    //         ->whereIn('buyer_status', ['SUCCESSFUL'])
    //         ->addSelect(DB::raw('SUM(unit_price) as unit_price, COUNT(id) as count, user_id'))
    //         ->groupBy('user_id');

    //         // Customer of the Day
    //         $customerOfTheDay = clone $query;
    //         $customerOfTheDay = $customerOfTheDay->whereDate('created_at', $today)
    //         ->orderBy('unit_price', 'DESC')
    //         ->first();

    //         // Customer of the Week
    //         $customerOfTheWeek = clone $query;
    //         $customerOfTheWeek = $customerOfTheWeek->whereBetween('created_at', [$startOfWeek, Carbon::now()])
    //         ->orderBy('unit_price', 'DESC')
    //         ->first();

    //         // Customer of the Month
    //         $customerOfTheMonth = clone $query;
    //         $customerOfTheMonth = $customerOfTheMonth->whereBetween('created_at', [$startOfMonth, Carbon::now()])
    //         ->orderBy('unit_price', 'DESC')
    //         ->first();

    //         // Return the results
    //         return [
    //             'customer_of_the_day' => $customerOfTheDay,
    //             'customer_of_the_week' => $customerOfTheWeek,
    //             'customer_of_the_month' => $customerOfTheMonth,
    //         ];
    //     }
    // }
    if (!function_exists("customerStatistics")) {
        function customerStatistics()
        {
            $today = Carbon::today();
            $startOfWeek = Carbon::now()->startOfWeek(); // Start of current week (Sunday)
            $startOfMonth = Carbon::today()->startOfMonth(); // Start of current month

            // Query logic
            $query = Transactions::with('customer:id,full_name,middle_name,last_name,email') 
                ->where('type','debit')
                ->whereNotIn('buyer_service', ['KYC-VERIFICATION-CHARGE', 'DEPOSIT', 'ADMIN-CREDIT', 'ADMIN-REFUNDED'])
                ->whereIn('buyer_status', ['SUCCESSFUL'])
                ->addSelect(DB::raw('SUM(unit_price) as unit_price, COUNT(id) as count, user_id'))
                ->groupBy('user_id');
            
            // Customer of the Day
            $customerOfTheDay = clone $query;
            $customerOfTheDay = $customerOfTheDay->whereDate('created_at', $today)
                ->addSelect(DB::raw('MIN(created_at) as date_from, MAX(created_at) as date_to'))  // Get date range
                ->orderBy('unit_price', 'DESC')
                ->first();

            // Customer of the Week
            $customerOfTheWeek = clone $query;
            $customerOfTheWeek = $customerOfTheWeek->whereBetween('created_at', [$startOfWeek, Carbon::now()])
                ->addSelect(DB::raw('MIN(created_at) as date_from, MAX(created_at) as date_to'))  // Get date range
                ->orderBy('unit_price', 'DESC')
                ->first();

            // Customer of the Month
            $customerOfTheMonth = clone $query;
            $customerOfTheMonth = $customerOfTheMonth->whereBetween('created_at', [$startOfMonth, Carbon::now()])
                ->addSelect(DB::raw('MIN(created_at) as date_from, MAX(created_at) as date_to'))  // Get date range
                ->orderBy('unit_price', 'DESC')
                ->first();

            // Convert date_from and date_to to Carbon instances before formatting
            if ($customerOfTheDay) {
                $customerOfTheDay->date_from = Carbon::parse($customerOfTheDay->date_from);
                $customerOfTheDay->date_to = Carbon::parse($customerOfTheDay->date_to);
            }

            if ($customerOfTheWeek) {
                $customerOfTheWeek->date_from = Carbon::parse($customerOfTheWeek->date_from);
                $customerOfTheWeek->date_to = Carbon::parse($customerOfTheWeek->date_to);
            }

            if ($customerOfTheMonth) {
                $customerOfTheMonth->date_from = Carbon::parse($customerOfTheMonth->date_from);
                $customerOfTheMonth->date_to = Carbon::parse($customerOfTheMonth->date_to);
            }
            
            return [
                'customer_of_the_day' => [
                    'user_id' => $customerOfTheDay->user_id ?? null,
                    'unit_price' => $customerOfTheDay->unit_price ?? null,
                    'count' => $customerOfTheDay->count  ?? null,
                    'date_from' => $customerOfTheDay->date_from  ?? null,
                    'date_to' => $customerOfTheDay->date_to  ?? null,
                    'name' => $customerOfTheDay->customer->name ?? null,
                    'email' => $customerOfTheDay->customer->email ?? null,
                ],
                'customer_of_the_week' => [
                    'user_id' => $customerOfTheWeek->user_id ?? null,
                    'unit_price' => $customerOfTheWeek->unit_price ?? null,
                    'count' => $customerOfTheWeek->count ?? null,
                    'date_from' => $customerOfTheWeek->date_from ?? null,
                    'date_to' => $customerOfTheWeek->date_to ?? null,
                    'name' => $customerOfTheWeek->customer->name ?? null,
                    'email' => $customerOfTheWeek->customer->email ?? null,
                ],
                'customer_of_the_month' => [
                    'user_id' => $customerOfTheMonth->user_id ?? null,
                    'unit_price' => $customerOfTheMonth->unit_price ?? null,
                    'count' => $customerOfTheMonth->count ?? null,
                    'date_from' => $customerOfTheMonth->date_from ?? null,
                    'date_to' => $customerOfTheMonth->date_to ?? null,
                    'name' => $customerOfTheMonth->customer->name ?? null,
                    'email' => $customerOfTheMonth->customer->email ?? null,
                ],
            ];
        }
    }

    if (!function_exists("kycDataStatus")) {
        function kycDataStatus($user_id)
        {
            $kyc = KycData::where('user_id', $user_id)->first();
            return !empty($kyc) ? $kyc->verification_data : null;
        }
    }

    if (!function_exists("deepObjectToArray")) {
        function deepObjectToArray($object){
            if (is_object($object)) {
                $object = get_object_vars($object);
            }

            if (is_array($object)) {
                return array_map('deepObjectToArray', $object);
            }

            return $object;
        }
    }
}