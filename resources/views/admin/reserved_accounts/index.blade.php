@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
@endsection
@section('title', 'Reserved Accounts')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('accounts.index')}}">All Generated Accounts</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">All Generated Accounts</li>
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
                    <form action="{{ route('accounts.index') }}" method="GET">
                        
                        <div class="row gy-4">
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ \Request::get('email')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gateway" class="form-label">Payment Gateway</label>
                                    <select name="gateway" class="form-control" id="gateway">
                                        <option value="">Select</option>
                                        @foreach ($gateways as $gateway)
                                        <option value="{{ $gateway->id }}">{{ ucfirst($gateway->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label for="reference" class="form-label">Account Reference</label>
                                    <input type="text" class="form-control" id="reference" name="reference" value="{{ \Request::get('reference')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label for="account_number" class="form-label">Account Number</label>
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ \Request::get('account_number')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bank" class="form-label">Bank</label>
                                    <select name="bank" class="form-control" id="bank">
                                        <option value="">Select</option>
                                        @foreach ($banks as $bank)
                                        <option value="{{ $bank->bank_code }}">{{ ucfirst($bank->bank_name) }}</option>
                                        @endforeach
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
                            <div class="col-md-2">
                                <label for="" class="form-label"></label>
                                <input type="submit" class="form-control btn btn-primary mt-2" value="Search">
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap"  style="width:100%">
                        <thead>
                            <tr>
                                <th>Customer Details</th>
                                <th>Gateway Details</th>
                                <th>Other Details</th>
                                <th>Bank Details</th>
                                @if(hasAccess('accounts.destroy') || hasAccess('accounts.destroy'))
                                <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $account)
                            <tr>
                                <td>
                                    <strong>GATEWAY: </strong>{{ strtoupper($account->gateway->name) }} <br>
                                    <strong>Reference: </strong>{{ strtoupper($account->account_reference )}} <br>
                                    @if (in_array('view.sensitive.data', allowedPermissions()))
                                    <strong>BVN: </strong>{{ $account->bvn }} <br>
                                    <strong>NIN: </strong>{{ $account->nin }} 
                                    @endif
                                </td>
                                <td>
                                    <strong>Name:</strong> {{ $account->customer->full_name }} <br>
                                    <strong>Email:</strong><a target="_blank" style="color:blue" href="{{ route('customers.edit', $account->customer->id) }}">{{ $account->customer->email }}</a> <br>                  
                                </td>
                                <td>
                                    <strong>Created: </strong> {{ $account->created_at }} <br>
                                    <strong>By:</strong> SYSTEM<br>
                                    <strong>Purpose: </strong>{{$account->purpose}}
                                </td>
                                <td>
                                    <strong>Bank Code: </strong>{{ $account->bank_code }} <br>
                                    <strong>Bank Name: </strong>{{ $account->bank_name }} <br>
                                    <strong>Account Name: </strong>{{ $account->account_name }} <br>
                                    <strong>Account No: </strong>{{ $account->account_number }} 
                                    @if((hasAccess('accounts.edit') || hasAccess('accounts.destroy')) && $account->gateway->id == 1 && $account->deposit_transactions->count() == 0)<br>
                                    <a target="_blank" href="{{ route('admin.deposit.transactions', ['account_number' => $account->account_number]) }}" class="btn btn-info btn-sm">View Transactions</a>
                                    @endif
                                </td>
                                @if((hasAccess('accounts.edit') || hasAccess('accounts.destroy')) && $account->deposit_transactions->count() == 0)
                                <td>
                                    <form action="{{ route('accounts.destroy', $account->id) }}" class="btn btn-custon-four btn-sm-cl-social" method="POST" onsubmit="return confirm('Are you sure you want to delete forever?');">
                                        {{ csrf_field() }}
                                        {{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-danger-light btn-wave btn-md"
                                            data-toggle="tooltip" title="Delete Account"><i class="bx bxs-trash"></i> </i><span class="align-middle ml-25"></span>                                                                 </button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {!!$accounts->appends(Request::all())->render()!!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js')
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<!-- Datatables Cdn -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Internal Datatables JS -->
<script src="{{asset('new/assets/js/datatables.js')}}"></script>
    
@endsection
