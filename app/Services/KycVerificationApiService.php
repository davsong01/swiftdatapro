<?php

namespace App\Services;

use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Collection;

class KycVerificationApiService
{
    public static function verify($type, $value)
    {
        if($type == 'nin'){
            self::verify('nin', $value);
        }
        
        if($type == 'bvn'){
            self::verify('bvn', $value);
        }
    }

    public static function verifyNin($nin)
    {

        $api_key = env('BD_API_KEY');
        $password = env('BD_API_PASSWORD');

        $accessToken = "{$api_key}{$password}";
        $postdata = array(
            'value' => $nin,
        );
        $endpoint = "https://bidayyibanurun.com.ng/api/nin/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                "Content-Type: application/json",
                "Authorization: " . $accessToken . "",
            ]
        );
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function verifyBvn($bvn)
    {

        $api_key = env('BD_API_KEY');
        $password = env('BD_API_PASSWORD');

        $accessToken = "{$api_key}{$password}";
        $postdata = array(
            'category' => "BVN",
            'planId' => "5",
            'value' => $bvn,
            'type' => "BVN",
        );
        $endpoint = "https://bidayyibanurun.com.ng/api/v1s";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                "Content-Type: application/json",
                "Authorization: Token " . $accessToken . "",
            ]
        );
        $response = curl_exec($ch);
        curl_close($ch);
        //echo $response;
    }
}
