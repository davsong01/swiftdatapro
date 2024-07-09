<?php

namespace App\Http\Controllers\Providers;

use App\Models\Product;
use App\Models\Discount;
use App\Models\Variation;
use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerLevel;

class EasyAccessController extends Controller
{

    public function getVariations($product)
    {
        $variations = $this->esayAccessProductVariations($product->slug);
        if($variations){
            $variations = $variations['variations'];
            foreach ($variations as $variation) {
                if(Variation::where('slug', $variation['variation_code'])->count() > 0){
                    continue;
                }
                Variation::updateOrCreate([
                    'product_id' => $product['id'],
                    'category_id' => $product['category_id'],
                    'api_id' => $product['api']['id'],
                    'slug' => $variation['variation_code'],
                ], [
                    'product_id' => $product['id'],
                    'category_id' => $product['category_id'],
                    'api_id' => $product['api']['id'],
                    'api_name' => $variation['name'],
                    'slug' => $variation['variation_code'],
                    'system_name' => $variation['name'],
                    'fixed_price' => $variation['fixedPrice'],
                    'api_price' => $variation['variation_amount'],
                    'system_price' => $variation['variation_amount'],
                    'network' => $variation['network'] ?? null,
                ]);
            }

            return true;
        }
        return false;        
    }

    public function esayAccessProductVariations($product_slug)
    {
        $variations = [
            'response_description' => '000',
            'variations' => [
                'mtn-cg' => [
                    'variations' => [
                        [
                            'name' => '500MB (SME)',
                            'variation_code' => 50,
                            'fixedPrice' => 'yes',
                            'variation_amount' => 129,
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 51,
                            'fixedPrice' => 'yes',
                            'variation_amount' => 257,
                            'name' => '1GB (SME) - 30days',
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 52,
                            'fixedPrice' => 'yes',
                            'name' => '2GB (SME) - 30days',
                            'variation_amount' => 514,
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 53,
                            'fixedPrice' => 'yes',
                            'name' => '3GB (SME) - 30days',
                            'variation_amount' => 771,
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 147,
                            'fixedPrice' => 'yes',
                            'name' => '70GB (CG) - 30days',
                            'variation_amount' => 1576923600,
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 148,
                            'fixedPrice' => 'yes',
                            'name' => '75GB (CG) -  30days',
                            'variation_amount' => 1689561000,
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 149,
                            'fixedPrice' => 'yes',
                            'name' => '80GB (CG) - 30days',
                            'variation_amount' => 1802198400,
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 150,
                            'fixedPrice' => 'yes',
                            'name' => '90GB (CG) - 30days',
                            'variation_amount' => 2027473200,
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 151,
                            'fixedPrice' => 'yes',
                            'name' => '100GB (CG) - 30days',
                            'variation_amount' => 2252748000,
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 152,
                            'fixedPrice' => 'yes',
                            'variation_amount' => 47262500,
                            'network' => 01,
                            'name' => '750MB (Direct) - 14days',
                            'network' => 01,
                        ],
                        [
                            'variation_code' => 83,
                            'fixedPrice' => 'yes',
                            'name' => '1.5GB (Direct) - 30days',
                            'variation_amount' => 965,
                            'network' => 01,
                        ],
                    ],
                ],

                'glo-cg' => [
                    'variations' => [
                        [
                            'name' => 'GLO 200MB (CG) - 14days',
                            'variation_code' => 158,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 46,
                            'network' => 02,
                        ],
                        [
                            'name' => 'GLO 500MB (CG) - 30days',
                            'variation_code' => 159,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 113,
                            'network' => 02,

                        ],
                        [
                            'name' => '1GB (CG) - 30days',
                            'variation_code' => 160,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 225,
                            'network' => 02,

                        ],
                        [
                            'name' => '2GB (CG) - 30days',
                            'variation_code' => 161,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 450,
                            'network' => 02,

                        ],
                        [
                            'name' => '3GB (CG) - 30days',
                            'variation_code' => 162,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 675,
                            'network' => 02,

                        ],
                        [
                            'name' => '5GB (CG) - 30days',
                            'variation_code' => 163,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 1125,
                            'network' => 02,

                        ],
                        [
                            'name' => '10GB (CG) - 30days',
                            'variation_code' => 164,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 2250,
                            'network' => 02,

                        ],
                    ],
                ],

                'airtel-cg' => [
                    'variations' => [
                        [
                            'name' => 'AIRTEL 100MB (CG) - 7days',
                            'variation_code' => 104,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 27,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 300MB (CG) - 7days',
                            'variation_code' => 105,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 67,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 500MB (CG) - 30days',
                            'variation_code' => 106,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 101,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 1GB (CG) - 30days',
                            'variation_code' => 107,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 200,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 2GB (CG) - 30days',
                            'variation_code' => 108,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 400,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 5GB (CG) - 30days',
                            'variation_code' => 109,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 1000,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 10GB (CG) - 30days',
                            'variation_code' => 124,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 2000,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 15GB (CG) - 30days',
                            'variation_code' => 139,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 3000,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 20GB (CG) - 30days',
                            'variation_code' => 140,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 4000,
                            'network' => 03,

                        ],
                    ],
                ],

                'etisalat-sme' => [
                    'variations' => [
                        [
                            'variation_code' => 166,
                            'name' => '100MB (SME) - 30days',
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 18,
                            'network' => 04,
                        ],
                        [
                            'variation_code' => 167,
                            'name' => '300MB (SME) - 30days',
                            'variation_amount' => 48,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 168,
                            'name' => '500MB (SME) - 30days',
                            'variation_amount' => 68,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 128,
                            'name' => '1GB (SME) - 30days',
                            'variation_amount' => 135,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 129,
                            'name' => '1.5GB (SME) 30days',
                            'variation_amount' => 203,
                            'network' => 04,
                            'fixedPrice' => 'Yes'
                        ],
                        [
                            'variation_code' => 130,
                            'name' => '2GB (SME) - 30days',
                            'variation_amount' => 270,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 131,
                            'name' => '2.5GB (SME) - 30days',
                            'variation_amount' => 3942000,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 132,
                            'name' => '3GB (SME) - 30days',
                            'variation_amount' => 405,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        // [
                        //     'variation_code' => 132,
                        //     'name' => '3GB (SME) - 30days',
                        //     'variation_amount' => 405,
                        //     'network' => 04,
                        // ],
                        [
                            'variation_code' => 169,
                            'name' => '4GB (SME) - 30days',
                            'variation_amount' => 540,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 134,
                            'name' => '5GB (SME) - 30days',
                            'variation_amount' => 675,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 135,
                            'name' => '6GB (SME) - 30days',
                            'variation_amount' => 810,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 170,
                            'name' => '7.5GB (SME) 30days',
                            'variation_amount' => -1014,
                            'network' => 01,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 136,
                            'name' => '10GB (SME) - 30days',
                            'variation_amount' => 1350,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ],
                        [
                            'variation_code' => 171,
                            'name' => '11GB (SME) - 30days',
                            'variation_amount' => 1485,
                            'network' => 04,
                            'fixedPrice' => 'Yes',

                        ],
                        [
                            'variation_code' => 137,
                            'name' => '15GB (SME) - 30days',
                            'variation_amount' => 2025,
                            'network' => 04,
                            'fixedPrice' => 'Yes',

                        ],
                        [
                            'variation_code' => 138,
                            'name' => '20GB (SME) - 30days',
                            'variation_amount' => 2700,
                            'network' => 04,
                            'fixedPrice' => 'Yes',

                        ],
                        [
                            'variation_code' => 172,
                            'name' => '25GB (SME) - 30days',
                            'variation_amount' => 352316250,
                            'network' => 04,
                            'fixedPrice' => 'Yes',

                        ],
                        [
                            'variation_code' => 173,
                            'name' => '30GB (SME) - 30days',
                            'variation_amount' => 42277950,
                            'network' => 04,
                            'fixedPrice' => 'Yes',

                        ],
                        [
                            'variation_code' => 174,
                            'name' => '40GB (SME) - 30days',
                            'variation_amount' => 563706000,
                            'network' => 04,
                            'fixedPrice' => 'Yes',

                        ],
                        [
                            'variation_code' => 175,
                            'name' => '50GB (SME) - 30days',
                            'variation_amount' => 704632500,
                            'network' => 04,
                            'fixedPrice' => 'Yes',

                        ],
                        [
                            'variation_code' => 176,
                            'name' => '100GB (SME) - 30days',
                            'variation_amount' => 1409265000,
                            'network' => 04,
                            'fixedPrice' => 'Yes',
                        ]
                    ],
                ],

                'airtel-gifting' => [
                    'variations' => [
                        [
                            'name' => 'AIRTEL 100MB (CG) - 7days',
                            'variation_code' => 104,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 27,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 300MB (CG) - 7days',
                            'variation_code' => 105,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 67,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 500MB (CG) - 30days',
                            'variation_code' => 106,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 101,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 1GB (CG) - 30days',
                            'variation_code' => 107,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 200,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 2GB (CG) - 30days',
                            'variation_code' => 108,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 400,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 5GB (CG) - 30days',
                            'variation_code' => 109,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 1000,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 10GB (CG) - 30days',
                            'variation_code' => 124,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 2000,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 15GB (CG) - 30days',
                            'variation_code' => 139,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 3000,
                            'network' => 03,

                        ],
                        [
                            'name' => 'AIRTEL 20GB (CG) - 30days',
                            'variation_code' => 140,
                            'fixedPrice' => 'Yes',
                            'variation_amount' => 4000,
                            'network' => 03,

                        ],
                    ],
                ],
            ],
        ];

        if (!empty($product_slug)) {
            if(isset($variations['variations'][$product_slug])){
                $response = $variations['variations'][$product_slug];
                return $response;
            }else{
                return [];
            }
        }
    }


    public function getOldVariations($product)
    {
        $category = $product->category;
        $productLevel = CustomerLevel::pluck('id')->toArray();
        $old_identifier = $product->old_identifier;
        
        // Data products
        if (isset($product->slug) && $product->slug == 'mtn-cg') {

            // Get variations
            // Loop through variations and create each variation
            $response = \DB::connection('old')->table($old_identifier)->select('easy_access125', 'easy_access126', 'easy_access127', 'easy_access45', 'easy_access46', 'easy_access47', 'easy_access48', 'easy_access49', 'easy_access88', 'easy_access89', 'easy_access90', 'easy_access141', 'easy_access142', 'easy_access143', 'easy_access144', 'easy_access145', 'easy_access146', 'easy_access147', 'easy_access148', 'easy_access149', 'easy_access150', 'easy_access151')->first();
            //create and update variation
            $data = [
                [
                    'name' => "MTN 50MB (CG) - 30days",
                    'price' => $response->easy_access125,
                    'code' => 125,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access125',
                    'val' => 50,
                ],
                [
                    'name' => "MTN 150MB (CG) - 30days",
                    'price' => $response->easy_access126,
                    'code' => 126,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access126',
                    'val' => 150,
                ],
                [
                    'name' => "MTN 250MB (CG) - 30days",
                    'price' => $response->easy_access127,
                    'code' => 127,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access127',
                    'val' => 250,
                ],
                [
                    'name' => "MTN 500MB (CG) - 30days",
                    'val' => 500,
                    'price' => $response->easy_access45,
                    'code' => 45,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access45',
                ],
                [
                    'name' => "MTN 1GB (CG) - 30days",
                    'val' => 1000,
                    'price' => $response->easy_access46,
                    'code' => 46,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access46',
                ],
                [
                    'name' => "MTN 2GB (CG) - 30days",
                    'val' => 2000,
                    'price' => $response->easy_access47,
                    'code' => 47,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access47',
                ],
                [
                    'name' => "MTN 3GB (CG) - 30days",
                    'val' => 3000,
                    'price' => $response->easy_access48,
                    'code' => 48,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access48',
                ],
                [
                    'name' => "MTN 5GB (CG) - 30days",
                    'val' => 5000,
                    'price' => $response->easy_access49,
                    'code' => 49,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access49'
                ],
                [
                    'name' => "MTN 10GB (CG) - 30days",
                    'val' => 10000,
                    'price' => $response->easy_access88,
                    'code' => 88,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access88',
                ],
                [
                    'name' => "MTN 12GB (CG) - 30days",
                    'val' => 12000,
                    'price' => $response->easy_access89,
                    'code' => 89,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access89',
                ],
                [
                    'name' => "MTN 15GB (CG) - 30days",
                    'val' => 15000,
                    'price' => $response->easy_access90,
                    'code' => 90,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access90',
                ],
                [
                    'name' => "MTN 20GB (CG) - 30days",
                    'val' => 20000,
                    'price' => $response->easy_access141,
                    'code' => 141,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access141',
                ],
                [
                    'name' => "MTN 25GB (CG) - 30days",
                    'val' => 25000,
                    'price' => $response->easy_access142,
                    'code' => 142,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access142',
                ],
                [
                    'name' => "MTN 30GB (CG) - 30days",
                    'val' => 30000,
                    'price' => $response->easy_access143,
                    'code' => 143,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access143',
                ],
                [
                    'name' => "MTN 40GB (CG) - 30days",
                    'val' => 40000,
                    'price' => $response->easy_access144,
                    'code' => 144,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access144',
                ],
                [
                    'name' => "MTN 50GB (CG) - 30days",
                    'price' => $response->easy_access145,
                    'val' => 50000,
                    'code' => 145,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access145',
                ],
                [
                    'name' => "MTN 60GB (CG) - 30days",
                    'price' => $response->easy_access146,
                    'val' => 60000,
                    'code' => 146,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access146',
                ],
                [
                    'name' => "MTN 70GB (CG) - 30days",
                    'price' => $response->easy_access147,
                    'val' => 70000,
                    'code' => 147,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access147',
                ],
                [
                    'name' => "MTN 75GB (CG) - 30days",
                    'val' => 75000,
                    'price' => $response->easy_access148,
                    'code' => 148,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access148',
                ],
                [
                    'name' => "MTN 80GB (CG) - 30days",
                    'val' => 80000,
                    'price' => $response->easy_access149,
                    'code' => 149,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access149',
                ],
                [
                    'name' => "MTN 90GB (CG) - 30days",
                    'val' => 90000,
                    'price' => $response->easy_access150,
                    'code' => 150,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access150',
                ],
                [
                    'name' => "MTN 100GB (CG) - 30days",
                    'val' => 100000,
                    'price' => $response->easy_access151,
                    'code' => 151,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access151',
                ],

            ];
          
            foreach ($data as $d) {
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                ], [
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                    'api_id' => $product->api_id,
                    'system_name' => $d['name'],
                    'fixed_price' => $d['fixedPrice'] ?? 'yes',
                    'api_price' => $d['price'],
                    'system_price' => $d['price'],
                    'min' => null,
                    'max' =>  null,
                    'status' => 'active'
                ]);

                foreach ($productLevel as $level) {
                    $response = \DB::connection('old')->table($old_identifier)->select('easy_access125', 'easy_access126', 'easy_access127', 'easy_access45', 'easy_access46', 'easy_access47', 'easy_access48', 'easy_access49', 'easy_access88', 'easy_access89', 'easy_access90', 'easy_access141', 'easy_access142', 'easy_access143', 'easy_access144', 'easy_access145', 'easy_access146', 'easy_access147', 'easy_access148', 'easy_access149', 'easy_access150', 'easy_access151')->where('id',$level)->first();

                    foreach($response as $key=>$value){
                        $discount = Discount::updateOrCreate([
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id
                        ], [
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id,
                            'price' => $value ?? 0,
                            'status' => 'active'
                        ]);
   
                    }
                }
            }
        }

