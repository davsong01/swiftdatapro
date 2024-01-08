@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
  <div class="alert" style="background-color: #fff;color: blue;">
    <h4>CableTv Subscription</h4>
    <p style="color: red;font-weight: bold;"></p>
  </div>
</div>

@endsection
@section('body')
<div class="col-xl-12">
  <div class="alert" id="temp"
    style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;">
    <span><button class="btn btn-dark" onclick="FundWallet()" style="font-size:10px">Fund Wallet</button></span>
    <br /><br />
  </div>
</div>

<div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">

  <!-- start -->
   <div id="extra"
    style="margin:5px 0;background: black;color: yellow;padding: 20px;margin-bottom: 15px;font-size: initial; display:none">
  </div>
  
  <div class="alert verify-response" style="color:white;background:red;border-radius: 10px 5px;display:none">
    <div id="responsex"></div>
  </div>


  <form id="form-data">
    @csrf
    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fa-solid fa-wifi" style="font-size: 25px;"></i></span>
      </div>
      <input type="hidden" id="network_discount">
      <select class="form-control" id="service_module" name="service_module" required>
        <option value="">--Choose--</option>
        @if($productAccess['dstv_lock'] != 0)
          <option value="DSTV" name="DSTV"
            {{ old('service_module') == 'DSTV' ? 'selected' : '' }}>
            DSTV</option>
        @endif
        @if($productAccess['gotv_lock'] != 0)
          <option value="GOTV" name="GOTV"
            {{ old('service_module') == 'GOTV' ? 'selected' : '' }}>
            GOTV</option>
        @endif
        @if($productAccess['star_lock'] != 0)
          <option value="STARTIMES" name="STARTIMES"
            {{ old('service_module') == 'STARTIMES' ? 'selected' : '' }}>
            STARTIMES</option>
        @endif
        @if($productAccess['showmax_lock'] != 0)
          <option value="SHOWMAX" name="SHOWMAX"
            {{ old('service_module') == 'SHOWMAX' ? 'selected' : '' }}>
            SHOWMAX</option>
        @endif
      </select>
    </div>
    <span id="result"></span>

    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fa fa-shopping-cart" style="font-size: 25px;"></i></span>
      </div>
      <select class="form-control" id="dataplanloader" required>
        <option>--Select Plan--</option>
      </select>
    </div>

    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fa fa-refresh" style="font-size: 25px;"></i></span>
      </div>
      <select class="form-control" id="request_type" required name ="request_type">
        <option>Select renewal type...</option>
        <option value="renewal" {{old('request_type') == 'renewal' ? 'selected' : ''}}>Renewal Plan</option>
        <option value="change" {{old('request_type') == 'change' ? 'selected' : ''}}>Change Plan</option>
      </select>
    </div>
    <div class="input-group mb-3">
        <div class="input-group-text">
          <span class="" id="basic-addon1"><i class="fa fa-pencil-square-o" style="font-size: 25px;"></i></span>
        </div>
        <input type="tel" value="{{old('smart_no')}}" name="smart_no" class="form-control" placeholder="IUC/Smart No." id="smart_no" required>
        <input type="hidden" name="renewal_amount" id="renewal_amount" class="form-control">
    </div>

    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fas fa-phone" style="font-size: 25px;"></i></span>
      </div>
      <input type="tel" class="form-control" placeholder="Mobile No." id="mobile_no" name="mobile_no"
        value="{{ auth()->user()->mobile ?? old('mobile_no') }}" required>
    </div>
  
    <small>Forget Pin ? <a href="{{ url('/') }}/reset_pin">Reset Here</a></small>
    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fa fa-unlock-alt" style="font-size: 25px;"></i></span>
      </div>
      <input type="password" class="form-control" placeholder="Security PIN" id="security_pin" required>
    </div>

    <br>
    <button class="btn btn-success btn-block" id="submitTV" style="width: 100%">Verify IUC/SMart No</button>

  </form>


  <hr />

</div>

<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="myModalLabel"></h3>
        <span id="verify-title"></span>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  $("#service_module").change(function () {
    if ($("#service_module").val() != "") {
      var service_module = $("#service_module").val();
      var result = service_module;
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        url: '/variations?services=' + service_module +
          '&level={{ auth()->user()->level }}&product=cable',
        method: 'GET',
        success: function (RES_data) {
          $.LoadingOverlay("hide");

          console.log(RES_data);
          $("#dataplanloader").html(RES_data);

          // $("#result").html(RES_data);
        }
      })
    }
  })

  
  $("#submitTV").click(function (event) {
    var formData = {
      service_module: $("#service_module").val(),
      service_plan: $("#service_plan").val(),
      smart_no: $("#smart_no").val(),
      request_type: $("#request_type").val(),
      security_pin: $("#security_pin").val(),
      plan_id: $("#dataplanloader").val(),
      type: 'cable',
    };

    // $.LoadingOverlay("show");
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      url: '{{ url("/") }}/verify/' + service_module,
      method: 'POST',
      dataType: 'json',
      data: formData,
      success: function (response_fd) {
        $.LoadingOverlay("hide");

        if (response_fd.status == 'success') {
          if(response_fd.renewal_amount && response_fd.renewal_amount > 0){
            $("#renewal_amount").val(response_fd.renewal_amount);           
          }
          $("#myModal2 .modal-body").html(response_fd.message);
          $("#verify-title").html(response_fd.title);
          
          $("#myModal2").modal("show");
        } else {
          $(".verify-response").show();
          $("#responsex").html(response_fd.message);
        }
      }
    });

    event.preventDefault();
  });

  ///
   function pay4Tv() {
    var formData = {
      service_module: $("#service_module").val(),
      network_module: $("#service_module").val(),
      dataplanloader: $("#dataplanloader").val(),
      renewal_amount: $("#renewal_amount").val(),
      smart_no: $("#smart_no").val(),
      mobile_no: $("#mobile_no").val(),
      security_pin: $("#security_pin").val(),
      req_token: $("#req_token").val(),
      request_type: $("#request_type").val()
    };

    $("#myModal2").modal("hide");
    // $.LoadingOverlay("show");
    $.ajax({
      url: '/buy/cable',
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
          }, 3000);
        } else {
           
          $("#response").html(response_fd.message);
        }
      }
    });

  };

  function closeModal() {
    $("#myModal2").modal("hide");
  }
</script>
@endsection