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
  <div id="extra"
    style="margin:5px 0;background: black;color: yellow;padding: 20px;margin-bottom: 15px;font-size: initial; display:none">
  </div>

  <div class="alert verify-response" style="color:white;background:red;border-radius: 10px 5px;display:none">
    <div id="responsex"></div>
  </div>


  <form id="form-data">
    @csrf
    <div class="input-group mb-3">
      <span class="input-group-text"><i style="font-weight: bold" class="fa-solid fa-wifi"></i></span>
      <input type="hidden" id="network_discount">
      <select class="form-control" id="service_module" required>
        <option value="">--Choose--</option>
        @foreach($category->products as $product)
        @if($product->has_variations == 'yes' && $product->variations->count() > 0 || $product->has_variations == 'no')
          
          <option data-fixed_price="{{ $product->fixed_price }}"
            data-allow_quantity="{{ $product->allow_quantity }}" data-max="{{ $product->max }}"
            data-min="{{ $product->min }}" data-has_variations="{{ $product->has_variations }}"
            value="{{ $product->id }}">{{ $product->display_name }}</option>
        @endif
        @endforeach
      </select>
    </div>
    <span id="result"></span>
    <div class="input-group mb-3">
      <span class="input-group-text"><i style="font-weight: bold" class="fa fa-shopping-cart"></i></span>
      <select class="form-control" id="service_plan" name="service_plan" style="font-size:11px" required>
        <option value="">--Select Plan--</option>
        {{-- <option value="prepaid"
          {{ old('service_plan') == 'prepaid' ? 'selected' : '' }}>
          Prepaid</option>
        <option value="postpaid"
          {{ old('service_plan') == 'postpaid' ? 'selected' : '' }}>
          Postspaid</option> --}}
      </select>
    </div>
    <div class="input-group mb-3">
      <span class="input-group-text"><i style="font-weight: bold" class="fas fa-phone"></i></span>
      <input type="tel" class="form-control" placeholder="Mobile No." id="mobile_no"
        value="{{ auth()->user()->mobile ?? old('mobile_no') }}" required>
    </div>

    <div class="input-group mb-3">
      <span class="input-group-text"><i style="font-weight: bold" class="fa fa-pencil-square-o"></i></span>
      <input type="tel" class="form-control" placeholder="Meter/Smart No." id="smart_no" name="smart_no"
        value="{{ old('smart_no') }}" required>
    </div>

    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><span style="font-weight: bold;font-size: 22px;">₦</span></span>
      </div>
      <input type="tel" class="form-control" maxlength="6" placeholder="Amount" id="amount" name="amount"
        value="{{ old('amount') }}" required>
    </div>
 
    <small>Forget Pin ? <a href="{{ route('pin.reset') }}">Reset Here</a></small>
     <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1"><i class="fa fa-unlock-alt" style="font-weight: bold"></i></span>
      
        <input type="password" class="form-control" placeholder="Security PIN" id="security_pin" required>
    </div>
    <div class="input-group mb-3">
      <button class="btn btn-success btn-block" id="submitBills" style="width: 100%">Verify Meter/SMart No</button>
    </div>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block" onclick="closeModal()">Close</span>
        </button>
        {{-- <button type="submit" class="btn btn-primary ml-1"><span class="d-none d-sm-block">Submit</span> --}}
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  reset();

  function reset() {
    $('#request_type').find('option').not(':first').remove();
    $('#service_plan').find('option').remove();
    $('#mobile_no').val();
    $('#amount').val();
  }

  $("#service_module").change(function () {
    if ($("#service_module").val() != "") {
      var product_id = $("#service_module").val();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.LoadingOverlay("show");

      $.ajax({
        url: "{{ url('customer-get-variations') }}/" + product_id,
        method: 'GET',
        success: function (RES_data) {
          $.LoadingOverlay("hide");
          $("#service_plan").html(RES_data);
        }
      })
    }
  })

  $("#submitBills").click(function (event) {
    var inputs = document.getElementById("form-data").getElementsByTagName("input");
    // Loop through each input and perform validation
    for (var i = 0; i < inputs.length; i++) {
        var input = inputs[i];

        // Check if the input is required and empty
        if (input.hasAttribute("required") && input.value.trim() === "") {
            alert("Please fill all inputs");
            return;
        }
    }

    var formData = {
      product_id: $("#service_module").val(),
      variation_id: $("#service_plan").val(),
      billersCode: $("#smart_no").val(),
      security_pin: $("#security_pin").val(),
    };

    $.LoadingOverlay("show");
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      url: '{{ url("/") }}/verify',
      method: 'POST',
      dataType: 'json',
      data: formData,
      success: function (response_fd) {
        $.LoadingOverlay("hide");

        if (response_fd.status == 'success') {
          if (response_fd.renewal_amount && response_fd.renewal_amount > 0) {
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

  function pay4Bills() {
    var formData = {
      product_id: $("#service_module").val(),
      variation: $("#service_plan").val(),
      billersCode: $("#smart_no").val(),
      security_pin: $("#security_pin").val(),
      amount: $("#amount").val(),
      mobile_no: $("#mobile_no").val(),
      security_pin: $("#security_pin").val(),
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