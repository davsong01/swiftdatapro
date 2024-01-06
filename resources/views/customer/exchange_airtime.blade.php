<?php 
    use App\Models\WebSetting;
    $web_settings = WebSetting::first();
?>
@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
  <div class="alert" style="background-color: #fff;color: blue;">
    <div id="response" style="text-align: center;">
      <h4>Swap Airtime To Cash</h4>
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

  <form id="form-data">
    @csrf
    <div class="input-group mb-3">
      <div class="input-group-text">
        <span class="" id="basic-addon1"><i class="fa-solid fa-wifi" style="font-size: 25px;"></i></span>
      </div>
      <input type="hidden" id="network_discount">
      <select class="form-control" id="network_module" required>
        <option>--Choose--</option>
        <option value="MTN-SWAP">MTN</option>
        <option value="GLO-SWAP">GLO</option>
        <option value="AIRTEL-SWAP">AIRTEL</option>
        <option value="9MOBILE-SWAP">9MOBILE</option>
      </select>
    </div>
    <span id="result"></span>
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

    <button class="btn btn-success btn-block" style="width: 100%" onclick="MakePay()">Proceed Now</button>

    <br>
    <br>
  </form>


  <hr />
  <div id="checkbal" style="margin-bottom: 30px;">
    <h5 style="font-weight:bold">HOW TO TRANSFER AIRTIME TO ANOTHER NUMBER</h5>
    <div style="color:#940798">
      <img src="backend/img/mtn.png" style="border-radius: 50%;" height="30px"> <br />
      TO TRANSFER MTN AIRTIME <br />
      <p>*600*recipient-number*amount*pin#</p>

      TO CHANGE MTN TRANSFER PIN <br />
      <p>*600*default-pin*new-pin*new-pin#</p>
    </div>
    <hr />

    <div style="color:#940798">
      <img src="backend/img/glo.png" style="border-radius: 50%;" height="30px"> <br />
      TO TRANSFER GLO AIRTIME <br />
      <p>*131*recipient-number*amount*pin#</p>

      TO CHANGE GLO TRANSFER PIN <br />
      <p>*132*00000*new-pin*new-pin#</p>
    </div>
    <hr />

    <div style="color:#940798">
      <img src="backend/img/airtel.png" style="border-radius: 50%;" height="30px"> <br />
      TO TRANSFER AIRTEL AIRTIME <br />
      <p>*432*1#</p>

      TO CHANGE AIRTEL TRANSFER PIN <br />
      <p>*432*3# (Default Pin: 1234)</p>
    </div>
    <hr />

    <div style="color:#940798">
      <img src="backend/img/mobile.png" style="border-radius: 50%;" height="30px"> <br />
      TO TRANSFER 9MOBILE AIRTIME <br />
      <p>*223*pin*amount*number#</p>

      TO CHANGE 9MOBILE TRANSFER PIN <br />
      <p>*247*default-pin*new-pin# (E.g *247*0000*new-pin#)</p>
    </div>


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

  function MakePay() {
    var network_module = $("#network_module").val();
    var mobile_no = $("#mobile_no").val();
    var amount = $("#amount").val();
    var whatsapp = {{$web_settings->whatsapp_no}};
    var network_discount = $("#network_discount").val();
    if (mobile_no != "" && amount != "" && network_module != "") {
      window.location.href =
        "https://api.whatsapp.com/send?phone="+whatsapp+"&text=Hello%20admin!%20i%20want%20to%20convert%20" +
        network_module + "%20₦" + amount + "%20to%20cash.";
    } else{
    }
  }
</script>


@endsection