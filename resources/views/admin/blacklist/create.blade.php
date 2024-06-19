@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Add Biller')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('blacklist.index')}}">All Blacklisted Billers</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">New Biller</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Add Biller</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <form action="{{route('blacklist.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row gy-4">
                            <div class="col-md-12">
                                <label class="form-label" for="biller">Biller</label>
                                <input type="text" class="form-control" id="biller" name="biller" value="{{ old('biller') }}" placeholder="Enter" required>
                            </div>
                            
                        </div>
                            <div class="col-md-12 mt-3">
                            <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
@endsection
