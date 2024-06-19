@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Edit '. $customerlevel->name)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('customerlevel.index')}}">Customer Levels</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">{{'Edit '. $customerlevel->name}} </li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>{{'Edit '. $customerlevel->name}}</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <form action="{{ route('customerlevel.update', $customerlevel->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name<span
                            class="text-danger">*</span></label>
                            <input type="text" value="{{ $customerlevel->name ?? old('name') }}" class="form-control" name="name" id="name" placeholder="Name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="slug" class="form-label">Upgrade Amount<span
                            class="text-danger">*</span></label>
                            <input type="number" value="{{ $customerlevel->upgrade_amount ?? old('upgrade_amount') }}" class="form-control" name="upgrade_amount" id="upgrade_amount" placeholder="Upgrade Amount" required>
                        </div>
                        <div class="col-md-6">
                            <label for="admin_inch" class="form-label">Admin In charge</label>
                            <input type="number" value="{{ $customerlevel->admin_inch ?? old('admin_inch') }}" class="form-control" name="admin_inch" id="admin_inch" placeholder="Admin Inch">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="order" class="form-label">Order of appearance on customer view</label>
                            <input type="number" value="{{ $customerlevel->order ?? old('order') }}" class="form-control" name="order" id="order" placeholder="Order of appearance">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
@endsection
