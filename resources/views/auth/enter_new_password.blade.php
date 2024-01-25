@extends('includes.customer.app')
@section('title')
Login
@endsection
@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner" style="max-width: 800px;">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="/" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img src="assets/img/favicon/favicon.png" height="30px">
                            </span>
                            <span
                                class="app-brand-text demo text-body fw-bolder">{{ config('app.name') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Welcome to {{ config('app.name') }}! 👋</h4>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>

                    @include('partials.alerts')
                    <form method="POST" action="{{ route('password.reset.change.process') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{$idx}}">
                        <input type="hidden" name="token" value="{{\Request::get('token') }}">
                        <div class="mb-3">
                            <label for="password" class="form-label required">Enter new password</label>
                            <input type="password" id="password" name="password" class="form-control"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                        </div>
                   
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" id="submitBtn">Change Password</button>
                        </div>

                    </form>
                    <p class="text-center">
                        <span>New on our platform?</span>
                        <a href="/register">
                            <span>Create an account</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('extra-js')
    {{-- <script type="text/javascript">
        $("#form-data").submit(function (event) {
            $("#submitBtn").html('Please wait...');
            var formData = {
                username: $("#username").val(),
                password: $("#password").val(),
                req_token: $("#req_token").val(),
            };
            $.ajax({
                url: 'app/resources/login',
                method: 'POST',
                dataType: 'json',
                data: formData,
                success: function (response_fd) {
                    console.log(response_fd);
                    if (response_fd.status == 'success') {
                        $('#submitBtn').html('Redirecting...');
                        setTimeout(function () {
                            window.location.href = response_fd.redirect;
                        }, 1000);
                    } else {
                        $("#submitBtn").html('Sign In');
                        $("#response").html(response_fd.message);
                    }
                }
            });

            event.preventDefault();
        });
    </script> --}}
    @endsection