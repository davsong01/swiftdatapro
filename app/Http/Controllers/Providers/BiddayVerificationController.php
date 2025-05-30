<?php

namespace App\Http\Controllers\Providers;

use App\Models\Discount;
use App\Models\Variation;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use App\Http\Controllers\Controller;
use App\Services\KycVerificationApiService;

class BiddayVerificationController extends Controller
{
    public function getVariations($product)
    {
        $variations = $this->productVariations($product->slug);
        $variations = $variations['variations'];

        foreach ($variations as $variation) {
            if (Variation::where(['slug' => $variation['code'], 'product_id' => $product->id, 'api_id' => $product->api_id])->count() > 0) {
                continue;
            }
            Variation::updateOrCreate([
                'product_id' => $product['id'],
                'category_id' => $product['category_id'],
                'api_id' => $product['api']['id'],
                'slug' => $variation['code'],
            ], [
                'product_id' => $product['id'],
                'category_id' => $product['category_id'],
                'api_id' => $product['api']['id'],
                'api_name' => $variation['name'],
                'slug' => $variation['code'],
                'system_name' => $variation['name'],
                'fixed_price' => $variation['fixedPrice'] ?? 'yes',
                'api_price' => $variation['price'],
                'system_price' => $variation['price'],
                'network' => $variation['network'] ?? null,
            ]);
        }
        
        return back()->with('Variations pulled successfully');
    }

    public function productVariations($product_slug){
        $variations = [
            'response_description' => '000',
            'variations' => [
                'nin-verification' => [
                    'variations' => [
                        [
                            'name' => "Version 1 (NIN Only)",
                            'price' => 60,
                            'code' => 'ninv1',
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 60,
                        ],
                        [
                            'name' => "Version 1 (Phone Only)",
                            'price' => 60,
                            'code' => 'ninv1-phone',
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 60,
                        ],
                        [
                            'name' => "Version 2 (NIN Only)",
                            'price' => 150,
                            'code' => 'ninv2',
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 150,
                        ],
                        [
                            'name' => "Version 1 (Phone Only)",
                            'price' => 150,
                            'code' => 'ninv2-phone',
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 150,
                        ],
                    ],
                ],
            ],
        ];

        if (!empty($product_slug)) {
            $response = $variations['variations'][$product_slug];
            return $response;
        }
    }


    public function query($data, $api){
        $format = [
            'status' => 'attention-required',
            'extra' => '',
            'response' => 'Something went wrong, please try again later',
            'error' => 'Something went wrong, please try again later',
        ];

        $response = 'no-response';
        
        if (str_contains($data['resource']->slug, 'nin')) {
            if($data['resource']->slug == 'ninv1'){
                $data['url'] = $api->live_base_url . "nin/";
                $data['postdata'] = [
                    'value' => $data['billersCode'],
                ];
            }

            if ($data['resource']->slug == 'ninv1-phone') {
                $data['url'] = $api->live_base_url . "nin/phone";
                $data['postdata'] = [
                    'value' => $data['billersCode'],
                ];
            }

            if ($data['resource']->slug == 'ninv2') {
                $data['url'] = $api->live_base_url . "nin/v2";
                $data['postdata'] = [
                    'value' => $data['billersCode'],
                ];
            }

            if ($data['resource']->slug == 'ninv2-phone') {
                $data['url'] = $api->live_base_url . "nin/phone2";
                $data['postdata'] = [
                    'value' => $data['billersCode'],
                ];
            }
            
            try {
                $response = KycVerificationApiService::genericVerification($data, $api);
                
                if (!empty($response)) {
                    $response = deepObjectToArray($response);
                }
                
                if($response && $response['status']){
                    $format = [
                        'true_response' => $response['status'],
                        'status' => 'success',
                        'postfields' => $data['postdata'] ?? '',
                        'api_response' => $response,
                        'response' => '',
                    ];
                }
            }catch (\Throwable $th) {
                $format = [
                    'api_response' => $response,         
                    'postfields' => $data['postdata'] ?? '',
                    'status' => 'attention-required',
                    'response' => '',
                    'error' => $th->getMessage(). '. File: '.$th->getFile().'. Line:'.$th->getLine(),
                ];
                
                return $format;
            }
        }

        return $format;
    }   
}
