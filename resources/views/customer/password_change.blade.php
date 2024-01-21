@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">

      <div id="response" style="text-align: center;">
        <h4>Change Password</h4>
      </div>

    </div>
</div>
@endsection
@section('body')
 
  <div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">

    <!-- start -->
    <div id="response"></div>

    <form id="form-data">

      <div class="input-group mb-3">
        <div class="input-group-text">
          <span class="" id="basic-addon1"><i class="fa fa-unlock-alt" style="font-size: 25px;"></i></span>
        </div>
        <input type="password" class="form-control" placeholder="Current Password" id="old_password" required>
      </div>
     
      <button style="padding-bottom: 13px;" class="btn btn-success btn-block form-control" style="width: 100%" id="submitBtn">SEND ME RESET EMAIL</button>

      <br>

    </form>

  </div>
@endsection
@section('scripts')
    <script type="text/javascript">
  $("#form-data").submit(function(event) {
    var formData = {
      old_password: $("#old_password").val(),
    };

    // $.LoadingOverlay("show");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
      url: '{{ route('process.password.change')}}',
      method: 'POST',
      dataType: 'json',
      data: formData,
      success: function(response_fd) {
        $.LoadingOverlay("hide");
        if (response_fd.status == 'success') {
          $("#successModal").modal("show");
          $("#successModal .modal-body").html(response_fd.message);
          setTimeout(function() {
            window.location.reload();
          }, 2000);
        } else {
          $("#errorModal").modal("show");
          $("#errorModal .modal-body").html(response_fd.message);
        }
      }
    });

    event.preventDefault();
  });
</script>
@endsection
