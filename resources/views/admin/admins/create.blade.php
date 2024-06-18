@extends('layouts.new.app')
@section('extra-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

@endsection
@section('title', 'Staff')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('role.index')}}">Staff</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">New Staff</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Add new staff</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <form action="{{ route('staff.store') }}" method="POST">
                    @csrf
                <div class="row gy-4">
                    <div class="col-md-6">
                        <label for="input-label" class="form-label">Full Name<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="full_name" id="input-label" placeholder="Full Name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="input-label" class="form-label">Username<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" id="input-label" placeholder="Username" required>
                    </div>
                    <div class="col-md-6">
                        <label for="input-label" class="form-label">Email Address<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" id="input-label" placeholder="Email Address" required>
                    </div>
                    <div class="col-md-6">
                        <label for="input-label" class="form-label">Password<span
                        class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="input-label" placeholder="Password" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="input-label" class="form-label">Status<span
                        class="text-danger">*</span></label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="rolepermissions" class="form-label">Role(s)<span
                        class="text-danger">*</span></label>
                        <select class="js-example-basic-multiple" name="rolepermissions[]" multiple>
                            <option value="">Select role</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->id }}">({{$rol->name}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-md-12">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
  <!-- Select2 Cdn -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Internal Select-2.js -->
    <script src="{{asset('new/assets/js/select2.js')}}"></script>
@endsection
