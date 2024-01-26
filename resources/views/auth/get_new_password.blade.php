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
                    <h4 class="mb-2">Hello {{ $user->full_name }}!</h4>
                    <p class="mb-4">In order to further protect your account, you are required to reset your password. Please click the button below to get a reet email with instructions on how to RESET your password</p>

                    @include('partials.alerts')
                    <form method="POST" action="{{ route('process.password.reset.new') }}">
                        @csrf
                        <input type="hidden" name="idxse" value="{{ base64_encode($user->id) }}">
                        <button style="padding-bottom: 13px;" class="btn btn-info btn-block form-control" style="width: 100%" id="submitBtn">SEND ME RESET EMAIL</button>
                    </form>
                    
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