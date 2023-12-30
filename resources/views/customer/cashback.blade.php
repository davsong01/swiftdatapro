@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">
        <div id="response" style="text-align: center;">
            <h4>CashBack Summary</h4>
            <p style="color: red;font-weight: bold;">This shows the proccess of your earning on their each purchase.</p>
        </div>
    </div>
</div>

@endsection
@section('body')
<div class="col-xl-12">
  <div class="alert" id="temp" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;">

        <div id="response" style="text-align: left;">
        <span>Commission Balance 
            <span><button class="btn btn-success" style="font-size:10px;display:none" id="withdrawButton">Withdraw to wallet</button></span>
        </span>
        <br/>

        <span style="color: #940798;font-weight: bold;font-size: 25px;">₦{{ number_format(auth()->user()->cashback, 2) }}</span>
        </div>
                  
  </div>
</div>
<div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">
    <div class="container-fluid p-0 sm_padding_15px">
        <div class="row justify-content-center">
            <div id="transactionsDiv" class="row"></div>
            <div id="loadMoreDiv"></div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $.LoadingOverlay("show");
    $.ajax({
      url: 'cashback_earn',
      dataType: 'json',
      success: function(transactionsRes) {
        $.LoadingOverlay("hide");
        if (transactionsRes.count > 0) {
          $("#withdrawButton").show();
        }
        $("#transactionsDiv").html(transactionsRes.message);
      }
    });
  });

  function showMore(id) {
    $.LoadingOverlay("show");
    $.ajax({
      url: '/refearning?load_id=' + id,
      dataType: 'json',
      success: function(transactionsRes) {
        $.LoadingOverlay("hide");
        $("#transactionsDiv").html(transactionsRes.message);
        $("#loadMoreDiv").html(transactionsRes.ctrlButton);
      }
    });
  }

  $('#withdrawButton').click(function() {
    $.LoadingOverlay("show");
    var confirmText = "You are about to withdraw your referral commissions to your wallet";
    if (confirm(confirmText)) {
      $.ajax({
        url: '/withdrawreferralbonus',
        dataType: 'json',
        success: function(transactionsRes) {
          $.LoadingOverlay("hide");
          $("#temp").hide();
          $("#transactionsDiv").html(transactionsRes.message);
        }
      });
      return false;
    } else {
      $.LoadingOverlay("hide");
    }
  });
</script>
@endsection