<?php
    $kycDataStatuses = kycDataStatus($customer->id);  
?>
@endphp
@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .verified-by {
        color: #a211c5;
    }

    .spinner-border {
        display: none;
        width: 1.5rem;
        height: 1.5rem;
        border-width: 0.2em;
        margin-left: 5px;
    }
</style>
@endsection
@section('title', 'Edit '.$customer->username)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('customers.index')}}">All Customers</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Edit {{$customer->username}}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs tab-style-1 d-sm-flex d-block" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#account" aria-current="page" href="#account" aria-selected="true" role="tab">Account</a>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#transactions" href="#transactions" aria-selected="false" role="tab" tabindex="-1">Transactions</a>
                    </li> --}}
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#downlines" href="#downlines" aria-selected="false" tabindex="-1" role="tab">Downlines</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#accounts" href="#accounts" aria-selected="false" tabindex="-1" role="tab">Reserved Accounts</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#products" href="#products" aria-selected="false" tabindex="-1" role="tab">Product Blacklisting</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#kyc-data" href="#kyc-data" aria-selected="false" tabindex="-1" role="tab">KYC Data</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#others" href="#others" aria-selected="false" tabindex="-1" role="tab">Other Actions</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active show" id="account" role="tabpanel">
                        <form action="{{ route('account.update', $customer->id) }}" method="POST">
                            @csrf
                            <div class="row gy-4">
                            <div class="col-md-6">
                                <label class="form-label" for="firstname">First Name</label>
                                <input type="text" class="form-control" name="full_name"
                                placeholder="First name" value="{{ $customer->full_name }}"
                                name="full_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="middle_name">Middle Name</label>
                                <input type="text" class="form-control" placeholder="Middle name" value="{{ $customer->middle_name }}"
                                    name="middle_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name"
                                    placeholder="Last name" value="{{ $customer->last_name }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label" for="ref_code">Ref Code</label>
                                <input type="text" class="form-control" name="ref_code"
                                    placeholder="Ref code" value="{{ $customer->ref_code }}"
                                    name="ref_code">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="gender">Gender</label>
                                <select name="gender" class="form-control" id="gender" required>
                                    <option value="">Select</option>
                                    <option value="male" {{$customer->gender == 'male' ? 'selected':''}}>Male</option>
                                    <option value="female" {{$customer->gender == 'female' ? 'selected':''}}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="status">Status</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active" @selected($customer->status == 'active')>Active</option>
                                    <option value="inactive" @selected($customer->status == 'inactive')>Inactive
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="kyc_status">KYC Status</label>
                                <select name="kyc_status" class="form-control" id="kyc_status" required>
                                    <option value="">Select Status</option>
                                    <option value="0" @selected($customer->kyc_status == '0')>Inactive</option>
                                    <option value="1" @selected($customer->kyc_status == '1')>Active
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="customerlevel">Customer Level</label>
                                <select name="customerlevel" class="form-control" id="customerlevel" required>
                                    <option value="">Select Customer level</option>
                                    @foreach ($customerLevels as $level)
                                    <option value="{{ $level->id }}" {{$customer->level == $level->id ? 'selected':''}}>{{ $level->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ $customer->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="username">Username</label>
                                <input required type="text" class="form-control" placeholder="Username" value="{{ $customer->username }}">
                            </div>
                            <div class="col-md-6">
                                <label for="phone">Phone</label>
                                <input type="text" required class="form-control" name="mobile"
                                    placeholder="Mobile number" value="{{ $customer->mobile }}"
                                    name="mobile">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- <div class="tab-pane" id="transactions" role="tabpanel">
                    sdd
                </div> --}}
                <div class="tab-pane" id="downlines" role="tabpanel">
                    <div class="table-responsive">
                        <table id="responsiveDataTable" class="table table-striped text-nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Level</th>
                                </tr>
                            </thead>
                            <?php 
                                $downlines = getDownlines($customer);
                            ?>
                            <tbody>
                                @if(!empty($downlines))
                                @foreach ( $downlines as $downline )
                                <tr>
                                    <td>
                                        Name: {{ $downline->full_name }} <br>
                                        Username: {{ $downline->username }} <br>
                                    </td>
                                    <td>{{ $downline->email }}</td>
                                    <td>{{ $downline->mobile }}</td>
                                    <td>{{$downline->customer_level->name ?? 'N/A'}}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                            
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="accounts" role="tabpanel">
                    <a style="margin-bottom:10px" class="btn btn-info btn-md" data-bs-toggle="modal" data-bs-target="#pull-accounts">Pull Accounts by reference</a>
                    <a style="margin-bottom:10px" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#add-local-accounts">Add Local Account</a>
                    <div class="table-responsive">
                        <table id="datatable-basic" class="table table-striped text-nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Gateway</th>
                                    <th>Bank Name</th>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    @if(hasAccess('accounts.edit') || hasAccess('accounts.destroy'))
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <?php 
                                $accounts = $customer->automated_accounts;
                            ?>
                            <tbody>
                                @if(!empty($accounts))
                                @foreach ( $accounts as $account )
                                <tr>
                                    <td>{{ $account->gateway->name }}</td>
                                    <td>{{ $account->bank_name }}</td>
                                    <td>{{ $account->account_name }}</td>
                                    <td>{{ $account->account_number }}</td>
                                    
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
                                @endif
                            </tbody>
                            
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="products" role="tabpanel">
                    <?php 
                        $product_blacklist = productBlacklist($customer);
                    ?>
                    <form action="{{ route('products.update', $customer->id) }}" method="POST">
                        @csrf
                        <p><strong>Products checked here (and their variations) will not be available for this customer</strong></p>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input ms-2" type="checkbox" name="masterCheckbox" id="masterCheckbox" value="all">
                                    <label for="masterCheckbox"> &nbsp;<strong>Toggle all</strong></label>
                                </div>
                            </div>
                            @foreach ($products as $value)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input ms-2 childCheckbox" type="checkbox" name="product_blacklist[]" id="product_blacklist-{{$value->id}}" value="{{$value->id}}" {{in_array($value->id, $product_blacklist) ? 'checked' : ''}}>
                                    <label for="product_blacklist-{{$value->id}}"> &nbsp;{{$value->name}}</label>
                                </div>
                            </div>
                            @endforeach
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    Update Product Blacklist
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="others" role="tabpanel">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#changepassword">Change Password</button>
                        <button data-bs-toggle="modal" data-bs-target="#changetransactionpin" type="button" class="btn btn-secondary btn-wave">Change Transaction Pin</button>
                        
                        
                        <form action="{{ route('customer.other.actions') }}" class="btn btn-custon-four btn-sm-cl-social" method="POST" onsubmit="return confirm('Are you sure you want to verify email?');">
                            @csrf
                            <input type="hidden" name="type" value="verify-email">
                            <input type="hidden" name="customer" value="{{ $customer->id }}">
                            <button type="submit" class="btn btn-success btn-wave">Verify Email</button>
                        </form>

                        <button type="button" data-bs-toggle="modal" data-bs-target="#kycstatus" class="btn btn-danger btn-wave">Update Kyc Status</button>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane" id="kyc-data" role="tabpanel">
                        @if(!empty($customer->kycData))
                        @include('admin.customers.single-pending-kyc-form')
                        @else
                        No Data
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="pull-accounts" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">Pull Accounts by reference (Monnify Reference Only)</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="{{ route('pull.reserved.account.by.reference') }}" method="POST" onsubmit="return confirm('Are you sure you want to pull accounts for this customer?');">                   
                @csrf
                <div class="hstack gap-3">
                    <input class="form-control me-auto" name="account_reference" required type="text" placeholder="Enter Account reference">
                    <button type="submit" class="btn btn-primary">Pull</button>
                    <div class="vr"></div>
                </div>
                <input type="hidden" name="customer_email" value="{{ $customer->email }}">
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-local-accounts" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">Add Local Account, please ensure that this account exists on the provider</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="{{ route('add.manual.reserved.account') }}" method="POST" onsubmit="return confirm('Are you sure you want to add account for this customer?');">                   
                @csrf
                
                <div class="row gy-4">
                    <div class="col-md-6">
                        <label class="form-label" for="account_reference">Account Reference</label>
                        <input type="text" class="form-control" id="account_reference" name="account_reference" value="{{ old('account_reference') }}" placeholder="Enter account name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="account_name">Account Name</label>
                        <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name') }}" placeholder="Enter account name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="account_number">Account Number</label>
                        <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}" placeholder="Enter account number" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="account_reference">Account Reference</label>
                        <input type="text" class="form-control" id="account_reference" name="account_reference" value="{{ old('account_reference') }}" placeholder="Enter account reference" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="icon">Bank Name</label>
                        <select name="bank_name" class="form-control" id="bank_name" required>
                            <option value="">Select Bank</option>
                            @foreach (getBanks() as $bank)
                            <option value="{{ $bank->bank_name }}">{{ $bank->bank_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="icon">Provider</label>
                        <select name="payment_gateway_id" class="form-control" id="payment_gateway_id" required>
                            <option value="">Select Provider</option>
                            <option value="1">Monnify</option>
                            <option value="2">Pay Vessel</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="customer_email" value="{{ $customer->email }}">
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="changepassword" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">Change Password</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="{{ route('customer.other.actions') }}" method="POST" onsubmit="return confirm('Are you sure you want to change password for this customer?');">                   
                @csrf
                <div class="hstack gap-3">
                    <input class="form-control me-auto" name="password" required type="text" placeholder="New Password" aria-label="New password">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <div class="vr"></div>
                </div>
                <input type="hidden" name="type" value="password-change">
                <input type="hidden" name="customer" value="{{ $customer->id }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changetransactionpin" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">Change Transaction Pin</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="{{ route('customer.other.actions') }}" method="POST" onsubmit="return confirm('Are you sure you want to change transaction PIN for this customer?');">                   
                @csrf
                <div class="hstack gap-3">
                    <input class="form-control me-auto" name="transaction_pin" required type="text" placeholder="New Transaction PIN" aria-label="New transaction PIN">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <div class="vr"></div>
                </div>
                <input type="hidden" name="type" value="transaction-pin-change">
                <input type="hidden" name="customer" value="{{ $customer->id }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="kycstatus" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">KYC Status</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="{{ route('customer.other.actions') }}" method="POST" onsubmit="return confirm('Are you sure you want to change Kyc status for this customer?');">                   
                @csrf
                <div class="hstack gap-3">
                    <select class="form-control me-auto" name="kyc_status" id="kyc_status" required>
                        <option value="1" {{ $customer->kyc_status == 1 ? 'selected' : ''}}>Active</option>
                        <option value="0" {{ $customer->kyc_status == 0 ? 'selected' : ''}}>Inactive</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <div class="vr"></div>
                </div>
                <input type="hidden" name="type" value="kyc-status">
                <input type="hidden" name="customer" value="{{ $customer->id }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">Close</button>
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
<script>
    $(document).ready(function() {
        $('#masterCheckbox').on('change', function() {
            $('.childCheckbox').prop('checked', this.checked);
        });

        $('.childCheckbox').on('change', function() {
            $('#masterCheckbox').prop('checked', $('.childCheckbox:checked').length === $('.childCheckbox').length);
        });
    });
</script>
@endsection

