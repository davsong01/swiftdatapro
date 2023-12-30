@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;display: <?php echo $web_settings->notification1_mode; ?>;">
        <div id="response" style="text-align: center;">
            <p><?php echo $web_settings->notification1; ?></p>
        </div>
    </div>
</div>

<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;display: <?php echo $web_settings->notification2_mode; ?>">

        <div id="response" style="text-align: center;">
            <p><?php echo $web_settings->notification2; ?></p>
        </div>

    </div>
</div>

<div class="col-xl-12">
    <div class="alert"
        style="background-color: #fff;display: <?php echo $web_settings->notification3_mode; ?>;color: blue;">

        <div id="response" style="text-align: center;">
            <h4><?php echo $web_settings->notification3; ?></h4>
        </div>

    </div>
</div>

<div class="col-xl-12">
    <span style="color:blue">
        <marquee><?php if ($web_settings->scrolling_mode=="ON"){ echo $web_settings->notification_scroll; } ?>
        </marquee>
    </span>
</div>
@endsection
@section('body')

<div class="col-xl-12">
    <div class="alert" style="background-color: transparent;">
        <center>
            <span class="btn" style="font-size:12px;background-color:#940798;color:#fff" onclick="SalesAna()">Sales
                Summary</span>
            <span class="btn" style="font-size:12px;background-color:#940798;color:#fff"
                onclick="Transact()">Transactions</span>
        </center>
    </div>
</div>


<!-- Option-section -->
<center>
    <div class="alert" style="background-color: #fff;color:#fff;border-radius: 7px 7px;">
        <div class="option-section mb-15">
            <div class="row gx-3">
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card border-2 border-rose-600">
                        <a href="<?php echo $baseurl; ?>/airtime_vtu">
                            <div class="option-card-icon">
                                <i class="fa fa-phone" style="font-size: 25px;color: #940798;margin: 5px;"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Airtime</p>
                        </a>
                    </div>
                </div>
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="<?php echo $baseurl; ?>/data">
                            <div class="option-card-icon">
                                <i class="fa fa-wifi" style="font-size: 25px;color: #940798;margin: 5px;"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Buy Data</p>
                        </a>
                    </div>
                </div>
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="<?php echo $baseurl; ?>/cable">
                            <div class="option-card-icon">
                                <i class="fa fa-tv" style="font-size: 25px;color: #940798;margin: 5px;"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">CableTv</p>
                        </a>
                    </div>
                </div>
            </div>

            <br />

            <div class="row gx-3">
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="<?php echo $baseurl; ?>/bills">
                            <div class="option-card-icon">
                                <i class="fa fa-lightbulb" style="font-size: 25px;color: #940798;margin: 5px;"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Pay Bills</p>
                        </a>
                    </div>
                </div>
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="<?php echo $baseurl; ?>/epins">
                            <div class="option-card-icon">
                                <i class="fa fa-qrcode" style="font-size: 25px;color: #940798;margin: 5px;"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Buy E-Pins</p>
                        </a>
                    </div>
                </div>
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="<?php echo $baseurl; ?>/withdraw">
                            <div class="option-card-icon">

                                <i class="fa fa-download" style="font-size: 25px;margin: 5px;color: #940798"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Withdraw</p>
                        </a>
                    </div>
                </div>
            </div>

            <br />

            <div class="row gx-3">
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="<?php echo $baseurl; ?>/bulk_sms">
                            <div class="option-card-icon">
                                <i class="fa fa-envelope" style="font-size: 25px;color: #940798;margin: 5px;"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Bulk SMS</p>
                        </a>
                    </div>
                </div>
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="<?php echo $baseurl; ?>/exchange_airtime">
                            <div class="option-card-icon">
                                <i class="fa fa-wallet" style="font-size: 25px;margin: 5px;color: #940798"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Swap Airtime</p>
                        </a>
                    </div>
                </div>
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="<?php echo $baseurl; ?>/get_flyer">
                            <div class="option-card-icon">
                                <i class="fa fa-paper-plane" style="font-size: 25px;color: #940798;margin: 5px;"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Get Graphic</p>
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </div>
</center>
<!-- Option-section -->

<div class="col-xl-12">
    <div class="alert" style="background-color: #fff">

        <div id="response"></div>
        <label style="color: red;">Refer a friend and earn extra cash on
            {{ config('app.name') }}</label>
        <p>Get 50% bonus immediately a user you referred upgrade to level 2 or 3.</p>
        <p class="text-dark" style="font-weight: bold;">Referral Link
            <span>{{ url('/') }}/regsiter?referral={{ auth()->user()->ref_code }}</span> <i
                class="fa fa-copy" style="font-size: 13px;cursor: pointer;" onclick="copyFunction()">Copy</i> <span
                id="refres"></span>
        </p>

        <select class="form-control" id="level">
            <option value="">--Choose--</option>
            <option value="2">Upgrade To Level 2 => N{{ $web_settings->level2_charge }}></option>
            <option value="3">Upgrade To Level 3 => N{{ $web_settings->level3_charge }}></option>
            <option value="4">Upgrade To Level 4 => N{{ $web_settings->level4_charge }}></option>
        </select>

        <br />
        <button class="btn btn-success btn-block" style="width: 100%" id="upgrade">UPGRADE</button>
    </div>

</div>
 <input type="text" id="myCode" value="{{ url('/') }}/register?referral={{ auth()->user()->ref_code }}"
    style="background-color: transparent; color: transparent; height: 0px;border: none;">

@endsection
@section('scripts')
<script>
    function LiveChat() {
        $.LoadingOverlay("show");
        window.location.href = "live_chat";
    }

    function SalesAna() {
        $.LoadingOverlay("show");
        window.location.href = "sales_analysis";
    }

    function Transact() {
        $.LoadingOverlay("show");
        window.location.href = "transactions";
    }
</script>


<script type="text/javascript">
    function copyFunction() {
        var copyText = document.getElementById("myCode");
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        navigator.clipboard.writeText(copyText.value);
        $("#refres").text("COPIED");
    }

    $("#contactus").click(function () {
        var message_report = $("#message").val();
        var req_token = $("#req_token").val();
        // $.LoadingOverlay("show");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "<?php echo $baseurl; ?>/customer/report",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            method: "POST",
            dataType: "Json",
            data: {
                message_report: message_report,
                req_token: req_token
            },
            success: function (response) {
                $.LoadingOverlay("hide");
                if (response.status == 'success') {
                    $("#message").val("");
                    $("#successModal").modal("show");
                    $("#successModal .modal-body").html(response.message);
                } else {
                    $("#errorModal").modal("show");
                    $("#errorModal .modal-body").html(response.message);
                }
            }
        })
    })

    $("#upgrade").click(function () {
        var level = $("#level").val();
        var req_token = $("#req_token").val();
        $.LoadingOverlay("show");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "<?php echo $baseurl; ?>/customer/level",
            method: "POST",
            dataType: "Json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            method: "POST",
            dataType: "Json",
            data: {
                level: level,
                req_token: req_token
            },
            success: function (response) {
                $.LoadingOverlay("hide");
                if (response.status == 'success') {
                    $("#message").val("");
                    $("#successModal").modal("show");
                    $("#successModal .modal-body").html(response.message);
                } else {
                    $("#errorModal").modal("show");
                    $("#errorModal .modal-body").html(response.message);
                }
            }
        })
    })
</script>
@endsection
