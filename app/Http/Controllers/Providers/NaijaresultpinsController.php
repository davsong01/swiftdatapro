<?php

namespace App\Http\Controllers\Providers;

use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NaijaresultpinsController extends Controller
{
    public function getVariations($type, $product, $level, $plan_id = null, $amount = null, $variation = null)
    {
        $response = [
            'name' => '',
            'price' => '',
            'code' => '',
            'val' => '',
        ];

        return $response;

    }

    public function prepareParams(Request $request)
    {
        $api = WebSetting::first();
        $environment = env('ENT');
        $service = strtolower($request->service_module);
        $quantity = $request->service_quantity;

        $price = \DB::table("epin_planlist")->select("waec","gce","neco","nabteb","waec_registration","nbais")->first();
        
        if ($service == "waec") {
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
            $card_type_id = 1;
            $service_price = $price->waec * $quantity;
            $service_comm = $api->waec_comm;
        }

        if ($service == "waec") {
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
            $card_type_id = 3;
            $service_price = $price->nabteb * $quantity;
            $service_comm = $api->nabteb_comm;
        }

        if ($service == "neco") {
            $card_type_id = 2;
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
            $service_price = $price->neco * $quantity;
            $service_comm = $api->neco_comm;
        }

        if ($service == "nbais") {
            $card_type_id = 5;
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
            $service_price = $price->nbais * $quantity;
            $service_comm = $api->nbais_comm;
        }

        if ($service == "waec-reg") {
            $card_type_id = 4;
            $service_url = "https://www.naijaresultpins.com/api/v1/exam-card";
            $service_price = $price->waec_registration * $quantity;
            $service_comm = $api->waec_comm;
        }


        $headers = [
            "Authorization: Bearer ". $api->naijaresultpins_api_key,
            'Content-Type: application/json'
        ];

        $postdata = [
            'serviceID' => $request['serviceId'],
            'billersCode' => $request['billersCode'],
            'card_type_id' =>  $card_type_id,
            'request_id' => $request['request_id'],
            'variation_code' => $request['variation_code'],
            'amount' => $service_price,
            'phone' => $request['mobile_no'],
        ];

        $pstfields = [
            'url' =>  $service_url,
            'payload' => $postdata,
        ];

        $params = [
            'url' => $service_url,
            'headers' => $headers,
            'postfields' => $pstfields,
            'payload' => Arr::except($postdata, 'url'),
            'provider' => 'NAIJARESULTPINS',
            'service_price' => $service_price,
        ];

        $request['discount'] = $service_comm ?? 0;
        $request['service_price'] = $service_price;

        return $params;
    }

    public function query($postdata, $request, $type)
    {
        $resX = $this->advancedApiCall($postdata['url'], json_encode($postdata['payload']), $postdata['headers']);
        $resX = '{"status": "success","message": "2 WAEC Scratch Card generated","reference": "qXFPHaTZM3rSkfY","amount": 6700,"quantity": "3","old_balance": 259800,"new_balance": 253100,"transaction_date": "2023-10-02 02:45:13","cards":[{"pin": "941264752596","serial_no": "WRN193474585"},{"pin": "941264877068","serial_no": "WRN193474586"}]}';

        try {
            $format = $this->processApiResponse($resX, $request, $type);
            $format['api_response'] = $resX;
        } catch (\Throwable $th) {

            $format = [
                'status' => 'attention-required',
                'response' => '',
                'error' => $th->getMessage() . '. File: ' . $th->getFile() . '. Line:' . $th->getLine(),
            ];
            $format['api_response'] = $resX;

            return $format;
        }

        return $format;
    }

    public function processApiResponse($res, $request, $type)
    {

        if (empty($res)) {
            $formatted = [
                'status' => 'failed',
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
                    $true_response = strtoupper($request->service_module . ' N' . number_format($request->service_price) . ' E-pin Purchase failed.');

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