        if (isset($product->slug) && ($product->slug == 'airtel-cg' || $product->slug == 'airtel-gifting')) {
            $response = \DB::connection('old')->table('airtelcgdata_plan')->select('easy_access104', 'easy_access105', 'easy_access106', 'easy_access107', 'easy_access108', 'easy_access109', 'easy_access124', 'easy_access139', 'easy_access140')->first();
            //create and update variation
            $data = [
                [
                    'name' => "AIRTEL 100MB (CG) - 7days",
                    'val' => 100,
                    'price' => $response->easy_access104,
                    'code' => 104,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access104',
                ],
                [
                    'name' => "AIRTEL 300MB (CG) - 7days",
                    'price' => $response->easy_access105,
                    'val' => 300,
                    'code' => 105,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access105',
                ],
                [
                    'name' => "AIRTEL 500MB (CG) - 30days",
                    'val' => 500,
                    'price' => $response->easy_access106,
                    'code' => 106,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access106',
                ],
                [
                    'name' => "AIRTEL 1GB (CG) - 30days",
                    'val' => 1000,
                    'price' => $response->easy_access107,
                    'code' => 107,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access107',
                ],
                [
                    'name' => "AIRTEL 2GB (CG) - 30days",
                    'val' => 2000,
                    'price' => $response->easy_access108,
                    'code' => 108,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access108',
                ],
                [
                    'name' => "AIRTEL 5GB (CG) - 30days",
                    'val' => 5000,
                    'price' => $response->easy_access109,
                    'code' => 109,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access109',
                ],
                [
                    'name' => "AIRTEL 10GB (CG) - 30days",
                    'val' => 10000,
                    'price' => $response->easy_access124,
                    'code' => 124,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access124',
                ],
                [
                    'name' => "AIRTEL 15GB (CG) - 30days",
                    'price' => $response->easy_access139,
                    'val' => 15000,
                    'code' => 139,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access139'
                ],
                [
                    'name' => "AIRTEL 20GB (CG) - 30days",
                    'val' => 20000,
                    'price' => $response->easy_access140,
                    'code' => 140,
                    'fixedPrice' => 'yes',
                    'id' => 'easy_access140',
                ],
            ];

            foreach ($data as $d) {
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                ], [
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                    'api_id' => $product->api_id,
                    'system_name' => $d['name'],
                    'fixed_price' => $d['fixedPrice'] ?? 'yes',
                    'api_price' => $d['price'],
                    'system_price' => $d['price'],
                    'min' => null,
                    'max' =>  null,
                    'status' => 'active'
                ]);

