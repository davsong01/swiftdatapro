<?php

namespace App\Http\Controllers\Providers;

use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NaijaResultPinsController extends Controller
{
    public function getPostData($request, $api)
    {
        $slug = strtolower($request['resource']->slug);
        $resource = $request['resource'];

        if ($slug == "waec-result" || $slug == "waec-verification-pin") {
            $service_url = $api->live_base_url. "exam-card/buy";
            $card_type_id = 1;
        }

        if ($slug == "neco" || $slug == "neco-result") {
            $card_type_id = 2;
            $service_url = $api->live_base_url. "exam-card/buy";
        }

        if ($slug == "nabteb" || $slug == "nabteb-result") {
            $service_url = $api->live_base_url. "exam-card/buy";
            $card_type_id = 3;
        }
        
        if ($slug == "nbais") {
            $card_type_id = 5;
            $service_url = $api->live_base_url. "exam-card/buy";
        }

        if ($slug == "waec-registration") {
            $card_type_id = 6;
            $service_url = $api->live_base_url. "exam-card/buy";
        }
        $postdata = [
            'card_type_id' => $card_type_id,
            'service_url' => $service_url,
            "quantity" => $request['buyer_quantity']
        ];

        return $postdata;
    }

    public function query($postdata, $api)
    {
        $headers = [
            "Authorization: Bearer " . $api->api_key,
            'Content-Type: application/json'
        ];

        $pstfields = $this->getPostData($postdata, $api);
        
        if(env('ENT') == 'local'){
            $resX = '{"status":true,"code":"000","message":"1 WAEC Scratch Card generated","reference":"wY6duU4zsAYyJwn","quantity":"1","amount":"3340.00","old_balance":"28035.00","new_balance":"24695.00","transaction_date":"2024-08-12 21:45:10","cards":[{"pin":"231390375872","serial_no":"WRN202652128"}]}';
        }else{
            $resX = $this->advancedApiCall($pstfields['service_url'], json_encode($pstfields), $headers);
        }

        \Log::info(['Naija response: '=> $resX]);

        try {
            $format = $this->processApiResponse($resX);
            $format['api_response'] = $resX;
            $format['postfields'] = $pstfields;      
        } catch (\Throwable $th) {

            $format = [
                'api_response' => $resX,
                'postfields' => $pstfields,
                'status' => 'attention-required',
                'response' => '',
                'error' => $th->getMessage() . '. File: ' . $th->getFile() . '. Line:' . $th->getLine(),
            ];
            $format['api_response'] = $resX;

            return $format;
        }

        return $format;
    }

    public function processApiResponse($res)
    {

        if (empty($res)) {
            $formatted = [
                'status' => 'attention-required',
                'extra' => '',
                'response' => 'Something went wrong, please try again later',
                'error' => 'Something went wrong, please try again later',
            ];
        } else {
            try {
                // Process APi call
                $result = json_decode($res, true);

                $status = strtolower($result['status']);
                $epins = '';
                if(!empty($result['cards'])){
                    foreach ($result['cards'] as $key => $card) {
                        $suffix = ($key < count($result['cards']) - 1) ? ' || ' : '';
                        if(!empty($card)){
                            $epins .= 'PIN: '. $card['pin'].', SERIAL NUMBER: '.$card['serial_no'] . $suffix;
                        }
                    }
                }
                
                if (isset($status) && $status == "success" || $status == true) {
                    $true_response = strtoupper($result['message'] . '. E-Pin Purchase Successful. ' . $epins);

                    $formatted = [
                        'status' => "success",
                        'response' => $true_response,
                        'extra' => $epins ?? null,
                        'error' => null,
                    ];
                } else {
                    $true_response = strtoupper('E-pin Purchase failed.');

                    $formatted = [
                        'status' => 'failed',
                        'response' => $true_response,
                        'extra' => $epins ?? null,
                        'error' => null,
                    ];
                }
                // after            
            } catch (\Throwable $th) {
                $formatted = [
                    'status' => 'attention-required',
                    'extra' => '',
                    'response' => $th->getMessage() . ' ' . $th->getFile() . ' ' . $th->getLine(),
                    'error' => $th->getMessage(),
                ];
            }
        }
        
        return $formatted;
    }

    public function balance($api, $no_format = null)
    {
        try {
            $url = $api->live_base_url."account";

            $headers = [
                "Authorization: Bearer " . $api->api_key,
                "Content-Type: application/json"

            ];

            $response = $this->advancedApiCall($url, [], $headers, 'GET');
            $response = !empty($response) ? json_decode($response, true) : [];
            
            if (empty($response)) {
                $status = 'failed';
                $status_code = 0;
                $balance = null;
            } else {
                $result = $response[0];
                $balance = '#' . number_format($result['wallet_balance'], 2);
                $status = 'success';
                $status_code = 1;
            }

            $format = [
                'status' => $status,
                'balance' => $balance,
                'status_code' => $status_code,
            ];
        } catch (\Throwable $th) {
            $format = [
                'status' => 'failed',
                'status_code' => 0,
                'balance' => $th->getMessage() . '. File: ' . $th->getFile() . '. Line:' . $th->getLine(),
            ];
        }

        if (isset($no_format)) {
            $format = [
                'status' => $status,
                'balance' => $balance,
                'status_code' => $status_code,
            ];
        }

        return $format;
    }
}
