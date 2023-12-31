<?php

namespace App\Http\Controllers\Providers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GongzoController extends Controller
{
    public function getVariations($type, $product, $level, $plan_id=null)
    {
        $response = [
            'name' => '',
            'price' => '',
            'code' => '',
            'val' => '',
        ];
        // dd($type, $product, $level);
        if ($type == 'data' && $product == 'mtn-cg') {
            $response = \DB::table('mtncgdata_plan')->where('id', $level)->select('gongzo_data280', 'gongzo_data281', 'gongzo_data282', 'gongzo_data283','gongzo_data284','gongzo_data285','gongzo_data286','gongzo_data287','gongzo_data288')->first();
            
            $response = [
                [
                    'name' => "MTN 500MB (CG) - 30days",
                    'price' => $response->gongzo_data280,
                    'code' => 280,
                    'id' => 'gongzo_data280',
                    'val' => '500',
                ],
                [
                    'name' => "MTN 1GB (CG) - 30days",
                    'price' => $response->gongzo_data281,
                    'code' => 281,
                    'id' => 'gongzo_data281',
                    'val' => '1000',

                ],
                [
                    'name' => "MTN 2GB (CG) - 30days",
                    'price' => $response->gongzo_data282,
                    'code' => 282,
                    'id' => 'gongzo_data282',
                    'val' => '2000',
                ],
                [
                    'name' => "MTN 3GB (CG) - 30days",
                    'price' => $response->gongzo_data283,
                    'code' => 283,
                    'id' => 'gongzo_data283',
                    'val' => '3000',

                ],
                [
                    'name' => "MTN 5GB (CG) - 30days",
                    'price' => $response->gongzo_data284,
                    'code' => 284,
                    'id' => 'gongzo_data284',
                    'val' => '5000',

                ],
                [
                    'name' => "MTN 10GB (CG) - 30days",
                    'price' => $response->gongzo_data285,
                    'code' => 285,
                    'id' => 'gongzo_data285',
                    'val' => '10000',

                ],
                [
                    'name' => "MTN 15GB (CG) - 30days",
                    'price' => $response->gongzo_data286,
                    'code' => 286,
                    'id' => 'gongzo_data286',
                    'val' => '15000',
                ],
                [
                    'name' => "MTN 20GB (CG) - 30days",
                    'price' => $response->gongzo_data287,
                    'code' => 287,
                    'id' => 'gongzo_data287',
                    'val' => '20000',
                ],
                [
                    'name' => "MTN 40GB (CG) - 30days",
                    'price' => $response->gongzo_data288,
                    'code' => 288,
                    'id' => 'gongzo_data288',
                    'val' => '40000',
                ],
            ];
        }

        // MTN data
        if ($type == "data" && $product == "mtn") {
            $response = \DB::table('mtnsmedata_plan')->where('id', $level)->select('mtnsme_500','mtnsme_1', 'mtnsme_2', 'mtnsme_3', 'mtnsme_5', 'mtnsme_10')->first();

            $response = [
                [
                    'name' => "MTN SME 500M",
                    'price' => $response->mtnsme_500,
                    'code' => 289,
                    'id' => '',
                    'val' => 500,
                ],
                [
                    'name' => "MTN-SME 1GB",
                    'price' => $response->mtnsme_1,
                    'code' => 290,
                    'id' => '',
                    'val' => 1000,
                ],
                [
                    'name' => "MTN-SME 2GB",
                    'price' => $response->mtnsme_2,
                    'code' => 291,
                    'id' => '',
                    'val' => 2000,
                ],
                [
                    'name' => "MTN-SME 3GB",
                    'price' => $response->mtnsme_3,
                    'code' => 292,
                    'id' => '',
                    'val' => 3000,
                ],
                [
                    'name' => "MTN-SME 5GB",
                    'price' => $response->mtnsme_5,
                    'code' => 293,
                    'id' => '',
                    'val' => 5000,
                ],
                [
                    'name' => "MTN-SME 10GB",
                    'price' => $response->mtnsme_10,
                    'code' => 294,
                    'id' => '',
                    'val' => 10000,
                ],

            ];
        }

        if ($type == 'data' && $product == 'airtel-cg') {
            $response = \DB::table('airtelcgdata_plan')->where('id', $level)->select('gongzo_data312', 'gongzo_data313', 'gongzo_data314', 'gongzo_data315', 'gongzo_data317', 'gongzo_data318', 'gongzo_data319')->first();

            $response = [
                [
                    'name' => "AIRTEL CG 500MB - 7days",
                    'price' => $response->gongzo_data312,
                    'code' => 312,
                    'id' => 'gongzo_data312',
                    'val' => '500',
                ],
                [
                    'name' => "AIRTEL CG 1GB - 30days",
                    'price' => $response->gongzo_data313,
                    'code' => 313,
                    'id' => 'gongzo_data313',
                    'val' => '1000',

                ],
                [
                    'name' => "AIRTEL CG 2GB - 30days",
                    'price' => $response->gongzo_data314,
                    'code' => 314,
                    'id' => 'gongzo_data314',
                    'val' => '2000',
                ],
                [
                    'name' => "AIRTEL CG 5GB - 30days",
                    'price' => $response->gongzo_data315,
                    'code' => 315,
                    'id' => 'gongzo_data315',
                    'val' => '5000',

                ],
                [
                    'name' => "AIRTEL CG 10GB - 30days",
                    'price' => $response->gongzo_data317,
                    'code' => 317,
                    'id' => 'gongzo_data317',
                    'val' => '10000',

                ],
                [
                    'name' => "AIRTEL CG 300MB - 1 DAY",
                    'price' => $response->gongzo_data318,
                    'code' => 318,
                    'id' => 'gongzo_data318',
                    'val' => '300',

                ],
                [
                    'name' => "AIRTEL CG 200MB - 1 DAY",
                    'price' => $response->gongzo_data319,
                    'code' => 319,
                    'id' => 'gongzo_data319',
                    'val' => '200',

                ],
            ];
        }

        if ($type == 'data' && $product == 'airtel') {
            $response = \DB::table('airteldata_plan')->where('id', $level)->select('gongzo_data40', 'gongzo_data45', 'gongzo_data46', 'gongzo_data47', 'gongzo_data48', 'gongzo_data144', 'gongzo_data145', 'gongzo_data146', 'gongzo_data296', 'gongzo_data297', 'gongzo_data298')->first();

            $response = [
                [
                    'name' => "	AIRTEL GIFTING 750.0 MB - 14DAYS",
                    'price' => $response->gongzo_data40,
                    'code' => 40,
                    'id' => 'gongzo_data40',
                    'val' => '750',
                ],
                [
                    'name' => "AIRTEL GIFTING 3.0 GB - MONTHLY",
                    'price' => $response->gongzo_data45,
                    'code' => 45,
                    'id' => 'gongzo_data45',
                    'val' => '3000',
                ],
                [
                    'name' => "AIRTEL GIFTING 2.0 GB - MONTHLY",
                    'price' => $response->gongzo_data46,
                    'code' => 46,
                    'id' => 'gongzo_data46',
                    'val' => '2000',
                ],
                [
                    'name' => "AIRTEL GIFTING 20.0 GB - MONTHLY",
                    'price' => $response->gongzo_data47,
                    'code' => 47,
                    'id' => 'gongzo_data47',
                    'val' => '20000',
                ],
                [
                    'name' => "AIRTEL GIFTING 40.0 GB - MONTHLY",
                    'price' => $response->gongzo_data48,
                    'code' => 48,
                    'id' => 'gongzo_data48',
                    'val' => '40000',
                ],
                [
                    'name' => "AIRTEL GIFTING 1.5 GB - MONTHLY",
                    'price' => $response->gongzo_data144,
                    'code' => 144,
                    'id' => 'gongzo_data144',
                    'val' => '1500',
                ],
                [
                    'name' => "AIRTEL GIFTING 4.5 GB - MONTHLY",
                    'price' => $response->gongzo_data145,
                    'code' => 145,
                    'id' => 'gongzo_data145',
                    'val' => '4500',
                ],
                [
                    'name' => " AIRTEL GIFTING 10.0 GB - MONTHLY",
                    'price' => $response->gongzo_data146,
                    'code' => 146,
                    'id' => 'gongzo_data146',
                    'val' => '4500',
                ],
                [
                    'name' => "AIRTEL GIFTING 6.0 GB - MONTHLY",
                    'price' => $response->gongzo_data296,
                    'code' => 296,
                    'id' => 'gongzo_data296',
                    'val' => '6000',
                ],
                [
                    'name' => "AIRTEL GIFTING 75.0 GB - MONTHLY",
                    'price' => $response->gongzo_data297,
                    'code' => 297,
                    'id' => 'gongzo_data297',
                    'val' => '6000',
                ],
                [
                    'name' => "AIRTEL GIFTING 120.0 GB - MONTHLY",
                    'price' => $response->gongzo_data298,
                    'code' => 298,
                    'id' => 'gongzo_data298',
                    'val' => '120000',
                ],
            ];
        }

        if ($type == 'data' && $product == 'glo-cg') {
            $response = \DB::table('glocgdata_plan')->where('id', $level)->select('gongzo_data321', 'gongzo_data322', 'gongzo_data323', 'gongzo_data324', 'gongzo_data325', 'gongzo_data326', 'gongzo_data327', 'gongzo_data337')->first();

            $response = [
                [
                    'name' => "GLO (CG) 1.0 GB - 30days",
                    'price' => $response->gongzo_data321,
                    'code' => 321,
                    'id' => 'gongzo_data321',
                ],
                [
                    'name' => "GLO (CG) 2.0 GB - 30days",
                    'price' => $response->gongzo_data322,
                    'code' => 322,
                    'id' => 'gongzo_data322',
                ],
                [
                    'name' => "GLO (CG) 3.0 GB - 30days",
                    'price' => $response->gongzo_data323,
                    'code' => 323,
                    'id' => 'gongzo_data323',
                ],
                [
                    'name' => "GLO (CG) 5.0 GB - 30days",
                    'price' => $response->gongzo_data324,
                    'code' => 324,
                    'id' => 'gongzo_data324',
                ],
                [
                    'name' => "GLO (CG) 10.0 GB - 30days",
                    'price' => $response->gongzo_data325,
                    'code' => 325,
                    'id' => 'gongzo_data325',
                ],
                [
                    'name' => "GLO (CG) 500MB (CG) - 14days",
                    'price' => $response->gongzo_data326,
                    'code' => 326,
                    'id' => 'gongzo_data326',
                ],
                [
                    'name' => "GLO 500MB (CG) - 30days",
                    'price' => $response->gongzo_data327,
                    'code' => 327,
                    'id' => 'gongzo_data327',
                ],
                [
                    'name' => "GLO 200MB (CG) - 14days",
                    'price' => $response->gongzo_data337,
                    'code' => 337,
                    'id' => 'gongzo_data337',
                ],
            ];
        }

        if ($type == 'data' && $product == 'glo') {
            $response = \DB::table('glodata_plan')->where('id', $level)->select('glo_105', 'glo_29', 'glo_41', 'glo_58', 'glo_77', 'glo_10', 'glo_1325', 'glo_1825', 'glo_295', 'glo_50', 'glo_93')->first();

            $response = [
                [
                    'name' => "GLO 1.03GB",
                    'price' => $response->glo_105,
                    'code' => 252,
                    'id' => 'glo_105',
                ],
                [
                    'name' => "GLO 2.9GB",
                    'price' => $response->glo_29,
                    'code' => 253,
                    'id' => 'glo_29',
                ],
                [
                    'name' => "GLO 4.1GB",
                    'price' => $response->glo_41,
                    'code' => 254,
                    'id' => 'glo_41',
                ],
                [
                    'name' => "GLO 5.8GB",
                    'price' => $response->glo_58,
                    'code' => 256,
                    'id' => 'glo_58',
                ],
                [
                    'name' => "GLO 7.7GB",
                    'price' => $response->glo_77,
                    'code' => 205,
                    'id' => 'glo_77',
                ],
                [
                    'name' => "GLO 10GB",
                    'price' => $response->glo_10,
                    'code' => 257,
                    'id' => 'glo_10',
                ],
                [
                    'name' => "GLO 13.25GB",
                    'price' => $response->glo_1325,
                    'code' => 258,
                    'id' => 'glo_1325',
                ],
                [
                    'name' => "GLO 18.25GB",
                    'price' => $response->glo_1825,
                    'code' => 259,
                    'id' => 'glo_1825',
                ],
                [
                    'name' => "GLO 29G",
                    'price' => $response->glo_295,
                    'code' => 260,
                    'id' => 'glo_295',
                ],
                [
                    'name' => "GLO 50GB",
                    'price' => $response->glo_50,
                    'code' => 261,
                    'id' => 'glo_50',
                ],
                [
                    'name' => "GLO 93GB",
                    'price' => $response->glo_93,
                    'code' => 262,
                    'id' => 'glo_93',
                ],
            ];
        }

        if (!empty($plan_id)) {
            foreach ($response as $res) {
                if ($plan_id == $res['code']) {
                    $response = [
                        'price' => $res['price'],
                        'service' => "mtn-cg",
                        'databyte' => $res['val'],
                        'name' => $res['name'],
                        'code' => $res['code'],
                    ];
                }
            }
        }

        return $response;
    }

