<?php

namespace App\Http\Controllers\Providers;

use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VtpassController extends Controller
{
    public function getVariations($type, $product, $level, $plan_id=null,$amount=null)
    {
        $environment = env('ENT');
        $product = strtolower($product);

        $response = [
            'name' => '',
            'price' => '',
            'code' => '',
            'val' => '',
        ];
        // dd($type, $product, $level);
        if ($type == 'data' && $product == 'spectranet-data') {
            $response = \DB::table('spectranetdata_plan')->where('id', $level)->select('spectranet1', 'spectranet2', 'spectranet3')->first();

            $response = [
                [
                    'name' => $environment == 'local' ? "Spectranet N5000" : "Spectranet N5000",
                    'variation_code' => $environment == 'local' ? "vt-5000" : "vt-5000",
                    'price' => $response->spectranet1,
                    'val' => 5000,
                    'id' => 'spectranet1',
                ],
                [
                    'name' => $environment == 'local' ? "Spectranet N7000" : "Spectranet N7000",
                    'variation_code' => $environment == 'local' ? "vt-7000" : "vt-7000",
                    'price' => $response->spectranet2,
                    'val' => 7000,
                    'id' => 'spectranet2',
                ],
                [
                    'name' => $environment == 'local' ? "Spectranet N10000" : "Spectranet N10000",
                    'variation_code' => $environment == 'local' ? "vt-10000" : "vt-10000",
                    'price' => $response->spectranet3,
                    'val' => 10000,
                    'id' => 'spectranet3',

                ],
            ];

        }
    
        if($type == 'airtime'){
            
            // $response = [
            //     'name' => strtoupper($product),
            //     'variation_code' => '',
            //     'product' => strtoupper($product),
            //     'price' => $amount,
            // ];
        
            if ($product == "mtn-vtu" || $product == "mtn") {
                $commission = app('App\Http\Controllers\TransactionsController')->commissions($type, $product);
                $req = new \Illuminate\Http\Request(['services' => $product, 'level' => $level]);
                
                $discount = app('App\Http\Controllers\TransactionsController')->discounts($req);

                $response = [
                    'serviceId' => "mtn",
                    'name' => strtoupper($product),
                    'variation_code' => 'mtn',
                    'product' => strtoupper($product),
                    'discount' => $discount,
                    'commission' => $commission,
                    'price' => $amount,
                ];

            }

            if ($product == "glo-vtu" || $product == "glo") {
                $commission = app('App\Http\Controllers\TransactionsController')->commissions($type, $product);
                $req = new \Illuminate\Http\Request(['services' => $product, 'level' => $level]);

                $discount = app('App\Http\Controllers\TransactionsController')->discounts($req);

                $discount_now = $amount * $discount / 100;
                $service_price = $amount - $discount_now;

                $response = [
                    'serviceId' => "glo",
                    'name' => strtoupper($product),
                    'variation_code' => 'mtn',
                    'product' => strtoupper($product),
                    'discount' => $discount,
                    'commission' => $commission,
                    'price' => $service_price,
                ];
            }

            if ($product == "airtel-vtu" || $product == "airtel") {
                $commission = app('App\Http\Controllers\TransactionsController')->commissions($type, $product);
                $req = new \Illuminate\Http\Request(['services' => $product, 'level' => $level]);

                $discount = app('App\Http\Controllers\TransactionsController')->discounts($req);

                $discount_now = $amount * $discount / 100;
                $service_price = $amount - $discount_now;

                $response = [
                    'serviceId' => "airtel",
                    'name' => strtoupper($product),
                    'variation_code' => 'mtn',
                    'product' => strtoupper($product),
                    'discount' => $discount,
                    'commission' => $commission,
                    'price' => $service_price,
                ];
            }

            if ($product == "9mobile-vtu" || $product == "9mobile" || $product == "etisalat-vtu" || $product == "etisalat") {
                $commission = app('App\Http\Controllers\TransactionsController')->commissions($type, $product);
                $req = new \Illuminate\Http\Request(['services' => $product, 'level' => $level]);

                $discount = app('App\Http\Controllers\TransactionsController')->discounts($req);

                $discount_now = $amount * $discount / 100;
                $service_price = $amount - $discount_now;

                $response = [
                    'serviceId' => "etisalat",
                    'name' => strtoupper($product),
                    'variation_code' => 'mtn',
                    'product' => strtoupper($product),
                    'discount' => $discount,
                    'commission' => $commission,
                    'price' => $service_price,
                ];
            }
        }

        if (!empty($plan_id)) {
            foreach ($response as $res) {
                if ($plan_id == $res['serviceId']) {
                    $response = [
                        'serviceId' => $res['serviceId'],
                        'name' => strtoupper($product),
                        'product' => strtoupper($product),
                        'discount' => $discount,
                        'commission' => $commission,
                        'variation_code' => $res['variation_code'] ?? '',
                        'price' => $res['price'],
                    ];
                }
            }
        }
       
        return $response;
    }

    public function prepareParams(Request $request){
        $vtpass = WebSetting::first();
        $environment = env('ENT');
        $api_key = $vtpass->vtpass_api_key ?? 'f742eb6055ff05d109de74caaf563b4f';
        $public_key = $vtpass->vtpass_public ?? 'PK_84802bc898ef45c19317552eea90c660efaa2f67122';
        $secret_key = $vtpass->vtpass_secret_key ?? 'SK_14525bc8c1f7e82026cadc079fcdb892d3c83d58025';
        
        $headers = [
            'api-key' => $api_key,
            'secret-key' => $secret_key,
            'public-key' => $public_key,
        ];

        // Save post fields
        if ($environment == 'local') {
            $url = "https://sandbox.vtpass.com/api/pay";
        } else {
            $url = "https://vtpass.com/api/pay";
        }
        if ($request['type'] == 'airtime') {
            $postdata = [
                'serviceID' => $request['serviceId'],
                'request_id' => $request['request_id'],
                'amount' => $request['amount'] ?? $request['price'],
                'phone' => $request['mobile_no'],
                'quantity' => $request['no_of_pins'] ?? 1,
            ];
        } 

        $pstfields = [
            'url' =>  $url,
            'payload' => $postdata,
        ];
       
        $params = [
            'url' => $url,
            'headers' => $headers,
            'postfields' => $pstfields,
            'payload' => Arr::except($postdata, 'url'),
            'provider' => 'VTPASS',
        ];
        
        return $params;
    }

    public function query($postdata, $request, $type){
        $curl = curl_init();
        $headers = [
            'api-key: '. $postdata['headers']['api-key'],
            'public-key: '. $postdata['headers']['public-key'],
            'secret-key: '.$postdata['headers']['secret-key'],    
        ];

        $resX = $this->advancedApiCall($postdata['url'], $postdata['payload'], $headers);

        try {
            $format = $this->processApiResponse($resX,$request, $type);
            $format['api_response'] = $resX;      
        } catch (\Throwable $th) {

            $format = [
                'status' => 'attention-required',
                'response' => '',
                'error' => $th->getMessage(). '. File: '.$th->getFile().'. Line:'.$th->getLine(),
            ];
            $format['api_response'] = $resX;  
            
            return $format;

        }
       
        return $format;
    }

    public function processApiResponse($res, $request, $type){
       
        if(empty($res)){
            $formatted = [
                'status' => 'failed',
                'extra' => '',
                'response' => 'Something went wrong, please try again later',
                'error' => 'Something went wrong, please try again later',
            ];
            
        }else{
            $statusn= 'failed';
            try {
                // Process APi call
                $result = json_decode($res, true);
                $status = $result['content']['transactions']['status'];
                $response_description = $result['response_description'];
                $epin = isset($result['purchased_code']) ? $result['purchased_code'] : '';
                $code = $result['code'] ?? '083';

                $chargeableStatuses = ['pending', 'delivered'];
                if (in_array($status, $chargeableStatuses)) {
                    $status = "successful";
                }

                $pinsx = [];

                if ($status == "successful" && $code == '000') {
                    $status = 'success';
                    $true_response = $response_description;
                    $extra = $epin;
                } else {                    
                    $status = 'failed';
                    $true_response = $response_description;
                }
                
                ////
                $formatted = [
                    'status' => $status,
                    'response' => $true_response,
                    'extra' => $extra ?? null,
                    'error' => null,
                ];
            } catch (\Throwable $th) {
                $formatted = [
                    'status' => 'attention-required',
                    'extra' => '',
                    'response' => $th->getMessage().' '.$th->getFile().' '.$th->getLine() ,
                    'error' => $th->getMessage(),
                ];
    
            }
        }
        
        return $formatted;
    }
}
