@extends('layouts.new.app')
@section('extra-css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

@endsection
@section('title', 'Credit Customer')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Credit Customer</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Credit Customer</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <form action="{{ route('admin.process.credit.debit') }}" method="POST"  enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email<span
                            class="text-danger">*</span></label>
                            <select class="js-example-basic-single" name="email" >
                                <option value="">Select user</option>
                                @foreach($users as $user)
                                <option value="{{ $user->email }}">{{ $user->email }} ({{$user->username}})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="amount" class="form-label">Amount<span
                            class="text-danger">*</span></label>
                            <input type="number" value="{{ old('amount') }}" class="form-control" name="amount" id="amount" placeholder="Amount" required>
                        </div>
                        <div class="col-md-12">
                            <label for="reason" class="form-label">Reason<span
                            class="text-danger">*</span></label>
                            <textarea class="form-control" name="reason" id="" cols="30" rows="10" required>{{ old('reason') }}</textarea>
                        </div>
                    </div>
                    <input type="hidden" value="credit" class="form-control" name="type" id="type" required>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<!-- Select2 Cdn -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Internal Select-2.js -->
<script src="{{asset('new/assets/js/select2.js')}}"></script>
@endsection
