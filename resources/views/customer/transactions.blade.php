@extends('includes.customer_dashboard.app')
<?php 
    use App\Models\WebSetting;
    $web_settings = WebSetting::first();
?>
@section('extra-css')
<style>
    .extra{
        margin: 5px 0;
        background: #940798;
        padding: 5px 5px;
        border-radius: 5px;
        color: white;
    }
     .title{
            font-weight: bold;
        }
</style>
@endsection
@section('header')


@endsection
@section('body')
 <div class="content-body">
    <div class="row">
        <!-- Marketing Campaigns Starts -->
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
                                            <label for="service">Service</label>
                                            <select class="form-control" name="service" id="service">
                                                <option value="">Select</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ \Request::get('service') == $product->id ? 'selected' : ''}}>{{ $product->display_name }}</option>
                                                @endforeach
                                            </select>
                                        </fieldset>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label for="transaction_id">Transaction ID</label>
                                            <input type="text" class="form-control" id="transaction_id" name="transaction_id" placeholder="Enter transaction ID" value="{{ \Request::get('transaction_id')}}">
                                        </fieldset>
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="">Select</option>
                                                <option value="SUCCESSFUL" {{ \Request::get('status') == 'SUCCESSFUL' ? 'selected' : ''}}>Successful</option>
                                                <option value="FAILED" {{ \Request::get('status') == 'FAILED' ? 'selected' : ''}}>Failed</option>
                                                <option value="ATTENTION-REQUIRED" {{ \Request::get('status') == 'ATTENTION-REQUIRED' ? 'selected' : ''}}>Pending</option>
                                            </select>
                                        </fieldset>
                                        
                                    </div>
                                    <div class="col-md-3">
                                        <fieldset class="form-group">
                                            <label for="type">Type</label>
                                            <select class="form-control" name="type" id="type">
                                                <option value="">Select</option>
                                                <option value="credit" {{ \Request::get('type') == 'credit' ? 'selected' : ''}}>Credit</option>
                                                <option value="debit" {{ \Request::get('type') == 'debit' ? 'selected' : ''}}>Debit</option>
                                            </select>
                                        </fieldset>
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="form-group">
                                            <label for="billersCode">Unique Element</label>
                                            <input type="text" class="form-control" id="billersCode" name="billersCode" placeholder="Enter billers code" value="{{ \Request::get('billersCode') }}">
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

                                    <div class="col-md-2">
                                        <label style="color:white">sd</label>
                                        <input type="submit" class="form-control btn btn-primary" value="Search">
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
                                <?php 
                                    $colors = buyerStatusColors($transaction);
                                ?>
                                <div class="col-md-4" style="box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;padding-top:10px;padding-bottom: 10px;">
                                    <div class="">
                                        @if(!empty($transaction->product_id))
                                            <span class="title">Service</span>:
                                            <small>
                                            <span class="mr-50 text-bold-200">
                                                <strong>{{ $transaction->product->name ?? $transaction->buyer_descr}}</strong>
                                                    @if($transaction?->variation?->system_name) {{ " | ". $transaction?->variation?->system_name }} 
                                                    @endif
                                                </span> 
                                            </small> <br>
                                        @endif
                                        <strong>
                                        <button class="btn btn-{{ $colors['bg'] }} btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>

                                        {{-- @if($transaction->status == 'DELIVERED' || $transaction->buyer_status == 'SUCCESSFUL')
                                        <button class="btn btn-success btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                        @elseif(in_array($transaction->buyer_statusbuyer_status, ['ATTENTION-REQUIRED', 'PENDING']))
                                        <button class="btn btn-warning btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                        @else
                                        <button class="btn btn-danger btn-sm" readonly>{{ucfirst($transaction->buyer_status) }}</button>
                                        @endif  --}}
                                        <br>
                                        </strong>
                                        @if(!empty($transaction->extra))
                                        <div class="extra">
                                            <span class="title">{{$transaction->extra}} </span>
                                        </div>
                                    
                                        @endif
                                        <span class="title">Description: </span>
                                        <small>
                                            {{ $transaction->buyer_descr }}</strong>
                                        </small>
                                        @if(!empty($transaction->billersCode))
                                         <br>
                                        <span class="title">Biller</span>:
                                        <small>
                                            {{ $transaction->billersCode }}</strong>
                                        </small> 
                                        @endif
                                        <br>
                                        <span class="title">Unit Price</span>:
                                        <small>{{ number_format($transaction->unit_price, 2) }}</small> <br>
                                        <span class="title">Buyer Amount</span>:
                                        <small style="color:{{$transaction->type == 'debit' ? 'red' : 'green' }}">{{$transaction->type == 'debit' ? '- ' : '+ '}}{!! settings()->currency !!}{{ number_format($transaction->buyer_amount, 2) }}
                                        {{$transaction->type == 'debit' ? '(Debit)' : '(Credit)' }}
                                        </small> 
                                        @if($transaction->discount > 0)
                                        <br>
                                        <span class="title">Discount Applied</span>:
                                        <small class="{{$transaction->type == 'debit' ? 'red' : 'green' }}">{!! settings()->currency !!}{{ number_format($transaction->discount, 2) }}
                                        </small>
                                        @endif
                                        <br>
                                        <span class="title">Pre Balance</span>:
                                        <small class="">{!! settings()->currency !!}{{ number_format($transaction->buyer_prebal, 2) }}
                                        </small>

                                        <br>
                                        <span class="title">Post Balance</span>:
                                        <small class="">{!! settings()->currency !!}{{ number_format($transaction->buyer_postbal, 2) }}
                                        </small>
                                        <br>
                                        
                                        <span class="title">Transaction Id</span>:
                                        <small>
                                            {{ $transaction->sys_ref }}</strong>
                                        </small> <br>
                                        <span class="title">Payment Method</span>:
                                        <small>
                                            {{ $transaction->charge_from }}</strong>
                                        </small> <br>
                                        <span class="title">Date</span>:
                                        <small>
                                            
                                            {{ date("M jS, Y g:iA", strtotime($transaction->created_at)) }}
                                        </small> <br>

                                    </div>
                                    <div class="statistics-date">
                                        <small class="text-muted">
                                            <a target="_blank" href="{{ route('transaction.receipt.download', $transaction->id) }}" class="btn btn-sm btn-info glow mt-md-2 mb-1">Transaction Receipt</a>
                                            @if(in_array($transaction->product->slug, ['nin-verification']) && $transaction->buyer_status == 'SUCCESSFUL' )
                                                <a target="_blank" href="{{ route('transaction.generateslip', $transaction->id) }}" class="btn btn-sm btn-dark glow mt-md-2 mb-1">BVN/NIN Slip</a>
                                            @endif
                                        </small>
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
</div>
@endsection
