@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Edit '.$bank->name)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('bank.index')}}">All Manual Banks</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Edit {{$bank->name}}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Edit {{$bank->name}}</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <form action="{{route('bank.update', $bank->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <label class="form-label" for="bank_name">Bank Name</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $bank->bank_name ?? old('bank_name') }}" placeholder="Enter" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="icon">Account Name</label>
                                <input type="text" class="form-control" id="account_name" name="account_name" value="{{ $bank->account_name ?? old('account_name') }}" placeholder="Enter account name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="display_name">Account Number</label>
                                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $bank->account_number ??old('account_number') }}" placeholder="Enter name" required>
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
