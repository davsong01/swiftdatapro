@if(session()->get('message'))
<div class="alert alert-success" role="alert">
      {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
      {!! session()->get('message') !!}
</div>
@endif

@if(session()->get('error'))
<div class="alert alert-danger" role="alert">
      {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
     {{ session()->get('error')}}
</div>
@endif

@if(session()->get('any'))
<div class="alert alert-warning" role="alert">
      {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
     {!! session()->get('any') !!}
</div>
@endif

@if(session()->get('warning'))
<div class="alert alert-warning" role="alert">
        {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
        {{ session()->get('warning')}}
      </div>
@endif

@if($errors->any())
 <div class="alert alert-danger" role="alert">
      {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
      @foreach($errors->all() as $error)
            {{ $error }}<br>
      @endforeach
</div>
@endif