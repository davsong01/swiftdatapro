@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('title', $title)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('customers.index')}}">All Customers</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <form action="{{ route('customers.index') }}" method="GET">
                        {{-- @csrf --}}
                        <div class="row gy-4">
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ \Request::get('email')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="{{ \Request::get('username')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label for="mobile" class="form-label">Mobile Phone</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter phone number" value="{{ \Request::get('mobile')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="level" class="form-label">Level</label>
                                    <select class="form-control" name="level" id="level">
                                        <option value="">Select</option>
                                        @foreach($customer_levels as $level)
                                        <option value="{{ $level->id }}" {{ \Request::get('level') == $level->id ? 'selected' : ''}}>{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" for="kyc">Status</label>
                                    <select class="form-control" name="kyc" id="">
                                        <option value="">Select...</option>
                                        <option value="active" {{ \Request::get('kyc') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ \Request::get('kyc') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" for="kyc_status">KYC Status</label>
                                    <select class="form-control" name="kyc_status" id="">
                                        <option value="">Select...</option>
                                        <option value="1" {{ \Request::get('kyc_status') == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ \Request::get('kyc_status') == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" for="from">Joined From</label>
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
                          
                            <div class="col-md-2">
                                <label for="" class="form-label"></label>
                                <a class="form-control btn btn-info mt-2" href="{{ url('/'). \Request::route()->getPrefix().'/customers?download=yes&' . http_build_query(request()->query()) }}"><i class="fa fa-download"></i> Download</a>
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>
                <form id="bulkActionForm" method="POST" action="{{ route('customers.bulkAction') }}">
                    @csrf
                    <div class="mb-3 col-md-3">
                        <select name="action" id="bulkAction" class="form-control">
                            <option value="">Select Action</option>
                            <option value="delete">Delete</option>
                            <option value="change_customer_level">Change Customer Level</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-3">
                        <button type="submit" class="btn btn-primary mt-2" onclick="return confirmBulkAction()">Apply</button>
                    </div>
                    <input type="hidden" name="customer_level" id="customerLevelInput">
                    <div class="table-responsive">
                        <table class="table text-nowrap"  style="width:100%">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>S/N</th>
                                    <th>Status</th>
                                    <th>Level</th>
                                    <th>Balances</th>
                                    <th>Joined</th>
                                    @if(hasAccess('customers.edit'))
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="customer_id[]" value="{{ $customer->id }}" class="customer-checkbox">
                                    </td>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <p>
                                            <strong>Name: </strong><a target="_blank" href="{{ route('customers.edit', $customer->id) }}">
                                            {{ $customer->full_name . ' ' . $customer->middle_name .' ' . $customer->last_name  }}</a> <br>
                                            <strong>Username: </strong> {{ $customer->username }} <br>
                                            <strong>Email: </strong> {{ $customer->email }} <br>
                                            <strong>Phone: </strong> {{ $customer->mobile }}
                                        </p>                            
                                    </td>
                                    <td>
                                        <strong>Account Status: </strong><span style="color:{{$customer->status == 'active' ? 'green' : 'red'}}">{{ ucfirst($customer->status) }}</span> <br>
                                        <strong>KYC Status: </strong><span style="color:{{$customer->kyc_status == 1 ? 'green' : 'red'}}">{{$customer->kyc_status == 0 ? 'Inactive' : 'Active'}}</span>
                                    </td>
                                    <td>{{ $customer->customer_level->name ?? 'N/A' }}</td>
                                    <td>
                                        Wallet: {!! settings()->currency !!}{{ number_format(walletBalance($customer)) }} <br>
                                        Referral:  {!! settings()->currency !!}{{ number_format(referralBalance($customer)) }} <br>
                                    </td>
                                    <td> 
                                        <strong>Joined: </strong>{{ date("M jS, Y g:iA", strtotime($customer->created_at)) }} <br>
                                        <strong>Last Login: </strong>{{ date("M jS, Y g:iA", strtotime($customer->last_login)) }} <br>
                                        <strong>Email Verification: </strong><span style="color:{{ !empty($customer->email_verified_at) ? 'green' : 'red' }}">{{ !empty($customer->email_verified_at) ? 'Verified' : 'Unverified' }}</span> <br>
                                    </td>
                                    @if(hasAccess('customers.edit') || hasAccess('customers.destroy'))
                                    <td>
                                        <a href="{{ route('customers.edit', $customer->id) }}"><button type="button" class="btn btn-info btn-sm mr-1 mb-1"><i class="fa fa-edit"></i><span class="align-middle ml-25">View</span></button>
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <script>
                    document.getElementById('selectAll').addEventListener('click', function() {
                        let checkboxes = document.querySelectorAll('.customerCheckbox');
                        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                    });

                    function confirmBulkAction() {
                        let action = document.getElementById('bulkAction').value;
                        if (!action) {
                            alert('Please select an action to apply.');
                            return false;
                        }

                        if (action === 'change_customer_level') {
                            // Show modal instead of submitting
                            const modal = new bootstrap.Modal(document.getElementById('customerLevelModal'));
                            modal.show();
                            return false; 
                        }

                        return confirm(`Are you sure you want to perform "${action}" on the selected customers?`);
                    }
                </script>
            </div>
            {!! $customers->appends($_GET)->links() !!}
        </div>
        <script>
            document.getElementById('select-all').addEventListener('change', function () {
                let checkboxes = document.querySelectorAll('.customer-checkbox');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });
        </script>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="customerLevelModal" tabindex="-1" aria-labelledby="customerLevelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Customer Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <select id="modalCustomerLevel" name="customer_level" class="form-control">
                    <option value="">Select Level</option>
                    @foreach($customer_levels as $level)
                        <option value="{{ $level->id }}" {{ \Request::get('level') == $level->id ? 'selected' : ''}}>{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitCustomerLevel()">Proceed</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('extra-js')
<script>
    function submitCustomerLevel() {
        const selectedLevel = document.getElementById('modalCustomerLevel').value;
        if (!selectedLevel) {
            alert("Please select a customer level.");
            return;
        }

        document.getElementById('customerLevelInput').value = selectedLevel;
        document.getElementById('bulkActionForm').submit();
    }
</script>
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
