@extends('layouts.new.auth_app')
@section('title', 'Login')
@section('content')
<p class="h5 fw-semibold mb-2 text-center">ADMIN LOGIN</p>
<p class="mb-4 text-muted op-7 fw-normal text-center">Provide your valid creditials and sign in.</p>
<div class="row gy-3">
    @include('includes.alerts')
    <form action="{{route('admin.login.process')}}" method="POST">
        @csrf
    <div class="col-xl-12">
        <label for="username" class="form-label text-default">User Name</label>
        <input name="username" type="text" class="form-control form-control-lg" id="username" placeholder="user name" required>
    </div>
    <div class="col-xl-12 mb-2">
        <label for="password" class="form-label text-default d-block">Password<a href="reset-password-basic.html" class="float-end text-danger">Forget password ?</a></label>
        <div class="input-group">
            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="password" required>
            <button class="btn btn-light" type="button" id="password"><i class="ri-eye-off-line align-middle"></i></button>
        </div>
        <div class="mt-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" name="remember" id="remember">
                <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                    Remember me
                </label>
            </div>
        </div>
    </div>
    <div class="col-xl-12 d-grid mt-2">
        <button class="btn btn-lg btn-primary">Access</button>
    </div>
    </form>
</div>
{{-- <div class="text-center">
    <p class="fs-12 text-muted mt-3">Dont have an account? <a href="sign-up-basic.html" class="text-primary">Sign Up</a></p>
</div>
<div class="text-center my-3 authentication-barrier">
    <span>OR</span>
</div>
<div class="btn-list text-center">
    <button class="btn btn-icon btn-light">
        <i class="ri-facebook-line fw-bold text-dark op-7"></i>
    </button>
    <button class="btn btn-icon btn-light">
        <i class="ri-google-line fw-bold text-dark op-7"></i>
    </button>
    <button class="btn btn-icon btn-light">
        <i class="ri-twitter-line fw-bold text-dark op-7"></i>
    </button>
</div> --}}
@endsection