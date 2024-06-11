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
@section('extra-css')
    <style>
        .title{
            font-weight: bold;
        }
    </style>
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
                        <form action="" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <fieldset class="form-group">
                                        <label for="transaction_id">Transaction ID</label>
                                        <input type="text" class="form-control" id="transaction_id" name="transaction_id" placeholder="Enter transaction ID" value="{{ \Request::get('transaction_id')}}">
                                    </fieldset>
                                </div>
                                <div class="col-md-2">
                                    <fieldset class="form-group">
                                        <label for="type">Type</label>
                                        <select class="form-control" name="type" id="type">
                                            <option value="">Select</option>
                                            <option value="credit" {{ \Request::get('type') == 'credit' ? 'selected' : ''}}>Credit</option>
                                            <option value="debit" {{ \Request::get('type') == 'debit' ? 'selected' : ''}}>Debit</option>
                                        </select>
                                    </fieldset>
                                    
                                </div>
                                
                                <div class="col-md-3">
                                    <fieldset class="form-group">
                                        <label for="from">From</label>
                                        <input type="date" class="form-control" value="{{ \Request::get('from')}}" name="from">
                                    </fieldset>
                                </div>
                                <div class="col-md-3">
                                    <fieldset class="form-group">
                                        <label for="to">To</label>
                                        <input type="date" class="form-control" value="{{ \Request::get('to')}}" name="to">
                                    </fieldset>
                                </div>

                                <div class="col-md-1">
                                    <label style="color:white">sd</label>
                                    <button class="form-control btn btn-primary"><i class='fa fa-search'></i></button>
                                </div>
                            </div>
                        </form>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            @foreach ($transactions as $transaction)
                            <div class="col-md-4" style="box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;padding-top:10px;padding-bottom: 10px;">
                                <div class="">
                                    <span class="title">Amount</span>:
                                    <small style="color:{{$transaction->type == 'debit' ? 'red' : 'green' }}">{{$transaction->type == 'debit' ? '- ' : '+ '}}{!! settings()->currency !!}{{ number_format($transaction->amount, 2) }}
                                    {{$transaction->type == 'debit' ? '(Debit)' : '(Credit)' }}
                                    </small> 
                                    
                                    <br>
                                    
                                    <span class="title">Transaction Id</span>:
                                    <small>
                                        {{ $transaction->transaction_id }}</strong>
                                    </small> <br>
                                    <span class="title">Initiated by</span>:
                                    <small>
                                        {{ $transaction->ref_code }}</strong>
                                    </small> <br>
                                    <span class="title">Description</span>:
                                    <small>
                                        {{ $transaction->description }}</strong>
                                    </small> <br>
                                    <span class="title">Date</span>:
                                    <small>
                                        {{ date("M jS, Y g:iA", strtotime($transaction->created_at)) }}
                                    </small> <br>

                                </div>
                                
                            </div>
                            <hr>
                            @endforeach
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! $transactions->appends($_GET)->links() !!}
            </div>
        </div>
        
    </div>
</div>

@endsection