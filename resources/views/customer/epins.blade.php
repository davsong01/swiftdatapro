@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
  <div class="alert" style="background-color: #fff;color: blue;">
    <h4>Result Epins</h4>
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

    <div id="response"></div>
    <form id="form-data">

      <div class="input-group mb-3">
        <div class="input-group-text">
          <span class="" id="basic-addon1"><i class="	fa fa-graduation-cap" style="font-size: 25px;"></i></span>
        </div>
        <select class="form-control" id="service_module">
          <option value="">--Choose--</option>
         
          @if($productAccess['waec_lock'] != 0)
            <option value="WAEC">WAEC</option>;
          @endif
          @if($productAccess['waec_reg_lock'] != 0)
            <option value="WAEC">WAEC REGISTRATION PIN</option>;
          @endif
          @if($productAccess['gce_lock'] != 0)
            <option value="GCE">GCE</option>;
          @endif
          @if($productAccess['neco_lock'] != 0)
            <option value="NECO">NECO</option>;
          @endif
          @if($productAccess['nabt_lock'] != 0)
            <option value="NABTEB">NECO</option>;
          @endif
          @if($productAccess['nabt_lock'] != 0)
            <option value="NBAIS">NBAIS</option>;
          @endif
        </select>
      </div>
      <span id="result"></span>

      <br>

      <div class="input-group mb-3">
        <div class="input-group-text">
          <span class="" id="basic-addon1"><i class="fa fa-shopping-cart" style="font-size: 25px;"></i></span>
        </div>
        <select class="form-control" id="service_quantity">
          <option value="">--Choose--</option>
          <option value="1">One Unit</option>
          <option value="2">Two Units</option>
          <option value="3">Three Units</option>
          <option value="4">Four Units</option>
          <option value="5">Five Units</option>
          <option value="10">Ten Units</option>
        </select>
      </div>

      <br>

      <div class="input-group mb-3">
        <div class="input-group-text">
          <span class="" id="basic-addon1"><i class="fas fa-phone" style="font-size: 25px;"></i></span>
        </div>
        <input type="tel" class="form-control" placeholder="Mobile No." id="mobile_no" value="{{AUTH()->USER()->mobile}}" maxlength="11" required>
      </div>

      <br>

      <small>Forget Pin ? <a href="{{ route('pin.reset') }}">Reset Here</a></small>
      <div class="input-group mb-3">
        <div class="input-group-text">
          <span class="" id="basic-addon1"><i class="fa fa-unlock-alt" style="font-size: 25px;"></i></span>
        </div>
        <input type="password" class="form-control" placeholder="Security PIN" id="security_pin" required>
      </div>

      <br>

      <button class="btn btn-success btn-block" style="width: 100%" id="submitBtn">Purchase Now</button>

      <br>
      <br>
    </form>

  </div>

{{-- <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="myModalLabel"></h3>
        <span id="verify-title"></span>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body"></div>
      
    </div>
  </div> --}}
{{-- </div> --}}
@endsection
@section('scripts')
<script type="text/javascript">
    $("#service_module").change(function() {
      if ($("#service_module").val() != "") {
        var service_module = $("#service_module").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
      $.LoadingOverlay("show");

      $.ajax({
        url: '/variations?services=' + service_module + '&level={{ auth()->user()->level }}&product=epin&ajax=yes',
          method: 'GET',
          success: function(RES_data) {
            $.LoadingOverlay("hide");
            $("#result").html(RES_data);
          }
        })
      }
    })

    $("#form-data").submit(function(event) {
      var formData = {
        service_module: $("#service_module").val(),
        service_quantity: $("#service_quantity").val(),
        mobile_no: $("#mobile_no").val(),
        security_pin: $("#security_pin").val(),
        req_token: $("#req_token").val(),
      };

      // $.LoadingOverlay("show");
      $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        url: '/buy/epin',
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

    function closeModal() {
      $("#myModal2").modal("hide");
    }
</script>
@endsection