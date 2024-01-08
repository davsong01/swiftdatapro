@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
  <div class="alert" style="background-color: #fff;color: blue;">
    <h4>Electricity Bills Payment</h4>
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
        @if($productAccess['abuja_lock'] != 0)
          <option value="abuja-electric" {{ old('service_module') == 'abuja-electric' ? 'selected' : ''}}>ABUJA</option>
        @endif
        @if($productAccess['eko_lock'] != 0)
          <option value="eko-electric"  {{ old('service_module') == 'eko-electric' ? 'selected' : ''}}>EKO</option>
        @endif
        @if($productAccess['ibadan_lock'] != 0)
          <option value="ibadan-electric" {{ old('service_module') == 'ibadan-electric' ? 'selected' : ''}}>IBADAN</option>
        @endif
        @if($productAccess['ikeja_lock'] != 0)
          <option value="ikeja-electric" {{ old('service_module') == 'ikeja-electric' ? 'selected' : ''}}>IKEJA</option>
        @endif
        @if($productAccess['kaduna_lock'] != 0)
          <option value="kaduna-electric" {{ old('service_module') == 'kaduna-electric' ? 'selected' : ''}}>KADUNA</option>
        @endif
        @if($productAccess['kano_lock'] != 0)
          <option value="kano-electric" {{ old('service_module') == 'kano-electric' ? 'selected' : ''}}>KANO</option>
        @endif
        @if($productAccess['jos_lock'] != 0)
          <option value="jos-electric" {{ old('service_module') == 'jos-electric' ? 'selected' : ''}}>JOS</option>
        @endif
        @if($productAccess['port_lock'] != 0)
          <option value="portharcourt-electric" {{ old('service_module') == 'portharcourt-electric' ? 'selected' : ''}}>PORTHARCOURT</option>
        @endif
        @if($productAccess['benin_lock'] != 0)
          <option value="benin-electric" {{ old('service_module') == 'benin-electric' ? 'selected' : ''}}>BENIN ELECTRIC</option>
        @endif
        @if($productAccess['enugu_lock'] != 0)
          <option value="enugu-electric"  {{ old('service_module') == 'enugu-electric' ? 'selected' : ''}}>ENUGU ELECTRIC</option>
        @endif
        @if($productAccess['aba_lock'] != 0)
          <option value="aba-electric"  {{ old('service_module') == 'aba-electric' ? 'selected' : ''}}>ABA ELECTRIC</option>
        @endif
      </select>
    </div>
    <span id="result"></span>

    <br>
    <div class="input-group mb-3">
        <div class="input-group-text">
          <span class="" id="basic-addon1"><i class="fa fa-shopping-cart" style="font-size: 25px;"></i></span>
        </div>
        <select class="form-control" id="service_plan" name="service_plan" required>
          <option value="">--Select Plan--</option>
          <option value="prepaid" {{ old('service_plan') == 'prepaid' ? 'selected' : ''}}>Prepaid</option>
          <option value="postpaid" {{ old('service_plan') == 'postpaid' ? 'selected' : ''}}>Postspaid</option>
        </select>
      </div>

    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fas fa-phone" style="font-size: 25px;"></i></span>
      </div>
      <input type="tel" class="form-control" placeholder="Mobile No." id="mobile_no" name="mobile_no" value="{{ auth()->user()->mobile ?? old('mobile_no') }}" required>
    </div>
    <br>
    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fa fa-pencil-square-o" style="font-size: 25px;"></i></span>
      </div>
      <input type="tel" class="form-control" placeholder="Meter/Smart No." id="smart_no" name="smart_no" value="{{old('smart_no')}}" required>
    </div>


    <br>

      <div class="input-group mb-3">
        <div class="input-group-text">
          <span class="" id="basic-addon1"><span style="font-weight: bold;font-size: 22px;">₦</span></span>
        </div>
        <input type="tel" class="form-control" maxlength="6" placeholder="Amount" id="amount" name="amount" value="{{ old('amount') }}" required>
      </div>

      <br>



    <small>Forget Pin ? <a href="{{ url('/') }}/reset_pin">Reset Here</a></small>
    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fa fa-unlock-alt" style="font-size: 25px;"></i></span>
      </div>
      <input type="password" class="form-control" placeholder="Security PIN" id="security_pin" required>
    </div>

    <br>
      <button class="btn btn-success btn-block" id="submitBills" style="width: 100%">Verify Meter/SMart No</button>
    <br>
    <br>
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
        url: '/variations?services=' + service_module + '&level={{ auth()->user()->level }}&product=electricity&ajax=yes',
        method: 'GET',
        success: function (RES_data) {
          $.LoadingOverlay("hide");
        
          console.log(RES_data);
          $("#result").html(RES_data);
        }
      })
    }
  })

  $("#submitBills").click(function (event) {
    var formData = {
      service_module: $("#service_module").val(),
      service_plan: $("#service_plan").val(),
      smart_no: $("#smart_no").val(),
      security_pin: $("#security_pin").val(),
      type: 'electricity',
    };

    $.LoadingOverlay("show");
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      url: '{{url("/")}}/verify/'+service_module,
      method: 'POST',
      dataType: 'json',
      data: formData,
      success: function (response_fd) {
        $.LoadingOverlay("hide");

        if (response_fd.status == 'success') {
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

  function pay4Bills() {
    var formData = {
      service_module: $("#service_module").val(),
      network_module: $("#service_module").val(),
      service_plan: $("#service_plan").val(),
      smart_no: $("#smart_no").val(),
      amount: $("#amount").val(),
      mobile_no: $("#mobile_no").val(),
      security_pin: $("#security_pin").val(),
      req_token: $("#req_token").val(),
    };

    $("#myModal2").modal("hide");
    $.LoadingOverlay("show");
    $.ajax({
      url: '/buy/electricity',
      method: 'POST',
      dataType: 'json',
      data: formData,
      success: function (response_fd) {
        $.LoadingOverlay("hide");
        if (response_fd.status == 'success') {
          $("#successModal").modal("show");
          $("#successModal .modal-body").html(response_fd.message);
          setTimeout(function () {
            window.location.reload();
          }, 3000);
        } else {
          $("#errorModal").modal("show");
          $("#errorModal .modal-body").html(response_fd.message);
        }
      }
    });

  };


  function closeModal() {
    $("#myModal2").modal("hide");
  }
</script>
@endsection