@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="{{asset('new/assets/libs/quill/quill.snow.css')}}">
<link rel="stylesheet" href="{{asset('new/assets/libs/quill/quill.bubble.css')}}">
@endsection
@section('title', 'Edit Notification')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('notification.index')}}">Notifications</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Edit Notification</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Add Notification</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <form action="{{ route('notification.update', $notification->id) }}" method="POST"  enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="row gy-4">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Name<span
                            class="text-danger">*</span></label>
                            <input type="text" value="{{$notification->name ?? old('name')}}" class="form-control" name="name" id="name" placeholder="Name" required>
                        </div>
                        <div class="col-md-12">
                            <div class="editor" id="editor_id" style="min-height: 240px">
                                {!! $notification->content ?? old('content') !!}
                            </div>
                        </div>
                        <input name="content" type="hidden" id="content" />
                        <div class="col-md-12">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-control" name="type" id="type">
                                <option value="">Select</option>
                                <option value="popup" {{ $notification->type == 'popup' ? 'selected' : ''}}>Pop Up Message</option>
                                <option value="scroll" {{$notification->type == 'scroll' ? 'selected' : ''}}>Scroll Message</option>
                                <option value="general" {{$notification->type == 'general' ? 'selected' : ''}}>General</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="">Select</option>
                                <option value="active" {{ $notification->status == 'active' ? 'selected' : ''}}>Active</option>
                                <option value="inactive" {{ $notification->status == 'inactive' ? 'selected' : ''}}>InActive</option>
                            </select>
                        </div>
                    </div>
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
