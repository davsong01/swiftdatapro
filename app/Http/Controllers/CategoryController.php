<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Http\Request;
use App\Models\CustomerLevel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderby('order', 'ASC')->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $customerlevel = CustomerLevel::orderBy('order', 'ASC')->where('id', '>', 1)->get();
        return view('admin.categories.create', compact('customerlevel'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "display_name" => "nullable",
            "slug" => "required",
            "unique_element" => "required",
            "status" => "required",
            "order" => "required",
            "description" => "nullable",
            "seo_description" => "nullable",
            "seo_title" => "nullable",
            "icon" => "nullable",
            "seo_keywords" => "nullable",
            "discount_type" => "nullable",
            "discount_value" => "nullable",
        ]);

        $category = Category::updateOrCreate([
            "name" => $request->name,
            "icon" => $request->icon,
            "display_name" => $request->display_name,
            "slug" => $request->slug,
            "status" => $request->status,
            "order" => $request->order,
            "description" => $request->description,
            "seo_description" => $request->seo_description,
            "seo_title" => $request->seo_title,
            "seo_keywords" => $request->seo_keywords,
            "unique_element" => $request->unique_element,
            "discount_type" => $request->discount_type,
            "discount_value" => $request->discount_value,
        ]);

        if (isset($request->level) && isset($category)) {
            foreach ($request->level as $key => $price) {
                if (!empty($price)) {
                    Discount::updateOrCreate([
                        'customer_level' => $key,
                        'category_id' => $category->id,
                    ], [
                        'customer_level' => $key,
                        'category_id' => $category->id,
                        'price' => $price,
                    ]);
                } else {
                    Discount::where(['category_id' => $category->id, 'customer_level' => $key])->delete();
                }
            }
        }

        return redirect(route('category.index'))->with('message', 'Added successfully');
    }

    public function edit(Category $category)
    {
        $customerlevel = CustomerLevel::orderBy('order', 'ASC')->where('id', '>', 1)->get();
        return view('admin.categories.edit', compact('category','customerlevel'));
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            "name" => "required",
            "icon" => "nullable",
            "display_name" => "nullable",
            "slug" => "required",
            "status" => "required",
            "order" => "required",
            "unique_element" => "required",
            "description" => "nullable",
            "seo_description" => "nullable",
            "seo_title" => "nullable",
            "seo_keywords" => "nullable",
            "discount_type" => "nullable",
            "discount_value" => "nullable",
        ]);

        $category->update([
            "name" => $request->name,
            "icon" => $request->icon,
            "display_name" => $request->display_name,
            "slug" => $request->slug,
            "status" => $request->status,
            "unique_element" => $request->unique_element,
            "order" => $request->order,
            "description" => $request->description,
            "seo_description" => $request->seo_description,
            "seo_title" => $request->seo_title,
            "seo_keywords" => $request->seo_keywords,
            "discount_type" => $request->discount_type,
            "discount_value" => $request->discount_value,
        ]);
    
        if (isset($request->level) && isset($category)) {
            foreach ($request->level as $key => $price) {
                if (!empty($price)) {
                    Discount::updateOrCreate([
                        'customer_level' => $key,
                        'category_id' => $category->id,
                    ], [
                        'customer_level' => $key,
                        'category_id' => $category->id,
                        'price' => $price,
                    ]);
                } else {
                    Discount::where(['category_id' => $category->id,'customer_level' => $key])->delete();
                }
            }
        }

        return back()->with('message', 'Updated successfully');
    }

    public function destroy(Category $category)
    {
        if ($category->products->count() < 1) {
            $category->delete();
        } else {
            return back()->with('error', 'Category in use, cannot delete');
        }

        return back()->with('message', 'Deleted successfully');
    }

}
