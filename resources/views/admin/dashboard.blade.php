@extends('layouts.new.app')
@section('content')
<div class="row">
    <div class="card-header" style="margin-bottom:20px">
        @include('includes.alerts')
    </div>
    <div class="col-md-12 col-xl-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-center ecommerce-icon px-0">
                                <span class="rounded p-3 bg-primary-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-white primary" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24"/><path d="M18,6h-2c0-2.21-1.79-4-4-4S8,3.79,8,6H6C4.9,6,4,6.9,4,8v12c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8C20,6.9,19.1,6,18,6z M12,4c1.1,0,2,0.9,2,2h-4C10,4.9,10.9,4,12,4z M18,20H6V8h2v2c0,0.55,0.45,1,1,1s1-0.45,1-1V8h4v2c0,0.55,0.45,1,1,1s1-0.45,1-1V8 h2V20z"/></g></svg>
                                </span>
                            </div>
                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Total Wallet Balances</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ settings()->currency.number_format($wallet_balance)}}
                                    </span>
                                </div>
                                <br> <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-center justify-content-center ecommerce-icon px-0">
                                <span class="rounded p-3 bg-primary-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-white primary" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><g><rect fill="none" height="24" width="24"/><path d="M18,6h-2c0-2.21-1.79-4-4-4S8,3.79,8,6H6C4.9,6,4,6.9,4,8v12c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8C20,6.9,19.1,6,18,6z M12,4c1.1,0,2,0.9,2,2h-4C10,4.9,10.9,4,12,4z M18,20H6V8h2v2c0,0.55,0.45,1,1,1s1-0.45,1-1V8h4v2c0,0.55,0.45,1,1,1s1-0.45,1-1V8 h2V20z"/></g></svg>
                                </span>
                            </div>
                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Total Commission Balances</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ settings()->currency.number_format($earnings_balance)}}
                                    </span>
                                </div>
                                 <br> <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                                <span class="rounded p-3 bg-success-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-white success" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Total Success</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ settings()->currency.number_format($t_success->sum('buyer_amount'))}} <small>({{$t_success->count()}})</small>
                                    </span>
                                </div>
                                 <br> <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                <span class="rounded p-3 bg-warning-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-white warning" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Today's Total Pending</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ settings()->currency.number_format($t_pending->sum('buyer_amount'))}} <small>({{$t_pending->count()}})</small>
                                    </span>
                                </div>
                                 <br> <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon danger px-0">
                                <span class="rounded p-3 bg-danger-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-white danger" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Today's Total Failed</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ settings()->currency.number_format($t_failed->sum('buyer_amount'))}} <small>({{$t_failed->count()}})</small>
                                    </span>
                                </div>
                                 <br> <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="rounded p-3 bg-secondary-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-white secondary" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0,0h24v24H0V0z" fill="none"/><g><path d="M19.5,3.5L18,2l-1.5,1.5L15,2l-1.5,1.5L12,2l-1.5,1.5L9,2L7.5,3.5L6,2v14H3v3c0,1.66,1.34,3,3,3h12c1.66,0,3-1.34,3-3V2 L19.5,3.5z M15,20H6c-0.55,0-1-0.45-1-1v-1h10V20z M19,19c0,0.55-0.45,1-1,1s-1-0.45-1-1v-3H8V5h11V19z"/><rect height="2" width="6" x="9" y="7"/><rect height="2" width="2" x="16" y="7"/><rect height="2" width="6" x="9" y="10"/><rect height="2" width="2" x="16" y="10"/></g></svg>
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Today's Total Profit</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{settings()->currency.number_format($t_profit)}}
                                    </span>
                                </div>
                                <br> <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                                <span class="rounded p-3 bg-success-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-white success" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Total Customers</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ number_format($customers) }} 
                                    </span>
                                </div>
                                <br> <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $transaction = topProduct(); ?>
            @if(!empty($transaction))
            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                                <span class="rounded p-3 bg-success-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg-white success" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Today's Top Seller: <strong> <br>{{ $transaction->product->display_name.' ('. $transaction->variation?->system_name.')' }}</strong></div>
                                
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        <small> {{ settings()->currency.number_format($transaction->total_buyer_amount) }} ({{number_format($transaction->count)}})</small> 
                                    
                                    </span>
                                    <br>
                                    <small><strong>Provider: </strong><i>{{ $transaction->api?->name }}</i></small> |
                                    <small><strong>Profit: </strong><i>{{ settings()->currency.number_format($transaction->total_profit) }}</i></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between d-sm-flex d-block">
                        <div class="card-title">
                            Recent Transactions
                        </div>
                        <div>
                            <ul class="nav nav-tabs justify-content-end nav-tabs-header mb-0 d-sm-flex d-block mt-sm-0 mt-2" role="tablist">
                                
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#all" aria-selected="true">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#pending" aria-selected="true">Pending</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#completed" aria-selected="true">Completed</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#cancelled-orders" aria-selected="true">Failed</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <div class="tab-pane show active text-muted border-0 p-0" id="all"
                                role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap mb-0">
                                        <tbody class="active-tab">
                                            @if(!empty($t_all))
                                                @foreach($t_all as $transaction)
                                                <tr>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="align-items-center">
                                                                <strong>
                                                                @if(!empty($transaction->product_id))
                                                                    {{$transaction->product->display_name ?? ''}} @if(!empty($transaction->variation->system_name)) ({{$transaction->variation->system_name}})@endif
                                                                @else
                                                                    {{ $transaction->buyer_service }}
                                                                @endif
                                                                </strong> <br>
                                                                <a target="_blank" href="{{ route('customers.edit', $transaction->user_id)}}">{{ $transaction->customer->email }} </a><br>
                                                                <strong>{{ $transaction->customer->full_name }}</strong><br>
                                                                {{ $transaction->mobile_number }} <br>
                                                                {{ date("M jS, Y g:iA", strtotime($transaction->created_at)) }} <br>
                                                                @if($transaction->status == 'success' || $transaction->buyer_status == 'SUCCESSFUL')
                                                                <button class="btn btn-success btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                                                @else
                                                                <button class="btn btn-warning btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                                                @endif 
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="align-items-center">
                                                                <span style="color:rgb(27, 20, 220)"><strong>System Reference ID: </strong> <br>{{ $transaction->sys_ref }}</span> <br>
                                                                <span style="color:crimson"><strong>API Reference: </strong> <br>{{ $transaction->api_ref }}</span> 
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="align-items-center">
                                                            <span style="color:blue">
                                                            @if(!empty($transaction->provider_id))
                                                            <strong>Provider: </strong>{{ $transaction->api->name }} <br>
                                                            @endif
                                                            @if(!empty($transaction->wallet_funding_provider))
                                                            <strong>Provider: </strong>{{ $transaction->gateway->name }} <br>
                                                            @endif
                                                            </span>
                                                            <small>
                                                            <strong>Charge: </strong>{!! settings()->currency. number_format($transaction->charges, 2) !!} <br>
                                                            <strong>Source Price: </strong>{!! settings()->currency. number_format($transaction->src_price, 2) !!} <br>
                                                            <strong>Discount Applied: </strong>{!! settings()->currency. number_format($transaction->discount, 2) !!} <br>
                                                            <strong>Initial Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_prebal, 2) !!} <br>
                                                            <strong>Final Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_postbal, 2) !!} <br>
                                                            <strong>Total Amount: </strong>{!! settings()->currency. number_format($transaction->buyer_amount, 2) !!} <br>
                                                            @if(in_array('view.sensitive.data', allowedPermissions()))
                                                            <span style="color:blue"><strong>Provider Price: </strong>{!! settings()->currency. number_format($transaction->src_price, 2) !!}</span> <br>
                                                            <span style="color:green"><strong>Profit: </strong>{!! settings()->currency. number_format($transaction->profit, 2) !!}</span> <br>
                                                            @endif
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 10%;">
                                                        <div class="align-items-center">
                                                            @if(hasAccess('admin.single.transaction.view'))
                                                                <a target="_blank" class="btn btn-info-light btn-wave btn-sm" href="{{ route('admin.single.transaction.view', $transaction->id) }}">View</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane show text-muted border-0 p-0" id="pending"
                                role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap mb-0">
                                        <tbody class="active-tab">
                                            @if(!empty($pending))
                                                @foreach($pending as $transaction)
                                                <tr>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="align-items-center">
                                                                <strong>
                                                                @if(!empty($transaction->product_id))
                                                                    {{$transaction->product->display_name ?? ''}} @if(!empty($transaction->variation->system_name)) ({{$transaction->variation->system_name}})@endif
                                                                @else
                                                                    {{ $transaction->buyer_service }}
                                                                @endif
                                                                </strong> <br>
                                                                <a target="_blank" href="{{ route('customers.edit', $transaction->user_id)}}">{{ $transaction->customer->email }} </a><br>
                                                                <strong>{{ $transaction->customer->full_name }}</strong> <br>
                                                                {{ $transaction->mobile_number }} <br>
                                                                {{ date("M jS, Y g:iA", strtotime($transaction->created_at)) }} <br>
                                                                @if($transaction->status == 'success' || $transaction->buyer_status == 'SUCCESSFUL')
                                                                <button class="btn btn-success btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                                                @else
                                                                <button class="btn btn-warning btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                                                @endif 
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="align-items-center">
                                                                <span style="color:rgb(27, 20, 220)"><strong>System Reference ID: </strong> <br>{{ $transaction->sys_ref }}</span> <br>
                                                                <span style="color:crimson"><strong>API Reference: </strong> <br>{{ $transaction->api_ref }}</span> 
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="align-items-center">
                                                            <span style="color:blue">
                                                            @if(!empty($transaction->provider_id))
                                                            <strong>Provider: </strong>{{ $transaction->api->name }} <br>
                                                            @endif
                                                            @if(!empty($transaction->wallet_funding_provider))
                                                            <strong>Provider: </strong>{{ $transaction->gateway->name }} <br>
                                                            @endif
                                                            </span>
                                                            <small>
                                                            <strong>Charge: </strong>{!! settings()->currency. number_format($transaction->charges, 2) !!} <br>
                                                            <strong>Source Price: </strong>{!! settings()->currency. number_format($transaction->src_price, 2) !!} <br>
                                                            <strong>Discount Applied: </strong>{!! settings()->currency. number_format($transaction->discount, 2) !!} <br>
                                                            <strong>Initial Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_prebal, 2) !!} <br>
                                                            <strong>Final Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_postbal, 2) !!} <br>
                                                            <strong>Total Amount: </strong>{!! settings()->currency. number_format($transaction->buyer_amount, 2) !!} <br>
                                                            @if(in_array('view.sensitive.data', allowedPermissions()))
                                                            <span style="color:blue"><strong>Provider Price: </strong>{!! settings()->currency. number_format($transaction->src_price, 2) !!}</span> <br>
                                                            <span style="color:green"><strong>Profit: </strong>{!! settings()->currency. number_format($transaction->profit, 2) !!}</span> <br>
                                                            @endif
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 10%;">
                                                        <div class="align-items-center">
                                                            @if(hasAccess('admin.single.transaction.view'))
                                                                <a target="_blank" class="btn btn-info-light btn-wave btn-sm" href="{{ route('admin.single.transaction.view', $transaction->id) }}">View</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane text-muted border-0 p-0" id="completed"
                                role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap mb-0">
                                        <tbody class="active-tab">
                                            @if(!empty($success))
                                                @foreach($success as $transaction)
                                                <tr>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="align-items-center">
                                                                <strong>
                                                                @if(!empty($transaction->product_id))
                                                                    {{$transaction->product->display_name ?? ''}} @if(!empty($transaction->variation->system_name)) ({{$transaction->variation->system_name}})@endif
                                                                @else
                                                                    {{ $transaction->buyer_service }}
                                                                @endif
                                                                </strong> <br>
                                                                <a target="_blank" href="{{ route('customers.edit', $transaction->user_id)}}">{{ $transaction->customer->email }} </a><br>
                                                                <strong>{{ $transaction->customer->full_name }}</strong> <br>
                                                                {{ $transaction->mobile_number }} <br>
                                                                {{ date("M jS, Y g:iA", strtotime($transaction->created_at)) }} <br>
                                                                @if($transaction->status == 'success' || $transaction->buyer_status == 'SUCCESSFUL')
                                                                <button class="btn btn-success btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                                                @else
                                                                <button class="btn btn-danger btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                                                @endif 
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="align-items-center">
                                                                <span style="color:rgb(27, 20, 220)"><strong>System Reference ID: </strong> <br>{{ $transaction->sys_ref }}</span> <br>
                                                                <span style="color:crimson"><strong>API Reference: </strong> <br>{{ $transaction->api_ref }}</span> 
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="align-items-center">
                                                            <span style="color:blue">
                                                            @if(!empty($transaction->provider_id))
                                                            <strong>Provider: </strong>{{ $transaction->api->name }} <br>
                                                            @endif
                                                            @if(!empty($transaction->wallet_funding_provider))
                                                            <strong>Provider: </strong>{{ $transaction->gateway->name }} <br>
                                                            @endif
                                                            </span>
                                                            <small>
                                                            <strong>Charge: </strong>{!! settings()->currency. number_format($transaction->charges, 2) !!} <br>
                                                            <strong>Source Price: </strong>{!! settings()->currency. number_format($transaction->src_price, 2) !!} <br>
                                                            <strong>Discount Applied: </strong>{!! settings()->currency. number_format($transaction->discount, 2) !!} <br>
                                                            <strong>Initial Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_prebal, 2) !!} <br>
                                                            <strong>Final Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_postbal, 2) !!} <br>
                                                            <strong>Total Amount: </strong>{!! settings()->currency. number_format($transaction->buyer_amount, 2) !!} <br>
                                                            @if(in_array('view.sensitive.data', allowedPermissions()))
                                                            <span style="color:blue"><strong>Provider Price: </strong>{!! settings()->currency. number_format($transaction->src_price, 2) !!}</span> <br>
                                                            <span style="color:green"><strong>Profit: </strong>{!! settings()->currency. number_format($transaction->profit, 2) !!}</span> <br>
                                                            @endif
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 10%;">
                                                        <div class="align-items-center">
                                                            @if(hasAccess('admin.single.transaction.view'))
                                                                <a target="_blank" class="btn btn-info-light btn-wave btn-sm" href="{{ route('admin.single.transaction.view', $transaction->id) }}">View</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane text-muted border-0 p-0" id="cancelled-orders"
                                role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap mb-0">
                                        <tbody class="active-tab">
                                            @if(!empty($failed))
                                                @foreach($failed as $transaction)
                                                <tr>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="align-items-center">
                                                                <strong>
                                                                @if(!empty($transaction->product_id))
                                                                    {{$transaction->product->display_name ?? ''}} @if(!empty($transaction->variation->system_name)) ({{$transaction->variation->system_name}})@endif
                                                                @else
                                                                    {{ $transaction->buyer_service }}
                                                                @endif
                                                                </strong> <br>
                                                                <a target="_blank" href="{{ route('customers.edit', $transaction->user_id)}}">{{ $transaction->customer->email }} </a><br>
                                                                <strong>{{ $transaction->customer->full_name }}</strong> <br>
                                                                {{ $transaction->mobile_number }} <br>
                                                                {{ date("M jS, Y g:iA", strtotime($transaction->created_at)) }} <br>
                                                                @if($transaction->status == 'success' || $transaction->buyer_status == 'SUCCESSFUL')
                                                                <button class="btn btn-success btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                                                @else
                                                                <button class="btn btn-danger btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                                                @endif 
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="align-items-center">
                                                               <span style="color:rgb(27, 20, 220)"><strong>System Reference ID: </strong> <br>{{ $transaction->sys_ref }}</span> <br>
                                                                <span style="color:crimson"><strong>API Reference: </strong> <br>{{ $transaction->api_ref }}</span> 
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 30%;">
                                                        <div class="align-items-center">
                                                            <span style="color:blue">
                                                            @if(!empty($transaction->provider_id))
                                                            <strong>Provider: </strong>{{ $transaction->api->name }} <br>
                                                            @endif
                                                            @if(!empty($transaction->wallet_funding_provider))
                                                            <strong>Provider: </strong>{{ $transaction->gateway->name }} <br>
                                                            @endif
                                                            </span>
                                                            <small>
                                                            <strong>Charge: </strong>{!! settings()->currency. number_format($transaction->charges, 2) !!} <br>
                                                            <strong>Source Price: </strong>{!! settings()->currency. number_format($transaction->src_price, 2) !!} <br>
                                                            <strong>Discount Applied: </strong>{!! settings()->currency. number_format($transaction->discount, 2) !!} <br>
                                                            <strong>Initial Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_prebal, 2) !!} <br>
                                                            <strong>Final Balance: </strong>{!! settings()->currency. number_format($transaction->buyer_postbal, 2) !!} <br>
                                                            <strong>Total Amount: </strong>{!! settings()->currency. number_format($transaction->buyer_amount, 2) !!} <br>
                                                            @if(in_array('view.sensitive.data', allowedPermissions()))
                                                            <span style="color:blue"><strong>Provider Price: </strong>{!! settings()->currency. number_format($transaction->src_price, 2) !!}</span> <br>
                                                            <span style="color:green"><strong>Profit: </strong>{!! settings()->currency. number_format($transaction->profit, 2) !!}</span> <br>
                                                            @endif
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4" style="width: 10%;">
                                                        <div class="align-items-center">
                                                            @if(hasAccess('admin.single.transaction.view'))
                                                                <a target="_blank" class="btn btn-info-light btn-wave btn-sm" href="{{ route('admin.single.transaction.view', $transaction->id) }}">View</a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</div>
<!--End::row-1 -->

<!-- Start:: row-2 -->

@endsection
