@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Deposit Transactions')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">All Deposit Transactions</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <form action="{{ route('admin.deposit.transactions') }}" method="GET">
                        <div class="row gy-4">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="email" class="form-label">Customer Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter customer email address" value="{{ \Request::get('email')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type" class="form-label">Payment Gateway</label>
                                    <select class="form-control" name="payment_gateway_id" id="payment_gateway_id">
                                        <option value="">Select</option>
                                        <option value="1" {{ \Request::get('payment_gateway_id') == '1' ? 'selected' : ''}}>Monnify</option>
                                        <option value="2" {{ \Request::get('payment_gateway_id') == '2' ? 'selected' : ''}}>PayVessel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label for="sys_reference" class="form-label">System Reference</label>
                                    <input type="text" class="form-control" id="sys_reference" name="sys_reference" placeholder="Enter sys reference or api reference" value="{{ \Request::get('sys_reference')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="reference" class="form-label">Provider Reference</label>
                                    <input type="reference" class="form-control" id="reference" name="reference" placeholder="Enter Payment Gateway reference" value="{{ \Request::get('reference')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="account_number" class="form-label">Account Number</label>
                                    <input type="account_number" class="form-control" id="account_number" name="account_number" placeholder="Enter Account number" value="{{ \Request::get('account_number')}}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="user_status" class="form-label">Status</label>
                                    <select class="form-control" name="user_status" id="user_status">
                                        <option value="">Select</option>
                                        <option value="SUCCESSFUL" {{ \Request::get('user_status') == 'SUCCESSFUL' ? 'selected' : ''}}>Successful</option>
                                        <option value="FAILED" {{ \Request::get('user_status') == 'FAILED' ? 'selected' : ''}}>Failed</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="service" class="form-label">Service</label>
                                    <select class="form-control" name="user_service" id="user_service">
                                        <option value="">Select</option>
                                        <option value="DEPOSIT" {{\Request::get('user_service') == 'DEPOSIT' ? 'selected' : ''}}>DEPOSIT</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" for="from">From</label>
                                    <input type="date" class="form-control" value="{{ \Request::get('from')}}" name="from">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" for="to">To</label>
                                    <input type="date" class="form-control" value="{{ \Request::get('to')}}" name="to">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label for="" class="form-label"></label>
                                <input type="submit" class="form-control btn btn-primary mt-2" value="Search">
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Customer Details</th>
                            <th>Payment Details</th>
                            @if(hasAccess('admin.single.transaction.view'))
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{$count++}}</td>
                            <td><strong>Email:</strong> {{$transaction->customer->email}} <br>
                                <strong>Account No: </strong>{{ $transaction->account_number }} <br>
                                @if( $transaction->user_status == 'SUCCESSFUL')
                                <button class="btn btn-success btn-sm" readonly>{{ucfirst($transaction->user_status) }}</button>
                                @else
                                <button class="btn btn-danger btn-sm" readonly>{{ucfirst($transaction->user_status) }}</button>
                                @endif 
                            </td>
                            <td>
                                <strong>Gateway: </strong>{{ucfirst($transaction->gateway?->name) }} <br>
                                <strong>Sys Reference: </strong>{{$transaction->sys_ref}} <br>
                                <strong>Provider Reference: </strong>{{$transaction->api_ref }} <br>
                                <strong>Date: </strong>{{ date("M jS, Y g:iA", strtotime($transaction->created_at)) }}
                            </td>
                            <td>
                                @if(hasAccess('admin.single.transaction.view'))
                                    <a target="_blank" class="btn btn-info-light btn-wave btn-sm" href="{{ route('admin.single.transaction.view', $transaction->transaction->id) }}">View</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
@section('extra-js')
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    
@endsection
