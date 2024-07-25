<?php

namespace App\Http\Controllers\Providers;

use App\Models\Discount;
use App\Models\Variation;
use App\Models\WebSetting;
use App\Http\Controllers\Controller;
use App\Models\CustomerLevel;

class GraphicController extends Controller
{
    public function getVariations($product)
    {
        return $this->getOldVariations($product);      
    }

    public function getOldVariations($product)
    {
        $category = $product->category;
        $productLevel = CustomerLevel::pluck('id')->toArray();
        $old_identifier = 'graphics_planlist';
        
        // Data products
        if (isset($product->slug) && $product->slug == 'flyer' || $product->slug == 'flyer') {
            // Get variations
            // Loop through variations and create each variation
            $response = \DB::connection('old')->table($old_identifier)->select('Corporate', 'Individual')->first();
            //create and update variation
            $data = [
                [
                    'name' => "Individual",
                    'price' => $response->Individual,
                    'variation_code' => 'individual',
                    'fixedPrice' => 'yes',
                ],
                [
                    'name' => "Corporate",
                    'price' => $response->Corporate,
                    'variation_code' => 'corporate',
                    'fixedPrice' => 'yes',
                ],
            ];
            
            foreach ($data as $d) {
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'api_name' => $d['name'],
                    'slug' => $d['variation_code'],
                    'api_id' => $product->api_id,

                ], [
                    'product_id' => $product->id,
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
                    $response = \DB::connection('old')->table($old_identifier)->select('Corporate', 'Individual')->where('id',$level)->first();

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

        // Data card
        return back()->with('Old Variations pulled successfully');
    }

    public function query($request, $api){
        $settings = WebSetting::first();
        
        $subject = "A New Business Has Arrived !!!";
    
        $body ='<p>Hello Admin !</p>';
        $body .='<p>My Name Is '.auth()->user()->fullname.'!</p>';
        $body .='<p>I just proccessed a request for flyers for my business, please find below my business information.</p>';
        $body .='<p><strong>Business Name:</strong> '.$request['business_name'].'!</p>';
        $body .= '<p><strong>Business Type:</strong> '. $request['buyer_service'].'!</p>';
        $body .= '<p><strong>Business Email: </strong>'. $request['business_email'].'!</p>';
        $body .= '<p><strong>Business Lines: </strong>'. $request['business_line'].'!</p>';
        $body .= '<p><strong>Business Content:</strong> '. $request['business_content'].'!</p>';
        
        $resX= sendNativeMail($settings->admin_notification_email, $subject, $body);
        
        try {
            $format = [
                'status' => 'success',
                'api_response' => '',
                'postfields' => $body,
                'response' => 'Purchase of flyer for your '. $request['buyer_service'] .' business has been received !!!',
            ];    
        } catch (\Throwable $th) {
            $format = [
                'api_response' => '',         
                'postfields' => $body,
                'status' => 'attention-required',
                'response' => '',
                'error' => $th->getMessage(). '. File: '.$th->getFile().'. Line:'.$th->getLine(),
            ];
            
            return $format;
        }

        return $format;
    }

    public function requery($transaction){

    }
}
