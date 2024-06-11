@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">
        <div id="response" style="text-align: center;">
            <h4>Add Money</h4>
            <p style="color: red;font-weight: bold;">Transfer money into one of the reserved account below and your
                wallet will be automatically credited.</p>
        </div>
    </div>
</div>

@endsection
@section('body')
<div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">
    <div class="row">
        <div class="col-md-12">
            <p style="color:black; margin-bottom:20px">Deposit/Transfer Money using any of the automated transfer payment options below.</p>
            <p style="color:red;margin-bottom:10px"><i class="fa fa-info-circle"></i> #30 charges is incurred on transfer made to this/these bank/banks. We advise you to make use of this payment option if you are funding any amount above #3,000</p>
        </div>
        @foreach ($accounts as $account) 
        <div class="col-md-12 alert alert-primary" style="background-color: #94079803;color:black;font-size: 15px;border-color: #940798;">           
            <p style="color:#940798;font-weight: 500;"><strong>Ban Name: </strong>{{ $account->bank_name }}<br><strong>Account Number: </strong>{{$account['account_number']}} <br><strong>Account Name: </strong> {{$account->account_name}} 
            </p>
        </div>
        @endforeach
    </div>
</div>
@endsection
