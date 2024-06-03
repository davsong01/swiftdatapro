<?php

namespace App\Http\Controllers\Providers;

use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmePlugController extends Controller
{
    public function getPostData($request, $api)
    {
        $slug = strtolower($request['resource']->slug);
        $resource = $request['resource'];

        if ($slug == "mtn-sns" || $slug == "mtn") {
            $network_id = 1;
            $url ='https://smeplug.ng/api/v1/vtu';
            $type = 2;
        }

        if ($slug == "glo-sns" || $slug == "glo") {
            $network_id = 4;
            $type = 2;
            $url = 'https://smeplug.ng/api/v1/vtu';
        }

        if ($slug == "airtel-sns" || $slug == "airtel") {
            $network_id = 2;
            $type = 2;
            $url = 'https://smeplug.ng/api/v1/vtu';
        }

        if ($slug == "9MOBILE-SNS" || $slug == "9MOBILE" || $slug == "ETISALAT-SNS" || $slug == "ETISALAT") {
            $network_id = 3;
            $url = 'https://smeplug.ng/api/v1/vtu';
            $type = 2;
        }


        $postdata = [
            'network_id' => $network_id,
            'type' => $type,
            'url' => $url
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
            $resX = $this->advancedApiCall($pstfields['url'], json_encode($pstfields), $headers);
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
                
                if (isset($status) && $status == "true") {
                    $true_response = strtoupper($result['message'] . '. Recharge Successful.');

                    $formatted = [
                        'status' => $status,
                        'response' => $true_response,
                        'extra' => null,
                        'error' => null,
                    ];
                } else {
                    $true_response = strtoupper('E-pin Purchase failed.');
                    $formatted = [
                        'status' => 'failed',
                        'response' => $true_response,
                        'extra' => null,
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