    public function prepareParams(Request $request){
        $postdata = $this->getPostData($request->all());
       
        $headers = [
            'Authorization: Token 3b24d3f969dd1aa39cc434ada0c4de75f54e4948',
            'Content-Type: application/json'
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
            'provider' => 'GONGZO',
        ];
        
        return $params;
    }

    public function getPostData($request){
        if ($request['network_module'] == 'MTN-CG' || $request['network_module'] == 'GIFTING') {
            $type = 'data';
            $product = 'mtn-cg';
            $ntwk = 01;
            $url = 'https://www.gongozconcept.com/api/data/';
        } elseif ($request['network_module'] == 'AIRTEL-CG') {
            $type = 'data';
            $product = 'airtel-cg';
            $ntwk = 4;
            $url = "https://www.gongozconcept.com/api/data/";
        } elseif ($request['network_module'] == 'AIRTEL') {
            $type = 'data';
            $product = 'airtel';
            $ntwk = 4;
            $url = "https://www.gongozconcept.com/api/data/";

        } elseif ($request['network_module'] == 'GLO-CG') {
            $type = 'data';
            $product = 'glo-cg';
            $ntwk = 2;
            $url = "https://www.gongozconcept.com/api/data/";
        } elseif ($request['network_module'] == 'GLO') {
            $type = 'data';
            $product = 'glo';
            $ntwk = 2;
            $url = 'https://www.gongozconcept.com/api/data/';
        } else if ($request['network_module'] == 'MTN' || $request['network_module'] == 'MTN-SME') {
            $type = 'data';
            $product = 'mtn';
            $ntwk = 01;
            $url = 'https://www.gongozconcept.com/api/data/';

        }
        
        $postdata = [
            'url' => $url,
            'network' => $ntwk,
            'plan' => $request['plan_id'],
            'mobile_number' => $request['mobile_no'],
            'Ported_number' => true,
            'no_of_pins' => $request['no_of_pins'],
        ];
        
        return $postdata;

    }

