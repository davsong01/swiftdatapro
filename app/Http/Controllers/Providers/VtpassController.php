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

            if ($product == "9MOBILE-VTU" || $product == "9MOBILE" || $product == "ETISALAT-VTU" || $product == "ETISALAT") {
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

        // dd($postdata['headers']);
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $postdata['url'],
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => $postdata['payload'],
        //     CURLOPT_HTTPHEADER => $headers,
        // ));

        // $response = curl_exec($curl);
        // dd($response);
        // curl_close($curl);
        // echo $response;

        $resX = $this->advancedApiCall($postdata['url'], $postdata['payload'], $headers);

        // $resX = '{"success": "true","message": "Data Card Purchase was Successful","network": "MTN","pin": "408335193S","pin2": "184305851S","dataplan": "1.5GB","amount": 574,"balance_before": "27833","balance_after": 27259,"transaction_date": "07-04-2023 07:57:47 pm","reference_no": "ID5345892220","client_reference": "client_ref84218868382855","status": "Successful","auto_refund_status": "success"}';
        'STOP HERE FOR TODAY';
    
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
            try {
                // Process APi call
                $result = json_decode($res);
                
                $pinsx = [];

                if (isset($result) && !empty($result)) {
                    foreach ($result as $key => $value) {
                        if (strpos($key, 'pin') !== false) {
                            $pinsx[] = $value;
                        }
                    }
                }

                $pins = (isset($pinsx) && !empty($pinsx)) ? 'PINS: ' . implode(', ', $pinsx) : '';
                $true_response = $result->true_response ?? ($result->message ?? '');
                
                $status = isset($result->status) ? strtolower($result->status) : 'failed';
                $auto_refund_status = isset($result->auto_refund_status) ? strtolower($result->auto_refund_status) : 'nil';
             
                if (isset($status) && $status !== "failed" && $auto_refund_status !== 'failed' && $auto_refund_status != 'nil') {
                    $status = 'success';
                    $true_response = $true_response;
                    $extra = $pins;
                } else {
                    $true_response = isset($result->true_response) ? str_replace("'", "", $result->true_response) : ($result->true_response ?? '');
                    $status = 'failed';
                    $true_response = $result->message ?? $true_response ?? '';
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
