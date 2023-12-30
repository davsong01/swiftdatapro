@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">
        <div id="response" style="text-align: center;">
            <h4>Buy Data Bundle</h4>
            <p style="color: red;font-weight: bold;"></p>
        </div>
    </div>
</div>

@endsection
@section('body')
<div class="col-xl-12">
    <div class="alert" id="temp"
        style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;">
        <div id="response" style="text-align: left;">
            <span><button class="btn btn-dark" onclick="FundWallet()" style="font-size:10px">Fund Wallet</button></span>
            <br /><br />
        </div>
    </div>
</div>

<div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">

    <!-- start -->


    <div id="response"></div>
    <div id="extra"
        style="margin:5px 0;background: black;color: yellow;padding: 20px;margin-bottom: 15px;font-size: initial; display:none">
    </div>

    <div id="how_to_load" style="display:none;background: #d5f4d5;padding: 10px;margin-bottom: 10px;">
        <div class="col-lg-12 me">
            <div class="form-group">
                <label class="small mb-1" for="amount" style="color: #000000">To Load MTN Data Card</label>
                <ol style="margin-left: 15px;">
                    <li style=" list-style: decimal;">Dial: *460*6*1#</li>
                    <li style="list-style: decimal;">Insert the pin you generated from your swiftdatang account (make
                        sure you include the letter S that ends the number)</li>
                    <li style="list-style: decimal;">Recharge successful</li>
                </ol>
                To Check Data coupon balance: Dial *323*4#
            </div>
        </div>
    </div>

    <form id="form-data">
        @csrf
        <div class="input-group mb-3">
            <div class="input-group-text">
                <span class="" id="basic-addon1"><i class="fa-solid fa-wifi" style="font-size: 25px;"></i></span>
            </div>
           
            <select class="form-control" id="network_module" required>
                <option value="">--Choose--</option>
                
                @if ($productAccess['m_lock1'] != 0)
                    <option value="MTN">MTN-SME</option>
                @endif
                @if ($productAccess['mg_lock1'] != 0)
                    <option value="MTN-CG">MTN-CG</option>
                @endif
                @if ($productAccess['mtn_data_card_lock'] != 0)
                    <option value="MTN-DATACARD">MTN-DATACARD</option>
                @endif
                @if ($productAccess['g_lock1'] != 0)
                    <option value="GLO">GLO</option>
                @endif
                @if ($productAccess['gcg_lock1'] != 0)
                    <option value="GLO-CG">GLO-CG</option>
                @endif
                @if ($productAccess['a_lock1'] != 0)
                    <option value="AIRTEL">AIRTEL</option>
                @endif
                @if ($productAccess['ag_lock1'] != 0)
                    <option value="AIRTEL-CG">AIRTEL-CG</option>
                @endif
                @if ($productAccess['mo_lock1'] != 0)
                    <option value="9MOBILE">9MOBILE</option>
                @endif
                @if ($productAccess['mobilesme_lock'] != 0)
                    <option value="MOBILE-SME">9MOBILE SME</option>
                @endif
                @if ($productAccess['spectranet_lock'] != 0)
                    <option value="SPECTRANET-DATA">SPECTRANET</option>
                @endif
                @if ($productAccess['smile_lock'] != 0)
                    <option value="SMILE-DATA">SMILE</option>
                @endif
            </select>
        </div>

        <br>

        <div class="input-group mb-3">
            <div class="input-group-text">
                <span class="" id="basic-addon1"><i class="fa fa-shopping-cart" style="font-size: 25px;"></i></span>
            </div>
            @csrf
            <select class="form-control" class="dataplanload" style="font-size:11px" id="dataplanloader" required>
                <option value="">--Select Network--</option>
            </select>

        </div>
        <br>

        <div class="input-group mb-3" id="ask_pins" style="display:none">
            <div class="input-group-text">
                <span class="" id="basic-addon1"><i class="fa fa-calculator" style="font-size: 25px;"></i></span>
            </div>
            <input type="number" class="form-control" placeholder="No of Pins" id="no_of_pins" value="">
            <br>
        </div>

        <small id="ask_pins_small" style="color:red;display:none">Phone number is optional<br></small>
        <div class="input-group mb-3">
            <div class="input-group-text">
                <span class="" id="basic-addon1"><i class="fas fa-phone" style="font-size: 25px;"></i></span>
            </div>
            <input type="tel" class="form-control" placeholder="Mobile No." id="mobile_no" maxlength="11" required>
        </div>

        <div class="input-group mb-3" id="smile-verify-email" style="margin-top: 35px;display:none">
            <div class=" input-group-text">
                <br><br>
                <span class="" id="smile-email"><i class="fas fa-envelope" style="font-size: 25px;"></i></span>
            </div>
            <input type="email" class="form-control" placeholder="Email address associated with smile Account"
                id="smile_email">
        </div>

        <div id="extra"
            style="margin:5px 0;background: black;color: yellow;padding: 20px;margin-bottom: 15px;font-size: initial; display:none">
        </div>

        <small>Forget Pin ? <a href="{{ url('/') }}/reset_pin">Reset Here</a></small>
        <div class="input-group mb-3">
            <div class="input-group-text">
                <span class="" id="basic-addon1"><i class="fa fa-unlock-alt" style="font-size: 25px;"></i></span>
            </div>
            <input type="password" class="form-control" placeholder="Security PIN" id="security_pin" required>
        </div>

        <br>

        <button class="btn btn-success btn-block" style="width: 100%" id="submitBtn">BUY NOW</button>

        <br>
        <br>
    </form>


    <hr />
    <div id="checkbal">
        <h5 style="font-weight:bold">CODES FOR BALANCE CHECKING AND RECHARGES</h5>

        <h5 style="color:#940798"><img src="{{ asset('backend/img/mtn.png') }}" style="border-radius: 50%;" height="30px"> MTN SME DATA
            <=> *323*3# OR *461*4# </h5>
        <h5 style="color:#940798"><img src="{{ asset('backend/img/mtn.png') }}" style="border-radius: 50%;" height="30px"> MTN CG & CG
            LITE DATA <=> *460*260# </h5>
       
        <h5 style="color:#940798"><img src="{{ asset('backend/img/glo.png') }}" style="border-radius: 50%;" height="30px"> GLO (CG &
            Direct) >> *127*0# </span></h5>
       
        <h5 style="color:#940798"><img src="{{ asset('backend/img/airtel.png') }}" style="border-radius: 50%;" height="30px"> AirTel
            (CG & Direct) >> *140# </h5>

        <h5 style="color:#940798"><img src="{{ asset('backend/img/mobile.png') }}" style="border-radius: 50%;" height="30px"> 9mobile
            (Direct & SME) >> *228#</span></h5>
        <br>
    </div>