    public function query($postdata, $request, $type){
       
        $resX = $this->advancedApiCall($postdata['url'], json_encode($postdata['payload']), $postdata['headers']);
        
        // $resX = '{"id":8796051,"ident":"Data3c8b22038-740","network":1,"apiresponse":"something went wrong please check back later","api_response":"something went wrong please check back later","balance_before":"3938.0","balance_after":"3711.0","mobile_number":"08149292337","plan":281,"Status":"success","plan_network":"MTN","plan_name":"1.0GB","plan_amount":"227.0","create_date":"2023-08-21T20:56:25.563155","Ported_number":true}';
       
        try {
            $format = $this->processApiResponse($resX,$request, $type);
            $format['api_response'] = $resX;          
        
        } catch (\Throwable $th) {

            $format = [
                'status' => 'attention-required',
                'response' => '',
                'error' => $th->getMessage(),
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
                $result = json_decode($res, true);
                
                if($request['network_module'] == 'MTN' && $type == 'data'){
                    $result['Status'] = isset($result['Status']) ? strtoupper($result['Status']) : null;

                    if (isset($result['Status']) && $result['Status'] !== 'FAILED') {
                        if ($result['Status'] == 'PROCESSING') {
                            $result['apiresponse'] = '';
                        }
                        $status = 'success';
                        $true_response = 'You have successfully shared data to ' . $request['mobile_no'] . '. ' . $result['api_response'];
                    } else {
                        $status = 'failed';
                        $true_response = 'Something went wrong. ' . $result['api_response'];
                    }
                }else{
                    $pinsx = [];
                    $res = str_replace(array(
                        '\'', '"',
                        '',
                    ), ' ', $result);
                    if (isset($result) && !empty($result)) {
                        foreach ($result as $key => $value) {
                            if (strpos($key, 'pin') !== false) {
                                $pinsx[] = $value;
                            }
                        }
                    }
        
                    $pins = (isset($pinsx) && !empty($pinsx)) ? 'PINS: ' . implode(', ', $pinsx) : '';
                    $true_response = isset($result['api_response']) ? str_replace("'", '', $result['api_response']) : '';
                    
                    $result['Status'] = isset($result['Status']) ? strtoupper($result['Status']) : null;
                    
                    if ((isset($result['Status']) && $result['Status'] !== 'FAILED' && !empty($result['Status']) || (isset($result['Status']) && $result['Status'] == 'NULL'))) {
                        if ($result['Status'] == 'PROCESSING') {
                            $true_response = '';
                        }
                        $status = 'success';
                    } else {
                        $status = 'failed';
                    }
                }
                
                $formatted = [
                    'status' => $status,
                    'response' => $true_response,
                    'extra' => $pins ?? null,
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
