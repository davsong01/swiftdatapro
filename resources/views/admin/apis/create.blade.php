@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Apis')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('api.index')}}">Apis</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">New API</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Add Api Provider</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <form action="{{ route('api.store') }}" method="POST">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="slug" class="form-label">Slug<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slug" id="slug" placeholder="Slug" required>
                        </div>
                        <div class="col-md-6">
                            <label for="warning_threshold_status" class="form-label">Warning Threshold Status</label>
                            <select class="form-control" name="warning_threshold_status" id="warning_threshold_status">
                                <option value="">Select</option>
                                <option value="active" {{ old('warning_threshold_status') == 'active' ? 'selected' : ''}}>Active</option>
                                <option value="inactive" {{ old('warning_threshold_status') == 'inactive' ? 'selected' : ''}}>InActive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="warning_threshold" class="form-label">Balance Warning Threshold</label>
                            <input type="number" class="form-control" name="warning_threshold" id="warning_threshold" placeholder="Balance Warning Threshold">
                        </div>
                        <div class="col-md-6">
                            <label for="sandbox_base_url" class="form-label">Sandbox Base URL<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sandbox_base_url" id="sandbox_base_url" placeholder="Sandbox Base UR" required>
                        </div>
                        <div class="col-md-6">
                            <label for="live_base_url" class="form-label">Live Base URL<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="live_base_url" id="live_base_url" placeholder="Sandbox Base UR" required>
                        </div>
                        <div class="col-md-6">
                            <label for="api_key" class="form-label">Api Key<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="api_key" id="api_key" placeholder="Api key" required>
                        </div>
                        <div class="col-md-6">
                            <label for="secret_key" class="form-label">Secret Key<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="secret_key" id="secret_key" placeholder="Secret key" required>
                        </div>
                        <div class="col-md-6">
                            <label for="public_key" class="form-label">Public Key<span
                            class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="public_key" id="public_key" placeholder="Public key" required>
                        </div>
                        <div class="col-md-6">
                            <label for="input-label" class="form-label">Status<span
                            class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="">Select</option>
                                <option value="active">Enabled</option>
                                <option value="inactive">Inactive</option>
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