</div>

<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="myModalLabel"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body"> </div>

    </div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  $("#network_module").change(function() {
    if ($("#network_module").val() != "") {
      var network_module = $("#network_module").val();
      $.LoadingOverlay("show");
      $.ajax({
        url: '/variations?services=' + network_module + '&level={{ auth()->user()->level }}&&product=data',
        method: 'GET',
        success: function(RES_data) {
          $.LoadingOverlay("hide");

          if ($("#network_module").val() == 'SMILE-DATA') {
            $("#smile-verify-email").show();
          } else {
            $("#smile-verify-email").hide();

          }
          if (network_module == 'MTN-DATACARD') {
            $("#ask_pins").show();
            $("#ask_pins_small").show();
            $("#how_to_load").show();
            $("#how_to_load").show();

            $("#no_of_pins").attr("required", true);
            $("#mobile_no").attr("required", false);

          } else {
            $("#ask_pins").hide();
            $("#no_of_pins").attr("required", false);
            $("#no_of_pins").val("1");
            $("#ask_pins_small").hide();
            $("#how_to_load").hide();
            $("#mobile_no").attr("required", true);

          }
          $("#dataplanloader").html(RES_data);

        }
      })
    }
  })

  $("#form-data").submit(function(event) {
    var formData = {
      network_module: $("#network_module").val(),
      dataplanloader: $("#dataplanloader").val(),
      mobile_no: $("#mobile_no").val(),
      security_pin: $("#security_pin").val(),
      req_token: $("#req_token").val(),
      no_of_pins: $("#no_of_pins").val()
    };

    if ($("#network_module").val() == 'SMILE-DATA') {
      return verifySmile();
    }
    // $.LoadingOverlay("show");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      url: '{{ config('app.url')}}/buy/data',
      method: 'POST',
      dataType: 'json',
      data: formData,
      success: function(response_fd) {
        $.LoadingOverlay("hide");
        if (response_fd.status == 'success') {
          $("#successModal").modal("show");
          $("#successModal .modal-body").html(response_fd.message);

          if ($("#network_module").val() == 'MTN-DATACARD') {
            if (response_fd.extra) {
              $("#extra").html(response_fd.extra);
              $("#extra").show();
              $("#how_to_load").show();

              document.getElementById("form-data").reset();
            }
          } else {
            setTimeout(function() {
              window.location.reload();
            }, 2000);
          }

        } else {
          $("#errorModal").modal("show");
          $("#errorModal .modal-body").html(response_fd.message);
        }
      }
    });

    event.preventDefault();
  });

  function verifySmile() {
    var formData = {
      service_module: $("#network_module").val(),
      smart_no: $("#smile_email").val(),
      dataplanloader: $("#dataplanloader").val(),
      mobile_no: $("#mobile_no").val(),
      security_pin: $("#security_pin").val(),
      req_token: $("#req_token").val(),
      no_of_pins: $("#no_of_pins").val(),
    };

    $.LoadingOverlay("show");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      url: 'customer/verify',
      method: 'POST',
      dataType: 'json',
      data: formData,
      success: function(response_fd) {
        $.LoadingOverlay("hide");

        if (response_fd.status == 'success') {
          $("#myModal2 .modal-header").html(response_fd.title);
          $("#myModal2 .modal-body").html(response_fd.message);
          $("#myModal2").modal("show");
        } else {
          $("#errorModal").modal("show");
          $("#errorModal .modal-body").html(response_fd.message);
        }
      }

    });

    event.preventDefault();
  }

  function pay4smile(account_id) {
    var formData = {
      network_module: $("#network_module").val(),
      smart_no: $("#smile_email").val(),
      dataplanloader: $("#dataplanloader").val(),
      mobile_no: $("#mobile_no").val(),
      security_pin: $("#security_pin").val(),
      req_token: $("#req_token").val(),
      no_of_pins: $("#no_of_pins").val(),
      account_id: account_id,
    };

    $("#myModal2").modal("hide");
    $.LoadingOverlay("show");
    $.ajax({
      url: 'customer/data',
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