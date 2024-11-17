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
        display: none;
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
    $colors = buyerStatusColors($transaction);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="col-md-12">
                <div class="card-header" style="padding:1.4rem 0.7rem">
                    <h4 class="card-title">Transaction Details <a class="btn btn-{{ $colors['bg'] }} btn-sm"><span><strong>{{ $transaction->buyer_status }}</strong></span></a></h4>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h5 style="color:black"><strong>
                                @if(!empty($transaction->product_id))
                                    {{$transaction->product->display_name ?? ''}} @if(!empty($transaction->variation->system_name)) ({{$transaction->variation->system_name}})@endif
                                @else
                                    {{ $transaction->buyer_service }}
                                @endif
                            </strong></h5>
                                {{ $transaction->created_at }}
                            @if(in_array($transaction->buyer_status, ['SUCCESSFUL','DELIVERED']))
                            <br>
                            <a href="{{ route('transaction.receipt.download', $transaction->id)}}" target="_blank" class="btn btn-info btn-sm" style="color:#fff;"><i class="fa fa-download"></i> Download Receipt</a> <br>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Request Id:</strong> <br>{{ $transaction->sys_ref }} <br>
                            <strong>Transaction Id:</strong> <br>{{ $transaction->api_ref }} <br>
                            @if($transaction->is_resolved == 1)
                            <span style="color:green">
                            <strong>Resolved Transaction:</strong> <br><a style="color:green" target="_blank" href="{{ route('admin.single.transaction.view', $transaction->resolved_transaction_id) }}">{{ $transaction->resolved_transaction->sys_ref }}</a><br>
                            </span>
                            @endif
                            <strong>IP Address: </strong><br>{{ $transaction->ip_address }} <br>
                            <strong>Domain: </strong><br>{{ $transaction->domain }} <br>
                        </div>
                        
                        <div class="col-md-3">
                            <strong>User Status:</strong> <br>
                            <span style="color:{{ $colors['color'] }}"><strong>{{ ucfirst($transaction->buyer_descr) }}</strong></span><br><br>
                            <strong>Buyer Status</strong> <br>
                            <span style="color:{{ $colors['color'] }}"><strong>{{ ucfirst($transaction->buyer_status) }}</strong></span><br><br>
                        </div>
                        <div class="col-md-3">
                            <strong>Extras:</strong> <br>
                            @if(!empty($transaction->extra))
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
                            <strong>CUST. EMAIL: </strong><a target="_blank" href="{{ route('customers.edit', $transaction->customer->id) }}">{{ $transaction->customer->email  }}</a><br>
                            <strong>PHONE: </strong>{{ $transaction->mobile_number }} <br>
                            @if($transaction->variation)
                                <strong>Variation: </strong>{{ $transaction->variation->system_name ?? 'null'}} <br>
                            @endif
                            {{-- @if(!in_array($transaction->reason, ['LEVEL-UPGRADE','WALLET-FUNDING'])) --}}
                            <br>
                            <strong class="heads">Transaction Details</strong>  <br>
                            <strong>Product:</strong> {{ $transaction->buyer_service }}
                            @if($transaction->category)<br>
                            <strong>Category:</strong> {{ $transaction->category->name }}
                            @endif
                            @if($transaction->variation)
                            <br>
                            <strong>Variation:</strong> {{ $transaction->variation->name }}
                            @endif
                            {{-- @endif --}}
                            @if(!empty($transaction->wallet_funding_provider))
                            <br>
                            <strong>Provider:</strong> {{ ucfirst($transaction->gateway->name ?? 'N/A') }} <br>
                            @else
                            <br>
                            <strong>Provider:</strong> {{  ucfirst($transaction->api->name ?? 'N/A') }} <br>
                            @endif
                            
                        </div>
                        <div class="col-md-3">
                            <strong class="heads">Request Payload</strong> <br>
                            @if(!empty($transaction->post_fields))
                                <div>
                                    <code style="margin:10px 0">
                                        {!! $transaction->post_fields !!}
                                    </code>

                                </div>
                            @else
                            No Data
                            @endif
                        </div>
                        
                        <div class="col-md-3">
                            <strong class="heads">API Response/Reason</strong> <br>
                            @if(!empty($transaction->api_response))
                            <div>
                                <code style="margin:10px 0">
                                    {!! $transaction->api_response!!}
                                </code>

                            </div>
                            @else
                            No Data
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="heads" style="color:#a211c5">Resolved Trail</strong> <br>
                            @if($transaction->is_resolved == 1)
                            <strong>Resolved On: </strong>{{$transaction->resolved_on}} <br>
                            <strong>Resolved By: </strong>{{$transaction->resolved_by->full_name}} <br>
                            <strong>Transaction ID: </strong>{{ $transaction->resolved_transaction->sys_ref}} <br>
                            <strong>Type: {{ $transaction->resolved_transaction->type}} </strong> <br>
                            <span style="color:red"><strong>Wallet Before : </strong>{!! settings()->currency. number_format($transaction->resolved_transaction->buyer_prebal, 2) !!} <br>
                            <span style="color:green"><strong>Wallet After : </strong>{!! settings()->currency. number_format($transaction->resolved_transaction->buyer_postbal, 2) !!}
                            </span>
                            @else
                            No Data
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong class="heads" style="color:#a211c5">Status Trail</strong> <br>
                            @if($transaction->transaction_change_histories->count() > 0)
                            @foreach($transaction->transaction_change_histories as $history)
                                <strong>Added On: </strong>{{ $history->created_at }} <br>
                                <strong>Added By: </strong>{{$history->resolved_by->full_name}} <br>
                                <span style="color:#f76800"><strong>Previous Status: </strong>{{ $history->previous_status}}</span><br>
                                <span style="color:#168c33"><strong>Current Status: </strong>{{ $history->current_status}}</span>
                                <hr style="margin: 2.8px !important;">
                            @endforeach
                            @else
                            No Data
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="table-extended-success" class="table mb-0">
                                <thead>
                                    <tr>
                                        <th style="color:black">Item</th>
                                        <th style="color:black">Unit Cost</th>
                                        <th style="color:black">Quantity</th>
                                        <th style="color:black">Amount</th>
                                        <th style="color:black">Biller</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        @if(in_array($transaction->reason, ['LEVEL-UPGRADE','WALLET-FUNDING','ADMIN-DEBIT','ADMIN-CREDIT']))
                                            {{ ucfirst(str_replace("-"," ",$transaction->reason))}}
                                        @else
                                        {{ !empty($transaction->product) ? $transaction->product->name : $transaction->buyer_service }}
                                        @if(!empty($transaction->variation->system_name)) <strong> | {{ !empty($transaction->variation) ? $transaction->variation->system_name : ''}} </strong> 
                                        @endif
                                        @endif
                                    </td>
                                    <td>
                                        {!! settings()->currency. number_format($transaction->buyer_amount, 2) !!}
                                    </td>
                                        <td>
                                        {{ $transaction->buyer_quantity }}
                                    </td>

                                    <td>
                                        <span style="color:black">Convenience Fee:</span> {!! settings()->currency. number_format($transaction->charges, 2) !!} <br>
                                        <span style="color:black">Discount: </span>{!! settings()->currency. number_format($transaction->discount, 2) !!} <br>
                                        <span style="color:black">Provider Charge:</span>{!! settings()->currency. number_format($transaction->provider_charge, 2) !!} <br>
                                        @if(in_array('view.sensitive.data', allowedPermissions()))
                                        <span style="color:blue"><strong>Provider Price: </strong>{!! settings()->currency. number_format($transaction->src_price, 2) !!}</span> <br>
                                        <span style="color:green"><strong>Profit: </strong>{!! settings()->currency. number_format($transaction->profit, 2) !!}</span> <br>
                                        @endif
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
                                    <td>
                                        <span style="color:black"> {{ $transaction->billersCode }}

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well">
                                <address>
                                    <img src="{{url('/')}}/site/loading.gif" height="70" style="display:none; margin-left: auto; margin-right:auto;height:initial;" id="img_loading">
                                    <div><span id="qw_status"></span></div>
                                    <div id="q_res" style="max-height:300px;overflow:scroll;word-wrap: break-word">
                                    </div>
                                </address>
                            </div>
                            {{-- <button id="qw_credit" class="btn btn-success btn-sm" style="color:#fff;"><svg fill="white" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q65 0 123 19t107 53l-58 59q-38-24-81-37.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160q32 0 62-6t58-17l60 61q-41 20-86 31t-94 11Zm280-80v-120H640v-80h120v-120h80v120h120v80H840v120h-80ZM424-296 254-466l56-56 114 114 400-401 56 56-456 457Z"/></svg> Query Credit</button> --}}

                            {{-- <button id="qw_debit" class="btn btn-danger btn-sm" style="color:#fff;"><svg fill="white" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M200-440v-80h560v80H200Z"/></svg> Query Debit</button> --}}
                            @if(!in_array($transaction->buyer_status, ['ADMIN-RESOLVED','ADMIN-REFUND','REFUND','DEPOSIT']))
                                <a id="qw-transaction" onclick="queryStatus()" class="btn btn-dark btn-sm" style="color:#fff;"><i class='bx bx-search-alt' ></i> Re Query Transaction</a>
                            @endif
                            @if(in_array('view.sensitive.data', allowedPermissions()) && in_array($transaction->buyer_status, ['ADMIN-RESOLVED','ATTENTION-REQUIRED','SUCCESSFUL']))
                                <button type="button" class="btn btn-primary btn-sm" style="color:#fff;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class='bx bx-cog'></i> Resolve
                                </button>
                                <div class="modal fade" id="exampleModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('resolve-transaction', $transaction->id) }}" method="POST">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="exampleModalLabel1">Resolve Transaction</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                           
                                            <div class="modal-body">
                                                @csrf
                                                <div class="row gy-4">
                                                    <div class="col-md-12">
                                                        <label for="status" class="form-label">Select Final Status</label>
                                                        <select class="form-control" name="status" id="status" required>
                                                            <option value="">Select</option>
                                                            <option value="ADMIN-RESOLVED" {{ old('status') == 'ADMIN-RESOLVED' ? 'selected' : ''}}>ADMIN RESOLVED</option>
                                                            <option value="FAILED" {{ old('status') == 'FAILED' ? 'selected' : ''}}>FAILED</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($transaction->buyer_status == 'ATTENTION-REQUIRED' && in_array('view.sensitive.data', allowedPermissions()))
                                <a id="reverse-to-wallet" class="btn btn-info btn-sm" style="color:#fff;" onclick="if (confirm('Are you sure you want to refund customer?')) reverseToWallet('{{ $transaction->id }}'); return false;"><i class='bx bx-undo'></i> Refund Customer</a>
                            @endif
                        </div>
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
<script>
    function queryStatus(e){
        url = "{{ route('re-query-transaction', $transaction->id) }}";
		$.ajax({
			url : url,
			type : 'GET',
			beforeSend: function (){
				$('#q_res').hide();
				$('#img_loading').show();
                $('.well').show();
				$('#img_loading2').show();
				$('#qw_status').html('Processing....');
			},
			success:function (data) {
                $('#qw_status').html('<strong style="color:' + (data.status === 'success' ? 'green' : 'red') + ';">' + data.response + '</strong>');
				$('#img_loading').hide();
				
				$('#q_res').show();
				$('#q_res').html('<strong>Provider Response</strong><br>'+data.api_response);

			}
		});
	}

    function reverseToWallet(e){
        $('#q_res').val("");

        url = "{{ route('reverse-to-wallet', $transaction->id) }}";
		$.ajax({
			url : url,
			type : 'GET',
			beforeSend: function (){
				$('#q_res').hide();
				$('#img_loading').show();
                $('.well').show();
				$('#img_loading2').show();
				$('#qw_status').html('Processing....');
			},
			success:function (data) {
                $('#qw_status').html('<strong style="color:' + (data.status === 'success' ? 'green' : 'red') + ';">' + data.message + '</strong>');
				$('#img_loading').hide();

                location.reload();
			}
		});
	}
</script>
@endsection
