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
{{-- <div class="col-xl-12">
  <div class="alert" id="temp" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;">

        <div id="response" style="text-align: left;">
        <span>Commission Balance 
            <span><button class="btn btn-success" style="font-size:10px;display:none" id="withdrawButton">Withdraw to wallet</button></span>
        </span>
        <br/>

        <span style="color: #940798;font-weight: bold;font-size: 25px;">₦{{ number_format(auth()->user()->cashback, 2) }}</span>
        </div>
                  
  </div>
</div> --}}
<div class="col-xl-12">
    <div class="white_card card_height_100 mb_30 sales_card_wrapper">
        <div class="white_card_header d-flex justify-content-end">
            <a href="{{ route('password.change')}}"><button class="export_btn">Change Password</button></a>
        </div>


        <div class="alert" style="background-color: #fff;">

            <div class="input-group mb-3">
                <div class="input-group-text">
                    <span class="" id="basic-addon1"><i class="fa fa-user" style="font-size: 25px;"></i></span>
                </div>
                <input type="text" class="form-control" value="{{ auth()->user()->full_name }}" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-text">
                    <span class="" id="basic-addon1"><i class="fa fa-phone" style="font-size: 25px;"></i></span>
                </div>
                <input type="text" class="form-control" value="{{ auth()->user()->mobile ?? old('phone') }}" aria-describedby="basic-addon1">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-text">
                    <span class="" id="basic-addon1"><i class="fa fa-envelope" style="font-size: 25px;"></i></span>
                </div>
                <input type="text" class="form-control" value="{{ auth()->user()->email ?? old('email') }}" aria-describedby="basic-addon1">
            </div>

            <button class="btn btn-primary" style="width: 100%">UPDATE PROFILE</button>


            <hr>

            <input type="checkbox" name=""> I want to deactivate my account.
            <br>
            <button class="btn btn-danger">Deactivate</button>
        </div>

    </div>
</div>
@endsection