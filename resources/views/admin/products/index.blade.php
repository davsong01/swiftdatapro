@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
@endsection
@section('title', 'Products')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('product.index')}}">All Products</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">All Products</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    <a class="btn btn-primary mb-2 d-flex align-items-center" href="{{ route('product.create') }}"><i class="bx bx-plus"></i>&nbsp; Add Product</a>
                </div>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <table id="datatable-basic" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Variations</th>
                            <th>Active API</th>
                            <th>Status</th>
                            <th>Date Added</th>
                            @if(hasAccess('product.edit'))
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><img src="{{asset($product->image)}}" alt="{{$product->image}}" style="width: 50px;float:left">{{ $product->name }}</td>
                            <td>{{ $product->category->name }} <br>
                                <strong>API:</strong> {{ $product->api->name }}
                            </td>
                            
                            <td>
                                All: {{ $product->variations->count() }} <br>
                                <span style="color:green">Active: {{ $product->variations->where('status','active')->count() }}</span>
                            </td>
                            <td>{{ $product->api->name }}</td>
                            <td>{{ $product->status }}</td>
                            <td>{{ $product->created_at }}</td>
                            @if(hasAccess('product.edit'))
                                <td>
                                    <a class="btn btn-primary-light btn-wave btn-sm" href="{{ route('product.edit', $product->id) }}"><i class="bx bxs-pencil"></i><span class="align-middle ml-25">View</span></button></a>
                                    <a class="btn btn-info-light btn-wave btn-sm" onclick="return confirm('{{$product->name}} will be duplicated!')" href="{{ route('duplicate.product', $product->id) }}"><i class="bx bxs-copy"></i><span class="align-middle ml-25">Duplicate</span></button></a>
                                    @if($product->has_variations == 'yes')
                                    <a class="btn btn-dark btn-sm mr-1 mb-1" href="{{ route('product.edit', $product->id) }}"><i class="bx bxs-copy"></i><span class="align-middle ml-25">Edit Variations</span></button></a>
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
