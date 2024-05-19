<?php

namespace App\Http\Controllers;

use App\Mail\EmailMessages;
use App\Models\Transactions;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendEmail($email_to, $subject, $body){
        // $headers = 'MIME-Version: 1.0' . "\r\n";
        // $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // $headers .= 'From: ' . $email_from . "\r\n" .
        //     'Reply-To: ' . $email_from . "\r\n" .
        //     'X-Mailer: PHP/' . phpversion();
        // mail($email_to, $subject, $body, $headers);
        $data = [
            'subject' => $subject,
            'body' => $body,
        ];

        try {
            Mail::to($email_to,)->send(new EmailMessages($data));
            \Log::info(['Email: '=>$data]);
        } catch (\Exception $e) {
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
        } elseif (!empty($timeout)) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Auth: ' . $xAuth,
            'token: ' . $token,
        ));

        $response = curl_exec($ch);
        
        // if ($response == false) {
        //     $err =  new \Exception(curl_error($ch), curl_errno($ch));
        //     // \Log::info(['url' => $url, 'payload' => $payload, 'res'=>$response]);
        //     // \Log::error($err);
        //     return (object)['message' => $err->getMessage(), 'code' => $err->getCode()];
        // }
      
        return $response;
    
    }

    public function chargeUserWallet(){

    }

    public function logTransaction($data){
        $data['app_version'] = Session::get('app_version');
        $data['domain_name'] = Session::get('domain_name');
        $data['ip_address'] = Session::get('ip_address');

        $transaction = Transactions::create($data);

        return $transaction;
    }

    public function getDomainDetails(){
        
    }
    public function advancedApiCall($url, $payload=[], $headers, $method = 'POST', $status_code=null)
    {
        \Log::info(['Begin connection...', $url, $payload, $headers]);
        
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
        \Log::info(['End connection', $url, $payload, $headers, $response]);
        
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

        // if (env('ENT') == 'local') {
        //     $path = "/Applications/MAMP/htdocs/kingsvtu/public";
        // } else {
        //     $path = '/home/kingntxc/portal.kingsvtu.ng';
        // }
        $path = public_path();
        // 
        if (!is_dir($path . '/' . $location)) {
            mkdir($path . '/' . $location, 0777, true);
        }

        return $location;
    }
}