                foreach ($productLevel as $level) {
                    $response = \DB::connection('old')->table('airtelcgdata_plan')->select('easy_access104', 'easy_access105', 'easy_access106', 'easy_access107', 'easy_access108', 'easy_access109', 'easy_access124', 'easy_access139', 'easy_access140')->where('id', $level)->first();

                    foreach ($response as $key => $value) {
                        $discount = Discount::updateOrCreate([
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id
                        ], [
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id,
                            'price' => $value ?? 0,
                            'status' => 'active'
                        ]);
                    }
                }
            }
        }

        if (isset($product->slug) && $product->slug == 'airtel') {
            $response = \DB::connection('old')->table('airtelcgdata_plan')->select('easy_access66', 'easy_access67', 'easy_access68', 'easy_access69', 'easy_access70', 'easy_access71', 'easy_access72', 'easy_access74', 'easy_access96', 'easy_access97', 'easy_access157')->first();
            //create and update variation
            $data = [
                [
                    'name' => "AIRTEL 100MB (CG) - 7days",
                    'val' => 100,
                    'price' => $response->easy_access104,
                    'code' => 104,
                    'id' => 'easy_access104',
                ],
                [
                    'name' => "AIRTEL 300MB (CG) - 7days",
                    'price' => $response->easy_access105,
                    'val' => 300,
                    'code' => 105,
                    'id' => 'easy_access105',
                ],
                [
                    'name' => "AIRTEL 500MB (CG) - 30days",
                    'val' => 500,
                    'price' => $response->easy_access106,
                    'code' => 106,
                    'id' => 'easy_access106',
                ],
                [
                    'name' => "AIRTEL 1GB (CG) - 30days",
                    'val' => 1000,
                    'price' => $response->easy_access107,
                    'code' => 107,
                    'id' => 'easy_access107',
                ],
                [
                    'name' => "AIRTEL 2GB (CG) - 30days",
                    'val' => 2000,
                    'price' => $response->easy_access108,
                    'code' => 108,
                    'id' => 'easy_access108',
                ],
                [
                    'name' => "AIRTEL 5GB (CG) - 30days",
                    'val' => 5000,
                    'price' => $response->easy_access109,
                    'code' => 109,
                    'id' => 'easy_access109',
                ],
                [
                    'name' => "AIRTEL 10GB (CG) - 30days",
                    'val' => 10000,
                    'price' => $response->easy_access124,
                    'code' => 124,
                    'id' => 'easy_access124',
                ],
                [
                    'name' => "AIRTEL 15GB (CG) - 30days",
                    'price' => $response->easy_access139,
                    'val' => 15000,
                    'code' => 139,
                    'id' => 'easy_access139'
                ],
                [
                    'name' => "AIRTEL 20GB (CG) - 30days",
                    'val' => 20000,
                    'price' => $response->easy_access140,
                    'code' => 140,
                    'id' => 'easy_access140',
                ],
            ];

            foreach ($data as $d) {
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                ], [
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                    'api_id' => $product->api_id,
                    'system_name' => $d['name'],
                    'fixed_price' => 'yes',
                    'api_price' => $d['price'],
                    'system_price' => $d['price'],
                    'min' => null,
                    'max' =>  null,
                    'status' => 'active'
                ]);

                foreach ($productLevel as $level) {
                    $response = \DB::connection('old')->table('airteldata_plan')->select('easy_access66', 'easy_access67', 'easy_access68', 'easy_access69', 'easy_access70', 'easy_access71', 'easy_access72', 'easy_access74', 'easy_access96', 'easy_access97', 'easy_access157')->where('id', $level)->first();

                    foreach ($response as $key => $value) {
                        $discount = Discount::updateOrCreate([
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id
                        ], [
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id,
                            'price' => $value ?? 0,
                            'status' => 'active'
                        ]);
                    }
                }
            }
        }
        
        if (isset($product->slug) && $product->slug == 'glo') {
            $response = \DB::connection('old')->table('glodata_plan')->select('easy_access59', 'easy_access60', 'easy_access87', 'easy_access61', 'easy_access62', 'easy_access63', 'easy_access64', 'easy_access92', 'easy_access93', 'easy_access94', 'easy_access100', 'easy_access101', 'easy_access102')->first();

            $data = [
                [
                    'name' => "1.35GB (Direct) - 14days",
                    'val' => 1500,
                    'price' => $response->easy_access59,
                    'code' => 59,
                    'id' => 'easy_access59',
                ],
                [
                    'name' => "2.9GB (Direct) - 30days",
                    'price' => $response->easy_access60,
                    'val' => 2900,
                    'code' => 60,
                    'id' => 'easy_access60',
                ],
                [
                    'name' => "4.1GB (Direct) - 30days",
                    'val' => 4100,
                    'price' => $response->easy_access87,
                    'code' => 87,
                    'id' => 'easy_access87',
                ],
                [
                    'name' => '5.8GB (Direct) - 30days',
                    'val' => 5800,
                    'price' => $response->easy_access61,
                    'code' => 61,
                    'id' => 'easy_access61',
                ],
                [
                    'name' => "7.7GB (Direct) - 30days",
                    'val' => 7700,
                    'price' => $response->easy_access62,
                    'code' => 62,
                    'id' => 'easy_access62',
                ],
                [
                    'name' => "10GB (Direct) - 30days",
                    'val' => 10000,
                    'price' => $response->easy_access63,
                    'code' => 63,
                    'id' => 'easy_access63',
                ],
                [
                    'name' => "13.25GB (Direct) - 30days",
                    'val' => 10000,
                    'price' => $response->easy_access64,
                    'code' => 64,
                    'id' => 'easy_access64',
                ],
                [
                    'name' => "18.25GB (Direct) - 30days",
                    'val' => 18225,
                    'price' => $response->easy_access92,
                    'code' => 92,
                    'id' => 'easy_access92',
                ],
                [
                    'name' => "29.5GB (Direct) - 30days",
                    'price' => $response->easy_access93,
                    'code' => 29500,
                    'code' => 93,
                    'id' => 'easy_access93'
                ],
                [
                    'name' => "50GB (Direct) - 30days",
                    'val' => 50000,
                    'price' => $response->easy_access94,
                    'code' => 94,
                    'id' => 'easy_access94',
                ],
                [
                    'name' => "93GB (Direct) - 30days",
                    'val' => 93000,
                    'price' => $response->easy_access100,
                    'code' => 100,
                    'id' => 'easy_access100',
                ],
                [
                    'name' => "119GB (Direct) - 30days",
                    'val' => 119000,
                    'price' => $response->easy_access101,
                    'code' => 101,
                    'id' => 'easy_access101',
                ],
                [
                    'name' => "138GB (Direct) - 30days",
                    'val' => 75000,
                    'price' => $response->easy_access102,
                    'code' => 102,
                    'id' => 'easy_access102',
                ],
            ];

            foreach ($data as $d) {
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                ], [
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                    'api_id' => $product->api_id,
                    'system_name' => $d['name'],
                    'fixed_price' => 'yes',
                    'api_price' => $d['price'],
                    'system_price' => $d['price'],
                    'min' => null,
                    'max' =>  null,
                    'status' => 'active'
                ]);

                foreach ($productLevel as $level) { 
                    $response = \DB::connection('old')->table('glodata_plan')->select('easy_access59', 'easy_access60', 'easy_access87', 'easy_access61', 'easy_access62', 'easy_access63', 'easy_access64', 'easy_access92', 'easy_access93', 'easy_access94', 'easy_access100', 'easy_access101', 'easy_access102')->where('id', $level)->first();

                    foreach ($response as $key => $value) {
                        $discount = Discount::updateOrCreate([
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id
                        ], [
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id,
                            'price' => $value ?? 0,
                            'status' => 'active'
                        ]);
                    }
                }
            }
        }

        if (isset($product->slug) && $product->slug == 'glo-cg') {
            $response = \DB::connection('old')->table('glocgdata_plan')->select('easy_access158', 'easy_access159', 'easy_access160', 'easy_access161', 'easy_access162', 'easy_access163', 'easy_access164')->first();

            $data = [
                [
                    'name' => "GLO 200MB (CG) - 14days",
                    'val' => 200,
                    'price' => $response->easy_access158,
                    'code' => 158,
                    'id' => 'easy_access158',
                ],
                [
                    'name' => "GLO 500MB (CG) - 30days",
                    'val' => 500,
                    'price' => $response->easy_access159,
                    'code' => 159,
                    'id' => 'easy_access159',
                ],
                [
                    'name' => "GLO 1GB (CG) - 30days",
                    'val' => 1000,
                    'price' => $response->easy_access160,
                    'code' => 160,
                    'id' => 'easy_access160',
                ],
                [
                    'name' => "GLO 2GB (CG) - 30days",
                    'val' => 2000,
                    'price' => $response->easy_access161,
                    'code' => 161,
                    'id' => 'easy_access161',
                ],
                [
                    'name' => "GLO 3GB (CG) - 30days",
                    'val' => 3000,
                    'price' => $response->easy_access162,
                    'code' => 162,
                    'id' => 'easy_access162',
                ],
                [
                    'name' => "GLO 5GB (CG) - 30days",
                    'val' => 500,
                    'price' => $response->easy_access163,
                    'code' => 163,
                    'id' => 'easy_access163',
                ],
                [
                    'name' => "GLO 10GB (CG) - 30days",
                    'val' => 10000,
                    'price' => $response->easy_access164,
                    'code' => 164,
                    'id' => 'easy_access164',
                ],
            ];

            foreach ($data as $d) {
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                ], [
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                    'api_id' => $product->api_id,
                    'system_name' => $d['name'],
                    'fixed_price' => 'yes',
                    'api_price' => $d['price'],
                    'system_price' => $d['price'],
                    'min' => null,
                    'max' =>  null,
                    'status' => 'active'
                ]);

                foreach ($productLevel as $level) {
                    $response = \DB::connection('old')->table('glocgdata_plan')->select('easy_access158', 'easy_access159', 'easy_access160', 'easy_access161', 'easy_access162', 'easy_access163', 'easy_access164')->where('id', $level)->first();

                    foreach ($response as $key => $value) {
                        $discount = Discount::updateOrCreate([
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id
                        ], [
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id,
                            'price' => $value ?? 0,
                            'status' => 'active'
                        ]);
                    }
                }
            }
        }


        if (isset($product->slug) && $product->slug == 'apimobile-sme') {
            $response = \DB::connection('old')->table('mobilesmedata_plan')->select('easy_access166', 'easy_access167', 'easy_access168', 'easy_access128', 'easy_access129', 'easy_access130', 'easy_access131', 'easy_access132', 'easy_access169', 'easy_access134', 'easy_access135', 'easy_access170', 'easy_access136', 'easy_access171', 'easy_access137', 'easy_access138', 'easy_access172', 'easy_access173', 'easy_access174', 'easy_access175', 'easy_access176')->first();

            $data = [
                [
                    'name' => "9MOBILE 100MB (SME) - 30days",
                    'val' => 100,
                    'price' => $response->easy_access166,
                    'code' => 166,
                    'id' => 'easy_access166',
                ],
                [
                    'name' => "9MOBILE 300MB (SME) - 30days",
                    'val' => 300,
                    'price' => $response->easy_access167,
                    'code' => 167,
                    'id' => 'easy_access167',
                ],
                [
                    'name' => "9MOBILE 500MB (SME) - 30days",
                    'val' => 500,
                    'price' => $response->easy_access168,
                    'code' => 168,
                    'id' => 'easy_access168',
                ],
                [
                    'name' => "9MOBILE 1GB (SME) - N190 30days",
                    'val' => 1000,
                    'price' => $response->easy_access128,
                    'code' => 128,
                    'id' => 'easy_access128',
                ],
                [
                    'name' => "9MOBILE 1.5GB (SME) - 30days",
                    'val' => 1500,
                    'price' => $response->easy_access129,
                    'code' => 129,
                    'id' => 'easy_access129',
                ],
                [
                    'name' => "9MOBILE 2GB (SME) - 30days",
                    'val' => 2000,
                    'price' => $response->easy_access130,
                    'code' => 130,
                    'id' => 'easy_access130',
                ],
                [
                    'name' => "9MOBILE 2.5GB (SME) - 30days",
                    'val' => 25000,
                    'price' => $response->easy_access131,
                    'code' => 131,
                    'id' => 'easy_access131'
                ],

                [
                    'name' => "9MOBILE 3GB (SME) - 30days",
                    'val' => 3000,
                    'price' => $response->easy_access132,
                    'code' => 132,
                    'id' => 'easy_access132',
                ],
                [
                    'name' => "9MOBILE 4GB (SME) - 30days",
                    'val' => 4000,
                    'price' => $response->easy_access169,
                    'code' => 169,
                    'id' => 'easy_access169',
                ],
                [
                    'name' => "9MOBILE 5GB (SME) - 30days",
                    'val' => 5000,
                    'price' => $response->easy_access134,
                    'code' => 134,
                    'id' => 'easy_access134',
                ],
                [
                    'name' => "9MOBILE 6GB (SME) - 30days",
                    'val' => 6000,
                    'price' => $response->easy_access135,
                    'code' => 135,
                    'id' => 'easy_access135',
                ],
                [
                    'name' => "9MOBILE 7.5GB (SME) - 30days",
                    'val' => 7500,
                    'price' => $response->easy_access170,
                    'code' => 170,
                    'id' => 'easy_access170',
                ],
                [
                    'name' => "9MOBILE 10GB (SME) - 30days",
                    'val' => 10000,
                    'price' => $response->easy_access136,
                    'code' => 136,
                    'id' => 'easy_access136',
                ],
                [
                    'name' => "9MOBILE 11GB (SME) - 30days",
                    'val' => 11000,
                    'price' => $response->easy_access171,
                    'code' => 171,
                    'id' => 'easy_access171',
                ],
                [
                    'name' => "9MOBILE 15GB (SME) - 30days",
                    'val' => 15000,
                    'price' => $response->easy_access137,
                    'code' => 137,
                    'id' => 'easy_access137',
                ],
                [
                    'name' => "9MOBILE 20GB (SME) - 30days",
                    'val' => 20000,
                    'price' => $response->easy_access138,
                    'code' => 138,
                    'id' => 'easy_access138',
                ],
                [
                    'name' => "9MOBILE 25GB (SME) - 30days",
                    'val' => 25000,
                    'price' => $response->easy_access172,
                    'code' => 172,
                    'id' => 'easy_access172',
                ],
                [
                    'name' => "9MOBILE 30GB (SME) - 30days",
                    'val' => 30000,
                    'price' => $response->easy_access173,
                    'code' => 173,
                    'id' => 'easy_access173',
                ],
                [
                    'name' => "9MOBILE 40GB (SME) - 30days",
                    'val' => 40000,
                    'price' => $response->easy_access174,
                    'code' => 174,
                    'id' => 'easy_access174',
                ],
                [
                    'name' => "9MOBILE 50GB (SME) - 30days",
                    'val' => 50000,
                    'price' => $response->easy_access175,
                    'code' => 175,
                    'id' => 'easy_access175',
                ],
                [
                    'name' => "9MOBILE 100GB (SME) - 30days",
                    'val' => 100000,
                    'price' => $response->easy_access176,
                    'code' => 176,
                    'id' => 'easy_access176',
                ],

            ];

            foreach ($data as $d) {
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                ], [
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                    'api_id' => $product->api_id,
                    'system_name' => $d['name'],
                    'fixed_price' => 'yes',
                    'api_price' => $d['price'],
                    'system_price' => $d['price'],
                    'min' => null,
                    'max' =>  null,
                    'status' => 'active'
                ]);

                foreach ($productLevel as $level) {
                    $response = \DB::connection('old')->table('mobilesmedata_plan')->select('easy_access166', 'easy_access167', 'easy_access168', 'easy_access128', 'easy_access129', 'easy_access130', 'easy_access131', 'easy_access132', 'easy_access169', 'easy_access134', 'easy_access135', 'easy_access170', 'easy_access136', 'easy_access171', 'easy_access137', 'easy_access138', 'easy_access172', 'easy_access173', 'easy_access174', 'easy_access175', 'easy_access176')->where('id', $level)->first();

                    foreach ($response as $key => $value) {
                        $discount = Discount::updateOrCreate([
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id
                        ], [
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id,
                            'price' => $value ?? 0,
                            'status' => 'active'
                        ]);
                    }
                }
            }
        }


        if (isset($product->slug) && $product->slug ==  'mtn-datacard') {
            $response = \DB::connection('old')->table('mtndata_card_plan')->first();

            $data = [
                [
                    'name' => "MTN 1.5GB (DATACARD) - 30days",
                    'val' => 1500,
                    'price' => $response->easy_access165,
                    'code' => 165,
                    'id' => 'easy_access165',
                ],

            ];

            foreach ($data as $d) {
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                ], [
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['code'],
                    'api_id' => $product->api_id,
                    'system_name' => $d['name'],
                    'fixed_price' => 'yes',
                    'api_price' => $d['price'],
                    'system_price' => $d['price'],
                    'min' => null,
                    'max' =>  null,
                    'status' => 'active'
                ]);

                foreach ($productLevel as $level) {
                    $response = \DB::connection('old')->table('mtndata_card_plan')->where('id', $level)->first();

                    foreach ($response as $key => $value) {
                        $discount = Discount::updateOrCreate([
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id
                        ], [
                            'customer_level' => $level,
                            'product_id' => $product->id,
                            'variation_id' => $variation->id,
                            'price' => $value ?? 0,
                            'status' => 'active'
                        ]);
                    }
                }
            }
        }

        // Data card
        return back()->with('Old Variations pulled successfully');
    }

    public function getPostData($request, $api){
        $slug = strtolower($request['resource']->slug);
        $resource = $request['resource'];
        if ($slug == 'mtn-cg' || $slug == 'mtn' || $slug == 'mtn-sme') {
            $ntwk = '01';
            $url = $api->live_base_url."data.php";
        } elseif ($slug == 'airtel-cg' || $slug == 'airtel') {
            $ntwk = '03';
            $url = $api->live_base_url . "data.php";
        } elseif ($slug == 'glo-cg' || $slug == 'glo' || $slug == 'glo-data') {;
            $ntwk = '02';
            $url = $api->live_base_url . "data.php";
        } elseif ($slug == '9mobile-sme' || $slug == 'apimobile-sme') {
            $ntwk = '04';
            $url = $api->live_base_url . "data.php";
        } elseif ($slug == 'mtn-datacard') {
            $ntwk = '01';
            $url = $api->live_base_url . "datacard.php";
        }

        $postdata = [
            'url' => $url,
            'network' => $ntwk,
            'mobileno' =>  $request['mobile_number'] ?? '',
            'dataplan' =>  $resource->slug,
            'client_reference' => $request['sys_ref'],
            'quantity' => $request['buyer_quantity'] ?? '',
        ];
        
        return $postdata;

    }

    public function query($postdata, $api){
        $headers = [
            "Authorization: Token " . $api->api_key . "",
            'cache-control: no-cache'
        ];
        
        // Save post fields
        $pstfields = $this->getPostData($postdata, $api);

        if(env('ENT') == 'local'){
            $resX = '{"success": "true","message": "Data Card Purchase was Successful","network": "MTN","pin": "408335193S","pin2": "184305851S","dataplan": "1.5GB","amount": 574,"balance_before": "27833","balance_after": 27259,"transaction_date": "07-04-2023 07:57:47 pm","reference_no": "ID5345892220","client_reference": "client_ref84218868382855","status": "Successful","auto_refund_status": "success"}';
        }else{
            $resX = $this->advancedApiCall($pstfields['url'], $pstfields, $headers);
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
                'error' => $th->getMessage(). '. File: '.$th->getFile().'. Line:'.$th->getLine(),
            ];
            
            return $format;
        }
       
        return $format;
    }

    public function processApiResponse($res){
        if(empty($res)){
            $formatted = [
                'status' => 'attention-required',
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

    public function requery($transaction){

    }
}
