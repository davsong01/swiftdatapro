<?php

namespace App\Http\Controllers\Providers;

use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DonlessonController extends Controller
{
    public function getVariations($type, $product, $level, $plan_id=null)
    {
        // Not done
        $response = [
            'name' => '',
            'price' => '',
            'code' => '',
            'val' => '',
        ];
        // dd($type, $product, $level);
        // if ($type == 'data' && $product == 'mtn-cg') {
        //     $response = \DB::table('mtncgdata_plan')->where('id', $level)->select('easy_access125', 'easy_access126', 'easy_access127', 'easy_access45', 'easy_access46', 'easy_access47', 'easy_access48', 'easy_access49', 'easy_access88', 'easy_access89', 'easy_access90', 'easy_access141', 'easy_access142', 'easy_access143', 'easy_access144', 'easy_access145', 'easy_access146', 'easy_access147', 'easy_access148', 'easy_access149', 'easy_access150', 'easy_access151')->first();
            
        //     $response = [
        //         [
        //             'name' => "MTN 50MB (CG) - 30days",
        //             'price' => $response->easy_access125,
        //             'code' => 125,
        //             'id' => 'easy_access125',
        //             'val' => 50,
        //         ],
        //         [
        //             'name' => "MTN 150MB (CG) - 30days",
        //             'price' => $response->easy_access126,
        //             'code' => 126,
        //             'id' => 'easy_access126',
        //             'val' => 150,
        //         ],
        //         [
        //             'name' => "MTN 250MB (CG) - 30days",
        //             'price' => $response->easy_access127,
        //             'code' => 127,
        //             'id' => 'easy_access127',
        //             'val' => 250,
        //         ],
        //         [
        //             'name' => "MTN 500MB (CG) - 30days",
        //             'val' => 500,
        //             'price' => $response->easy_access45,
        //             'code' => 45,
        //             'id' => 'easy_access45',
        //         ],
        //         [
        //             'name' => "MTN 1GB (CG) - 30days",
        //             'val' => 1000,
        //             'price' => $response->easy_access46,
        //             'code' => 46,
        //             'id' => 'easy_access46',
        //         ],
        //         [
        //             'name' => "MTN 2GB (CG) - 30days",
        //             'val' => 2000,
        //             'price' => $response->easy_access47,
        //             'code' => 47,
        //             'id' => 'easy_access47',
        //         ],
        //         [
        //             'name' => "MTN 3GB (CG) - 30days",
        //             'val' => 3000,
        //             'price' => $response->easy_access48,
        //             'code' => 48,
        //             'id' => 'easy_access48',
        //         ],
        //         [
        //             'name' => "MTN 5GB (CG) - 30days",
        //             'val' => 5000,
        //             'price' => $response->easy_access49,
        //             'code' => 49,
        //             'id' => 'easy_access49'
        //         ],
        //         [
        //             'name' => "MTN 10GB (CG) - 30days",
        //             'val' => 10000,
        //             'price' => $response->easy_access88,
        //             'code' => 88,
        //             'id' => 'easy_access88',
        //         ],
        //         [
        //             'name' => "MTN 12GB (CG) - 30days",
        //             'val' => 12000,
        //             'price' => $response->easy_access89,
        //             'code' => 89,
        //             'id' => 'easy_access89',
        //         ],
        //         [
        //             'name' => "MTN 15GB (CG) - 30days",
        //             'val' => 15000,
        //             'price' => $response->easy_access90,
        //             'code' => 90,
        //             'id' => 'easy_access90',
        //         ],
        //         [
        //             'name' => "MTN 20GB (CG) - 30days",
        //             'val' => 20000,
        //             'price' => $response->easy_access141,
        //             'code' => 141,
        //             'id' => 'easy_access141',
        //         ],
        //         [
        //             'name' => "MTN 25GB (CG) - 30days",
        //             'val' => 25000,
        //             'price' => $response->easy_access142,
        //             'code' => 142,
        //             'id' => 'easy_access142',
        //         ],
        //         [
        //             'name' => "MTN 30GB (CG) - 30days",
        //             'val' => 30000,
        //             'price' => $response->easy_access143,
        //             'code' => 143,
        //             'id' => 'easy_access143',
        //         ],
        //         [
        //             'name' => "MTN 40GB (CG) - 30days",
        //             'val' => 40000,
        //             'price' => $response->easy_access144,
        //             'code' => 144,
        //             'id' => 'easy_access144',
        //         ],
        //         [
        //             'name' => "MTN 50GB (CG) - 30days",
        //             'price' => $response->easy_access145,
        //             'val' => 50000,
        //             'code' => 145,
        //             'id' => 'easy_access145',
        //         ],
        //         [
        //             'name' => "MTN 60GB (CG) - 30days",
        //             'price' => $response->easy_access146,
        //             'val' => 60000,
        //             'code' => 146,
        //             'id' => 'easy_access146',
        //         ],
        //         [
        //             'name' => "MTN 70GB (CG) - 30days",
        //             'price' => $response->easy_access147,
        //             'val' => 70000,
        //             'code' => 147,
        //             'id' => 'easy_access147',
        //         ],
        //         [
        //             'name' => "MTN 75GB (CG) - 30days",
        //             'val' => 75000,
        //             'price' => $response->easy_access148,
        //             'code' => 148,
        //             'id' => 'easy_access148',
        //         ],
        //         [
        //             'name' => "MTN 80GB (CG) - 30days",
        //             'val' => 80000,
        //             'price' => $response->easy_access149,
        //             'code' => 149,
        //             'id' => 'easy_access149',
        //         ],
        //         [
        //             'name' => "MTN 90GB (CG) - 30days",
        //             'val' => 90000,
        //             'price' => $response->easy_access150,
        //             'code' => 150,
        //             'id' => 'easy_access150',
        //         ],
        //         [
        //             'name' => "MTN 100GB (CG) - 30days",
        //             'val' => 100000,
        //             'price' => $response->easy_access151,
        //             'code' => 151,
        //             'id' => 'easy_access151',
        //         ],

        //     ];
        // }
        // if (!empty($plan_id)) {
        //     foreach ($response as $res) {
        //         if ($plan_id == $res['code']) {
                   
        //             $response = [
        //                 'price' => $res['price'],
        //                 'service' => $product,
        //                 'databyte' => $res['val'],
        //                 'name' => $res['name'],
        //                 'code' => $res['code'],
        //             ];
        //         }
        //     }
        // }

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
        if ($request['network_module'] == 'MTN-CG' || $request['network_module'] == 'GIFTING') {
            $type = 'data';
            $product = 'mtn';
            $ntwk = 01;
            $url = "https://easyaccess.com.ng/api/data.php";
        } elseif ($request['network_module'] == 'AIRTEL-CG') {
            $type = 'data';
            $product = 'airtel-cg';
            $ntwk = 03;
            $url = "https://easyaccess.com.ng/api/data.php";

        } elseif ($request['network_module'] == 'AIRTEL') {
            $type = 'data';
            $product = 'airtel';
            $ntwk = 03;
            $url = "https://easyaccess.com.ng/api/data.php";
        } elseif ($request['network_module'] == 'GLO-CG') {
            $type = 'data';
            $product = 'glo-cg';
            $ntwk = 02;
            $url = "https://easyaccess.com.ng/api/data.php";
        } elseif ($request['network_module'] == 'GLO') {
            $type = 'data';
            $product = 'glo';
            $ntwk = 02;
            $url = "https://easyaccess.com.ng/api/data.php";
        } elseif ($request['network_module'] == 'MOBILE-SME' || $request['network_module'] == 'MOBILE SME') {
            $type = 'data';
            $product = 'apimobile-sme';
            $ntwk = 04;
            $url = "https://easyaccess.com.ng/api/data.php";
        } elseif ($request['network_module'] == 'MTN-DATACARD') {
            $type = 'datacard';
            $product = 'mtn-data-card';
            $ntwk = 01;
            $url = "https://easyaccess.com.ng/api/datacard.php";
        }else if ($request['network_module'] == 'MTN' || $request['network_module'] == 'MTN-SME') {
            $type = 'data';
            $product = 'mtn';
            $ntwk = 01;
            $url = "https://easyaccess.com.ng/api/data.php";
        }
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
