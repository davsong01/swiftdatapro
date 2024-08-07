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
      <span class="input-group-text"><i style="font-weight: bold" class="fa fa-graduation-cap"></i></span>
      <select class="form-control" id="service_module">
          <option value="">--Choose--</option>
          @foreach($category->products as $product)
            @if($product->has_variations == 'yes' && $product->variations->count() > 0 || $product->has_variations == 'no')
              <option data-system_price="{{ intval($product->system_price) }}" data-fixed_price="{{ $product->fixed_price }}"
                data-allow_quantity="{{ $product->allow_quantity }}" data-max="{{ $product->max }}"
                data-min="{{ $product->min }}" data-has_variations="{{ $product->has_variations }}"
                value="{{ $product->id }}">{{ $product->display_name .' - '.getProductDiscount($product->system_price, $product->id)['display_value'] }}</option>
            @endif
          @endforeach
        </select>
    </div>

    <div class="alert alert-success" id="valAmount" style="display:none"></div>
    {{-- @if($product->allow_quantity == 'yes') --}}
    <div class="input-group mb-3">
      <span class="input-group-text"><i style="font-weight: bold" class="fa fa-shopping-cart"></i></span>
      <select class="form-control" id="service_quantity" onchange="calDiscount()">
        <option value="">--Choose--</option>
        <option value="1">One Unit</option>
        <option value="2">Two Units</option>
        <option value="3">Three Units</option>
        <option value="4">Four Units</option>
        <option value="5">Five Units</option>
        <option value="10">Ten Units</option>
      </select>
    </div>
    {{-- @endif --}}
    <div class="input-group mb-3">
      <span class="input-group-text"><i style="font-weight: bold" class="fas fa-phone"></i></span>
      <input type="tel" class="form-control" placeholder="Mobile No." id="mobile_no" required>
    </div>
    <small>Forget Pin ? <a href="/reset_pin">Reset Here</a></small>
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1"><i class="fa fa-unlock-alt" style="font-weight: bold"></i></span>
      
        <input type="password" class="form-control" placeholder="Security PIN" id="security_pin" required>
    </div>
    <div class="input-group mb-3">
      <button class="btn btn-success btn-block" style="width: 100%" id="submitBtn">Purchase Now</button>
    </div>
  </form>
 <br>
</div>


@endsection
@section('scripts')
<script type="text/javascript">
    $('#service_quantity').val('');

    $("#service_module").change(function() {
      $('#valAmount').hide();
      $('#service_quantity').val('');
    })

    function calDiscount() {
      
        var product_id = $('#service_module').val();
        var amount = $('#service_module').find(':selected').data('system_price');
        var quantity = $('#service_quantity').val();
        
        if (amount > 0) {
          formData = {
            product_id: product_id,
            amount: amount,
            quantity: quantity,
          };

          $.ajax({
            url: "{{ url('customer-get-discount') }}",
            method: 'POST',
            dataType: 'json',
            data: formData,
            success: function (data) {
              if (data.new_amount > 0) {
                $('#valAmount').show();
                $("#valAmount").text("You will pay " + data.display_value);
              } else {
                $('#valAmount').hide();
                $('#valAmount').text("");
              }
            }
          });
        } else {
          $('#valAmount').hide();
          $('#valAmount').text("");
        }
    };

    $("#form-data").submit(function(event) {
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
        quantity: $("#service_quantity").val(),
        amount: $('#service_module').find(':selected').data('system_price'),
        mobile_no: $("#mobile_no").val(),
        security_pin: $("#security_pin").val(),
      };
      
      $.LoadingOverlay("show");
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        url: '{{ config('app.url')}}/buy/exam-epins',
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