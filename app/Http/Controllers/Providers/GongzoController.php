<?php

namespace App\Http\Controllers\Providers;

use App\Models\Discount;
use App\Models\Variation;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use App\Http\Controllers\Controller;

class GongzoController extends Controller
{
    public function getVariations($product)
    {
        $variations = $this->gongzoProductVariations($product->slug);
        $variations = $variations['variations'];

        foreach ($variations as $variation) {
            if (Variation::where(['slug' => $variation['variation_code'], 'product_id' => $product->id, 'api_id' => $product->api_id])->count() > 0) {
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

    public function gongzoProductVariations($product_slug){
        $variations = [
            'response_description' => '000',
            'variations' => [
                'mtn-sme' => [
                    'variations' => [
                        [
                            'name' => "MTN SME 500M",
                            'price' => 133,
                            'code' => 289,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 500,
                        ],
                        [
                            'name' => "MTN-SME 1GB",
                            'price' => 265,
                            'code' => 290,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 1000,
                        ],
                        [
                            'name' => "MTN-SME 2GB",
                            'price' => 530,
                            'code' => 291,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 2000,
                        ],
                        [
                            'name' => "MTN-SME 3GB",
                            'price' => 795,
                            'code' => 292,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 3000,
                        ],
                        [
                            'name' => "MTN-SME 5GB",
                            'price' => 1325,
                            'code' => 293,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 5000,
                        ],
                        [
                            'name' => "MTN-SME 10GB",
                            'price' => 2650,
                            'code' => 294,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 10000,
                        ],

                    ],
                ],

                'mtn-cg' => [
                    'variations' => [
                        [
                            'name' => "MTN CG 500M",
                            'price' => 130,
                            'code' => 280,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 500,
                        ],
                        [
                            'name' => "MTN-CG 1GB",
                            'price' => 259,
                            'code' => 281,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 1000,
                        ],
                        [
                            'name' => "MTN-CG 2GB",
                            'price' => 520,
                            'code' => 282,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 2000,
                        ],
                        [
                            'name' => "MTN-CG 3GB",
                            'price' => 780,
                            'code' => 283,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 3000,
                        ],
                        [
                            'name' => "MTN-CG 5GB",
                            'price' => 1300,
                            'code' => 284,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 5000,
                        ],
                        [
                            'name' => "MTN-CG 10GB",
                            'price' => 2600,
                            'code' => 285,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 10000,
                        ],

                        [
                            'name' => "MTN-CG 15GB",
                            'price' => 3900,
                            'code' => 286,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 15000,
                        ],
                        [
                            'name' => "MTN-CG 20GB",
                            'price' => 5200,
                            'code' => 287,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 20000,
                        ],
                        [
                            'name' => "MTN-CG 40GB",
                            'price' => 10400,
                            'code' => 288,
                            'id' => '',
                            'fixedPrice' => 'yes',
                            'val' => 20000,
                        ],

                    ],
                ],

                'airtel-cg' => [
                    'variations' => [
                        [
                            'name' => "AIRTEL CG 500MB - 7days",
                            'price' => 140,
                            'code' => 312,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data312',
                            'val' => '500',
                        ],
                        [
                            'name' => "AIRTEL CG 1GB - 30days",
                            'price' => 280,
                            'code' => 313,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data313',
                            'val' => '1000',

                        ],
                        [
                            'name' => "AIRTEL CG 2GB - 30days",
                            'price' => 560,
                            'code' => 314,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data314',
                            'val' => '2000',
                        ],
                        [
                            'name' => "AIRTEL CG 5GB - 30days",
                            'price' => 1400,
                            'code' => 315,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data315',
                            'val' => '5000',

                        ],
                        [
                            'name' => "AIRTEL CG 10GB - 30days",
                            'price' => 2800,
                            'code' => 317,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data317',
                            'val' => '10000',

                        ],
                        [
                            'name' => "AIRTEL CG 300MB - 1 DAY",
                            'price' => 84,
                            'code' => 318,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data318',
                            'val' => '300',

                        ],
                        [
                            'name' => "AIRTEL CG 100MB - 1 DAY",
                            'price' => 28,
                            'code' => 319,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data319',
                            'val' => '200',

                        ],
                        [
                            'name' => "AIRTEL CG 15GB - 1 MONTH",
                            'price' => 4200,
                            'code' => 353,
                            'fixedPrice' => 'yes',
                            'val' => '200',

                        ],
                    ],
                ],

                'airtel' => [
                    'variations' => [
                        [
                            'name' => "	AIRTEL GIFTING 750.0 MB - 14DAYS",
                            'price' => 475,
                            'code' => 40,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data40',
                            'val' => '750',
                        ],
                        [
                            'name' => "AIRTEL GIFTING 3.0 GB - MONTHLY",
                            'price' => 1425,
                            'code' => 45,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data45',
                            'val' => '3000',
                        ],
                        [
                            'name' => "AIRTEL GIFTING 2.0 GB - MONTHLY",
                            'price' => 1140,
                            'code' => 46,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data46',
                            'val' => '2000',
                        ],
                        [
                            'name' => "AIRTEL GIFTING 20.0 GB - MONTHLY",
                            'price' => 4750,
                            'code' => 47,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data47',
                            'val' => '20000',
                        ],
                        [
                            'name' => "AIRTEL GIFTING 40.0 GB - MONTHLY",
                            'price' => 9500,
                            'code' => 48,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data48',
                            'val' => '40000',
                        ],
                        [
                            'name' => "AIRTEL GIFTING 1.5 GB - MONTHLY",
                            'price' => 950,
                            'code' => 144,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data144',
                            'val' => '1500',
                        ],
                        [
                            'name' => "AIRTEL GIFTING 4.5 GB - MONTHLY",
                            'price' => 1900,
                            'code' => 145,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data145',
                            'val' => '4500',
                        ],
                        [
                            'name' => " AIRTEL GIFTING 10.0 GB - MONTHLY",
                            'price' => 2850,
                            'code' => 146,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data146',
                            'val' => '4500',
                        ],
                        [
                            'name' => "AIRTEL GIFTING 6.0 GB - MONTHLY",
                            'price' => 2375,
                            'code' => 296,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data296',
                            'val' => '6000',
                        ],
                        [
                            'name' => "AIRTEL GIFTING 75.0 GB - MONTHLY",
                            'price' => 14250,
                            'code' => 297,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data297',
                            'val' => '6000',
                        ],
                        [
                            'name' => "AIRTEL GIFTING 120.0 GB - MONTHLY",
                            'price' => 18600,
                            'code' => 298,
                            'fixedPrice' => 'yes',
                            'id' => 'gongzo_data298',
                            'val' => '120000',
                        ],
                    ]
                ],

                'glo-cg' => [
                    'variations' => [
                        [
                            'name' => "GLO (CG) 1.0 GB - 30days",
                            'price' => 245,
                            'code' => 321,
                            'fixedPrice' => 'yes',
                            'val' => 1000,
                            'id' => 'gongzo_data321',
                        ],
                        [
                            'name' => "GLO (CG) 2.0 GB - 30days",
                            'price' => 490,
                            'code' => 322,
                            'fixedPrice' => 'yes',
                            'val' => 2000,
                            'id' => 'gongzo_data322',
                        ],
                        [
                            'name' => "GLO (CG) 3.0 GB - 30days",
                            'price' => 735,
                            'code' => 323,
                            'fixedPrice' => 'yes',
                            'val' => 3000,
                            'id' => 'gongzo_data323',
                        ],
                        [
                            'name' => "GLO (CG) 5.0 GB - 30days",
                            'price' => 1225,
                            'code' => 324,
                            'fixedPrice' => 'yes',
                            'val' => 5000,
                            'id' => 'gongzo_data324',
                        ],
                        [
                            'name' => "GLO (CG) 10.0 GB - 30days",
                            'price' => 2450,
                            'code' => 325,
                            'fixedPrice' => 'yes',
                            'val' => 10000,
                            'id' => 'gongzo_data325',
                        ],
                        [
                            'name' => "GLO (CG) 500MB (CG) - 14days",
                            'price' => 123,
                            'code' => 326,
                            'fixedPrice' => 'yes',
                            'val' => 500,
                            'id' => 'gongzo_data326',
                        ],
                        [
                            'name' => "GLO 500MB (CG) - 30days",
                            'price' => 123,
                            'code' => 327,
                            'fixedPrice' => 'yes',
                            'val' => 500,
                            'id' => 'gongzo_data327',
                        ],
                        [
                            'name' => "GLO 200MB (CG) - 14days",
                            'price' => 44,
                            'code' => 337,
                            'fixedPrice' => 'yes',
                            'val' => 200,
                            'id' => 'gongzo_data337',
                        ],
                    ]
                ],

                'glo' => [
                    'variations' => [
                        [
                            'name' => "GLO 1.3GB",
                            'price' => 485,
                            'code' => 252,
                            'fixedPrice' => 'yes',
                            'val' => 1300,
                            'id' => 'glo_105',
                        ],
                        [
                            'name' => "GLO 2.9GB",
                            'price' => 970,
                            'code' => 253,
                            'fixedPrice' => 'yes',
                            'val' => 2900,
                            'id' => 'glo_29',
                        ],
                        [
                            'name' => "GLO 4.1GB",
                            'price' => 1455,
                            'code' => 254,
                            'fixedPrice' => 'yes',
                            'val' => 4100,
                            'id' => 'glo_41',
                        ],
                        [
                            'name' => "GLO 5.2GB",
                            'price' => 1940,
                            'code' => 255,
                            'fixedPrice' => 'yes',
                            'val' => 5200,
                            'id' => 'glo_41',
                        ],
                        [
                            'name' => "GLO 7.7GB",
                            'price' => 2425,
                            'code' => 256,
                            'fixedPrice' => 'yes',
                            'val' => 7700,
                            'id' => 'glo_58',
                        ],
                        [
                            'name' => "GLO 10GB",
                            'price' => 2910,
                            'code' => 257,
                            'fixedPrice' => 'yes',
                            'val' => 10000,
                            'id' => 'glo_10',
                        ],
                        [
                            'name' => "GLO 13.25GB",
                            'price' => 3800,
                            'code' => 258,
                            'fixedPrice' => 'yes',
                            'val' => 13250,
                            'id' => 'glo_1325',
                        ],
                        [
                            'name' => "GLO 18.25GB",
                            'price' => 4750,
                            'code' => 259,
                            'fixedPrice' => 'yes',
                            'val' => 18250,
                            'id' => 'glo_1825',
                        ],
                        [
                            'name' => "GLO 29G",
                            'price' => 7760,
                            'code' => 260,
                            'fixedPrice' => 'yes',
                            'val' => 29000,
                            'id' => 'glo_295',
                        ],
                        [
                            'name' => "GLO 50GB",
                            'price' => 9700,
                            'code' => 261,
                            'fixedPrice' => 'yes',
                            'val' => 50000,
                            'id' => 'glo_50',
                        ],
                        [
                            'name' => "GLO 93GB",
                            'price' => 14550,
                            'code' => 262,
                            'fixedPrice' => 'yes',
                            'val' => 93000,
                            'id' => 'glo_93',
                        ],
                        [
                            'name' => "GLO 119GB",
                            'price' => 17460,
                            'code' => 263,
                            'fixedPrice' => 'yes',
                            'val' => 119000,
                            'id' => 'glo_93',
                        ],
                        [
                            'name' => "GLO 138GB",
                            'price' => 19400,
                            'code' => 264,
                            'fixedPrice' => 'yes',
                            'val' => 138000,
                            'id' => 'glo_93',
                        ],
                        [
                            'name' => "GLO SPECIAL 7GB - 1500 7 DAYS",
                            'price' => 1445,
                            'code' => 295,
                            'fixedPrice' => 'yes',
                            'val' => 7000,
                            'id' => 'glo_93',
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

    public function getOldVariations($product)
    {
        $category = $product->category;
        $productLevel = CustomerLevel::pluck('id')->toArray();
        $old_identifier = $product->old_identifier;
    
        // Data products
        if (isset($product->slug) && $product->slug == 'mtn-sme') {
            // Get variations
            // Loop through variations and create each variation
            $response = \DB::connection('old')->table($old_identifier)->select('mtnsme_500', 'mtnsme_1', 'mtnsme_2', 'mtnsme_3', 'mtnsme_5', 'mtnsme_10')->first();
            //create and update variation
            $data = [
                [
                    'name' => "MTN SME 500M",
                    'price' => $response->mtnsme_500,
                    'code' => 289,
                    'id' => '',
                    'fixedPrice' => 'yes',
                    'val' => 500,
                ],
                [
                    'name' => "MTN-SME 1GB",
                    'price' => $response->mtnsme_1,
                    'code' => 290,
                    'id' => '',
                    'fixedPrice' => 'yes',
                    'val' => 1000,
                ],
                [
                    'name' => "MTN-SME 2GB",
                    'price' => $response->mtnsme_2,
                    'code' => 291,
                    'id' => '',
                    'fixedPrice' => 'yes',
                    'val' => 2000,
                ],
                [
                    'name' => "MTN-SME 3GB",
                    'price' => $response->mtnsme_3,
                    'code' => 292,
                    'id' => '',
                    'fixedPrice' => 'yes',
                    'val' => 3000,
                ],
                [
                    'name' => "MTN-SME 5GB",
                    'price' => $response->mtnsme_5,
                    'code' => 293,
                    'id' => '',
                    'fixedPrice' => 'yes',
                    'val' => 5000,
                ],
                [
                    'name' => "MTN-SME 10GB",
                    'price' => $response->mtnsme_10,
                    'code' => 294,
                    'id' => '',
                    'fixedPrice' => 'yes',
                    'val' => 10000,
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
                    $response = \DB::connection('old')->table($old_identifier)->select('mtnsme_500', 'mtnsme_1', 'mtnsme_2', 'mtnsme_3', 'mtnsme_5', 'mtnsme_10')->where('id', $level)->first();

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

        if (isset($product->slug) && $product->slug == 'airtel-cg') {
            $response = \DB::connection('old')->table('airtelcgdata_plan')->select('gongzo_data312', 'gongzo_data313', 'gongzo_data314', 'gongzo_data315', 'gongzo_data317', 'gongzo_data318', 'gongzo_data319')->first();
            //create and update variation
            $data = [
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
                    $response = \DB::connection('old')->table('airtelcgdata_plan')->select('gongzo_data312', 'gongzo_data313', 'gongzo_data314', 'gongzo_data315', 'gongzo_data317', 'gongzo_data318', 'gongzo_data319')->where('id', $level)->first();

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
            $response = \DB::connection('old')->table('airteldata_plan')->select('gongzo_data40', 'gongzo_data45', 'gongzo_data46', 'gongzo_data47', 'gongzo_data48', 'gongzo_data144', 'gongzo_data145', 'gongzo_data146', 'gongzo_data296', 'gongzo_data297', 'gongzo_data298')->first();
            //create and update variation

            $data = [
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
                    $response = \DB::connection('old')->table('airteldata_plan')->select('gongzo_data40', 'gongzo_data45', 'gongzo_data46', 'gongzo_data47', 'gongzo_data48', 'gongzo_data144', 'gongzo_data145', 'gongzo_data146', 'gongzo_data296', 'gongzo_data297', 'gongzo_data298')->where('id', $level)->first();

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
            $response = \DB::connection('old')->table('glocgdata_plan')->select('gongzo_data321', 'gongzo_data322', 'gongzo_data323', 'gongzo_data324', 'gongzo_data325', 'gongzo_data326', 'gongzo_data327', 'gongzo_data337')->first();

            $data = [
                [
                    'name' => "GLO (CG) 1.0 GB - 30days",
                    'price' => $response->gongzo_data321,
                    'code' => 321,
                    'val' => 1000,
                    'id' => 'gongzo_data321',
                ],
                [
                    'name' => "GLO (CG) 2.0 GB - 30days",
                    'price' => $response->gongzo_data322,
                    'code' => 322,
                    'val' => 2000,
                    'id' => 'gongzo_data322',
                ],
                [
                    'name' => "GLO (CG) 3.0 GB - 30days",
                    'price' => $response->gongzo_data323,
                    'code' => 323,
                    'val' => 3000,
                    'id' => 'gongzo_data323',
                ],
                [
                    'name' => "GLO (CG) 5.0 GB - 30days",
                    'price' => $response->gongzo_data324,
                    'code' => 324,
                    'val' => 5000,
                    'id' => 'gongzo_data324',
                ],
                [
                    'name' => "GLO (CG) 10.0 GB - 30days",
                    'price' => $response->gongzo_data325,
                    'code' => 325,
                    'val' => 10000,
                    'id' => 'gongzo_data325',
                ],
                [
                    'name' => "GLO (CG) 500MB (CG) - 14days",
                    'price' => $response->gongzo_data326,
                    'code' => 326,
                    'val' => 500,
                    'id' => 'gongzo_data326',
                ],
                [
                    'name' => "GLO 500MB (CG) - 30days",
                    'price' => $response->gongzo_data327,
                    'code' => 327,
                    'val' => 500,
                    'id' => 'gongzo_data327',
                ],
                [
                    'name' => "GLO 200MB (CG) - 14days",
                    'price' => $response->gongzo_data337,
                    'code' => 337,
                    'val' => 200,
                    'id' => 'gongzo_data337',
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
                    $response = \DB::connection('old')->table('glocgdata_plan')->select('gongzo_data321', 'gongzo_data322', 'gongzo_data323', 'gongzo_data324', 'gongzo_data325', 'gongzo_data326', 'gongzo_data327', 'gongzo_data337')->where('id', $level)->first();

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
            $response = \DB::connection('old')->table('glodata_plan')->select('glo_105', 'glo_29', 'glo_41', 'glo_58', 'glo_77', 'glo_10', 'glo_1325', 'glo_1825', 'glo_295', 'glo_50', 'glo_93')->first();

            $data = [
                [
                    'name' => "GLO 1.03GB",
                    'price' => $response->glo_105,
                    'code' => 252,
                    'val' => 1000,
                    'id' => 'glo_105',
                ],
                [
                    'name' => "GLO 2.9GB",
                    'price' => $response->glo_29,
                    'code' => 253,
                    'val' => 2900,
                    'id' => 'glo_29',
                ],
                [
                    'name' => "GLO 4.1GB",
                    'price' => $response->glo_41,
                    'code' => 254,
                    'val' => 4100,
                    'id' => 'glo_41',
                ],
                [
                    'name' => "GLO 5.8GB",
                    'price' => $response->glo_58,
                    'code' => 256,
                    'val' => 5800,
                    'id' => 'glo_58',
                ],
                [
                    'name' => "GLO 7.7GB",
                    'price' => $response->glo_77,
                    'code' => 205,
                    'val' => 7700,
                    'id' => 'glo_77',
                ],
                [
                    'name' => "GLO 10GB",
                    'price' => $response->glo_10,
                    'code' => 257,
                    'val' => 10000,
                    'id' => 'glo_10',
                ],
                [
                    'name' => "GLO 13.25GB",
                    'price' => $response->glo_1325,
                    'code' => 258,
                    'val' => 13250,
                    'id' => 'glo_1325',
                ],
                [
                    'name' => "GLO 18.25GB",
                    'price' => $response->glo_1825,
                    'code' => 259,
                    'val' => 18250,
                    'id' => 'glo_1825',
                ],
                [
                    'name' => "GLO 29G",
                    'price' => $response->glo_295,
                    'code' => 260,
                    'val' => 29000,
                    'id' => 'glo_295',
                ],
                [
                    'name' => "GLO 50GB",
                    'price' => $response->glo_50,
                    'code' => 261,
                    'val' => 50000,
                    'id' => 'glo_50',
                ],
                [
                    'name' => "GLO 93GB",
                    'price' => $response->glo_93,
                    'code' => 262,
                    'val' => 93000,
                    'id' => 'glo_93',
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
                    $response = \DB::connection('old')->table('glodata_plan')->select('glo_105', 'glo_29', 'glo_41', 'glo_58', 'glo_77', 'glo_10', 'glo_1325', 'glo_1825', 'glo_295', 'glo_50', 'glo_93')->where('id', $level)->first();

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

    // public function prepareParams(Request $request){
    //     $postdata = $this->getPostData($request->all());
       
    //     $headers = [
    //         'Authorization: Token 3b24d3f969dd1aa39cc434ada0c4de75f54e4948',
    //         'Content-Type: application/json'
    //     ];

    //     // Save post fields
    //     $pstfields = [
    //         'url' =>  $postdata['url'],
    //         'payload' => $postdata,
    //     ];
       
    //     $params = [
    //         'url' => $postdata['url'],
    //         'headers' => $headers,
    //         'postfields' => $pstfields,
    //         'payload' => Arr::except($postdata, 'url'),
    //         'provider' => 'GONGZO',
    //     ];
        
    //     return $params;
    // }

    public function getPostData($request, $api){
        $slug = $request['product']->slug ?? null;
        $resource = $request['resource'] ?? null;

        if ($slug  == 'mtn-cg' || $slug == 'gifting' || $slug == 'mtn-sme') {
            $ntwk = 1;
            $url = $api->live_base_url . "data/";
        } elseif ($slug == 'airtel-cg') {
            $ntwk = 4;
            $url = $api->live_base_url . "data/";
        } elseif ($slug == 'airtel') {
            $ntwk = 4;
            $url = $api->live_base_url . "data/";
        } elseif ($slug == 'glo-cg') {
            $ntwk = 2;
            $url = $api->live_base_url . "data/";
        } elseif ($slug == 'glo') {
            $ntwk = 2;
            $url = $api->live_base_url . "data/";
        } else if ($slug == 'mtn' || $slug == 'mtn-sme') {
            $ntwk = 1;
            $url = $api->live_base_url . "data/";
        }
       
        $postdata = [
            'url' => $url ?? $api->live_base_url . "data/",
            'network' => $ntwk,
            'plan' => $resource->slug ?? $request['buyer_service'],
            'mobile_number' => $request['mobile_number'],
            'Ported_number' => true,
            'quantity' => $request['quantity'] ?? 1,
        ];
        
        return $postdata;
    }

    public function query($data, $api){
        $headers = [
            "Authorization: Token " . $api->api_key . "",
            'Content-Type: application/json'
        ];
        
        $pstfields = $this->getPostData($data, $api);
       
        // if (env('ENT') == 'local') {
        //     $resX = '{"id":19263669,"ident":"Data22d134b73-a60","payment_medium":"MAIN WALLET","network":1,"apiresponse":"Dear Customer, You have gifted 3072MB, please dial *323*2*5# to check your balance. Thankyou.","api_response":"Dear Customer, You have gifted 3072MB, please dial *323*2*5# to check your balance. Thankyou.","balance_before":"62185.88000000008","balance_after":"61411.88000000008","mobile_number":"09135759113","plan":283,"Status":"SUCCESSFUL","plan_network":"MTN","plan_name":"3.0GB","plan_amount":"774.0","create_date":"2024-05-31T18:51:00.642075","Ported_number":true}';
        // } else {
        $resX = $this->advancedApiCall($pstfields['url'], json_encode($pstfields), $headers);
        // }
        // dd($resX, $postdata)
        // $resX = '{"id":8796051,"ident":"Data3c8b22038-740","network":1,"apiresponse":"something went wrong please check back later","api_response":"something went wrong please check back later","balance_before":"3938.0","balance_after":"3711.0","mobile_number":"08149292337","plan":281,"Status":"success","plan_network":"MTN","plan_name":"1.0GB","plan_amount":"227.0","create_date":"2023-08-21T20:56:25.563155","Ported_number":true}';

        // '{"id":19292559,"ident":"Data102a1dfaca-cbe","payment_medium":"MAIN WALLET","network":1,"apiresponse":"You are not sending to valid MTN number.","api_response":"You are not sending to valid MTN number.","balance_before":"2779.9800000000832","balance_after":"2649.4800000000832","mobile_number":"08180010243","plan":289,"Status":"failed","plan_network":"MTN","plan_name":"500.0MB","plan_amount":"130.5","create_date":"2024-06-01T18:43:51.781740","Ported_number":true}'

        try {
            $format = $this->processApiResponse($resX, $data);

            $format['postfields'] = $pstfields;      
            $format['api_response'] = $resX;          
        
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

    public function processApiResponse($res,$request){
       
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
                $result = json_decode($res, true);
                
                if($request['product']->slug == 'mtn' && $request['category']->slug == 'data'){
                    $result['Status'] = isset($result['Status']) ? strtoupper($result['Status']) : null;

                    if (isset($result['Status']) && $result['Status'] !== 'FAILED') {
                        if ($result['Status'] == 'PROCESSING') {
                            $result['apiresponse'] = '';
                        }
                        $status = 'success';
                        $true_response = 'You have successfully shared data to ' . $request['mobile_number'] . '. ' . $result['api_response'];
                    } else {
                        $status = 'failed';
                        $true_response = 'Something went wrong. ' . $result['api_response'];
                    }
                }else{
                    try {
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
                    } catch (\Throwable $th) {}
                    
                    $true_response = isset($result['api_response']) ? str_replace("'", '', $result['api_response']) : '';
                    
                    $result['Status'] = isset($result['Status']) ? strtoupper($result['Status']) : null;
                    
                    if ((isset($result['Status']) && $result['Status'] !== 'FAILED' && !empty($result['Status']) || (isset($result['Status']) && $result['Status'] == 'NULL'))) {
                        if ($result['Status'] == 'PROCESSING') {
                            $true_response = '';
                        }
                        $status = 'success';
                    } else {
                        $status = 'failed';
                        $true_response = $result['error'][0] ?? ($true_response ?? '');
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
                    'error' => $th->getMessage() . ' ' . $th->getFile() . ' ' . $th->getLine(),
                ];
    
            }
        }
       
        return $formatted;
    }
}
