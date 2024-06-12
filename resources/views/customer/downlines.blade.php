@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">
        <div id="response" style="text-align: center;">
            <h4>My Downlines</h4>
            <p style="color: red;font-weight: bold;">This shows the lists of who used your referral code.</p>
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
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            @foreach ($lettr as $downline)
                            <div class="col-md-4" style="box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;padding-top:10px;padding-bottom: 10px;" >
                                <div style="margin-bottom:10px;color:white;padding:20px;background-color:{{ $downline->status == 1 ? '#199263' : '#DB1A22' }}">
                                    <span>{{ $downline->status == 1 ? 'Verified' : 'Not Verified'}}</span><br/>
                                    <span>Username: {{ $downline->username }}</span><br/>
                                    <span>Join Date: {{ $downline->reg_date ?? $downline->reg_date }} </small><br/>
                                    <span>Level: {{$downline->level}}</span><br/>
                                </div>
                                
                            </div>
                            <hr>
                            @endforeach
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! $lettr->appends($_GET)->links() !!}
            </div>
        </div>
        
    </div>
</div>


{{-- <div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">
    <div class="container-fluid p-0 sm_padding_15px">
        <div class="row">
            @foreach($lettr as $transaction)
            <div class="col-md-6" style="margin-bottom:10px;color:white;padding:20px;background-color:{{ $transaction->status == 1 ? '#199263' : '#DB1A22' }};cursor:pointer;">
                <span>{{ $transaction->status == 1 ? 'Verified' : 'Not Verified'}}</span><br/>
                <span>Username: {{ $transaction->username }}</span><br/>
                <span>Join Date: {{ $transaction->reg_date}} </small><br/>
                <span>Level: {{$transaction->level}}</span><br/>
            </div>
            @endforeach
        </div>
        {{$lettr->links()}}
    </div>
</div> --}}
@endsection
@section('scripts')

@endsection