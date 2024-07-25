@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">
        <div id="response" style="text-align: center;">
            <h4>Get Graphic</h4>
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
    <div id="response"></div>
    <div id="extra"
        style="margin:5px 0;background: black;color: yellow;padding: 20px;margin-bottom: 15px;font-size: initial; display:none">
    </div>
    <form id="form-data">
        <div class="mb-3">
          <input type="hidden" id="network_discount">
          <label for="">Select Graphic Type</label>
          <select class="form-control" id="network_module" required>
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
        
        <div class="mb-3">
          <label for="">Select Type</label>
              @csrf
              <select class="form-control" class="dataplanload" style="font-size:11px" id="dataplanloader" required>
                  <option value="">--Select Type--</option>
              </select>
        </div>
        
        <div class="mb-3">
          <label>Business Name</label>
          <input type="text" class="form-control" placeholder="Business name" id="business_name" required>
        </div>
        <div class="mb-3">
          <label>Business Email Address</label>
          <input type="email" class="form-control" placeholder="Business Email" id="email" required>
        </div>
        <div class="mb-3">
          <label>Business Line <span style="color: red;">Separate by commas if more than one.</span></label>
          <input type="text" class="form-control" placeholder="Business No(s)" id="phone" required>
        </div>
        <div class="mb-3">
          <label>Flyer Contents</label>
          <textarea required id="business_content" placeholder="Describe your business" class="form-control" rows="6"></textarea>
        </div>
        
        <small>Forget Pin ? <a target="_blank" href="{{ route('pin.reset') }}">Reset Here</a></small>
        <div class="input-group mb-3">
            <div class="input-group-text">
                <span class="" id="basic-addon1"><i class="fa fa-unlock-alt" style="font-weight: bold"></i></span>
            </div>
            <input type="password" class="form-control" placeholder="Security PIN" id="security_pin" required>
        </div>
        <br>
        <button class="btn btn-success btn-block" style="width: 100%" id="submitBtn">BUY NOW</button>
        <br>
        <br>
    </form>
    <hr/>
   
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
  reset();
  
  function reset(){
    $('#dataplanloader').find('option:not(:first)').remove();
    $('#mobile_no').val();
  }

  $("#network_module").change(function() {
    var has_variation = $('#network_module').find(':selected').data('has_variations');
    var product_id = $('#network_module').val();
    var variation_id = $('#variation').val();
    var allow_quantity = $('#product').find(':selected').data('allow_quantity');
    var max = $('#network_module').find(':selected').data('max');
    var min = $('#network_module').find(':selected').data('min');
    var quantity_graduation = $('#network_module').find(':selected').data('quantity_graduation');
    var fixed_price = $('#network_module').find(':selected').data('fixed_price');

    var amount = $('#amount').val();

    if ($("#network_module").val() != "") {
      var network_module = $("#network_module").val();
      $.LoadingOverlay("show");
      $.ajax({
        url: "{{ url('customer-get-variations') }}/" + product_id,
        method: 'GET',
        success: function(RES_data) {
          $.LoadingOverlay("hide");
          if ($("#network_module").val() == 'SMILE-DATA') {
            $("#smile-verify-email").show();
          } else {
            $("#smile-verify-email").hide();

          }
          if (network_module == 'mtn-datacard') {
            $("#ask_pins").show();
            $("#ask_pins_small").show();
            $("#how_to_load").show();
            $("#how_to_load").show();

            $("#quantity").attr("required", true);
            $("#mobile_no").attr("required", false);

          } else {
            $("#ask_pins").hide();
            $("#quantity").attr("required", false);
            $("#quantity").val("1");
            $("#ask_pins_small").hide();
            $("#how_to_load").hide();
            $("#mobile_no").attr("required", true);

          }
          $("#dataplanloader").html(RES_data);
        }
      })
    }else{
      $('#dataplanloader').find('option:not(:first)').remove();
      $('#mobile_no').val();
    }
  })

  $("#form-data").submit(function(event) {
    var formData = {
      network_module: $("#network_module").val(),
      dataplanloader: $("#dataplanloader").val(),
      mobile_no: $("#phone").val(),
      business_content: $("#business_content").val(),
      business_email: $("#email").val(),
      business_name: $("#business_name").val(),
      
      security_pin: $("#security_pin").val(),
      req_token: $("#req_token").val(),
      quantity: $("#quantity").val()
    };

    $.LoadingOverlay("show");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      url: '/buy/data',
      method: 'POST',
      dataType: 'json',
      data: formData,
      success: function(response_fd) {
        $.LoadingOverlay("hide");
        if (response_fd.status == 'success') {
          $("#successModal").modal("show");
          $("#successModal .modal-body").html(response_fd.message);

          if ($("#network_module").val() == 'mtn-datacard') {
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


  function closeModal() {
    $("#myModal2").modal("hide");
  }
</script>

@endsection