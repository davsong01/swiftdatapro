<?php

namespace App\Http\Controllers\Providers;

use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NaijaresultpinsController extends Controller
{
    public function getPostData($request, $api)
    {
        $slug = strtolower($request['resource']->slug);
        $resource = $request['resource'];

        if ($slug == "waec-result" || $slug == "waec-verification-pin") {
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
            $card_type_id = 1;
        }

        if ($slug == "neco" || $slug == "neco-result") {
            $card_type_id = 2;
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
        }

        if ($slug == "nabteb" || $slug == "nabteb-result") {
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
            $card_type_id = 3;
        }
        
        if ($slug == "nbais") {
            $card_type_id = 5;
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
        }

        if ($slug == "waec-registration") {
            $card_type_id = 6;
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
        }
        $postdata = [
            'card_type_id' => $card_type_id,
            'service_url' => $service_url
        ];

        return $postdata;
    }

    public function query($postdata, $api)
    {
        $headers = [
            "Authorization: Bearer " . $api->api_key . "r",
            'Content-Type: application/json'
        ];

        $pstfields = $this->getPostData($postdata, $api);
        
        if(env('ENT') == 'local'){
            $resX = '{"status": "success","message": "2 WAEC Scratch Card generated","reference": "qXFPHaTZM3rSkfY","amount": 6700,"quantity": "3","old_balance": 259800,"new_balance": 253100,"transaction_date": "2023-10-02 02:45:13","cards":[{"pin": "941264752596","serial_no": "WRN193474585"},{"pin": "941264877068","serial_no": "WRN193474586"}]}';
        }else{
            $resX = $this->advancedApiCall($pstfields['service_url'], $pstfields, $headers);
        }
        
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
            
                if (isset($status) && $status == "success") {
                    $true_response = strtoupper($result['message'] . '. E-Pin Purchase Successful. ' . $epins);

                    $formatted = [
                        'status' => $status,
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
}
