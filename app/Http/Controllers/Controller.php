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


    public function sendEmail($email_to, $subject, $body){
        $data = [
            'subject' => $subject,
            'body' => $body,
        ];
        
        try {
            if(env('ENT') == 'local'){
                \Log::info([
                    'subject' => $subject,
                    'body' => $body,
                ]);
            }else{
                Mail::to($email_to)->send(new EmailMessages($data));
            }
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
        
        $data['domain_name'] = request()->getHost();
        $data['ip_address'] = request()->header('cf-connecting-ip') ?? request()->ip();
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

    public function advancedApiCall($url, $payload=[], $headers=[], $method = 'POST', $status_code=null)
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
            $path = "/Applications/MAMP/htdocs/swiftdata2/public";
        } else {
            $path = '/home/swifvmvz/public_html';
        }
        
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
        } else if ($api->slug == 'bd') {
            $controller = 'BiddayVerificationController';
        }
        return $controller;
    }
}
