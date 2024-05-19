@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Edit ' .$paymentgateway->name)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('api.index')}}">Apis</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Edit {{$paymentgateway->name}}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Edit {{$paymentgateway->name}}</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <form action="{{ route('paymentgateway.update', $paymentgateway->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" value="{{$paymentgateway->name ?? old('name')}}" placeholder="Name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="slug" class="form-label">Slug<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slug" id="slug" value="{{$paymentgateway->slug ?? old('slug')}}" placeholder="Slug" required>
                        </div>
                        <div class="col-md-6">
                            <label for="warning_threshold_status" class="form-label">Warning Threshold Status</label>
                            <select class="form-control" name="warning_threshold_status" id="warning_threshold_status">
                                <option value="">Select</option>
                                <option value="active" {{ $paymentgateway->warning_threshold_status == 'active' ? 'selected' : ''}}>Active</option>
                                <option value="inactive" {{ $paymentgateway->warning_threshold_status == 'inactive' ? 'selected' : ''}}>InActive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="warning_threshold" class="form-label">Balance Warning Threshold</label>
                            <input type="number" class="form-control" name="warning_threshold" id="warning_threshold" {{$paymentgateway->warning_threshold ?? old('warning_threshold')}}>
                        </div>
                        <div class="col-md-6">
                            <label for="sandbox_base_url" class="form-label">Sandbox Base URL<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sandbox_base_url" id="sandbox_base_url" value="{{$paymentgateway->sandbox_base_url ?? old('sandbox_base_url')}}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="live_base_url" class="form-label">Live Base URL<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="live_base_url" id="live_base_url" value="{{$paymentgateway->live_base_url ?? old('live_base_url')}}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="public_key" class="form-label">Reserved Account Payment Charge
                            </label>
                            <input type="number" class="form-control" name="reserved_account_payment_charge" value="{{ $paymentgateway->reserved_account_payment_charge ?? old('reserved_account_payment_charge') }}" id="reserved_account_payment_charge" placeholder="Reserved Account Payment Charge">
                        </div>
                        <div class="col-md-6">
                            <label for="public_key" class="form-label">Contract ID</label>
                            <input type="text" class="form-control" name="contract_id" value="{{ $paymentgateway->contract_id ?? old('contract_id') }}" id="contract_id" placeholder="Contract id">
                        </div>
                        <div class="col-md-6">
                            <label for="api_key" class="form-label">Api Key</label>
                            <input type="text" class="form-control" name="api_key" id="api_key" value="{{$paymentgateway->api_key ?? old('api_key')}}">
                        </div>
                        <div class="col-md-6">
                            <label for="secret_key" class="form-label">Secret Key<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="secret_key" id="secret_key" value="{{$paymentgateway->secret_key ?? old('secret_key')}}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="public_key" class="form-label">Public Key<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="public_key" id="public_key" value="{{$paymentgateway->public_key ?? old('public_key')}}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="input-label" class="form-label">Reserved Account Payment Type<span
                            class="text-danger">*</span></label>
                            <select required class="form-control" name="reserved_account_payment_charge_type" id="reserved_account_payment_charge_type">
                                <option value="">Select</option>
                                <option value="flat" {{ $paymentgateway->reserved_account_payment_charge_type == 'flat' ? 'selected' : ''}}>Flat</option>
                                <option value="percentage" value="{{ $paymentgateway->reserved_account_payment_charge_type == 'percentage' ? 'selected' : ''}}">Percentage</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="public_key" class="form-label">Reserved Account Payment Charge<span
                            class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="reserved_account_payment_charge" value="{{ $paymentgateway->reserved_account_payment_charge ?? old('reserved_account_payment_charge') }}" id="reserved_account_payment_charge" placeholder="Public key">
                        </div>

                        <div class="col-md-6">
                            <label for="public_key" class="form-label">Card Payment Charge<span
                            class="text-danger">*</span></label>
                            <input type="number" class="form-control" value="{{ $paymentgateway->card_payment_charge }}" name="card_payment_charge" id="card_payment_charge" placeholder="Card Payment Charge">
                        </div>
                        

                        <div class="col-md-6">
                            <label for="input-label" class="form-label">Status<span
                            class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="">Select</option>
                                <option value="active" {{$paymentgateway->status == 'active' ? 'selected' : ''}} >Active</option>
                                <option value="inactive" {{$paymentgateway->status == 'inactive' ? 'selected' : ''}}>Inactive</option>
                            </select>
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
