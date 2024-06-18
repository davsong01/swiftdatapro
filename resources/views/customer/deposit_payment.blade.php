@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">
        <div id="response" style="text-align: center;">
            <h4>Add Money</h4>
            <p style="color: red;font-weight: bold;">Transfer money into one of the reserved account below and your
                wallet will be automatically credited.</p>
        </div>
    </div>
</div>

@endsection
@section('body')
<div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">
    <!-- start -->
    @if(!empty($list_acctno))
        @foreach($list_acctno as $Acc_data)
            <div class="alert" style="font-size: 25px;text-align: center;text-transform: uppercase;">
                {{ $Acc_data['account_name'] }}<br />
                {{ $Acc_data['bank_name'] }} -
                {{ $Acc_data['account_no'] }}
            </div>
            <hr />
        @endforeach
    @else
        <div class="alert" style="font-size: 25px;text-align: center;text-transform: uppercase;">
            NO ACCOUNT ADDED YET
        </div>
    @endif
</div>

<div class="col-xl-12">
    <div class="alert" style="background-color: transparent;">
        <div id="response"></div>
        <label style="color: red;">Let us know your payment narration and your wallet will be funded when confirmed and
            note you will be charge N20 if you make payment below N1000</label>
        <textarea class="form-control" id="narration" name="narration" required="required"
            placeholder="Enter Your Payment Narration">

        </textarea>
        <br />
        <button class="btn btn-success btn-block" id="sendNotification">Send Notification</button>
    </div>
    <br>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $("#narration").val("");
    })
    $("#sendNotification").click(function () {
        var narration = $("#narration").val();
        if (narration == "" || narration.length < 10) {
            $("#response").html('<div class="alert alert-danger">Please submit your payment narration</div>');
        } else {
            $.LoadingOverlay("show");
            window.location.href = "https://api.whatsapp.com/send?phone={{ settings()->whatsapp_no }}&text=Hello%20Admin!%20i%20make%20a%20deposit%20with%20the%20following%20narration%20%20" +
                narration + "";
            $.LoadingOverlay("hide");
        }
    })
</script>
@endsection
