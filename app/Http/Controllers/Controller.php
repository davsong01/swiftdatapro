<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Api;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Settings;
use App\Models\Variation;
use App\Models\WebSetting;
use App\Mail\EmailMessages;
use App\Models\Transactions;
use App\Models\CustomerLevel;
use Illuminate\Support\Carbon;
use App\Models\AutomatedDeposit;
use App\Models\DepositTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function apiResponseMatchCheck($needle){
        // $needle = 'Data Card Purchase was UnSuccessful. Reason: You are sending to the';
        $allowed = ['YOU ARE NOT SENDING TO VALID'];
        $needle = strtoupper($needle);
        $response_words = explode(' ', $needle);
        $match_found = false;

        foreach ($allowed as $phrase) {
            $phrase = strtoupper($phrase);
            $allowed_words = explode(' ', $phrase);

            foreach ($response_words as $word) {
                if (in_array($word, $allowed_words)) {
                    $match_found = true;
                    break 2; // Break both loops
                }
            }
        }


        return $match_found;
    }

    public function populateData(){
        return sendNativeMail('davsong16@gmail.com', 'hello Dave', 'this is to run a check');
    }

    public function synchronizeTransactions(){

        // $trans = Transactions::whereNull('created_at')->take(50000)->get();
        // if (!empty($trans)) {
        //     foreach ($trans as $transaction) {
        //         if (empty($transaction->created_at) && !empty($transaction->buyer_date)) {
        //             try {
        //                 $transaction->update([
        //                     'created_at' => Carbon::parse($transaction->buyer_date),
        //                 ]);
        //             } catch (\Throwable $th) {
        //                 continue;
        //             }
                    
        //         }
        //     }
        // }

        // $transactions = Transactions::whereNull('user_id')->take(5000)->get();

        // if (!empty($transactions)) {
        //     foreach ($transactions as $transaction) {
        //         if(empty($transaction->user_id)){
        //             $user = User::where('email', $transaction->buyer_email)->first();
                    
        //             if ($user) {
        //                 $transaction->update([
        //                     'user_id' => $user->id,
        //                 ]);
        //             }
        //         }

        //         if(empty($transaction->created_at) && !empty($transaction->buyer_date)){
        //             $transaction->update([
        //                 'created_at' => Carbon::parse($transaction->buyer_date),
        //             ]);
        //         }
                
        //         if ($transaction->buyer_prebal > $transaction->buyer_postbal) {
        //             $transaction->update([
        //                 'type' => 'debit',
        //             ]);
        //         } else if ($transaction->buyer_prebal < $transaction->buyer_postbal) {
        //             $transaction->update([
        //                 'type' => 'credit',
        //             ]);
        //         }
    
        //         if (empty($transaction->unit_price) && $transaction->buyer_prebal > 0 && $transaction->buyer_postbal > 0) {
        //             $transaction->update([
        //                 'unit_price' => (float) $transaction->type == 'debit' ? $transaction->buyer_prebal - $transaction->buyer_postbal :  $transaction->buyer_amount,
        //             ]);
        //         }
        //     }
        // }

        // $transactionsX = Transactions::whereNull('product_id')->take(5000)->get();
        
        // foreach($transactionsX  as $transaction){
        //     if (empty($transaction->product_id)) {
        //         $product = Product::where('slug', 'like', $transaction->buyer_service)->first();
        //         $variation = Variation::where('api_name', 'like', $transaction->buyer_descr)->first();

        //         if ($product) {
        //             $transaction->update([
        //                 'product_id' => $product->id,
        //                 'category_id' => $product->category->id
        //             ]);
        //         }

        //         if ($variation) {
        //             $transaction->update([
        //                 'variation_id' => $variation->id,
        //             ]);
        //         }
        //     }

        //     if (empty($transaction->provider_id)) {
        //         $provider = strtolower($transaction->provider);
        //         $api = Api::where('slug', 'like', $provider)->first();

        //         if ($api) {
        //             $transaction->update([
        //                 'provider_id' => $api->id,
        //             ]);
        //         }
        //     }
        // }


        // $transactionsXX = Transactions::whereNull('provider_id')->take(5000)->get();

        // foreach ($transactionsXX  as $transaction) {
        //     $provider = strtolower($transaction->provider);
        //     $api = Api::where('slug', 'like', $provider)->first();

        //     if ($api) {
        //         $transaction->update([
        //             'provider_id' => $api->id,
        //         ]);
        //     }
        // }

        // $users = User::where('status', 'active')->orWhere('status','inactive')->get();

        // if (!empty($users)) {
        //     foreach ($users as $user) {
        //         if ($user->status == 'active') {
        //             $user->update([
        //                 'status' => 1,
        //             ]);
        //         }

        //         if ($user->status == 'inactive') {
        //             $user->update([
        //                 'status' => 0,
        //             ]);
        //         }
        //     }
        // }
        $web_settings = WebSetting::first();
        
        if(empty($web_settings->download_android_app_link)){
            $web_settings->download_android_app_link = 'https://drive.google.com/drive/folders/107QE4pPKM4F2ah3c-uUM4nnxjM4O4EeC';
        }

        if (empty($web_settings->download_ios_app_link)) {
            $web_settings->download_ios_app_link = '';
        }

        // $web_settings->save();

        $users = User::whereNull('created_at')->take(500)->get();
        try {
            //code...
            if (!empty($users)) {
                foreach ($users as $user) {
                    $reg_date = str_replace('  ', ' ', $user->reg_date);

                    if (strpos($reg_date, 'PM') !== false) {
                        $reg_date = str_replace(' PM', '', $reg_date);
                        $date_parts = explode(' ', $reg_date);
                        $time_parts = explode(':', $date_parts[1]);

                        // Convert the hour to 24-hour format if necessary
                        $hour = (int)$time_parts[0];
                        if ($hour < 12) {
                            $hour += 12;
                        }
                        $time_parts[0] = str_pad($hour, 2, '0', STR_PAD_LEFT);
                        $date_parts[1] = implode(':', $time_parts);
                        $reg_date = implode(' ', $date_parts);
                    }

                    $reg_date = str_replace(' AM', '', $reg_date);
                    $reg_date = str_replace(' PM', '', $reg_date);

                    $time = Carbon::createFromFormat('d-M-y H:i', $reg_date)->toDateTimeString();

                    $user->update([
                        'created_at' => $time,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        // Update kyc status
        $users = User::whereNotNull('bvn')->whereNotNull('nin')->where('kyc_status', 0)->take(1500)->get();
        
        foreach($users as $user){
            if((!empty($user->nin) || !empty($user->bvn)) && $user->kyc_status == 0){
                // dump($user);
                $user->kyc_status = 1;
            }
        }
        
        // $levels = CustomerLevel::whereNull('group_link')->get();
        // foreach($levels as $level){
        //     if($level->id == 1){
        //         $level->update([
        //             'group_link' => $web_settings->group_link,
        //         ]);
        //     }else{
        //         $level->update([
        //             'group_link' => $web_settings->group_link2,
        //         ]);
        //     }
        // }

        dd('done');
    }

    public function sendEmail($email_to, $subject, $body){
        $data = [
            'subject' => $subject,
            'body' => $body,
        ];
        
        try {
            Mail::to($email_to)->send(new EmailMessages($data));
        } catch (\Exception $e) {
            \Log::info([
                'Email Sending Error' => $data,
                'error' => $e->getMessage(),
            ]);
            // dd($e->getMessage());
        }
    }

    public function basicApiCall($url, $payload, $headers, $method = 'POST'){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, url($url));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($method == "GET") {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } elseif ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        } elseif ($method == "PUT") {
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        } elseif ($method == "DELETE") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }elseif (!empty($timeout)) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        
        // if ($response == false) {
        //     $err =  new \Exception(curl_error($ch), curl_errno($ch));
        //     // \Log::info(['url' => $url, 'payload' => $payload, 'res'=>$response]);
        //     // \Log::error($err);
        //     return (object)['message' => $err->getMessage(), 'code' => $err->getCode()];
        // }
    
        return $response;
    
    }

    public function generateRequestId($suffix=null){        
        if(!empty($suffix)){
            $trx = date("YmdHi") . rand(10000, 90000). '-'.$suffix;
        }else{
            $trx = date("YmdHi") . rand(10000, 90000) . '-SWFT';
        }

        return $trx;
    }

    public function chargeUserWallet(){

    }

    public function logTransaction($request){
        $data = $request;
        $data['app_version'] = Session::get('app_version');
        $data['domain_name'] = Session::get('domain_name');
        $data['ip_address'] = Session::get('ip_address');
        $data['user_id'] = $request['user_id'] ?? auth()->user()->id;
        
        $transaction = Transactions::create($data);
        return $transaction;
    }

    public function updateWalletBalance($user, $amount, $type){
        
        if($type == 'credit'){
            $user->wallets = $user->wallets + $amount;
        }else{
            $user->wallets = $user->wallets - $amount;
        }

        $user->save();

        return $user;
    }

    public function getDomainDetails(){
        
    }

    public function advancedApiCall($url, $payload=[], $headers, $method = 'POST', $status_code=null)
    {
        // \Log::info(['Begin connection...', $url, $payload, $headers]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($method == "GET") {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } elseif ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        } elseif ($method == "PUT") {
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        } elseif (!empty($timeout)) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_exec($ch);
        
        // \Log::info(['End connection', $url, $payload, $headers, $response]);
        
        if(isset($status_code)){
            return [
                'response' => $response,
                'status_code' => $code
            ];
        }
        
        return $response;
    }

    public function uploadFile($file, $location = null, $width = null, $height = null)
    {
        $folder = $this->getPathToSaveFile($location);
        $name = uniqid(11) . '.' . $file->getClientOriginalExtension();

        if (substr($file->getMimeType(), 0, 5) == 'image') {
            $imageFile = Image::make($file);
            $imgWidth = $width ?? $imageFile->width();
            $imgHeight = $imgHeight ?? $imageFile->height();

            if (!is_null($imgWidth) && !is_null($imgHeight)) {
                if ($imgWidth < $imgHeight) {
                    $imageFile->resize($imgWidth, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } else {
                    $imageFile->resize(null, $imgWidth, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
            }
        }
        
        $imageFile->save($folder . '/' . $name);

        return $folder . '/' . $name;
    }

    public function getPathToSaveFile($location = null)
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        if ($location) {
            $location = $location . '/' . $year . '/' . $month . '/' . $day;
        } else {
            $location = $year . '/' . $month . '/' . $day;
        }

        if (env('ENT') == 'local') {
            $path = "/Applications/MAMP/htdocs/kingsvtu/public";
        } else {
            $path = '/home/swifvmvz/beta.swiftdatang.com';
        }

        // $path = public_path();
        // 
        
        if (!is_dir($path . '/' . $location)) {
            mkdir($path . '/' . $location, 0777, true);
        }

        return $location;
    }

    public function getApiFilename($api)
    {
        if ($api->slug == 'easyaccess') {
            $controller = 'EasyAccessController';
        } else if ($api->slug == 'vtpass') {
            $controller = 'VtpassController';
        } else if ($api->slug == 'gongzo-data') {
            $controller = 'GongzoController';
        } else if ($api->slug == 'naija-result-pins') {
            $controller = 'NaijaResultPinsController';
        } else if ($api->slug == 'club-konnect') {
            $controller = 'ClubkonnectController';
        } else if ($api->slug == 'sme-plug') {
            $controller = 'SmePlugController';
        } else if ($api->slug == 'graphic') {
            $controller = 'GraphicController';
        }

        return $controller;
    }

    public function sendLoginEmail($user){
        $body = 'Dear ' . $user->full_name . '<br>Your login to ' . config('app.php') . ' at ' . Carbon::now() . ' was successful<br>';
        $body .= '<br><br>Warm Regards<br>' . config('app.name');
        $subject = "Welcome Back!";

        // $this->sendEmail($user->email, $subject, $body);
    }
}
