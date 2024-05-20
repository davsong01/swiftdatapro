@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
@endsection
@section('title', 'APIs')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('paymentgateway.index')}}">Payment Gateways</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">All Payment Gateways</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                {{-- <div class="card-title">
                    <a class="btn btn-primary mb-2 d-flex align-items-center" href="{{ route('paymentgateway.create') }}"><i class="bx bx-plus"></i>&nbsp; Add API Provider</a>
                </div> --}}
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <table id="datatable-basic" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Webhook Notification URL</th>
                            <th>Status</th>
                            <th>Date Added</th>
                            @if(hasAccess('paymentgateway.edit'))
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentgateways as $gateway)
                        <tr>
                            <td>{{ $gateway->name }}</td>
                            <td>
                                <a href="{{ url('/').'/log-callback/'.$gateway->id }}">{{ url('/').'/log-callback/'.$gateway->id }}</a> <br>
                            </td>
                            <td style="color:{{ $gateway->status == 'active' ? 'green' : 'red'}}">{{ ucfirst($gateway->status) }}</td>
                            <td>{{ $gateway->created_at }}</td>

                            @if(hasAccess('paymentgateway.edit'))
                            <td>
                                <a href="{{ route('paymentgateway.edit', $gateway->id) }}"><button type="button" class="btn btn-primary-light btn-wave btn-sm"><i class="fa fa-edit"></i><span class="align-middle ml-25">View/Edit</span></button></a>
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
