<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Discount;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VariationController extends Controller
{
    public function pullVariations(Product $product)
    {
        $api = $product->api;
        Session::put('page', '1');
       
        // Get Variations from Filename
        $variations = app('App\Http\Controllers\Providers\\' . $this->getApiFilename($api))->getVariations($product);

        return back()->with('message', 'Variations pulled successfully');
    }

    public function pullOldVariations(Product $product)
    {
        // Get the product api id
        $api = $product->api;
        Session::put('page', '1');
        
        if($api->slug == 'vtpass'){
            app("App\Http\Controllers\Providers\VtpassController")->getOldVariations($product);
        }
        
        if ($api->slug == 'easyaccess') {
            app("App\Http\Controllers\Providers\EasyAccessController")->getOldVariations($product);
        }

        if ($api->slug == 'gongzo-data') {
            app("App\Http\Controllers\Providers\GongzoController")->getOldVariations($product);
        }
        
        if ($api->slug == 'graphic') {
            app("App\Http\Controllers\Providers\GraphicController")->getOldVariations($product);
        }
        
        return back()->with('message', 'Old Variations pulled successfully');
    }

    public function getCustomerVariations(Product $product)
    {
        $variations = Variation::where('product_id', $product->id)->where('status', 'active')->where('api_id', $product->api_id)->orderBy('system_price', 'ASC')->get();
        
        foreach ($variations as $key => $variation) {
            $discount = getVariationDiscount(amount: $variation->system_price, variation_id: $variation->id);

            $variation->discount = $discount['display_value'];
            if (in_array($variation->category->unique_element, verifiableUniqueElements()) || in_array($variation->slug, array_keys(specialVerifiableVariations()))) {
                $variation->verifiable = 'yes';
            } else {
                $variation->verifiable = 'no';
            }

            if (($variation->fixed_price == 'yes') && empty($variation->system_price) || $variation->system_price < 0) {
                unset($variations[$key]);
            }

            if (in_array($variation->slug, array_keys(specialVerifiableVariations()))) {
                $variation->unique_element = specialVerifiableVariations()[$variation->slug];
            } else {
                $variation->unique_element = $variation->category->unique_element;
            }

        }
        
        $data = [];


        if (\Request::is('mobile/*')) {
            foreach ($variations as $variation) {
                $price = $variation->fixed_price == 'yes' ? ' -' . $variation->discount : '';
                $data[] = [
                    'variation_id' => $variation->id,
                    'product_id' => $variation->product_id,
                    'max' => $variation->max,
                    'min' => $variation->min,
                    'slug' => $variation->slug,
                    'fixed_price' => $variation->fixed_price,
                    'verifiable' => $variation->verifiable,
                    'system_name' => $variation->system_name . $price,
                    'system_price' => $variation->system_price,
                    'discount' => $variation->discount
                ];

            }

            return $data;

        }else{
            foreach($variations as $variation){
                $price = $variation->fixed_price == 'yes' ? ' -' . $variation->discount : '';
                $data[] = '<option data-product_id="' . $variation->product_id . '"  data-max="' . $variation->max . '"  data-min="' . $variation->min . '"  data-slug="' . $variation->slug . '"  data-fixed_price="' . $variation->fixed_price . '" data-verifiable="'. $variation->verifiable.'" value="' . $variation->id . '">' . $variation->system_name . $price . '</option>';
            }

        }
        return response()->json($data);
    }

    public function updateVariations(Request $request)
    {
        if (isset($request->level)) {
            foreach ($request->level as $key => $level) {
                foreach ($level as $k => $price) {
                    if (!empty($price)) {
                        Discount::updateOrCreate([
                            'customer_level' => $key,
                            'variation_id' => $k,
                            // 'product_id' => $request->product_id,
                            // 'category_id' => $request->category_id,
                        ],[
                            'status' => 'active',
                            'customer_level' => $key,
                            'variation_id' => $k,
                            'price' => $price,
                            // 'product_id' => $request->product_id,
                            // 'category_id' => $request->category_id
                        ]);
                    } else {
                        Discount::where(['variation_id' => $k, 'customer_level' => $key])->delete();
                    }
                }
            }
        }
        
        foreach ($request->variation_id as $variation) {
            $data = [
                'api_name' => $request->api_name[$variation],
                'api_price' => $request->api_price[$variation],
                'system_name' => $request->system_name[$variation],
                'slug' => $request->slug[$variation],
                'system_price' => $request->system_price[$variation],
                'fixed_price' => $request->fixed_price[$variation],
                'min' => $request->min[$variation] ?? null,
                'max' => $request->max[$variation] ?? null,
                'status' => $request->status[$variation],
            ];
        
            Variation::where('id', $variation)->update($data);
        }

        \Session::flash('page', 2);
        return back()->with('message', 'Variations Updated succesfully');
    }

    public function addManualVariations(Request $request, Product $product)
    {
        // Create the variation
        if (isset($request->system_name)) {
            foreach ($request->system_name as $key => $variation) {
                // dd($variation, $key, $request->all(), $request->slug[$key]);
                $variation = Variation::updateOrCreate([
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'api_id' => $product->api_id,
                    'api_name' =>  $request->system_name[$key],
                    'slug' => $request->slug[$key],
                ], [
                    'product_id' => $request->product_id,
                    'category_id' => $product->category_id,
                    'api_id' => $product->api_id,
                    'api_name' =>  $request->system_name[$key],
                    'slug' => $request->slug[$key],
                    'system_name' =>  $request->system_name[$key],
                    'fixed_price' => $request->fixed_price[$key],
                    'api_price' => $request->system_price[$key],
                    'system_price' => $request->system_price[$key],
                    'min' => $request->minimum_amount[$key] ?? null,
                    'max' => $request->maximum_amount[$key] ?? null,
                    'status' => $request->status[$key]
                ]);

                if (isset($request->level)) {
                    foreach ($request->level as $l => $level) {
                        foreach ($level as $price) {
                            if (!empty($price)) {
                                Discount::updateOrCreate([
                                    'status' => 'active',
                                    'customer_level' => $l,
                                    'variation_id' => $variation->id,
                                    'price' => $level[$key]
                                ]);
                            }else{
                                Discount::where(['variation_id' => $variation->id, 'customer_level' => $l])->delete();
                            }
                        }
                    }
                }
            }
        }

        \Session::flash('page', 2);
        return back()->with('message', 'Variations added succesfully');
    }

    public function deleteVariations(Variation $variation)
    {

        if ($variation->discounts->count() > 0) {
            foreach ($variation->discounts as $dist) {
                $dist->delete();
            }
        }

        // dd($variation);
        $variation->delete();
        
        return back()->with('message', 'Variation deleted successfully');
        // Discount::
    }
}
