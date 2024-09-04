@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Add Airtime2Cash')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('airtime2cash.index')}}">All Airtime2Cash</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">New Product</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>New Product</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <div class="tab-content pt-1">
                    <div class="tab-pane active" id="product-details" role="tabpanel" aria-labelledby="home-tab-fill">
                        <form action="{{route('airtime2cash.store')}}" method="POST" enctype="multipart/form-data">
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
                                        <label class="form-label" for="seo_title">SEO Title</label>
                                        <input type="text" class="form-control" id="seo_title"  name="seo_title" placeholder="Enter SEO Title" value="{{ old('seo_title')}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="seo_keywords">SEO Keywords</label>
                                        <input type="text" class="form-control"  name="seo_keywords" placeholder="Enter SEO Keywords" id="seo_keywords" value="{{ old('seo_keywords')}}">
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
                                        <label for="formFile" class="form-label">Product Image</label>
                                        <input name="image" class="form-control" type="file" id="image" accept="image/*">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" class="form-label" for="category_id">Category</label>
                                        <select class="form-control" name="category_id" id="category_id" required>
                                            <option value="{{ $category->id }}">{{ $category->display_name}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="helperText">API to use</label>
                                        <select class="form-control" name="api" id="api" required>
                                            @if($api)
                                            <option value="{{ $api->id  }}" {{ old('api') == $api->id ? 'selected' : ''}}>{{ $api->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="min">Minimun Amount</label>
                                        <input type="number" class="form-control tiny" id="min" name="min"  value="{{ old('min') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="max">Maximum Amount</label>
                                        <input type="number" class="form-control tiny" id="max" name="max"  value="{{ old('max') }}">
                                    </div>
                                    @foreach($customerlevel as $level)
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $level->name }} @if($category->discount_type == 'flat') Charge Rate ({!! settings()['currency']!!}) @else Charge Rate (%) @endif</label>
                                        <input type="number" class="form-control tiny" id="productlevel" name="productlevel[{{ $level->id }}]"  value="">
                                    </div>
                                    @endforeach
                                    
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
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
@endsection
