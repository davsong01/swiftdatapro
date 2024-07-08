@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Add Product')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('product.index')}}">All Products</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">New Product</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Add New Product</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills justify-content-leftnav-style-2 mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#product-details" aria-selected="true">Product Details</a>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#variations" aria-selected="false" tabindex="-1">Variations</a>
                    </li> --}}
                </ul>
                 <div class="tab-content pt-1">
                    <div class="tab-pane active" id="product-details" role="tabpanel" aria-labelledby="home-tab-fill">
                        <form action="{{route('product.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">
                                    <div class="col-md-6">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name')}}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="display_name">Display Name</label>
                                        <input type="text" class="form-control" id="display_name" name="display_name" placeholder="Enter display name" value="{{ old('display_name')}}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="slug">Slug</label>
                                        <input type="text" class="form-control" name="slug" placeholder="Enter slug" id="slug" value="{{ old('slug')}}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="old_identifier">Old Identifier</label>
                                        <input type="text" class="form-control" name="old_identifier" placeholder="Enter old_identifier" id="old_identifier" value="{{ old('old_identifier')}}">
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <label for="formFile" class="form-label">Display Image</label>
                                        <input name="image" class="form-control" type="file" id="image" accept="image/*">
                                    </div> --}}
                                    <div class="col-md-6">
                                        <label class="form-label" for="category">Category</label>
                                        <select class="form-control" name="category" id="category" required>
                                            <option value="">Select</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id  }}" {{ old('category') == $category->id ? 'selected' : ''}}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="name">Allow Quantity</label>
                                        <select class="form-control" name="allow_quantity" id="allow_quantity">
                                            <option value="">Select</option>
                                            <option value="yes" {{ old('allow_quantity') == 'yes' ? 'selected' : ''}}>Yes</option>
                                            <option value="no" {{ old('allow_quantity') == 'no' ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="name">Quantity Graduation</label>
                                        <input type="text" class="form-control tiny" placeholder="Please enter each value seperated with a comma" id="quantity_graduation" name="quantity_graduation"  value="{{ old('quantity_graduation') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="name">Allow Subscription Type</label>
                                        <select class="form-control" name="allow_subscription_type" id="allow_subscription_type">
                                            <option value="">Select</option>
                                            <option value="yes" {{ old('allow_subscription_type') == 'yes' ? 'selected' : ''}}>Yes</option>
                                            <option value="no" {{ old('allow_subscription_type') == 'no' ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-md-6" id="referral_percentage">
                                        <label class="form-label" for="referral_percentage">Referral Percentage(%)</label>
                                        <input type="number" class="form-control" id="referral_percentage" step="0.01" name="referral_percentage" value="{{ old('referral_percentage') }}" placeholder="Enter percentage for referral commission">
                                    </div> --}}
                                    {{-- <div class="col-md-6" id="referral_commission_type">
                                        <label class="form-label" for="referral_commission_type">Referral Commission Type</label>
                                        <select class="form-control" name="referral_commission_type" id="referral_commission_type">
                                            <option value="">Select</option>
                                            <option value="flat" {{ old('referral_commission_type') == 'flat' ? 'selected' : ''}}>Flat</option>
                                            <option value="percnetage" {{ old('referral_commission_type') == 'percnetage' ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div> --}}
                                    <div class="col-md-6" id="referral_commission">
                                        <label class="form-label" for="referral_commission">Referral Commission(%)</label>
                                        <input type="number" class="form-control" id="referral_commission" step="0.01" name="referral_commission" value="{{ old('referral_commission') }}" placeholder="Enter commission">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label" for="seo_title">SEO Title</label>
                                        <input type="text" class="form-control" id="seo_title"  name="seo_title" placeholder="Enter SEO Title" value="{{ old('seo_title')}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="seo_keywords">SEO Keywords</label>
                                        <input type="text" class="form-control"  name="seo_keywords" placeholder="Enter SEO Keywords" id="seo_keywords" value="{{ old('seo_keywords')}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="helperText">API to use</label>
                                        <select class="form-control" name="api" id="api" required>
                                            <option value="">Select</option>
                                            @foreach ($apis as $item)
                                                <option value="{{ $item->id  }}" {{ old('api') == $item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label" for="status">Status</label>
                                        <select class="form-control" name="status" id="status" required>
                                            <option value="">Select</option>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : ''}}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : ''}}>InActive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="has_variations">Has Variations</label>
                                        <select class="form-control" name="has_variations" id="has_variations" required>
                                            <option value="">Select</option>
                                            <option value="yes" {{ old('has_variations') == 'active' ? 'selected' : ''}}>Yes</option>
                                            <option value="no" {{ old('has_variations') == 'inactive' ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="fixed_price">Fixed Price</label>
                                        <select class="form-control tiny" name="fixed_price" id="fixed_price" required>
                                            <option value="">Select</option>
                                            <option value="yes" {{ old('fixed_price') == 'yes' ? 'selected' : ''}}>Yes</option>
                                            <option value="no" {{ old('fixed_price') == 'no' ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="provider_percentage_discount">Provider Percentage Discount (%)</label>
                                        <input step="0.1" type="number" class="form-control tiny" id="provider_percentage_discount" name="provider_percentage_discount" value="{{ old('provider_percentage_discount') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="name">System Price</label>
                                        <input type="number" class="form-control tiny" id="system_price" name="system_price"  value="{{ old('system_price') }}">
                                    </div>
                                    @foreach($customerlevel as $level)
                                    <div class="col-md-6">
                                        <label class="form-label" for="productlevel">{{ $level->name }} Price ({!! settings()['currency']!!})</label>
                                        <input type="number" class="form-control tiny" id="productlevel" name="productlevel[{{ $level->id }}]"  value="">
                                    </div>
                                    @endforeach
                                    <div class="col-md-6">
                                        <label class="form-label" for="min">Minimun Amount</label>
                                        <input type="number" class="form-control tiny" id="min" name="min"  value="{{ old('min') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="max">Maximum Amount</label>
                                        <input type="number" class="form-control tiny" id="max" name="max"  value="{{ old('max') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description" value="{{ old('description')}}"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="seo_description">SEO Description</label>
                                        <textarea class="form-control" id="seo_description" rows="3" name="seo_description" placeholder="SEO Description">{{ old('seo_description') }}</textarea>
                                    </div>
                                    <input type="hidden" value="product.store" name="route">
                                </div>
                                <div class="col-md-12 mt-3">
                                <button class="btn btn-primary" type="submit">Submit</button>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="variations" role="tabpanel" aria-labelledby="profile-tab-fill">
                        <p>
                           
                        </p>
                    </div>
                    
                </div>
                {{-- <div class="row gy-4">
                    <form action="{{route('product.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="icon">Icon</label>
                                <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon') }}" placeholder="Enter icon" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="display_name">Display Name</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name') }}" placeholder="Enter name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="slug">Slug</label>
                                <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" placeholder="Enter slug" id="slug" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="seo_title">SEO Title</label>
                                <input type="text" class="form-control" id="seo_title" name="seo_title" placeholder="Enter SEO Title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="seo_keywords">SEO Keywords</label>
                                <input type="text" class="form-control" name="seo_keywords" placeholder="Enter SEO Keywords" id="seo_keywords">
                            </div>
                            
                        
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="status">Category Status</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="">Select</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : ''}}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : ''}}>InActive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="unique_element">Unique Element</label>
                                <select class="form-control" name="unique_element" id="unique_element" required>
                                    <option value="">Select</option>
                                    @foreach ( getUniqueElements() as $element )
                                        <option value="{{ $element }}" {{ old('unique_element') == 'element' ? 'selected' : ''}}>{{ ucfirst(str_replace("_"," ",$element))}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="discount_type">Discount Type</label>
                                <select class="form-control" name="discount_type" id="discount_type" required>
                                    <option value="">Select...</option>
                                    <option value="flat" {{ old('discount_type') == 'flat' ? 'selected' : '' }}>Flat</option>
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="order">Order</label>
                                <input type="number" class="form-control" name="order" value="{{ old('order') }}" placeholder="Enter order" id="order" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" class="form-label" for="seo_description">SEO Description</label>
                                <textarea class="form-control" id="seo_description" name="seo_description" rows="3" placeholder="SEO Description"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description"></textarea>
                            </div>
                            
                        </div>
                            <div class="col-md-12 mt-3">
                            <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
@endsection
