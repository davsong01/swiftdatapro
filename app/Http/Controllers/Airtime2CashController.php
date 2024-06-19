<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;

class Airtime2CashController extends Controller
{
    public function index()
    {
        $airtime2cashs = Product::where('type', 'airtime2cash')->with(['api'])->orderBy('created_at', 'DESC')->get();
        return view('admin.airtime2cash.index', compact('airtime2cashs'));
    }

    public function create()
    {
        $category = Category::where('status', 'active')->where('slug', 'airtime2cash')->first();
        $customerlevel = CustomerLevel::orderBy('order', 'ASC')->get();
        $api = Api::where('id', env('EXCHANGE_AIRTIME_API_ID'))->first();
        return view('admin.airtime2cash.create', compact('category', 'customerlevel', 'api'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "category_id" => "required",
            "description" => "nullable",
            "seo_title" => "nullable",
            "seo_keywords" => "nullable",
            "status" => "required",
            "seo_description" => "nullable",
            "fixed_price" => "nullable",
            "rate" => "nullable",
        ]);

        if (!empty($request->image)) {
            $image = $this->uploadFile($request->image, 'products');
        }

        $slug = empty($request->slug) ? strtolower('airtime2cash-'.Str::slug($request->name)) : $request->slug;
        $product = Product::updateOrCreate(
            [
                "name" => $request->name,
                "category_id" => $request->category_id,
                "slug" => $slug,
            ],
            [
                "name" => $request->name,
                "display_name" => $request->display_name,
                "category_id" => $request->category_id,
                "description" => $request->description,
                "seo_title" => $request->seo_title,
                "seo_keywords" => $request->seo_keywords,
                "slug" => $slug,
                "api_id" => $request->api,
                "status" => $request->status,
                "has_variations" => 'no',
                "seo_description" => $request->seo_description,
                "min" => $request->min,
                "max" => $request->max,
                "type" => 'airtime2cash',
            ]
        );

        if (isset($request->productlevel) && isset($product)) {
            foreach ($request->productlevel as $key => $price) {
                if (!empty($price)) {
                    Discount::updateOrCreate([
                        'customer_level' => $key,
                        'product_id' => $product->id,
                    ], [
                        'status' => 'active',
                        'customer_level' => $key,
                        'product_id' => $product->id,
                        'price' => $price,
                    ]);
                } else {
                    Discount::where('customer_level', $key)->where('product_id', $product->id)->delete();
                }
            }
        }

        return redirect(route('airtime2cash.edit', $product->id))->with('message', 'Product Added Successfully');
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)->first();
        $category = Category::where('status', 'active')->where('slug', 'airtime2cash')->first();
        $api = Api::where('id', env('EXCHANGE_AIRTIME_API_ID'))->first();

        $customerlevel = CustomerLevel::orderBy('order', 'ASC')->get();

        return view('admin.airtime2cash.edit', compact('category', 'product', 'customerlevel','api'));
    }

    public function update(Request $request, $id){
        $product = Product::where('id', $id)->first();
        
        if (!empty($request->image)) {
            $image = $this->uploadFile($request->image, 'products');
        }else{
            $image = $product->image;
        }

        $slug = empty($request->slug) ? strtolower('airtime2cash-' . Str::slug($request->name)) : $request->slug;
        
        $product->update(
            [
                "name" => $request->name,
                "has_variations" => 'no',
                "display_name" => $request->name,
                "category_id" => $request->category_id,
                "description" => $request->description,
                "seo_title" => $request->seo_title,
                "seo_keywords" => $request->seo_keywords,
                "slug" => $slug,
                "api_id" => $request->api,
                "status" => $request->status,
                "seo_description" => $request->seo_description,
                "image" => $image ?? null,
                "min" => $request->min,
                "max" => $request->max,
            ]
        );

        $productLevel = $request->productlevel;

        if (isset($productLevel) && count($productLevel) > 0 && isset($product)) {
            foreach ($productLevel as $key => $price) {
                if (!empty($price)) {
                    Discount::updateOrCreate([
                        'customer_level' => $key,
                        'product_id' => $product->id,
                    ], [
                        'status' => 'active',
                        'customer_level' => $key,
                        'product_id' => $product->id,
                        'price' => $price,
                    ]);
                } else {
                    Discount::where('customer_level', $key)->where('product_id', $product->id)->delete();
                }
            }
        }
        
        return back()->with('message', 'Update Successfull');
    }
}
