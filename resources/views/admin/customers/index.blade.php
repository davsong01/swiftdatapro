@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
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
                        </div>
                    </form>
                    <hr>
                </div>
                <table class="table table-bordered responsive"  style="width:100%">
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th>Email</th>
                            <th>Phone</th>
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
                                <p>
                                    Name:<a target="_blank" href="{{ route('customers.edit', $customer->id) }}">
                                        {{ $customer->full_name . ' ' . $customer->middle_name .' ' . $customer->last_name  }}</a> <br>
                                    Username:  {{ $customer->username }}
                                </p>                            
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->mobile }}</td>
                            <td> <small><strong>{{ ucfirst($customer->status) }}</strong></small></td>
                            <td>{{ $customer->customer_level->name ?? 'N/A' }}</td>
                            <td>
                                Wallet: {!! settings()->currency !!}{{ number_format(walletBalance($customer)) }} <br>
                                Referral:  {!! settings()->currency !!}{{ number_format(referralBalance($customer)) }} <br>
                            </td>
                            <td>{{ $customer->reg_date  }}</td>

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
            {!! $customers->appends($_GET)->links() !!}
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
