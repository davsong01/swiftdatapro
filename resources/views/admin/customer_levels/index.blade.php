@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
@endsection
@section('title', 'Customer Levels')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('api.index')}}">All Customer Levels</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">All Customer Levels</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    <a class="btn btn-primary mb-2 d-flex align-items-center" href="{{ route('customerlevel.create') }}"><i class="bx bx-plus"></i>&nbsp; Add Customer Level</a>
                </div>
                @include('includes.alerts')
                
            </div>
            <div class="card-body">
                <table id="datatable-basic" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Order</th>
                            <th>Customers Count</th>
                            <th>Upgrade Amount</th>
                            <th>Admin Inch</th>
                            @if(hasAccess('customerlevel.edit') || hasAccess('customerlevel.destroy'))
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($levels as $level)
                        <tr>
                            <td>{{ $level->name }}</td>
                            <td>{{ $level->order }}</td>
                            <td>{{ $level->customers_count }}</td>
                            <td>{{ $level->upgrade_amount }}</td>
                            <td>{{ $level->admin_inch }}</td>
                            @if(hasAccess('customerlevel.edit') || hasAccess('customerlevel.destroy'))
                            <td>
                                @if(hasAccess('customerlevel.edit'))
                                <a href="{{ route('customerlevel.edit', $level->id) }}"><button type="button" class="btn btn-info-light btn-wave btn-sm mb-2"><i class="bx bxs-pencil"></i>View/Edit</button></a>
                                @endif
                                @if(hasAccess('customerlevel.destroy'))
                                    @if ($level->customers_count < 1)
                                    <form action="{{ route('customerlevel.destroy', $level->id) }}"class=""  method="POST" onsubmit="return confirm('Are you sure you want to delete forever?');">{{ csrf_field() }} {{ method_field('DELETE') }}<button type="submit" class="btn btn-danger-light btn-wave btn-sm" data-toggle="tooltip" title="Delete Customer Level">Delete Customer Level</button>
                                    </form>
                                    @endif
                                @endif
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
