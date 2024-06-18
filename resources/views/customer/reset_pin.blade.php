@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">

      <div id="response" style="text-align: center;">
        <h4>Reset PIN</h4>
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
        <input type="password" class="form-control" placeholder="Enter your password" id="old_pin" required>
      </div>
      <br>

      <button class="btn btn-success btn-block" style="width: 100%">SUBMIT</button>
      <br>
      <br>
    </form>
  </div>
@endsection
@section('scripts')
<script type="text/javascript">
  $("#form-data").submit(function(event) {
    var formData = {
      old_pin: $("#old_pin").val(),
    };

    $.LoadingOverlay("show");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      url: '{{route('pin.process.reset')}}',
      method: 'POST',
      dataType: 'json',
      data: formData,
      success: function(response_fd) {
        $.LoadingOverlay("hide");
        if (response_fd.status == 'success') {
          $("#successModal").modal("show");
          $("#successModal .modal-body").html(response_fd.message);
            // location.reload(); 
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