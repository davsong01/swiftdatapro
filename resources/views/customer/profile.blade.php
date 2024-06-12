@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-12">
    <div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
        <div class="page_title_left">
            <h3 class="f_s_25 f_w_700 dark_text">My Account</h3>
            <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Profile</a></li>
                <li class="breadcrumb-item active">Default</li>
            </ol>
        </div>

    </div>
</div>
@endsection
@section('body')
<div class="col-xl-12">
    <div class="white_card card_height_100 mb_30 social_media_card">
        <div class="white_card_header">
            <div class="main-title">
                <h3 class="m-0">{{ config('app.name') }}</h3>
                <span>Level : {{ auth()->user()->level}}</span>
            </div>
        </div>

        <div class="media_thumb ml_25">
            <center>
                <h3> {{ auth()->user()->full_name }}</h3>
            </center>
        </div>

        <div class="media_card_body" style="width: 100%">
            <div class="media_card_list">


            </div>
        </div>

    </div>
</div>
<div class="col-xl-12">
    <div class="white_card card_height_100 mb_30 sales_card_wrapper">
        <div class="white_card_header d-flex justify-content-end">
            <a href="{{ route('password.change')}}"><button class="export_btn">Change Password</button></a>
        </div>


        <div class="alert" style="background-color: #fff;">
            <form action="{{ route('customer.profile.update') }}" method="POST">
                @csrf
                
                <label class="label-title">First Name</label> 
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="full_name" value="{{ auth()->user()->full_name }}">
                </div>

                <label class="label-title">Middle Name</label> 
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="middle_name" value="{{ auth()->user()->middle_name ?? old('middle_name') }}">
                </div>

                <label class="label-title">Last Name</label> 
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="last_name" value="{{ auth()->user()->last_name ?? old('last_name') }}">
                </div>
                <label class="label-title">Phone Number</label> 
                <div class="input-group mb-3">
                    <input type="text" class="form-control" disabled name="mobile" value="{{ auth()->user()->mobile ?? old('mobile') }}">
                </div>

                <label class="label-title">Email</label> 
                <div class="input-group mb-3">
                    <input type="text" class="form-control" value="{{ auth()->user()->email }}" readonly>
                </div>

                <label class="label-title">Gender</label> 
                <div class="input-group mb-3">
                    <select name="gender"  class="form-control" id="gender" name="gender">
                        <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : ''}}>Male</option>
                        <option value="female" {{ auth()->user()->gender == 'female' ? 'selected' : ''}}>Female</option>
                    </select>
                </div>
                <button class="btn btn-primary" style="width: 100%">UPDATE PROFILE</button>
            </form>
            <hr>

            {{-- <input type="checkbox" name=""> I want to deactivate my account.
            <br>
            <button class="btn btn-danger">Deactivate</button> --}}
        </div>

    </div>
</div>
@endsection