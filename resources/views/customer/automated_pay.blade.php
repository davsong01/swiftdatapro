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
        </div>

        @if($payvesel->status == 'ON')
            <?php
            $payve = !empty(auth()->user()->payvessel_reserved_account) ? json_decode(auth()->user()->payvessel_reserved_account, true) : [];
            $bankinfos1[] = $payve['banks'];
        ?>
        
        <div class="col-md-6 alert alert-primary" style="background-color: #94079803;color:black;font-size: 15px;border-color: #940798;">
            <h6>Automated Payment 1</h6>
            <p style="color:red"><i class="fa fa-info-circle"></i> #30 charges is incurred on transfer made to this/these bank/banks. We advise you to make use of this payment option if you are funding any amount above #5,000.</p>
                
            <div class="row">
                @foreach ($bankinfos1 as $bank) 
                    @foreach ($bank as $info)
                        <div class="col-md-6" style="margin-bottom: 20px;">
                            <p style="color:#940798;font-weight: 500;"><strong>Bank: </strong>{{$info['bankName']}}<br><strong>Acct Number: </strong>{{$info['accountNumber']}} <br><strong>Acct Name: </strong> {{$info['accountName']}} 
                            </p>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
        @endif

        @if($monnify->status == 'ON')
            <?php
                $json_decoded = json_decode(auth()->user()->reserved_account, true);
                $bankinfos[] = $json_decoded['responseBody']["accounts"];
            ?>

            <div class="col-md-6 alert alert-primary"
                style="background-color: #94079803;color:black;font-size: 15px;border-color: #940798;">

                <h6>Automated Payment 2</h6>
                    <p style="color:red"><i class="fa fa-info-circle"></i> Transfer money into one of the reserved account(s) below and your wallet will be automatically credited. Though moniepoint is fast. Note that 1% charge is incurred on every funding.</p>
                    
                    <div class="row">
                        @foreach ($bankinfos as $bank) 
                            @foreach ($bank as $info)
                                <div class="col-md-6" style="margin-bottom: 20px;"><p style="color:#940798;font-weight: 500;"><strong>Bank: </strong>{{ $info['bankName'] }}<br><strong>Acct Number: </strong>{{ $info['accountNumber'] }} <br><strong>Acct Name: </strong> {{ $info['accountName'] }} </p></div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
