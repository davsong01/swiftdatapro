@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
@endsection
@section('title', 'Categories')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('category.index')}}">All Categories</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">All Categories</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    <a class="btn btn-primary mb-2 d-flex align-items-center" href="{{ route('category.create') }}"><i class="bx bx-plus"></i>&nbsp; Add Category</a>
                </div>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <table id="datatable-basic" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Order</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Date Added</th>
                            @if(hasAccess('category.edit') || hasAccess('category.destroy'))
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->order }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td style="color:{{ $category->status == 'active' ? 'green' : 'red'}}">{{ ucfirst($category->status) }}</td>
                            <td>{{ $category->created_at }}</td>
                            @if(hasAccess('category.edit') || hasAccess('category.destroy'))
                            <td>
                                @if(hasAccess('category.edit'))
                                <a href="{{ route('category.edit', $category->id) }}"><button type="button" class="btn btn-primary-light btn-wave btn-sm"><i class="bx bxs-pencil"></i><span class="align-middle ml-25">View/Edit</span></button></a>
                                @endif
                                @if($category->products_count < 1)
                                @if(hasAccess('category.destroy'))
                                <form action="{{ route('category.destroy', $category->id) }}"
                                    class="btn btn-custon-four btn-sm-cl-social" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete forever?');">
                                    {{ csrf_field() }}
                                    {{method_field('DELETE')}}

                                    <button type="submit" class="btn btn-danger-light btn-wave btn-sm"
                                        data-toggle="tooltip" title="Delete Category"><i class="bx bxs-trash"></i> </i><span class="align-middle ml-25">Delete</span> Category                                                                </button>
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
