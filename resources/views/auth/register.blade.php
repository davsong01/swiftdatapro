{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
@extends('includes.customer.app')
@section('title')
Register
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
                                <span class="app-brand-text demo text-body fw-bolder">{{ config('app.name') }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <!-- <h3>Enjoy - <strong>{{ config('app.name') }}</strong></h3> -->
                        <h5>PART A: Personal Information</h5>
                        <p class="mb-4" style="color:red">Please ensure you submit valid details and accessible email address as they are used for further verification</p>

                        @include('partials.alerts')
                        <form method="POST" action="{{ route('register') }}">
                        @csrf
                            <div class="row" style="display:flex">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label required">First Name</label>
                                    <input type="hidden" id="myrefferal" name = "referral" value="{{ $myrefferal }}">
                                    
                                    <input required type="text" name="full_name" value="{{ old('full_name') }}" class="form-control" id="full_name" placeholder="Enter your first name" autofocus />
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Middle Name (Optional) </label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name') }}"  placeholder="Enter your middle name" autofocus />
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label required">Last Name</label>
                                    <input required type="text" class="form-control" id="last_name"  name="last_name" value="{{ old('last_name') }}"  placeholder="Enter your last name" autofocus />
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label required">Email</label>
                                    <input type="email"  name="email" value="{{ old('email') }}" class="form-control" id="email_address" placeholder="Enter your email" autofocus required />
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="mobile" class="form-label required">Mobile Number</label>
                                    <input required   name="mobile" value="{{ old('mobile') }}" type="mobile" class="form-control" id="mobile" placeholder="Enter your mobile number" autofocus />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input required  name="username" value="{{ old('username') }}" type="text" class="form-control" id="username" placeholder="Enter your username" autofocus />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label required">Gender</label>
                                    <select name="gender" class="form-control" id="gender" required>
                                        <option value="">Select Option</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label required" for="password">Password</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>

                            </div>

                            <hr>
                            <!-- <div class="row" style="display:flex">
                                <h5>PART B: Smart KYC Verification</h5>
                                <p class="mb-4" style="color:red">Note: Your information is safe and not shared. This is used for customer verification only. Please read and accept our privacy policy & terms below before submiting.</p>
                            </div> -->
                            <div class="row" style="display:flex">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input required class="form-check-input" type="checkbox" id="remember-me" required />
                                        <label class="form-check-label" for="remember-me"> I agree to <a href="{{ route('tac') }}" target="_blank"> <strong>terms and conditions</strong></a> </label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <button class="btn btn-primary d-grid w-100" id="submitBtn">Sign Up</button>
                                </div>
                                <p class="text-center">
                                    <span>Already a member?</span>
                                    <a href="/login">
                                        <span>Sign In</span>
                                    </a>
                                </p>

                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection 
