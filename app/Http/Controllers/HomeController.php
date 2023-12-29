<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\WebSetting;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\CustomerReview;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use App\Models\EarnedTransactions;
use Illuminate\Support\Facades\Auth;
use App\Services\JsonResponseService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $web_settings = WebSetting::first();
        $this->createReservedAccount($web_settings);
        $baseurl = url('/');
        return view('customer.dashboard', compact('web_settings','baseurl'));
    }

    public function customerReview(Request $request, JsonResponseService $response){
        $setting = WebSetting::first();
        CustomerReview::create([
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'comment' => $request->rate_comment,
            'date' => Carbon::now(),
        ]);

        $body = auth()->user()->email . " Sent You A Review (" . $request->rate_comment . ')<br/><br/>';
        $body .= '<p>Kindly attend to this review.' . '<br/></p>';
        $subject = "New Review Has Arrived";

        $this->sendEmail($setting->admin_email, $subject,$body);

        $response->handler('success', 'Thank you for your review. We always happy to hear from you !!!', 200);

    }

    public function upgradeLevel(Request $request, JsonResponseService $response){
        $validator = Validator::make($request->all(), [
            'level' => 'required',
        ]);

        if ($validator->fails()) {
            $response->handler('fail', $validator->messages()->first(), 100);
        }

        $setting = WebSetting::first();
        $reference = strtoupper(time() . rand(1000, 9000) . rand(20000000, 90000000));
        $reference2 = strtoupper(rand(1000, 9000) . rand(200000, 900000) . uniqid());
        $level = $request->level;
        $Account_Balance = auth()->user()->wallets;
        $dateTime = new DateTime('now', new DateTimeZone('Africa/Lagos'));
        $time = $dateTime->format("d-M-y  h:i A");
        $timestring = time();

        if ($level == "2") {
            $amount = $setting->level2_charge;
            $refb = $setting->level2_charge - $setting->level2_adminch;
            $admb = $setting->level2_adminch;
            $package = 2;
        }

        if ($level == "3") {
            $amount = $setting->level3_charge;
            $refb = $setting->level3_charge - $setting->level3_adminch;
            $admb = $setting->level3_adminch;
            $package = 3;
        }

        if ($level == "4") {
            $amount = $setting->level4_charge;
            $refb = $setting->level4_charge - $setting->level4_adminch;
            $admb = $setting->level4_adminch;
            $package = 4;
        }

        $adinfo = User::where('username', 'ADMIN')->first();
        $refinfo = auth()->user();
        
        if ($Account_Balance >= $amount && $amount > 0) {
            // Update user wallet and balance
            $newbal = $Account_Balance - $amount;
            auth()->user()->level = $package;
            auth()->user()->wallets = $newbal;
            auth()->user()->save();

            $descr1 = 'Activation of level ' . $package . ' was successful';
            
            $descr2 = 'Referral bonus of N' . $refb . ' landed from ' . auth()->user()->username;
            $descr3 = 'Admin bonus of N' . $admb . ' landed from ' . auth()->user()->username;
            $new_ad_bonus = $adinfo->wallets + $admb;

            $upline = $refinfo->ref_by;

            if ($upline !== 'ADMIN') {
                // Credit referral
                $uplineDetails = User::where('ref_code', $upline)->orwhere('username', $upline)->first();
                
                if ($uplineDetails) {
                    // Update cash back balance
                    $uplineDetails->update([
                        'cashback' => $uplineDetails->cashback + $refb,
                    ]);

                    // Update referral earning transactions
                    EarnedTransactions::create([
                        'user_id' => $uplineDetails->id,
                        'ref_code' => $refinfo->ref_code,
                        'amount' => $refb,
                        'type' => 'credit',
                        'description' => $descr2,
                        'created_at' => Carbon::now(),
                    ]);
                    
                    /****
                     * Update bonus to admin
                     */
                    // Update cash back balance for balance
                    $adinfo->update([
                        'cashback' => $adinfo->cashback + $admb,
                    ]);

                    // Update earninigs for admin
                    EarnedTransactions::create([
                        'user_id' => $adinfo->id,
                        'ref_code' => $refinfo->ref_code,
                        'amount' => $refb,
                        'type' => 'credit',
                        'description' => $descr3,
                        'created_at' => Carbon::now(),
                    ]);

                    $data = [
                        'mobile_number' => auth()->user()->mobile,
                        'buyer_email' => auth()->user()->email,
                        'buyer_username' => auth()->user()->username,
                        'buyer_service' => 'UPGRADE',
                        'buyer_amount' => $amount,
                        'buyer_prebal' => $Account_Balance,
                        'buyer_postbal' => $newbal,
                        'buyer_descr' => $descr1,
                        'buyer_status' => 'SUCCESSFUL',
                        'buyer_date' => $time,
                        'timestring' => $timestring,
                        'charge_from' => 'WALLET',
                        'user_type' => $package,
                        'sys_ref' => $reference,
                        'api_ref' => $reference2,
                        'api_report' => 'GOOD',
                        'src_price' => '0',
                        'profit' => '0',
                        'databyte' => '0',
                    ];

                    $this->logTransaction($data);

                    $response->handler('success', $descr1, 200);

                }
            } else {
                // credit admin only with full money
                // Update final transactions
                $new_ad_bonus = $adinfo->wallet + $amount;
                $descr3 = 'Admin bonus of N' . $amount . ' landed from ' . auth()->user()->username;

                // Update earnings
                EarnedTransactions::create([
                    'user_id' => $adinfo->id,
                    'ref_code' => $refinfo->ref_code,
                    'amount' => $amount,
                    'type' => 'credit',
                    'description' => $descr3,
                    'created_at' => Carbon::now(),
                ]);

                // Update cash back balance for balance
                $adinfo->update([
                    'cashback' => $adinfo->cashback + $amount,
                ]);

                // User upgrade transaction history
                $data = [
                    'mobile_number' => auth()->user()->mobile, 
                    'buyer_email' => auth()->user()->email, 
                    'buyer_username' => auth()->user()->username, 
                    'buyer_service' => 'UPGRADE', 
                    'buyer_amount' => $amount, 
                    'buyer_prebal' => $Account_Balance, 
                    'buyer_postbal' => $newbal, 
                    'buyer_descr' => $descr1, 
                    'buyer_status' => 'SUCCESSFUL', 
                    'buyer_date' => $time, 
                    'timestring' => $timestring, 
                    'charge_from' => 'WALLET', 
                    'user_type' => $package, 
                    'sys_ref' => $reference, 
                    'api_ref' => $reference2, 
                    'api_report' => 'GOOD', 
                    'src_price' => '0', 
                    'profit' => '0', 
                    'databyte' => '0',
                ];

                $this->logTransaction($data);
                $response->handler('success', $descr1, 200);

            }
        }else{
            $response->handler('fail', 'You do not have enough balance to upgrade !!!', 100);
        }
       
    }

    public function createReservedAccount($setting){
        $payvesel = PaymentGateway::where('id', 2)->first();
        $monnify = PaymentGateway::where('id', 1)->first();
        
        if ($payvesel->status == 'ON' && auth()->user()->payvessel_reserved_account == "") {
            $api_key = $payvesel->api_key;
            $public_key = $payvesel->public_key;
            $secret_key = $payvesel->secret_key;

            $payload = json_encode([
                "name" => auth()->user()->full_name,
                "email" => auth()->user()->email,
                "phoneNumber" => auth()->user()->mobile,
                "bankcode" => ["101", "120001"],
                "businessid" => $payvesel->merchant_id
            ]);

            $headers = [
                'api-key:' . $api_key,
                'api-secret:' . 'Bearer ' . $secret_key,
                'Content-Type:application/json'
            ];
           
            if (env('ENT') == 'local') {
                // $url = "https://sandbox.payvessel.com/api/external/request/customerReservedAccount/";
                $url = "https://api.payvessel.com/api/external/request/customerReservedAccount/";
            } else {
                $url = "https://api.payvessel.com/api/external/request/customerReservedAccount/";
            }

            $response = $this->advancedApiCall($url, $payload, $headers);
           
            if($response){
                auth()->user()->payvessel_reserved_account = $response;
                auth()->user()->save();
            }
        }

        if ($monnify->status == 'ON' && auth()->user()->reserved_account == "") {
            $monnify_hashkeys = $monnify->public_key.':'.$monnify->secret_key;
            $stringk = base64_encode($monnify_hashkeys);
            
            $url = 'https://api.monnify.com/api/v1/auth/login';
            $headers = [
                "Authorization: Basic " . $stringk . "",
            ];

            $response1 = $this->advancedApiCall($url, [], $headers);
            $result = json_decode($response1, true);

            $accessToken = $result['responseBody']['accessToken'];

            $postdata = [
                'accountName' => auth()->user()->full_name,
                'accountReference' => uniqid(). rand(1000, 9000). time(),
                'currencyCode' => "NGN",
                'customerEmail' => auth()->user()->email,
                'contractCode' => $monnify->api_key,
                'customerName' => auth()->user()->full_name,
                "getAllAvailableBanks" => false,
                "preferredBanks" => ["035", "232", "50515", "058"]
            ];

            $headers2 = [
                "Content-Type: application/json",
                "Authorization:Bearer " . $accessToken,
            ];

            $url2 = 'https://api.monnify.com/api/v2/bank-transfer/reserved-accounts';
         
            $response2 = $this->advancedApiCall($url2, json_encode($postdata), $headers2);
            
            if ($response2) {
                auth()->user()->reserved_account = $response2;
                auth()->user()->save();
            }

        }
    }
}
