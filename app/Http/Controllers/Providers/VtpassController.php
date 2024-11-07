<?php

namespace App\Http\Controllers\Providers;

use App\Models\Discount;
use App\Models\Variation;
use App\Models\BillersLog;
use App\Models\WebSetting;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use App\Http\Controllers\Controller;

class VtpassController extends Controller
{
    public function getOldVariations($product)
    {
        $category = $product->category;
        $productLevel = CustomerLevel::pluck('id')->toArray();
        $old_identifier = $product->old_identifier;
        $environment = env('ENT');
        // dd($type, $product, $level, $plan_id);
        if (isset($product->slug) && $product->slug == 'spectranet-data') {
            $response =  \DB::connection('old')->table('spectranetdata_plan')->select('spectranet1', 'spectranet2', 'spectranet3')->first();

            $data = [
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

        foreach ($data as $d) {
                if(Variation::where('slug', $d['variation_code'])->count() > 0){
                    continue;
                }
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_id' => $product->api->id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['variation_code'],
                ], [
                    'product_id' => $product->id,
                    'api_id' => $product->api->id,
                    'category_id' => $product->category_id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['variation_code'],
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
                    $response = \DB::connection('old')->table('spectranetdata_plan')->select('spectranet1', 'spectranet2', 'spectranet3')->where('id', $level)->first();

                    foreach ($response as $key => $value) {
                        $discount = Discount::updateOrCreate([
                            'customer_level' => $level,
                            'variation_id' => $variation->id
                        ], [
                            'customer_level' => $level,
                            'variation_id' => $variation->id,
                            'price' => $value ?? null,
                        ]);
                    }
                }
            }
        }

        if ($product->category->slug == "electricity") {
            $data = [
                [
                    'name' => 'Prepaid',
                    'variation_code' => 'prepaid',
                    'fixedPrice' => 'no',
                    'price' => null,
                ],
                [
                    'name' => 'Postpaid',
                    'variation_code' => 'postpaid',
                    'fixedPrice' => 'no',
                    'price' => null,
                ],
            ];

            foreach ($data as $d) {
                if(Variation::where('slug', $d['variation_code'])->count() > 0){
                        continue;
                }
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_id' => $product->api->id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['variation_code'],
                ], [
                    'product_id' => $product->id,
                    'api_id' => $product->api->id,
                    'category_id' => $product->category_id,
                    'api_name' =>  $d['name'],
                    'slug' => $d['variation_code'],
                    'api_id' => $product->api_id,
                    'system_name' => $d['name'],
                    'fixed_price' => $d['fixedPrice'] ?? 'no',
                    'api_price' => $d['price'],
                    'system_price' => $d['price'],
                    'min' => null,
                    'max' =>  null,
                    'status' => 'active'
                ]);

                foreach ($productLevel as $level) {
                    $response = \DB::connection('old')->table('spectranetdata_plan')->select('spectranet1', 'spectranet2', 'spectranet3')->where('id', $level)->first();

                    foreach ($response as $key => $value) {
                        $discount = Discount::updateOrCreate([
                            'customer_level' => $level,
                            'variation_id' => $variation->id
                        ], [
                            'customer_level' => $level,
                            'variation_id' => $variation->id,
                            'price' => $value ?? null,
                        ]);
                    }
                }
            }
        }

        if($product->category->slug  == 'cable') {
            if ($product->slug == "dstv") {
                $response =  \DB::connection('old')->table('dstv_planlist')->select('dstv_1', 'dstv_2', 'dstv_3', 'dstv_4', 'dstv_5', 'dstv_6', 'dstv_7', 'dstv_8', 'dstv_9', 'dstv_10', 'dstv_11', 'dstv_12', 'dstv_discount')->first();
                
                $data = [
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv Padi',
                        'variation_code' => 'dstv-padi',
                        'price' => $response->dstv_1,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv Yanga',
                        'variation_code' => 'dstv-yanga',
                        'price' => $response->dstv_2,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv Confam',
                        'variation_code' => 'dstv-confam',
                        'price' => $response->dstv_3,
                        'val' => '',
                    ],


                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv Padi Extra',
                        'variation_code' => 'padi-extra',
                        'price' => $response->dstv_4,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Yanga Extra',
                        'variation_code' => 'yanga-extra',
                        'price' => $response->dstv_5,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv ASIA',
                        'variation_code' => 'dstv6',
                        'price' => $response->dstv_6,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv Confam EXTRA',
                        'variation_code' => 'confam-extra',
                        'price' => $response->dstv_7,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv COMPACT',
                        'variation_code' => 'dstv79',
                        'price' => $response->dstv_8,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'DSTV COMPACT PLUS',
                        'variation_code' => 'dstv7',
                        'price' => $response->dstv_9,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv Extra View Access',
                        'variation_code' => 'extraview-access',
                        'price' => $response->dstv_10,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv Premium',
                        'variation_code' => 'dstv10',
                        'price' => $response->dstv_11,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'dstv',
                        'name' => 'Dstv Premium Asia',
                        'variation_code' => 'dstv10',
                        'price' => $response->dstv_12,
                        'val' => '',
                    ],
                ];

                foreach ($data as $d) {
                    if (Variation::where('slug', $d['variation_code'])->count() > 0) {
                        continue;
                    }
                    $variation = Variation::updateOrCreate(
                        [
                            'api_id' => $product->api->id,
                            'product_id' => $product->id,
                            'api_name' =>  $d['name'],
                            'slug' => $d['variation_code'],
                        ],[
                        'api_id' => $product->api->id,
                        'product_id' => $product->id,
                        'api_name' =>  $d['name'],
                        'slug' => $d['variation_code'],
                        'category_id' => $product->category_id,
                        'api_name' =>  $d['name'],
                        'system_name' => $d['name'],
                        'fixed_price' => $d['fixedPrice'] ?? 'yes',
                        'api_price' => $d['price'],
                        'system_price' => $d['price'],
                        'min' => null,
                        'max' =>  null,
                        'status' => 'active'
                    ]);

                    foreach ($productLevel as $level) {
                        $response = \DB::connection('old')->table('dstv_planlist')->select('dstv_1', 'dstv_2', 'dstv_3', 'dstv_4', 'dstv_5', 'dstv_6', 'dstv_7', 'dstv_8', 'dstv_9', 'dstv_10', 'dstv_11', 'dstv_12', 'dstv_discount')->where('id', $level)->first();

                        foreach ($response as $key => $value) {
                            $discount = Discount::updateOrCreate([
                                'customer_level' => $level,
                                'variation_id' => $variation->id
                            ], [
                                'customer_level' => $level,
                                'variation_id' => $variation->id,
                                'price' => $value ?? null,
                            ]);
                        }
                    }
                }

            }

            if ($product == "gotv") {
                $data =  \DB::connection('old')->table('gotv_planlist')->where('id', $level)->select('gotv_1', 'gotv_2', 'gotv_3', 'gotv_4', 'gotv_5', 'gotv_discount')->first();

                $data = [
                    [
                        'serviceId' => 'gotv',
                        'name' => 'GOTV SUPA',
                        'variation_code' => 'gotv-supa',
                        'price' => $response->gotv_1,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'gotv',
                        'name' => 'GOTV MAX',
                        'variation_code' => 'gotv-max',
                        'price' => $response->gotv_2,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'gotv',
                        'name' => 'GOTV JINJA',
                        'variation_code' => 'gotv-jinja',
                        'price' => $response->gotv_4,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'gotv',
                        'name' => 'GOTV JOLLI',
                        'variation_code' => 'gotv-jolli',
                        'price' => $response->gotv_3,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'gotv',
                        'name' => 'GOTV SMALLIE',
                        'variation_code' => 'gotv-smallie',
                        'price' => $response->gotv_5,
                        'val' => '',
                    ],
                ];

                foreach ($data as $d) {
                    if(Variation::where('slug', $d['variation_code'])->count() > 0){
                        continue;
                    }
                    $variation = Variation::updateOrCreate([
                        'product_id' => $product->id,
                        'api_id' => $product->api->id,
                        'api_name' =>  $d['name'],
                        'slug' => $d['variation_code'],
                    ],[
                        'product_id' => $product->id,
                        'api_id' => $product->api->id,
                        'api_name' =>  $d['name'],
                        'slug' => $d['variation_code'],
                        'category_id' => $product->category_id,
                        'system_name' => $d['name'],
                        'fixed_price' => $d['fixedPrice'] ?? 'yes',
                        'api_price' => $d['price'],
                        'system_price' => $d['price'],
                        'min' => null,
                        'max' =>  null,
                        'status' => 'active'
                    ]);

                    foreach ($productLevel as $level) {
                        $response = \DB::connection('old')->table('gotv_planlist')->where('id', $level)->select('gotv_1', 'gotv_2', 'gotv_3', 'gotv_4', 'gotv_5', 'gotv_discount')->where('id', $level)->first();

                        foreach ($response as $key => $value) {
                            $discount = Discount::updateOrCreate([
                                'customer_level' => $level,
                                'variation_id' => $variation->id
                            ], [
                                'customer_level' => $level,
                                'variation_id' => $variation->id,
                                'price' => $value ?? null,
                                'status' => 'active'
                            ]);
                        }
                    }
                }
            }
            
            if ($product == "showmax") {
                $response = \DB::connection('old')->table('showmax_planlist')->where('id', $level)->select('showmax1', 'showmax2', 'showmax3', 'showmax4', 'showmax_discount')->first();
                
                $data = [
                    [
                        'serviceId' => 'showmax',
                        'name' => 'Showmax FULL',
                        'variation_code' => 'full',
                        'price' => $response->showmax1,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'showmax',
                        'name' => 'Showmax Mobile Only',
                        'variation_code' => 'mobile_only',
                        'price' => $response->showmax2,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'showmax',
                        'name' => 'Showmax Sports Full',
                        'variation_code' => 'sports_full',
                        'price' => $response->showmax3,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'showmax',
                        'name' => 'Showmax Sports Mobile Only',
                        'variation_code' => 'sports_mobile_only',
                        'price' => $response->showmax4,
                        'val' => '',
                    ],
                ];


                foreach ($data as $d) {
                    if(Variation::where('slug', $d['variation_code'])->count() > 0){
                        continue;
                    }
                    $variation = Variation::updateOrCreate([
                        'product_id' => $product->id,
                        'api_id' => $product->api->id,
                        'api_name' =>  $d['name'],
                        'slug' => $d['variation_code'],
                    ],[
                        'product_id' => $product->id,
                        'api_name' =>  $d['name'],
                        'api_id' => $product->api->id,
                        'slug' => $d['variation_code'],
                        'category_id' => $product->category_id,
                        'api_name' =>  $d['name'],
                        'system_name' => $d['name'],
                        'fixed_price' => $d['fixedPrice'] ?? 'yes',
                        'api_price' => $d['price'],
                        'system_price' => $d['price'],
                        'min' => null,
                        'max' =>  null,
                        'status' => 'active'
                    ]);

                    foreach ($productLevel as $level) {
                        $response = \DB::connection('old')->table('showmax_planlist')->where('id', $level)->select('showmax_discount')->where('id', $level)->first();

                        foreach ($response as $key => $value) {
                            $discount = Discount::updateOrCreate([
                                'customer_level' => $level,
                                'variation_id' => $variation->id
                            ], [
                                'customer_level' => $level,
                                'variation_id' => $variation->id,
                                'price' => $value ?? null,
                            ]);
                        }
                    }
                }
            }

            if ($product->slug == "startimes" || $product->slug == "startime") {
                $response =  \DB::connection('old')->table('startime_planlist')->select('star_1', 'star_2', 'star_3', 'star_4', 'star_5', 'star_6', 'star_7', 'star_8', 'star_9', 'star_10', 'star_discount')->first();

                $data = [
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES NOVA WEEKLY',
                        'variation_code' => 'nova-weekly',
                        'price' => $response->star_1,
                        
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES NOVA MONTHLY',
                        'variation_code' => 'nova',
                        'price' => $response->star_2,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES BASIC WEEKLY',
                        'variation_code' => 'basic-weekly',
                        'price' => $response->star_3,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES BASIC MONTHLY',
                        'variation_code' => 'basic',
                        'price' => $response->star_4,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES SMART WEEKLY',
                        'variation_code' => 'smart-weekly',
                        'price' => $response->star_5,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES SMART MONTHLY',
                        'variation_code' => 'smart',
                        'price' => $response->star_6,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES CLASSIC WEEKLY',
                        'variation_code' => 'classic-weekly',
                        'price' => $response->star_7,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES CLASSIC MONTHLY',
                        'variation_code' => 'classic',
                        'price' => $response->star_8,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES SUPER WEEKLY',
                        'variation_code' => 'super-weekly',
                        'price' => $response->star_9,
                        'val' => '',
                    ],
                    [
                        'serviceId' => 'startimes',
                        'name' => 'STARTIMES SUPER MONTHLY',
                        'variation_code' => 'super',
                        'price' => $response->star_10,
                        'val' => '',
                    ],
                ];

                foreach ($data as $d) {
                    if(Variation::where('slug', $d['variation_code'])->count() > 0){
                        continue;
                    }
                    $variation = Variation::updateOrCreate([
                        'product_id' => $product->id,
                        'api_id' => $product->api->id,
                        'api_name' =>  $d['name'],
                        'slug' => $d['variation_code'],
                    ],[
                        'product_id' => $product->id,
                        'api_id' => $product->api->id,
                        'api_name' =>  $d['name'],
                        'slug' => $d['variation_code'],
                        'product_id' => $product->id,
                        'category_id' => $product->category_id,
                        'api_name' =>  $d['name'],
                        'system_name' => $d['name'],
                        'fixed_price' => $d['fixedPrice'] ?? 'yes',
                        'api_price' => $d['price'],
                        'system_price' => $d['price'],
                        'min' => null,
                        'max' =>  null,
                        'status' => 'active'
                    ]);

                    foreach ($productLevel as $level) {
                        $response = \DB::connection('old')->table('startime_planlist')->select('star_discount')->where('id', $level)->first();
                        
                        foreach ($response as $key => $value) {
                            $discount = Discount::updateOrCreate([
                                'customer_level' => $level,
                                'variation_id' => $variation->id
                            ], [
                                'customer_level' => $level,
                                'variation_id' => $variation->id,
                                'price' => $value ?? null,
                                'status' => 'active'
                            ]);
                        }
                    }
                }


            }

        }

        return back()->with('Old Variations pulled successfully');
    }

    public function getVariations($product)
    {
        $variations = $this->vtpassProductVariations($product);
        $variations = json_decode($variations, true);

        $variations = (!empty($variations['response_description']) && !empty($variations['content'])) ? $variations['content'] : [];
        $variations = $variations['variations'] ?? $variations['varations'];
        
        foreach ($variations as $variation) {
            if (Variation::where(['slug' => $variation['variation_code'], 'product_id' => $product->id, 'api_id' => $product->api_id])->count() > 0) {
                continue;
            }
            Variation::updateOrCreate([
                'product_id' => $product['id'],
                'category_id' => $product['category_id'],
                'api_id' => $product['api']['id'],
                'slug' => $variation['variation_code'],
            ], [
                'product_id' => $product['id'],
                'api_id' => $product->api->id,
                'category_id' => $product['category_id'],
                'api_id' => $product['api']['id'],
                'api_name' => $variation['name'],
                'slug' => $variation['variation_code'],
                'system_name' => $variation['name'],
                'fixed_price' => strtolower($variation['fixedPrice']),
                'api_price' => $variation['variation_amount'],
                'system_price' => $variation['variation_amount'],
            ]);
        }

        return true;
    }

    public function vtpassProductVariations($product){
        $api = $product->api;
        $url = env('ENT') == 'local' ? $api->sandbox_base_url : $api->live_base_url;
        $url = $url.'service-variations?serviceID='.$product->slug;
        $response = $this->advancedApiCall($url, [], [], 'GET');
       
        return $response;
       
    }

    public function verify($request, $api){
        $url = env('ENT') == 'local' ? $api->sandbox_base_url : $api->live_base_url;
        $url = $url . 'merchant-verify';
        
        $headers = [
            'api-key: ' . $api->api_key,
            'public-key: ' . $api->public_key,
            'secret-key: ' . $api->secret_key,
        ];

        $postdata = [
            'serviceID' => $request['product']->slug,
            'type' => $request['variation']->slug,
            'billersCode' => $request['billersCode'],
        ];

        $response = $this->advancedApiCall($url, $postdata, $headers, 'POST');
        
        $result = json_decode($response);
        if (isset($result->code) && $result->code == 000 && !empty($result->content) && !empty($result->content->Customer_Name)) {

            if ($request['variation']->category->slug == 'electricity') {
                $button = '<button class="btn btn-success btn-block" onclick="pay4Bills()">Make Payment</button>';
                $address = $result->content->Address ?? '';
                $response = [
                    'status' => 'success',
                    'provider' => 'VTPASS',
                    'status_code' => '200',
                    'customerName' => $result->content->Customer_Name ?? '',
                    'customerAddress' => $address,
                    'button_link' => $button,
                    'message' => 'Account Name: ' . $result->content->Customer_Name . '<br/>Address: ' . $address . ' <br/><br/>' . $button . '',
                    'title' => '<strong>Please confirm that the details are correct before you click make payment</strong>',
                    'raw_response' => $response,
                ];
            }

            if ($request['variation']->category->slug == 'cable') {
                $button = '<button class="btn btn-success btn-block" onclick="pay4Tv()">Make Payment</button>';
                
                // $renewal_amount = !empty($request['request_type']) && $request['request_type'] == 'renewal' ? $result->content->Renewal_Amount : ($request['price']);
                $renewal_amount = $result->content->Renewal_Amount ?? null;
                $message = '';

                if(!empty($result->content->Customer_Name)){
                    $message .= 'Account Name: ' . $result->content->Customer_Name;
                }

                if (!empty($result->content->Status)) {
                    $message .= '<br/>Status: ' . $result->content->Status;
                }

                if (!empty($result->content->Due_Date)) {
                    $message .= '<br/>Due Date:' . $result->content->Due_Date;
                }

                if (!empty($result->content->Current_Bouquet)) {
                    $message .= '<br>Current Bouquet:' . $result->content->Current_Bouquet;
                }

                if (!empty($result->content->Balance)) {
                    $message .= '<br>Balance: N' . number_format($result->content->Balance, 2);
                }

                if (!empty($result->content->Smartcard_Number)) {
                    $message .= '<br>Smart Card Number: ' . $result->content->Smartcard_Number;
                }

                if (!empty($renewal_amount)) {
                    $message .= '<br>Renewal Amount: N' . number_format($renewal_amount, 2);
                }
                
                
                $message .= $button . '<br>';
                
                $response = [
                    'status' => 'success',
                    'provider' => 'VTPASS',
                    'status_code' => '200',
                    'current_bouque_code' => $result->content->Current_Bouquet_Code ?? null,
                    'renewal_amount' => $renewal_amount,
                    'customerName' => $result->content->Customer_Number ?? '',
                    'button_link' => $button,
                    'message' => $message,
                    'title' => '<strong>Please confirm that the details are correct before you click make payment</strong>',
                    'raw_response' => $response,
                ];
            }
            
            if ($request['product']->slug == 'showmax') {
                $response = array(
                    'status' => 'success',
                    'message' =>  '<button class="btn btn-success btn-block" onclick="pay4Tv()">Make Payment</button>',

                );
            }
        } else {
            $error = $result->content->error ?? $result->message;
            $fail_response =  $fail_response = 'Validation Error: ' . $error ?? 'Unable to verify at the moment, please try again';
            
            $response = [
                'status' => 'failed',
                'status_code' => '100',
                'customerName' => '',
                'customerAddress' => '',
                'message' => $fail_response,
                'title' => 'Verification Failed',
                'raw_response' => $response,
            ];
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
                'variation_code' => $request['variation_code'],
                'billersCode' => $request['billersCode'],
                'request_id' => $request['request_id'],
                'amount' => $request['amount'] ?? $request['price'],
                'phone' => $request['mobile_no'],
                'quantity' => $request['quantity'] ?? 1,
            ];
        }

        if ($request['type'] == 'electricity') {
            $postdata = [
                'serviceID' => $request['serviceId'],
                'billersCode' => $request['billersCode'],
                'request_id' => $request['request_id'],
                'variation_code' => $request['service_plan'],
                'amount' => $request['amount'] ?? $request['price'],
                'phone' => $request['mobile_no'],
            ];
        }

        if ($request['type'] == 'cable') {
            $subscription_type = $request->request_type;

            $postdata = [
                'serviceID' => $request['serviceId'],
                'billersCode' => $request['billersCode'],
                'request_id' => $request['request_id'],
                'variation_code' => $request['variation_code'],
                'amount' => $request['amount'] ?? $request['price'],
                'phone' => $request['mobile_no'],
                'subscription_type' => $subscription_type
            ];
        }

        if ($request['type'] == 'epin') {
            $postdata = [
                'serviceID' => $request['serviceId'],
                'billersCode' => $request['billersCode'],
                'request_id' => $request['request_id'],
                'variation_code' => $request['variation_code'],
                'amount' => $request['amount'] ?? $request['price'],
                'phone' => $request['mobile_no'],
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

    public function query($request, $api){
        $url = env('ENT') == 'local' ? $api->sandbox_base_url : $api->live_base_url;
        $url = $url . 'pay';

        $headers = [
            'api-key: ' . $api->api_key,
            'public-key: ' . $api->public_key,
            'secret-key: ' . $api->secret_key,
        ];
        
        if ($request['product']->category->slug == 'cable') {
            $subscription_type = $request['request_type'];
        }

        $resource = $request['resource'];
        // $d = $t+1;
        $postdata = [
            'serviceID' => $request['product']->slug,
            'billersCode' => $request['billersCode'],
            'request_id' => $request['sys_ref'],
            'variation_code' => $resource->slug,
            'amount' => $request['unit_price'],
            'phone' => $request['mobile_number'],
            'subscription_type' => $subscription_type ?? null,
            'quantity' => $request['quantity'] ?? 1,
        ];

        $resX = $this->advancedApiCall($url, $postdata, $headers);
        
        // dd($postdata['url'], $postdata['payload'], $headers, $resX);
        try {
            $format = $this->processApiResponse($resX);
            $format['api_response'] = $resX;
            $format['postfields'] = json_encode($postdata);      

        } catch (\Throwable $th) {

            $format = [
                'api_response' => $resX,         
                'status' => 'attention-required',
                'response' => '',
                $format['postfields'] = json_encode($postdata),     
                'error' => $th->getMessage(). '. File: '.$th->getFile().'. Line:'.$th->getLine(),
            ];
            
            $format['api_response'] = $resX;
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
            $status = 'failed';
            try {
                // Process APi call
                $result = json_decode($res, true);
                $status = $result['content']['transactions']['status'] ?? $status;
                $response_description = $result['response_description'] ?? '';
                $epin = isset($result['purchased_code']) ? $result['purchased_code'] : '';
                $code = $result['code'];

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

    public function requery($transaction)
    {
        $api = $transaction->api;
        $request_id = $transaction->sys_ref;
        
        try {
            $url = env('ENT') == 'local' ? $api->sandbox_base_url : $api->live_base_url;
            $url = $url . "requery";
            
            $headers = [
                'api-key: ' . $api->api_key,
                'public-key: ' . $api->public_key,
                'secret-key: ' . $api->secret_key,
            ];

            $payload = [
                'request_id' => $request_id,
                'url' => $url
            ];

            $response = $this->advancedApiCall($url, $payload, $headers);
            
            $format = $this->processApiResponse($response);
            $format['api_response'] = $response;
            
            return $format;

        }catch (\Throwable $th) {
            $format = [
                'api_response' => $th->getMessage() . '. File: ' . $th->getFile() . '. Line:' . $th->getLine(),
                'status' => 'attention-required',
                'response' => '',
                'error' => $th->getMessage() . '. File: ' . $th->getFile() . '. Line:' . $th->getLine(),
            ];

            $format['api_response'] = $response;
            return $format;
        }
    }

    public function balance($api, $no_format = null)
    {
        try {
            $url = env('ENV') == 'local' ? $api->sandbox_base_url : $api->live_base_url;
            $url = $url . "balance";

            $headers = [
                'api-key: ' . $api->api_key,
                'public-key: ' . $api->public_key,
                'secret-key: ' . $api->secret_key,
            ];

            $response = $this->basicApiCall($url, [], $headers, 'GET');

            if (isset($response['code']) && $response['code'] == 1 && !empty($response['contents'])) {
                $result = $response;
                $balance = '#' . number_format($response['contents']['balance'], 2);
                $status = 'success';
                $status_code = 1;

                $api->update([
                    'balance' => $response['contents']['balance'],
                ]);
            } else {
                $status = 'failed';
                $status_code = 0;
                $balance = null;
            }

            $format = [
                'status' => $status,
                'balance' => $balance,
                'status_code' => $status_code,
            ];
        } catch (\Throwable $th) {
            $format = [
                'status' => 'failed',
                'status_code' => 0,
                'balance' => $th->getMessage() . '. File: ' . $th->getFile() . '. Line:' . $th->getLine(),
            ];
        }

        if (isset($no_format)) {
            $format = [
                'status' => $status,
                'balance' => $response['contents']['balance'] ?? null,
                'status_code' => $status_code,
            ];
        }

        return $format;
    }
}
