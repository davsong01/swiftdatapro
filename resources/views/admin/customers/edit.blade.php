@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
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
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#products" href="#products" aria-selected="false" tabindex="-1" role="tab">Product Access</a>
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
                                <input type="text" class="form-control" name="middle_name"
                                    placeholder="Middle name" value="{{ $customer->middle_name }}"
                                    name="middle_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name"
                                    placeholder="Last name" value="{{ $customer->last_name }}"
                                    name="last_name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="bvn">BVN</label>
                                <input type="text" class="form-control" name="bvn"
                                    placeholder="BVN" value="{{ $customer->bvn }}"
                                    name="bvn">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="nin">NIN</label>
                                <input type="text" class="form-control" name="nin"
                                    placeholder="NIN" value="{{ $customer->nin }}"
                                    name="nin">
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
                    <div class="table-responsive">
                        <table id="datatable-basic" class="table table-striped text-nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Bank Name</th>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                    <th>Provider</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php 
                                $accounts = $customer->automated_accounts;
                            ?>
                            <tbody>
                                @if(!empty($accounts))
                                @foreach ( $accounts as $account )
                                <tr>
                                    <td>{{ $account->bank_name }}</td>
                                    <td>{{ $account->account_name }}</td>
                                    <td>{{ $account->account_number }}</td>
                                    <td>{{ $account->gateway->name }}</td>
                                    <td>
                                    @if(hasAccess('accounts.destroy'))
                                    @if(isset($account->transactions) && $account->transactions->count() < 1)
                                    <form action="{{ route('accounts.destroy', $account->id) }}"
                                        class="btn btn-custon-four btn-sm-cl-social" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete forever?');">
                                        {{ csrf_field() }}
                                        {{method_field('DELETE')}}

                                        <button type="submit" class="btn btn-danger-light btn-wave btn-sm"
                                            data-toggle="tooltip" title="Delete Account"><i class="bx bxs-trash"></i> </i><span class="align-middle ml-25">Delete</span> Account</button>
                                    </form>
                                    @else
                                      &nbsp;
                                    @endif
                                @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                            
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="products" role="tabpanel">
                    <?php 
                        $product_access = productAccess($customer);
                    ?>
                    <form action="{{ route('products.update', $customer->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input ms-2" type="checkbox" name="product_access_all" id="product_access_all" value="all">
                                    <label for="product_access_all"> &nbsp;<strong>Select all</strong></label>
                                </div>
                            </div>
                            @foreach ($products as $value)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input ms-2" type="checkbox" name="product_access[]" id="product_access-{{$value->id}}" value="{{$value->id}}" {{in_array($value->id, $product_access) ? 'checked' : ''}}>
                                    <label for="product_access-{{$value->id}}"> &nbsp;{{$value->name}}</label>
                                </div>
                            </div>
                            @endforeach
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    Update Product Access
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
            </div>
        </div>
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
@endsection

