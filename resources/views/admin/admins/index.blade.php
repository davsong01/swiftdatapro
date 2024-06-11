@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
@endsection
@section('title', 'Staff')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('role.index')}}">All Staff</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">All Staff</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    <a class="btn btn-primary mb-2 d-flex align-items-center" href="{{ route('staff.create') }}"><i class="bx bx-plus"></i>&nbsp; Add Staff</a>
                </div>
                @include('includes.alerts')
                
            </div>
            <div class="card-body">
                <table id="datatable-basic" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admin as $staff)
                        <tr>
                            <td>
                                <strong>Name:</strong> {{ $staff->full_name }} <br>
                                <strong>Username:</strong> {{ $staff->username }} <br>
                                <strong>Email:</strong> {{ $staff->email }} <br>
                                <strong style="color:green">Last Seen:</strong> {{ $staff->lastseen }} <br>
                            </td>
                            <td>{{ $staff->username }}</td>
                            <td>{{ $staff->email }}</td>
                            <td>{{ ucfirst($staff->status) }}</td>
                            <td>
                                <div class="btn-list">
                                    <a href="{{route('staff.edit', $staff->id)}}" button class="btn btn-primary-light btn-wave btn-sm"><i class="bx bxs-pencil"></i> View/Edit</a>
                                    {{-- <button type="button" class="btn btn-secondary-light btn-wave btn-sm">Secondary</button>
                                    <button type="button" class="btn btn-success-light btn-wave btn-sm">Success</button>
                                    <button type="button" class="btn btn-danger-light btn-wave btn-sm">Danger</button>
                                    <button type="button" class="btn btn-warning-light btn-wave btn-sm">Warning</button>
                                    <button type="button" class="btn btn-info-light btn-wave btn-sm">Info</button>
                                    <button type="button" class="btn btn-purple-light btn-wave btn-sm">purple</button>
                                    <button type="button" class="btn btn-teal-light btn-wave btn-sm">teal</button>
                                    <button type="button" class="btn btn-orange-light btn-wave btn-sm">orange</button> --}}
                                </div>
                            </td>
                            
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
