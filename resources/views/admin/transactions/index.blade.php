@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Transactions')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">All Transactions</li>
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
                    <form action="{{ route('admin.transactions') }}" method="GET">
                        {{-- @csrf --}}
                        <div class="row gy-4">
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label for="sys_reference" class="form-label">System Reference</label>
                                    <input type="text" class="form-control" id="sys_reference" name="sys_reference" placeholder="Enter sys reference or api reference" value="{{ \Request::get('sys_reference')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="billersCode" class="form-label">Biller</label>
                                    <input type="billersCode" class="form-control" id="billersCode" name="billersCode" placeholder="Enter biller" value="{{ \Request::get('billersCode')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="email" class="form-label">Customer Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter customer email address" value="{{ \Request::get('email')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type" class="form-label">Transaction Type</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="">Select</option>
                                        <option value="credit" {{ \Request::get('type') == 'credit' ? 'selected' : ''}}>Credit</option>
                                        <option value="debit" {{ \Request::get('type') == 'debit' ? 'selected' : ''}}>Debit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="service" class="form-label">Service</label>
                                    <select class="form-control" name="service" id="service">
                                        <option value="">Select</option>
                                        <option value="DEPOSIT" {{ \Request::get('service') == 'DEPOSIT' ? 'selected' : ''}}>DEPOSIT</option>
                                        <option value="UPGRADE" {{ \Request::get('service') == 'UPGRADE' ? 'selected' : ''}}>UPGRADE</option>
                                        <option value="COMMISSION WITHDRAWAL" {{ \Request::get('service') == 'COMMISSION WITHDRAWAL' ? 'selected' : ''}}>COMMISSION WITHDRAWAL</option>
                                        <option value="ADMIN-CREDIT" {{ \Request::get('service') == 'ADMIN-CREDIT' ? 'selected' : ''}}>ADMIN-CREDIT</option>
                                        <option value="ADMIN-DEBIT" {{ \Request::get('service') == 'ADMIN-DEBIT' ? 'selected' : ''}}>ADMIN-DEBIT</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ \Request::get('service') == $product->id ? 'selected' : ''}}>{{ $product->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="unique_element" class="form-label">Unique Element</label>
                                    <input type="text" class="form-control" id="unique_element" name="unique_element" placeholder="Enter unique element" value="{{ \Request::get('unique_element') }}">
                                </div>
                            </div> --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Select</option>
                                        <option value="SUCCESSFUL" {{ \Request::get('status') == 'SUCCESSFUL' ? 'selected' : ''}}>Successful</option>
                                        <option value="FAILED" {{ \Request::get('status') == 'FAILED' ? 'selected' : ''}}>Failed</option>
                                        <option value="ATTENTION-REQUIRED" {{ \Request::get('status') == 'ATTENTION-REQUIRED' ? 'selected' : ''}}>Attention Required</option>
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
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <label class="form-label" for="customCheck2">Download</label> <br>
                                        <input type="checkbox" name="download" class="custom-control-input" id="customCheck2">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
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
                            <th>Customer</th>
                            <th>Payment Details</th>
                            {{-- <th>Unique Element</th> --}}
                            @if(hasAccess('admin.single.transaction.view'))
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{$count++}}</td>
                            <td>
                                <strong>
                                @if(!empty($transaction->product_id))
                                    {{$transaction->product->display_name ?? ''}} @if(!empty($transaction->variation->system_name)) ({{$transaction->variation->system_name}})@endif
                                @else
                                    {{ $transaction->buyer_service }}
                                @endif
                                </strong>
                                <br>
                                <span style="color:rgb(27, 20, 220)"><strong>System Reference ID: </strong> <br>{{ $transaction->sys_ref }}</span> <br>
                                <span style="color:crimson"><strong>API Reference: </strong> <br>{{ $transaction->api_ref }}</span> <br>
                                <strong><a target="_blank" href="{{ route('customers.edit', $transaction->customer?->id) }}">{{ $transaction->customer->full_name  }}</a> <br>
                                <a target="_blank" href="{{ route('customers.edit', $transaction->customer?->id) }}">{{ $transaction->customer->email  }}</a> <br>
                                </strong>
                                {{ $transaction->mobile_number }} <br>
                                {{ date("M jS, Y g:iA", strtotime($transaction->created_at)) }} <br>
                                @if($transaction->status == 'DELIVERED' || $transaction->buyer_status == 'SUCCESSFUL')
                                <button class="btn btn-success btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                @else
                                <button class="btn btn-danger btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                @endif 
                            </td>
                            <td>
                                <small>
                                    <strong>Charge: </strong>{!! settings()->currency. number_format($transaction->charges, 2) !!} <br>
                                    <strong>Unit Price: </strong>{!! settings()->currency. number_format($transaction->buyer_amount, 2) !!} <br>
                                    <strong>Discount Applied: </strong>{!! settings()->currency. number_format($transaction->discount, 2) !!} <br>
                                    <strong>Total Amount: </strong>{!! settings()->currency. number_format($transaction->total_amount, 2) !!} <br>
                                    <strong>Initial Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_prebal, 2) !!} <br>
                                    <strong>Final Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_postbal, 2) !!} <br>
                                </small>
                            </td>
                            
                            {{-- <td>{{ $transaction->unique_element }}</td> --}}
                            <td>
                                @if(hasAccess('admin.single.transaction.view'))
                                    <a class="btn btn-info-light btn-wave btn-sm" href="{{ route('admin.single.transaction.view', $transaction->id) }}">View</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- {{ $transactions->appends($query) }} --}}

            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
@section('extra-js')
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    
@endsection
