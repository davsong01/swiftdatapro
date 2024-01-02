@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
  <div class="alert" style="background-color: #fff;color: blue;">
    <div id="response" style="text-align: center;">
      <h4>Cheap Airtime VTU</h4>
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

  {{-- <div id="how_to_load" style="display:none;background: #d5f4d5;padding: 10px;margin-bottom: 10px;">
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
    </div> --}}

  <form id="form-data">
    @csrf
    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fa-solid fa-wifi" style="font-size: 25px;"></i></span>
      </div>
      <input type="hidden" id="network_discount">
      <select class="form-control" id="network_module" required>
        <option value="">--Choose--</option>

        @if($productAccess['m_lock2'] != 0)
          <option value="MTN-VTU">MTN-VTU</option>
        @endif
        @if($productAccess['g_lock2'] != 0)
          <option value="GLO-VTU">GLO-VTU</option>
        @endif
        @if($productAccess['a_lock2'] != 0)
          <option value="AIRTEL-VTU">AIRTEL-VTU</option>
        @endif
        @if($productAccess['mo_lock2'] != 0)
          <option value="9MOBILE-VTU">9MOBILE-VTU</option>
        @endif
        @if($productAccess['smile_vtu_lock'] != 0)
          <option value="SMILE-VTU">SMILE-VTU</option>
        @endif
      </select>
    </div>

    <br>
    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fas fa-phone" style="font-size: 25px;"></i></span>
      </div>
      <input type="tel" class="form-control" placeholder="Mobile No." id="mobile_no" required>
    </div>
    <br>
    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><span style="font-weight: bold;font-size: 22px;">₦</span></span>
      </div>
      <input type="tel" class="form-control" maxlength="4" placeholder="Amount" onkeyup="CalDiscount()" id="amount"
        required>
    </div>
    <div>
      <small style="color: red;font-weight: bold;" id="valAmount"></small>
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

    <button class="btn btn-success btn-block" style="width: 100%" id="submitBtn">BUY NOW</button>

    <br>
    <br>
  </form>


  <hr />
  <div id="checkbal">
    <h5 style="font-weight:bold;color:#940798">To Check All Networks Airtime Balances: *310#</h5> <br>
  </div>
</div>

<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="myModalLabel"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  function CalDiscount() {
    var amount = $("#amount").val();
    var network_discount = $("#network_discount").val();
    if (amount != "" && amount > 99 && network_discount != "") {
      var charges = parseInt(amount) * parseInt(network_discount) / 100;
      var fin_amount = amount - charges;
      $("#valAmount").text("You will pay N" + fin_amount);
    } else {
      $("#valAmount").text("");
    }
  }

  $("#network_module").change(function() {
    if ($("#network_module").val() != "") {
      var network_module = $("#network_module").val();
      $.LoadingOverlay("show");
      $.ajax({
        url: '/discounts?services=' + network_module + '&level={{ auth()->user()->level }}&&product=airtime',
        method: 'GET',
        success: function(RES_data) {
          $.LoadingOverlay("hide");
          //$("#result").html(RES_data);
          $("#network_discount").val(RES_data);
        }
      })
    }
  })

  $("#form-data").submit(function(event) {
    var formData = {
      network_module: $("#network_module").val(),
      amount: $("#amount").val(),
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
      url: '/buy/airtime',
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