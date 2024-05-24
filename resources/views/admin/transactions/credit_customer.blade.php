@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="{{asset('new/assets/libs/quill/quill.snow.css')}}">
<link rel="stylesheet" href="{{asset('new/assets/libs/quill/quill.bubble.css')}}">
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
                            <label for="name" class="form-label">Email<span
                            class="text-danger">*</span></label>
                            <input type="text" value="{{ old('email') }}" class="form-control" name="email" id="email" placeholder="Name" required>
                        </div>

                        <div class="col-md-12">
                            <label for="amount" class="form-label">Amount<span
                            class="text-danger">*</span></label>
                            <input type="number" value="{{ old('amount') }}" class="form-control" name="amount" id="amount" placeholder="Amount" required>
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

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.js"></script>
<script>
        let quill = new Quill('.editor', {
            theme: 'snow',
            toolbar: true,
            placeholder: 'Enter content...',
            modules: {
                syntax: true,
                toolbar: '#toolbar-container',
            },
        });

        $('form').on('submit', () => {
            var myEditor = document.querySelector('#editor_id')
            console.log( myEditor);
            var html = myEditor.children[0].innerHTML;
            $('#content').val(html);
        });
    </script>
@endsection
