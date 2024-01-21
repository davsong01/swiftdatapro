@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">
        <div id="response" style="text-align: center;">
            <h4>CashBack Summary</h4>
            <p style="color: red;font-weight: bold;">This shows the proccess of your earning on their each purchase.</p>
        </div>
    </div>
</div>

@endsection
@section('body')
<div class="col-xl-12">
  <div class="alert" id="temp" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;">

        <div id="response" style="text-align: left;">
        <span>Commission Balance 
            <span><button class="btn btn-success" style="font-size:10px;display:none" id="withdrawButton">Withdraw to wallet</button></span>
        </span>
        <br/>

        <span style="color: #940798;font-weight: bold;font-size: 25px;">₦{{ number_format(auth()->user()->cashback, 2) }}</span>
        </div>
                  
  </div>
</div>
<div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">
    <div class="container-fluid p-0 sm_padding_15px">
        <div class="row">
            @foreach($lettr as $transaction)
            <div class="col-md-6" style="margin-bottom:10px;color:white;padding:20px;background-color:{{ $transaction->type == 'credit' ? '#199263' : '#DB1A22' }};cursor:pointer;">
                <span>Initiated by: {{ $transaction->user->full_name }} </span><br/>
                <span>Type: {{ $transaction->type }} </span><br/>
                <span>Amount: ₦{{ $transaction->amount }}</span><br/>
                <span>Description: {{ $transaction->description }}</small><br/>
                <span>Date: {{ $transaction->created_at }} </small><br/>
            </div>
            @endforeach
        </div>
        {{$lettr->links()}}
    </div>
</div>
@endsection