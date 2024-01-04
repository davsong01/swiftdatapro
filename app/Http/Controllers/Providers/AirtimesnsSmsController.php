<?php

namespace App\Http\Controllers\Providers;

use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AirtimesnsSmsController extends Controller
{
    public function getVariations($type, $product, $level, $plan_id=null,$amount=null)
    {
        $response = [
            'name' => '',
            'price' => '',
            'code' => '',
            'val' => '',
        ];
        // dd($type, $product, $level);
        if ($type == 'airtime_sns') {
            if($product == 'mtn-sns'){
                $commission = app('App\Http\Controllers\TransactionsController')->commissions($type, $product);
                $req = new \Illuminate\Http\Request(['services' => $product, 'level' => $level]);

                $discount = app('App\Http\Controllers\TransactionsController')->discounts($req);

                $response = [
                    'ntwk' => '01',
                    'name' => strtoupper($product),
                    'product' => strtoupper($product),
                    'service' => strtoupper($product),
                    'discount' => $discount ,
                    'commission' => $commission,
                    'price' => $amount,
                ];
            }

            if ($product == 'airtel-sns') {
                $commission = app('App\Http\Controllers\TransactionsController')->commissions($type, $product);
                $req = new \Illuminate\Http\Request(['services' => $product, 'level' => $level]);

                $discount = app('App\Http\Controllers\TransactionsController')->discounts($req);

                $response = [
                    'ntwk' => '03',
                    'name' => strtoupper($product),
                    'product' => strtoupper($product),
                    'service' => strtoupper($product),
                    'discount' => $discount,
                    'commission' => $commission,
                    'price' => $amount,

                ];
            }

            if ($product == '9mobile-sns' || $product == '9mobile' || $product == 'etisalat-sns' || $product == 'etisalat') {
                $commission = app('App\Http\Controllers\TransactionsController')->commissions($type, $product);
                $req = new \Illuminate\Http\Request(['services' => $product, 'level' => $level]);

                $discount = app('App\Http\Controllers\TransactionsController')->discounts($req);

                $response = [
                    'ntwk' => '04',
                    'name' => strtoupper($product),
                    'product' => strtoupper($product),
                    'service' => strtoupper($product),
                    'discount' => $discount,
                    'commission' => $commission,
                    'price' => $amount,

                ];
            }

        }

        if (!empty($plan_id)) {
            foreach ($response as $res) {
                if ($plan_id == $res['code']) {
                   
                    $response = [
                        'ntwk' => $res['ntwk'],
                        'name' => $res['name'],
                        'product' => $res['product'],
                        'service' => $res['service'],
                        'price' => $res['price'],
                        'commission' => $res['commission'],
                        'discount' => $res['discount'],
                    ];
                }
            }
        }

        return $response;
    }

    public function prepareParams(Request $request){
        $postdata = $this->getPostData($request->all());
        $easyaccess_token = WebSetting::first()->value('easyaccess_token');
        $headers = [
            "AuthorizationToken: ".$easyaccess_token."",
            'cache-control: no-cache'
        ];
        
        // Save post fields
        $pstfields = [
            'url' =>  $postdata['url'],
            'payload' => $postdata,
        ];
       
        $params = [
            'url' => $postdata['url'],
            'headers' => $headers,
            'postfields' => $pstfields,
            'payload' => Arr::except($postdata, 'url'),
            'provider' => 'EASYACCESS',
        ];
     
        return $params;
    }

    public function getPostData($request){
        $webSetting = WebSetting::first();
        $mtn_n = $webSetting->mtn_n;
        $glo_n = $webSetting->glo_n;
        $airtel_n = $webSetting->airtel_n;
        $mobile_n = $webSetting->mobile_n;


        if ($request['network_module'] == "MTN-SNS" || $request['network_module'] == "MTN") {
            $service_code = "net01";
            $service_no = $mtn_n;
        }

        if ($request['network_module'] == "GLO-SNS" || $request['network_module'] == "GLO") {
            $service_code = "net02";
            $service_no = $glo_n;
        }

        if ($request['network_module'] == "AIRTEL-SNS" || $request['network_module'] == "AIRTEL") {
            $service_code = "net03";
            $service_no = $airtel_n;
        }

        if ($request['network_module'] == "9MOBILE-SNS" || $request['network_module'] == "9MOBILE" || $request['network_module'] == "ETISALAT-SNS" || $network == "ETISALAT") {
            $service_code = "net04";
            $service_no = $mobile_n;
        }

        // Stop here for today
        $postdata = [
            'url' => $url,
            'network' => $ntwk,
            'mobileno' =>  $request['mobile_no'] ?? '',
            'dataplan' =>  $request['plan_id'],
            'client_reference' => '',
            'no_of_pins' => $request['no_of_pins'] ?? '',
        ];
       
        return $postdata;

    }

    public function query($postdata, $request, $type){
        $resX = $this->advancedApiCall($postdata['url'], $postdata['payload'], $postdata['headers']);

        // $resX = '{"success": "true","message": "Data Card Purchase was Successful","network": "MTN","pin": "408335193S","pin2": "184305851S","dataplan": "1.5GB","amount": 574,"balance_before": "27833","balance_after": 27259,"transaction_date": "07-04-2023 07:57:47 pm","reference_no": "ID5345892220","client_reference": "client_ref84218868382855","status": "Successful","auto_refund_status": "success"}';
        // dd($resX, $postdata['payload'], $postdata['headers']);
    
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
