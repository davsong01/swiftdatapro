@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Add Customer Level')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('customerlevel.index')}}">Customer Levels</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">New Customer Level</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Add Customer Level</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <form action="{{ route('customerlevel.store') }}" method="POST">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="slug" class="form-label">Upgrade Amount<span
                            class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="admin_inch" id="upgrade_amount" placeholder="Upgrade Amount" required>
                        </div>
                        <div class="col-md-6">
                            <label for="slug" class="form-label">Admin Inch</label>
                            <input type="number" class="form-control" name="admin_inch" id="admin_inch" placeholder="Admin Inch">
                        </div>
                        <div class="col-md-6">
                            <label for="order" class="form-label">Order of appearance on customer view</label>
                            <input type="number" class="form-control" name="order" id="order" placeholder="Order of appearance">
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
