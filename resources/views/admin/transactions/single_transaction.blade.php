@extends('layouts.new.app')
@section('extra-css')
<style>
    .reset-pin {
        font-size: 10px;
        float: right;
    }

    .heads {
        color: black
    }
    body {
        font-size: 1rem;
        font-weight: 398;
        color: black;
        font-size: smaller;
    }
    .table {
        color: black;
    }

    code{
        max-height: 250px;
        display: block;
        overflow:scroll;
        word-wrap: break-word;
        padding: 10px;
        margin:bottom:10px;
        height: 250px;
    }
    .well, .validate-div {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
        margin-top: 10px;
    }
</style>
@endsection
@section('title', 'Transaction')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('notification.index')}}">Transaction</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Transaction</li>
@endsection
@section('content')
<?php 
    if($transaction->buyer_status == 'FAILED'){
        $color = 'red';
    }elseif($transaction->buyer_status == 'initiated'){
        $color = '#FDAC41';
    }else {
        $color = 'green';
    }
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="col-md-12">
                <div class="card-header" style="padding:1.4rem 0.7rem">
                    <h4 class="card-title">Transaction Details</h4>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h5 style="color:black"><strong>{{ $transaction->buyer_service }}</strong></h5>
                                {{ $transaction->created_at }}
                            @if(!in_array($transaction->buyer_status, ['SUCCESSFUL']))
                            <br>
                            <a href="{{ route('transaction.receipt.download', $transaction->id)}}" target="_blank" class="btn btn-info btn-sm" style="color:#fff;"><i class="fa fa-download"></i> Download Receipt</a> <br>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Request Id:</strong> <br>{{ $transaction->sys_ref }} <br>
                            <strong>Transaction Id:</strong> <br>{{ $transaction->api_ref }} <br>
                            <strong>IP Address: </strong><br>{{ $transaction->ip_address }} <br>
                            <strong>Domain: </strong><br>{{ $transaction->domain }} <br>
                        </div>
                        
                        <div class="col-md-3">
                            <strong>User Status:</strong> <br>
                            <span style="color:{{ $color }}"><strong>{{ ucfirst($transaction->buyer_descr) }}</strong></span><br><br>
                            <strong>Buyer Status</strong> <br>
                            <span style="color:{{ $color }}"><strong>{{ ucfirst($transaction->buyer_status) }}</strong></span><br><br>
                            {{-- @if(!in_array($transaction->buyer_status, ['completed','success']))
                            <a id="qw_resolve" class="btn btn-success btn-sm" style="color:#fff;"><svg fill="white" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M754-81q-8 0-15-2.5T726-92L522-296q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l85-85q6-6 13-8.5t15-2.5q8 0 15 2.5t13 8.5l204 204q6 6 8.5 13t2.5 15q0 8-2.5 15t-8.5 13l-85 85q-6 6-13 8.5T754-81Zm0-95 29-29-147-147-29 29 147 147ZM205-80q-8 0-15.5-3T176-92l-84-84q-6-6-9-13.5T80-205q0-8 3-15t9-13l212-212h85l34-34-165-165h-57L80-765l113-113 121 121v57l165 165 116-116-43-43 56-56H495l-28-28 142-142 28 28v113l56-56 142 142q17 17 26 38.5t9 45.5q0 24-9 46t-26 39l-85-85-56 56-42-42-207 207v84L233-92q-6 6-13 9t-15 3Zm0-96 170-170v-29h-29L176-205l29 29Zm0 0-29-29 15 14 14 15Zm549 0 29-29-29 29Z"/></svg> Resolve</a>
                            @endif --}}
                            {{-- Description <br> --}}
                            {{-- <span style="color:{{ $color }}"><strong>{{ ucfirst($transaction->descr) }}</strong></span><br><br> --}}
                        </div>
                        <div class="col-md-3">
                            @if(!empty($transaction->extra))
                            <strong>Extras:</strong> <br>
                            {{ ucfirst($transaction->extra) }}
                            
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <strong class="heads">Wallet Trail:</strong> <br>

                            <span style="color:red"><strong>WALLET BEFORE :</strong>{!! settings()->currency. number_format($transaction->buyer_prebal, 2) !!} <br>
                            </span>
                            <span style="color:green"><strong>WALLET AFTER : </strong>{!! settings()->currency. number_format($transaction->buyer_postbal, 2) !!}
                            </span>
                            @if($transaction->wallets)
                                @foreach($transaction->wallets as $wallet)
                                    @if($wallet->type == 'credit')
                                    @endif
                                    @if($wallet->type == 'debit')
                                    @endif
                                    <br>
                                @endforeach
                            @endif

                        </div>
                        <div class="col-md-3">
                            <strong class="heads">Payment Details</strong> <br>
                            <strong>PAYMENT METHOD: </strong><span> {{ $transaction->charge_from}}</span> <br>
                            <strong>CHANNEL: </strong>{{ $transaction->channel}} <br>
                            <strong>CUST. EMAIL: </strong>{{ $transaction->buyer_email }} <br>
                            <strong>PHONE: </strong>{{ $transaction->mobile_number }} <br>
                            @if($transaction->variation)
                                <strong>Variation: </strong>{{ $transaction->variation->system_name ?? 'null'}} <br>
                            @endif
                            @if(!in_array($transaction->reason, ['LEVEL-UPGRADE','WALLET-FUNDING']))

                                <br><br>
                                <strong class="heads">Transaction Details</strong> <br>
                                <strong>Product:</strong> {{ $transaction->buyer_service }}
                                @if($transaction->category)<br>
                                <strong>Category:</strong> {{ $transaction->category->name }}
                                @endif
                                @if($transaction->variation)
                                <br>
                                <strong>Variation:</strong> {{ $transaction->variation->name }}
                                @endif
                            @endif
                            <br>
                            <strong>Provider:</strong> {{ $transaction->api->name ?? $transaction->provider }} <br>
                            
                        </div>
                        @if(!in_array($transaction->reason, ['LEVEL-UPGRADE','WALLET-FUNDING']))
                        <div class="col-md-3">
                            <strong class="heads">Request Payload</strong> <br>
                            <div>
                                <code style="margin:10px 0">
                                    {!! $transaction->post_fields !!}
                                </code>

                            </div>

                        </div>
                        <div class="col-md-3">
                            <strong class="heads">API Response ({{ $transaction->api->name ?? null }})</strong> <br>
                            <div>
                                <code style="margin:10px 0">
                                    {!! $transaction->api_response!!}
                                </code>

                            </div>

                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="table-extended-success" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th style="color:black">Item</th>
                                        <th style="color:black">Unit Cost</th>
                                        <th style="color:black">Quantity</th>
                                        <th style="color:black">Amount</th>
                                        {{-- <th style="color:black">Biller</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        @if(in_array($transaction->reason, ['LEVEL-UPGRADE','WALLET-FUNDING','ADMIN-DEBIT','ADMIN-CREDIT']))
                                            {{ ucfirst(str_replace("-"," ",$transaction->reason))}}
                                        @else
                                        {{ !empty($transaction->product) ? $transaction->product->name : $transaction->buyer_service }}@if(!empty($transaction->variation->system_name)) <strong> | {{ !empty($transaction->variation) ? $transaction->variation->name : ''}} </strong> @endif
                                        @endif
                                    </td>
                                    <td>
                                        {!! settings()->currency. number_format($transaction->buyer_amount, 2) !!}
                                    </td>
                                        <td>
                                        {{ $transaction->buyer_quantity  }}
                                    </td>

                                    <td>
                                        <span style="color:black">Convenience Fee:</span> {!! settings()->currency. number_format($transaction->charges, 2) !!} <br>
                                        <span style="color:black">Discount: </span>{!! settings()->currency. number_format($transaction->discount, 2) !!} <br>
                                        <span style="color:black">Provider Charge:</span>{!! settings()->currency. number_format($transaction->provider_charge, 2) !!} <br>
                                        <span style="color:black">Total Amount:</span> {!! settings()->currency. number_format($transaction->total_amount, 2) !!}
                                    </td>
                                        <?php
                                            if (isset($transaction->variation) &&  in_array($transaction->category->unique_element, verifiableUniqueElements())
                                            ) {
                                                $element = $transaction->category->unique_element;
                                            } else if (isset($transaction->variation) &&  in_array($transaction->variation->slug, verifiableUniqueElements())
                                            )  {
                                                $element = specialVerifiableVariations()[$transaction->variation->slug];
                                            }  else{
                                                $element = null;
                                            }
                                        ?>
                                        
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<script>
    let quill = new Quill('.editor', {
        theme: 'snow',
        toolbar: true,
        placeholder: 'Enter content...',
        modules: {
            syntax: true,
            toolbar: '#toolbar-container',
        },
    });

    $('form').on('submit', () => {
        var myEditor = document.querySelector('#editor_id')
        console.log( myEditor);
        var html = myEditor.children[0].innerHTML;
        $('#content').val(html);
    });
</script>
@endsection
