<?php 
    use Illuminate\Support\Facades\Session;
    $page = Session::get('page') ?? 1;
?>
@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Edit Product')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('product.index')}}">All Products</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Edit Product</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                @include('includes.alerts')
                <h5>Edit {{$product->name}}</h5>
                {{-- <img src="{{asset($product->image)}}" alt="" style="width: 70px;"> --}}
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-pills justify-content-leftnav-style-2 mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link  {{ $page == 1 ? 'active' : ''}}" data-bs-toggle="tab" role="tab" aria-current="page" href="#product-details" aria-selected="true">Product Details</a>
                    </li>
                    @if($product->has_variations == 'yes')
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $page == 2 ? 'active' : ''}}" data-bs-toggle="tab" role="tab" aria-current="page" href="#variations" aria-selected="false" tabindex="-1">Variations ({{$product->variations->count()}})</a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content pt-1">
                    <div class="tab-pane {{ isset($page) && $page == 1 ? 'active' : ''}}" id="product-details" role="tabpanel" aria-labelledby="home-tab-fill">
                        <form action="{{route('product.update', $product->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{  $product->name ?? old('name')}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="display_name">Display Name</label>
                                    <input type="text" class="form-control" id="display_name" name="display_name" placeholder="Enter display name" value="{{  $product->display_name ?? old('display_name')}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="slug">Slug</label>
                                    <input type="text" class="form-control" name="slug" placeholder="Enter slug" id="slug" value="{{  $product->slug ?? old('slug')}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="old_identifier">Old Identifier</label>
                                    <input type="text" class="form-control" name="old_identifier" placeholder="Enter old_identifier" id="old_identifier" value="{{  $product->old_identifier ?? old('old_identifier')}}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="category">Category</label>
                                    <select class="form-control" name="category" id="category" required>
                                        <option value="">Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id  }}" {{ $product->category_id == $category->id ? 'selected' : ''}}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Allow Quantity</label>
                                    <select class="form-control" name="allow_quantity" id="allow_quantity">
                                        <option value="">Select</option>
                                        <option value="yes" {{ $product->allow_quantity == 'yes' ? 'selected' : ''}}>Yes</option>
                                        <option value="no" {{ $product->allow_quantity == 'no' ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Quantity Graduation</label>
                                    <input type="text" class="form-control" placeholder="Please enter each value seperated with a comma" id="quantity_graduation" name="quantity_graduation"  value="{{ $product->quantity_graduation ?? old('quantity_graduation') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Allow Subscription Type (For DSTV and GOTV purchses)</label>
                                    <select class="form-control" name="allow_subscription_type" id="allow_subscription_type">
                                        <option value="yes" {{ $product->allow_subscription_type == 'yes' ? 'selected' : ''}}>Yes</option>
                                        <option value="no" {{ $product->allow_subscription_type == 'no' ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="has_variations" class="form-label" >Has Variations</label>
                                    <select class="form-control" name="has_variations" id="has_variations" required>
                                        <option value="">Select</option>
                                        <option value="yes" {{ $product->has_variations == 'yes' ? 'selected' : ''}}>Yes</option>
                                        <option value="no" {{ $product->has_variations == 'no' ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-6" id="referral_percentage">
                                    <label class="form-label" for="referral_percentage">Referral Percentage(%)</label>
                                    <input type="number" class="form-control" id="referral_percentage" step="0.01" name="referral_percentage" value="{{ $product->referral_percentage ?? old('referral_percentage') }}" placeholder="Enter percentage for referral commission">
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
                                        <option value="{{ $item->id  }}" {{ $product->api_id == $item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" id="copy-variations-div" style="display:none">
                                    <label class="form-label" for="copy_variations" style="color:red">Copy Product variations</label>
                                    <select class="form-control tiny" name="copy_variations" id="copy_variations">
                                        <option value="">Select</option>
                                        <option value="yes" {{ $product->copy_variations == 'yes' ? 'selected' : ''}}>Yes</option>
                                        <option value="no" {{ $product->copy_variations == 'no' ? 'selected' : ''}}>No</option> 
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control" name="status" id="status" required>
                                        <option value="">Select</option>
                                        <option value="active" {{ $product->status == 'active' ? 'selected' : ''}}>Active</option>
                                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : ''}}>InActive</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label" for="fixed_price">Fixed Price</label>
                                    <select class="form-control" name="fixed_price" id="fixed_price" required>
                                        <option value="">Select</option>
                                        <option value="yes" {{ $product->fixed_price == 'yes' ? 'selected' : ''}}>Yes</option>
                                        <option value="no" {{ $product->fixed_price == 'no' ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="name">System Price ({!! settings()['currency']!!})</label>
                                    <input type="number" class="form-control" id="system_price" name="system_price"  value="{{ old('system_price') }}">
                                </div>

                                @foreach($customerlevel as $level)
                                <div class="col-md-6">
                                    <label class="form-label" for="name">{{ $level->name }} @if($product->category->discount_type == 'flat') Discounted Price ({!! settings()['currency']!!}) @else Discounted Percentage (%) @endif</label>
                                    <input type="number" class="form-control" id="productlevel" name="productlevel[{{ $level->id }}]" step=".01" value="{{ $product->customer_level_price($level->id) }}">
                                </div>
                                @endforeach

                                <div class="col-md-6">
                                    <label class="form-label" for="min">Minimun Amount</label>
                                    <input type="number" class="form-control" id="min" name="min"  value="{{  $product->min ?? old('min') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="max">Maximum Amount</label>
                                    <input type="number" class="form-control" id="max" name="max"  value="{{  $product->max ?? old('max') }}">
                                </div>
                                <input type="hidden" value="page1" name="route">

                                <div class="col-md-6">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description" value="{{ old('description')}}"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="seo_description">SEO Description</label>
                                    <textarea class="form-control" id="seo_description" rows="3" name="seo_description" placeholder="SEO Description">{{ old('seo_description') }}</textarea>
                                </div>
                                <input type="hidden" value="product.update" name="route">
                            </div>
                            <div class="col-md-12 mt-3">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane {{ isset($page) && $page == 2 ? 'active' : ''}}" id="variations" role="tabpanel" aria-labelledby="variations">
                    @if($product->has_variations == 'yes')
                        <div class="modal-primary mr-1 mb-1 d-inline-block">
                            <!-- Button trigger for primary themes modal -->
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#primary">
                                Add Variations
                            </button>

                            @include('admin.products.add_variations_form')
                        </div>

                        <a onclick="return confirm('You are about to pull old variations, I hope you know what you are doing')" href="{{ route('variations.old.pull', $product->id) }}" class="btn btn-light align-items-center"><i class="bx bx-refresh"></i>&nbsp; Pull Old Variations</a>

                        @if($product->variations->count() < 1)
                            <a href="{{ route('variations.pull', $product->id) }}" class="btn btn-dark align-items-center"><i class="bx bx-plus"></i>&nbsp; Pull Variations</a>
                        @else
                            <a style="width:fit-content;" href="{{ route('variations.pull', $product->id) }}" class="btn btn-info align-items-center"><i class="bx bx-plus"></i>&nbsp; Re Pull Variations</a>

                            <form action="{{route('variations.update', $product->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @foreach($product->variations as $variation)
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <input type="hidden" name="category_id" value="{{$product->category->id}}">
                                <div class="row gy-4 pt-4" style="margin-bottom:10px">
                                    <div class="col-md-3">
                                        <label class="form-label" for="api_name">API Name</label>
                                        <input type="text" class="form-control" id="api_name" name="api_name[{{ $variation->id }}]"  value="{{ $variation->api_name }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="name">API Price ({!! settings()['currency']!!})</label>
                                        <input type="text" class="form-control" id="api_price" name="api_price[{{ $variation->id }}]"  value="{{ $variation->api_price }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="name">System Name</label>
                                        <input type="text" class="form-control" id="system_name" name="system_name[{{ $variation->id }}]"  value="{{ $variation->system_name }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="name">System Price ({!! settings()['currency']!!})</label>
                                        <input type="number" class="form-control" id="system_price" name="system_price[{{ $variation->id }}]"  value="{{ $variation->system_price }}">
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label" for="name">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug[{{ $variation->id }}]"  value="{{ $variation->slug }}">
                                    </div>
                                    <div class="col-md-2">
                                            <label class="form-label" for="fixed_price">Fixed Price</label>
                                            <select class="form-control" name="fixed_price[{{ $variation->id }}]" id="fixed_price" required>
                                                <option value="">Select</option>
                                                <option value="yes" {{ $variation->fixed_price == 'yes' ? 'selected' : ''}}>Yes</option>
                                            <option value="no" {{ $variation->fixed_price == 'no' ? 'selected' : ''}}>No</option> 
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                            <label class="form-label" for="status">Status</label>
                                            <select class="form-control" name="status[{{ $variation->id }}]" id="status" required>
                                                <option value="">Select</option>
                                                <option value="active" {{ $variation->status == 'active' ? 'selected' : ''}}>Active</option>
                                            <option value="inactive" {{ $variation->status == 'inactive' ? 'selected' : ''}}>InActive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="min">Min Amount</label>
                                        <input type="number" class="form-control" id="min" name="min[{{ $variation->id }}]"  value="{{ $variation->min }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="max">Max Amount</label>
                                        <input type="number" class="form-control" id="max" name="max[{{ $variation->id }}]"  value="{{ $variation->max }}">
                                    </div>
                                    
                                    
                                    @foreach($customerlevel as $level)
                                    <div class="col-md-3">
                                        <label class="form-label" for="name">{{ $level->name }} @if($variation->category->discount_type == 'flat') Discounted Price ({!! settings()['currency']!!}) @else Discounted Percentage (%) @endif</label>
                                        <input type="number" step=".01" class="form-control" id="level" name="level[{{ $level->id }}][{{ $variation->id }}]"  value="{{ $variation->customer_level_price($level->id) }}">
                                    </div>
                                    @endforeach
                                    @if($variation->transaction->count() < 1)
                                    <div class="col-md-2">
                                        <label class="form-label" style="color:white">S</label>
                                        <a onclick="return confirm('You are about to delete a variation')" href="{{ route('variation.delete', $variation->id) }}"><button style="color: white;" class="btn btn-danger form-control" style="padding: 8px;" type="button"><i class="bx bx-trash"></i> Delete</button></a>
                                    </div>
                                    @endif
                                </div>
                                <input type="hidden" name="variation_id[{{$variation->id}}]" value="{{$variation->id}}">
                                    <hr style="height: 0px;border-color: #00cfdd;">
                                @endforeach
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>
    $("#api").on('change', function(e) {
        var id = $("#api").val();
        var currentId = "{{ $product->api->id }}";
        var hasVariations = "{{ $product->has_variations }}";
        
        if(id != currentId && hasVariations == 'yes'){
            $("#copy-variations-div").show();
            $("#copy_variations").val('');
        }else{
            $("#copy-variations-div").hide();
            $("#copy_variations").val('');
        }
    });
</script>
<script>
    $("#add-mode").on('click', function () {
        //get last ID
        var lastChild = $("#mode-holder").children().last();
        var countChildren = $("#mode-holder").children().length;
        
        var lastId = $(lastChild).attr('id').split('-');

        var id = lastId[1] + 1;
    
        var child = `<div class="row gy-4" id="mode-`+id+`">
                <div class="col-md-3">
                    <label class="form-label" for="name">System Name</label>
                    <input type="text" class="form-control" id="system_name" name="system_name[]"  value="" placeholder="Variation name" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="name">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug[]"  value="" placeholder="Variation slug" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="fixed_price">Fixed Price</label>
                    <select class="form-control" name="fixed_price[]" id="fixed_price" required>
                        <option value="">Select</option>
                        <option value="yes" {{ old('fixed_price') == 'yes' ? 'selected' : ''}}>Yes</option>
                        <option value="no" {{ old('fixed_price') == 'no' ? 'selected' : ''}}>No</option> 
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-control" name="status[]" id="status" required>
                        <option value="">Select</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : ''}}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : ''}}>InActive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="min">Min Amount</label>
                    <input type="number" class="form-control" id="min" name="min[]"  value="{{ old('min') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="max">Max Amount</label>
                    <input type="number" class="form-control" id="max" name="max[]"  value="{{ old('max') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="name">System Price ({!! settings()['currency']!!})</label>
                    <input type="number" class="form-control" id="system_price" name="system_price[]"  value="" placeholder="Variation price" required>
                </div>
                @foreach($customerlevel as $level)
                <div class="col-md-3">
                    <label class="form-label" for="{{ $level->name }}">{{ $level->name }} @if($product->category->discount_type == 'flat') Discounted Price ({!! settings()['currency']!!}) @else Discounted Percentage (%) @endif</label>
                    <input type="number" class="form-control" id="level" step=".01" name="level[{{ $level->id }}][]" value="" required>
                </div>
                @endforeach
                <div class="col-md-2">
                    <label class="form-label" for="mark" style="color:white">sdsdsddsdssd</label>
                    <button class="btn btn-danger remove-mode form-control" id="remove-mode-`+id+`" type="button" style="min-width: unset;"> <i class="bx bx-minus"></i> Remove</button>
                </div>
                <div class="col-md-12">
                    <hr style="height: 0px;border-color: red;">
                </div>
                </div>`
        $("#mode-holder").append(child);      
    });

    $("#mode-holder").on('click','.remove-mode', function(e) {
        var removeId = $(e.target).attr('id').split('-');
        var id = removeId[2];
        $("#mode-"+id).remove();
    });

</script>
@endsection
