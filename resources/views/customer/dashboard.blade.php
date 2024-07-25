@extends('includes.customer_dashboard.app')
@section('header')

@if(!empty(popupNotification()))
<div id="popupNotificationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Announcements!</h2>
            </div>
            <div class="modal-body" style="color:black !important"> {!! popupNotification()->content !!}</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

@if(!empty($notification1))
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;display: block;">
        <div id="response" style="text-align: center;">
            <p>{!! $notification1->content !!}</p>
        </div>
    </div>
</div>
@endif
@if(!empty($notification2))
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;display:block">
        <div id="response" style="text-align: center;">
            <p>{!! $notification2->content !!}</p>
        </div>
    </div>
</div>
@endif
@if(!empty(generalNotifications()))
<div class="col-xl-12">
    @foreach(generalNotifications() as $notify)
    <div class="alert" style="background-color: #fff;display: block;">
        <div id="response" style="text-align: center;">
            <h4>{{strip_tags($notify->content)}}</h4>
        </div>
    </div>
    @endforeach
</div>
@endif
@if(isset(scrollNotification()['notifications']) && !empty(scrollNotification()['notifications']))
<div class="col-xl-12">
    <span style="color:blue">
        <marquee> 
        @foreach(scrollNotification() as $scrol)
            {{ scrollNotification()['content'] }}
        @endforeach
        </marquee>
    </span>
</div>
@endif
@endsection
@section('body')

<div class="col-xl-12">
    <div class="alert" style="background-color: transparent;">
        <a class="btn" target="_blank" style="font-size:12px;background-color:#940798;color:#fff" href="{{route('customer.transactions') }}">All Transactions</a>
        <a class="btn" target="_blank" style="font-size:12px;background-color:blue;color:#fff" href="{{route('customer.cashback') }}">Commissions</a>
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
                        <a href="{{ route('buy', 'airtime')}}">
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
                        <a href="{{ route('buy', 'data')}}">
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
                        <a href="{{ route('buy', 'cable')}}">
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
                        <a href="{{ route('buy', 'electricity')}}">
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
                            <a href="{{ route('buy', 'exam-epins')}}">
                            <div class="option-card-icon">
                                <i class="fa fa-qrcode" style="font-size: 25px;color: #940798;margin: 5px;"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Buy E-Pins</p>
                        </a>
                    </div>
                </div>
                
                @if(referralBalance(auth()->user()) > 1)
                <div class="col pb-15" style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <div class="option-card-icon">
                            <i class="fa fa-download" style="font-size: 25px;margin: 5px;color: #940798"></i>
                        </div>
                        <p id="withdraw" style="font-weight:bold;font-size:10px;">Withdraw commission</p>
                    </div>
                </div>
                @endif
            </div>
            <br />
            <div class="row gx-3">
                {{-- <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="{{ route('buy', 'bulk-sms')}}">
                            <div class="option-card-icon">
                                <i class="fa fa-envelope" style="font-size: 25px;color: #940798;margin: 5px;"></i>
                            </div>
                            <p style="font-weight:bold;font-size:10px;">Bulk SMS</p>
                        </a>
                    </div>
                </div> --}}
                <div class="col pb-15"
                    style="box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;height: 70px;border-radius: 20px;margin: 5px;">
                    <div class="option-card">
                        <a href="{{ route('buy', 'airtime2cash')}}">
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
                        <a href="{{ route('buy', 'graphic') }}">
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
        <p>Get 50% bonus immediately a user you referred upgrades level.</p>
        <p class="text-dark" style="font-weight: bold;">Referral Link
            <span>{{ url('/') }}/regsiter?referral={{ auth()->user()->ref_code }}</span> <i
                class="fa fa-copy" style="font-size: 13px;cursor: pointer;" onclick="copyFunction()">Copy</i> <span
                id="refres"></span>
        </p>
        
        @if ($customer_levels->count() > 0) <br>
            <label style="color: blue;">Upgrade Customer level</label>
            <select class="form-control" id="level">
            <option value="">--Select Level--</option>
                @foreach ($customer_levels as $level)
                <option value="{{ $level->id }}">Upgrade To {{ $level->name }} =>{{ settings()->currency.number_format($level->upgrade_amount) }}</option>
                @endforeach
            </select>
            <br />
            <button class="btn btn-success btn-block" style="width: 50%" id="upgrade">UPGRADE</button>
        @endif

    </div>

</div>
<input type="text" id="myCode" value="{{ url('/') }}/register?referral={{ auth()->user()->ref_code }}"
    style="background-color: transparent; color: transparent; height: 0px;border: none;">

@endsection
@section('scripts')

<script>
    $('#popupNotificationModal').modal('show');


    $(document).ready(function() {
        $('#popupNotificationModal').modal('show');
    });
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
        $.LoadingOverlay("show");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/') }}/customer/report",
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
            url: "{{ url('/') }}/customer/level",
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
            },
           
            
        });

        setTimeout(function () {
            window.location.reload();
        }, 2000);
    })

    $("#withdraw").click(function () {
        // $.LoadingOverlay("show");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('customer.withdraw.cashback') }}",
            method: "GET",
            dataType: "Json",
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
            },
        });

        setTimeout(function () {
            window.location.reload();
        }, 2000);
    })
</script>
@endsection
