@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
@endsection
@section('title', 'Blacklisted Billers')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('blacklist.index')}}">All Blacklisted Billers</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">All Blacklisted Billers</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    <a class="btn btn-primary mb-2 d-flex align-items-center" href="{{ route('blacklist.create') }}"><i class="bx bx-plus"></i>&nbsp; Add Biller</a>
                </div>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <table id="datatable-basic" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Biller</th>
                            <th>Added By</th>
                            <th>Added On</th>
                            @if(hasAccess('blacklist.edit') || hasAccess('blacklist.destroy'))
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($billers as $bank)
                        <tr>
                            <td>{{ $bank->biller }}</td>
                            <td>{{ $bank->admin?->full_name }}</td>
                            <td>{{ $bank->created_at }}</td>
                            @if(hasAccess('blacklist.edit') || hasAccess('blacklist.destroy'))
                            <td>
                                @if(hasAccess('blacklist.destroy'))
                                <form action="{{ route('blacklist.destroy', $bank->id) }}"
                                    class="btn btn-custon-four btn-sm-cl-social" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete forever?');">
                                    {{ csrf_field() }}
                                    {{method_field('DELETE')}}

                                    <button type="submit" class="btn btn-danger-light btn-wave btn-sm"
                                        data-toggle="tooltip" title="Remove Biller from blacklist"><i class="bx bxs-trash"></i> </i><span class="align-middle ml-25">Remove</span> Biller                                                                </button>
                                </form>
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
