@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Edit '.$permission->name)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('role.index')}}">Staff Permission</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Edit {{ $permission->name }}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Edit {{ $permission->name }}</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <form action="{{ route('permission.update', $permission->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="row gy-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name ?? old('name') }}" placeholder="Slug" required>
                    </div>
                    <div class="col-md-6">
                        <label for="input-label" class="form-label">Route (route to be used in the system)<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="route" name="route" value="{{ $permission->route ?? old('route') }}" placeholder="Slug" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="input-label" class="form-label">Status<span
                        class="text-danger">*</span></label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="active" {{ $permission->status == 'active' ? 'selected' : ''}}>Active</option>
                            <option value="inactive" {{ $permission->status == 'inactive' ? 'selected' : ''}}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="input-label" class="form-label">Type<span
                        class="text-danger">*</span></label>
                        <select class="form-control" name="type" id="type" required>
                            <option value="menu" {{ $permission->type == 'menu' ? 'selected' : ''}}>Menu</option>
                            <option value="link" {{ $permission->type == 'link' ? 'selected' : ''}}>Link</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
@endsection
