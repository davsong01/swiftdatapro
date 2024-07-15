<?php

namespace App\Http\Controllers\Providers;

use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClubkonnectController extends Controller
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
        // if (!empty($plan_id)) {
        //     foreach ($response as $res) {
        //         if ($plan_id == $res['serviceId']) {
        //             $response = [
        //                 'name' => strtoupper($product),
        //                 'product' => strtoupper($product),
        //                 'discount' => $discount,
        //                 'commission' => $commission,
        //                 'price' => $res['price'],
        //             ];
        //         }
        //     }
        // }
        
        return $response;
    }

    public function prepareParams(Request $request){
        $clubkonnect = WebSetting::first();

        $request['clubkonnect_user_id'] = $clubkonnect->clubkonnect_user_id;
        $request['clubkonnect_api_key'] = $clubkonnect->clubkonnect_api_key;
        
        $url = $this->getPostData($request->all());
        
        $postdata = [
            'url' => $url,
            'request_id' => $request['request_id'],
            'amount' => $request['amount'] ?? $request['unit_price'],
            'phone' => $request['mobile_no'],
            'quantity' => $request['quantity'] ?? 1,
        ];
        
        $pstfields = [
            'url' =>  $postdata['url'],
            'payload' => $postdata,
        ];
       
        $params = [
            'url' => $postdata['url'],
            'postfields' => $pstfields,
            'payload' => $postdata,
            'provider' => 'CLUBKONNECT',
        ];
        
        return $params;
    }

    public function getPostData($request)
    {
        $request['network_module'] = strtolower($request['network_module']);
        $service_price = $request['amount'] ?? $request['price'];
        
        if($request['type'] == 'airtime'){
            if ($request['network_module'] == "mtn-vtu" || $request['network_module'] == "mtn") {
                $ntwk = '01';
            }
    
            if ($request['network_module']  == "glo-vtu" || $request['network_module']  == "glo") {
                $ntwk = '02';
            }
    
            if ($request['network_module']  == "airtel-vtu" || $request['network_module']  == "airtel") {
                $ntwk = '04';
            }
    
            if ($request['network_module']  == "9mobile-vtu" || $request['network_module']  == "9mobile" || $request['network_module']  == "etisalat-vtu" || $request['network_module'] == "etisalat") {
                $ntwk = 03;
            }

            $url = 'https://www.nellobytesystems.com/APIAirtimeV1.asp?UserID=' . $request['clubkonnect_user_id'] . '&APIKey=' . $request['clubkonnect_api_key'] . '&MobileNetwork=' . $ntwk . '&Amount=' . $service_price . '&MobileNumber=' . $request['mobile_no'] . '&RequestID=' . $request['request_id'] . '&CallBackURL=';
        }
        

        $postdata = $url;
       
        return $postdata;
    }


    public function query($postdata, $request, $type){
        $curl = curl_init();
        

        $resX = $this->advancedApiCall($postdata['url'], [], [], 'GET','status_code');
        
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
            $status = 'failed';
            
            try {
                // Process APi call
                $result = json_decode($res['response'], true);
                $code = $res['status_code'] ?? '';

                $status = $res['status'] ?? '';

                $chargeable_statuses = ['ORDER_RECEIVED', 'ORDER_PROCESSED', 'ORDER_COMPLETED', 'ORDER_ONHOLD'];
                $chargeable_response_codes = ['100', '300', '200', '201', '601'];

                if (in_array($status, $chargeable_statuses) && in_array($code, $chargeable_response_codes)) {
                    $status = 'success';
                } else {
                    $status = 'failed';
                }

                $response_description = '';
                $epin = isset($result['purchased_code']) ? $result['purchased_code'] : '';

                ////
                $formatted = [
                    'status' => $status,
                    'response' => '',
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
