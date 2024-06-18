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
                <table id="datatable-basic" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Customer Details</th>
                            <th>Details</th>
                            <th>Bank Name</th>
                            <th>Account Name</th>
                            <th>Account No</th>
                            <th>Status</th>
                            @if(hasAccess('accounts.destroy') || hasAccess('accounts.destroy'))
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $account)
                        <tr>
                            <td>
                                <strong>Name:</strong> {{ $account->customer->full_name }} <br>
                                <strong>Email:</strong> {{ $account->customer->email }} <br>                  
                            </td>
                             <td>
                                <strong>Created: </strong> {{ $account->created_at }} <br>
                                <strong>By:</strong> SYSTEM<br>
                               <strong>Provider: </strong>{{ $account->gateway->name }} <br>
                            </td>
                            <td>{{ $account->bank_name }}</td>
                            <td>{{ $account->account_name }}</td>
                            <td>{{ $account->account_number }}</td>
                            <td style="color:{{ $account->status == 'active' ? 'green' : 'red'}}">{{ ucfirst($account->status) }}</td>
                           
                            @if(hasAccess('accounts.edit') || hasAccess('accounts.destroy'))
                            <td>
                                <form action="{{ route('accounts.destroy', $account->id) }}" class="btn btn-custon-four btn-sm-cl-social" method="POST" onsubmit="return confirm('Are you sure you want to delete forever?');">
                                    {{ csrf_field() }}
                                    {{method_field('DELETE')}}
                                    <button type="submit" class="btn btn-danger-light btn-wave btn-sm"
                                        data-toggle="tooltip" title="Delete Account"><i class="bx bxs-trash"></i> </i><span class="align-middle ml-25">Delete Account</span>                                                                 </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
