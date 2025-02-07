<?php

namespace App\Services;

use App\Models\Api;
use App\Models\WebSetting;
use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;

class KycVerificationApiService
{
    public static function verify($type, $value)
    {
        if($type == 'nin'){
            self::verifyNin($value);
        }
        
        if($type == 'bvn'){
            self::verifyBvn($value);
        }
    }

    public static function verifyNin($nin)
    {
        $nin = '47283739147';
        $setting = WebSetting::value('nin_verification_provider_id');
        $provider = Api::where('id', $setting)->first();
        
        $api_key = $provider->api_key;
        $password = $provider->secret_key;

        $accessToken = "{$api_key}".":"."{$password}";
        $postdata = array(
            'value' => $nin,
        );
        
        $endpoint = $provider->live_base_url."nin/";
        
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
                "Authorization: " . $accessToken,
            ]
        );

        $response = curl_exec($ch);
        curl_close($ch);
        
        \Log::info(['nin verification response' => $response]);
        dd($response);
        return $response = !empty($response) ? json_decode($response) : null;        
    }

    public static function verifyBvn($bvn)
    {

        $setting = WebSetting::value('bvn_verification_provider_id');
        $provider = Api::where('id', $setting)->first();

        $api_key = $provider->api_key;
        $password = $provider->secret_key;

        $accessToken = "{$api_key}" . ":" . "{$password}";
        
        $endpoint = $provider->live_base_url . "v1s/";
        $postdata = array(
            'category' => "BVN",
            'planId' => "5",
            'value' => $bvn,
            'type' => "BVN",
        );
        
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

        \Log::info(['bvn verification response' => $response]);

        return $response = !empty($response) ? json_decode($response) : null;
    }

}
