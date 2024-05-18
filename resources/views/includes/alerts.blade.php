<div class="col-md-12">
@if(session()->get('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      {!! session()->get('message') !!}
</div>
@endif

@if(session()->get('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      {{ session()->get('error')}}
</div>
@endif

@if(session()->get('any'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      {!! session()->get('any') !!}
</div>
@endif

@if(session()->get('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
        {{ session()->get('warning')}}
      </div>
@endif

@if(session()->get('unverified'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      {!! session()->get('unverified') !!}
      </div>
@endif

@if(session()->get('welcomeback'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
       <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      {{ session()->get('welcomeback')  }} &#128515
</div>
@endif

@if($errors->any())
 <div class="alert alert-warning alert-dismissible fade show" role="alert">
       <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      @foreach($errors->all() as $error)
            {{ $error }}<br>
      @endforeach
</div>
@endif
@if(session('resent'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
       <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      <strong>A New verification link has been sent to your email address. </strong>
</div>
@endif
@if(session('verified'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
       <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      <strong>Email succesfully verified, Welcome! </strong>
</div>
@endif

@if(session('status'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
       <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      <strong>{{ session('status') }}</strong>
</div>
@endif

@if(isset(auth()->user()->permission))
@if(auth()->user()->permission == 1 && auth()->user()->bank == NULL || auth()->user()->account_name == NULL || auth()->user()->account_number == NULL)
<p style="display:none">{{ $bank_details=0 }}</p>
<div class="alert alert-danger alert-dismissible fade showr" role="alert">
       <button type="button" class="btn-close" data-bs-dismiss="alert"
        aria-label="Close"><i class="bi bi-x"></i></button>
      Hi, you have not filled your bank details, <strong>click <a href="{{ route('profile.edit', auth()->user()->id) }}">HERE</a> to fill it now</strong></strong>
</div>
@endif
@endif
</div>