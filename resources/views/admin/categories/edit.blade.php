@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Edit '.$category->name)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('category.index')}}">All Categories</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Edit {{$category->name}}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Edit {{$category->name}}</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <form action="{{route('category.update', $category->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $category->name ?? old('name') }}" placeholder="Enter name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="icon">Icon</label>
                                <input type="text" class="form-control" id="icon" name="icon" value="{{ $category->icon ?? old('icon') }}" placeholder="Enter icon" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="display_name">Display Name</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" value="{{ $category->display_name ?? old('display_name') }}" placeholder="Enter display" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="slug">Slug</label>
                                <input type="text" class="form-control" name="slug" value="{{ $category->slug ?? old('slug') }}" placeholder="Enter slug" id="slug" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label" for="seo_title">SEO Title</label>
                                <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ $category->seo_title ?? old('seo_title') }}"  placeholder="Enter SEO Title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="seo_keywords">SEO Keywords</label>
                                <input type="text" class="form-control" name="seo_keywords" value="{{ $category->seo_keywords ?? old('seo_keywords') }}" placeholder="Enter SEO Keywords" id="seo_keywords">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="status">Category Status</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="">Select</option>
                                    <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>InActive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="unique_element">Unique Element</label>
                                <select class="form-control" name="unique_element" id="unique_element" required>
                                    <option value="">Select</option>
                                    @foreach ( getUniqueElements() as $element )
                                        <option value="{{ $element }}" {{ $category->unique_element == $element ? 'selected' : ''}}>{{ ucfirst(str_replace("_"," ",$element))}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="discount_type">Discount Type</label>
                                <select class="form-control" name="discount_type" id="discount_type" required>
                                    <option value="">Select...</option>
                                    <option value="flat" {{ $category->discount_type == 'flat' ? 'selected' : '' }}>Flat</option>
                                    <option value="percentage" {{ $category->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="order">Order</label>
                                <input type="number" class="form-control" name="order" value="{{ $category->order ?? old('order') }}" placeholder="Enter order" id="order" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="seo_description">SEO Description</label>
                                <textarea class="form-control" id="seo_description" name="seo_description" rows="3" placeholder="SEO Description" value="{{ $category->seo_description ?? old('seo_description') }}">{{ $category->seo_description ?? old('seo_description') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description" value="{{ $category->description ?? old('description') }}">{{ $category->description ?? old('description') }}</textarea>
                            </div>
                            
                        </div>
                            <div class="col-md-12 mt-3">
                            <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
@endsection
