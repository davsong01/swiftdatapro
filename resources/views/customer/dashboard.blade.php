@extends('includes.customer_dashboard.app')
@section('header')
@php
    $settings = settings();
@endphp
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

<div class="alert" style="background-color: #fff; color: #000; border-radius: 15px; padding: 20px; box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 30px;">
    <div class="option-section">
        <div class="row gx-4 mt-3">
            <!-- All Time Funding -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #81c784;">
                    <div class="option-card-icon">
                        <i class="fa fa-eye toggleFunding" style="font-size: 20px; color: #fff; cursor: pointer;"></i>
                    </div>
                    <p style="font-weight: bold; font-size: 14px; margin-top: 10px; color: #fff;">All Time Funding</p>
                    <p class="hiddenFunding" style="font-size: 22px; font-weight: 800; color: #fff; margin-top: 5px;">
                        ****
                    </p>
                    <p class="funding" style="font-size: 22px; font-weight: 800; color: #fff; margin-top: 5px; display: none;">
                        {!! $settings->currency !!}{{ number_format($allTimeFunding, 2) }}
                    </p>
                </div>
            </div>

            <!-- All Time Transactions -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #ffcc80;">
                    <div class="option-card-icon">
                        <i class="fa fa-eye toggleTransactions" style="font-size: 20px; color: #fff; cursor: pointer;"></i>
                    </div>
                    <p style="font-weight: bold; font-size: 14px; margin-top: 10px; color: #fff;">All Time Transactions</p>
                    <p class="hiddenTransactions" style="font-size: 22px; font-weight: 800; color: #fff; margin-top: 5px;">
                        ****
                    </p>
                    <p class="transactions" style="font-size: 22px; font-weight: 800; color: #fff; margin-top: 5px; display: none;">
                        {!! $settings->currency !!}{{ number_format($allTimeTransactions, 2) }}
                    </p>
                </div>
            </div>

            <!-- Most Favorite Product -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #940798;">
                    <div class="option-card-icon">
                        <i class="fa fa-star" style="font-size: 30px; color: #fff;"></i>
                    </div>
                    <p style="font-weight: bold; font-size: 14px; margin-top: 10px; color: #fff;">Most Favorite Product</p>
                    <p style="font-size: 18px; font-weight: 800; color: #fff; margin-top: 5px;">
                        {{ $mostBoughtProduct?->product?->name }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row gx-4">
            <!-- Airtime Option -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #f7f7f7;">
                    <a href="{{ route('buy', 'airtime') }}">
                        <div class="option-card-icon">
                            <i class="fa fa-phone" style="font-size: 30px; color: #940798;"></i>
                        </div>
                        <p style="font-weight: bold; font-size: 14px; margin-top: 10px;">Airtime</p>
                    </a>
                </div>
            </div>
            <!-- Data Option -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #f7f7f7;">
                    <a href="{{ route('buy', 'data') }}">
                        <div class="option-card-icon">
                            <i class="fa fa-wifi" style="font-size: 30px; color: #940798;"></i>
                        </div>
                        <p style="font-weight: bold; font-size: 14px; margin-top: 10px;">Buy Data</p>
                    </a>
                </div>
            </div>
            <!-- Cable TV Option -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #f7f7f7;">
                    <a href="{{ route('buy', 'cable') }}">
                        <div class="option-card-icon">
                            <i class="fa fa-tv" style="font-size: 30px; color: #940798;"></i>
                        </div>
                        <p style="font-weight: bold; font-size: 14px; margin-top: 10px;">Cable TV</p>
                    </a>
                </div>
            </div>
        </div>
        
        
        
        <div class="row gx-4 mt-3">
            <!-- Pay Bills Option -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #f7f7f7;">
                    <a href="{{ route('buy', 'electricity') }}">
                        <div class="option-card-icon">
                            <i class="fa fa-lightbulb" style="font-size: 30px; color: #940798;"></i>
                        </div>
                        <p style="font-weight: bold; font-size: 14px; margin-top: 10px;">Pay Bills</p>
                    </a>
                </div>
            </div>
            <!-- Buy E-Pins Option -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #f7f7f7;">
                    <a href="{{ route('buy', 'exam-epins') }}">
                        <div class="option-card-icon">
                            <i class="fa fa-qrcode" style="font-size: 30px; color: #940798;"></i>
                        </div>
                        <p style="font-weight: bold; font-size: 14px; margin-top: 10px;">Buy E-Pins</p>
                    </a>
                </div>
            </div>
            <!-- Withdraw Commission Option -->
            @if(referralBalance(auth()->user()) > 1)
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #f7f7f7;">
                    <div class="option-card-icon">
                        <i class="fa fa-download" style="font-size: 30px; color: #940798;"></i>
                    </div>
                    <p id="withdraw" style="font-weight: bold; font-size: 14px; margin-top: 10px;">Withdraw Commission</p>
                </div>
            </div>
            @endif
        </div>

        <div class="row gx-4 mt-3">
            <!-- Swap Airtime Option -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #f7f7f7;">
                    <a href="{{ route('buy', 'airtime2cash') }}">
                        <div class="option-card-icon">
                            <i class="fa fa-wallet" style="font-size: 30px; color: #940798;"></i>
                        </div>
                        <p style="font-weight: bold; font-size: 14px; margin-top: 10px;">Swap Airtime</p>
                    </a>
                </div>
            </div>
            <!-- Get Graphic Option -->
            <div class="col-md-4 pb-3">
                <div class="option-card" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 20px 0px; border-radius: 15px; padding: 15px; text-align: center; background-color: #f7f7f7;">
                    <a href="{{ route('buy', 'graphic') }}">
                        <div class="option-card-icon">
                            <i class="fa fa-paper-plane" style="font-size: 30px; color: #940798;"></i>
                        </div>
                        <p style="font-weight: bold; font-size: 14px; margin-top: 10px;">Get Graphic</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Option-section -->
<div class="row">
    <div class="col-xl-12">
        <div class="alert" style="background-color: #fff; border-radius: 15px; padding: 20px; box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 30px;">
            <div id="response"></div>
            
            <div class="referral-section">
                @if($settings->refferal_commission_settings['allow_level_upgrade_refferal'] && $settings->refferal_commission_settings['allow_level_upgrade_refferal'] == 'yes')
                <label style="color: #e63946; font-size: 18px; font-weight: bold;">Refer a Friend and Earn Extra Cash on {{ config('app.name') }}</label>
                @endif

                @if($settings->refferal_commission_settings['allow_first_deposit_refferal'] && $settings->refferal_commission_settings['allow_first_deposit_refferal'] == 'yes')
                    <p style="font-size: 16px; margin-top: 5px;">Get a {{ $settings->refferal_commission_settings['first_deposit_refferal_commission_percent'] }}% bonus immediately on Your Refferal first Deposit.</p>
                @endif

                @if($settings->refferal_commission_settings['allow_level_upgrade_refferal'] && $settings->refferal_commission_settings['allow_level_upgrade_refferal'] == 'yes')
                <p style="font-size: 16px; margin-top: 5px;">Get a 50% bonus immediately when a user you referred upgrades their level.</p>
                @endif
                <p class="text-dark" style="font-weight: bold; margin-top: 20px;">Referral Link</p>
                <div class="input-group">
                    <input type="text" class="form-control" value="{{ url('/') }}/register?referral={{ auth()->user()->ref_code }}" id="myCode" style="background-color: transparent; color: #333; border: 1px solid #ccc; border-radius: 8px; padding-left: 10px;" readonly>
                    <button class="btn btn-outline-primary" style="border-radius: 8px;" onclick="copyFunction()">Copy <i class="fa fa-copy"></i></button>
                </div>
                <span id="refres" style="display: inline-block; margin-top: 5px; font-size: 14px; color: green;"></span>
                
                
            </div>
    
            @if ($customer_levels->count() > 0)
                <br />
                <div class="level-upgrade-section">
                    <label style="color: #1e90ff; font-size: 16px; font-weight: bold;">Upgrade Your Customer Level</label>
                    <select class="form-control" id="level" style="border-radius: 8px; margin-top: 10px;">
                        <option value="">--Select Level--</option>
                        @foreach ($customer_levels as $level)
                            <option value="{{ $level->id }}">
                                Upgrade to {{ $level->name }} => {{ $settings->currency.number_format($level->upgrade_amount) }}
                            </option>
                        @endforeach
                    </select>
                    <br />
                    <button class="btn btn-success btn-block" style="width: 50%; border-radius: 8px; margin-top: 15px;" id="upgrade">Upgrade</button>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Hidden Input Field for Referral Code -->
<input type="text" id="myCode" value="{{ url('/') }}/register?referral={{ auth()->user()->ref_code }}"
    style="background-color: transparent; color: transparent; height: 0px; border: none;">

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

    // $(".toggleFunding").on("click", function () {
    //     alert('icon');
    //     $(this).siblings(".hiddenFunding, .funding").toggle();

    //     let icon = $(this).find("i");
    //     if (icon.hasClass("fa-eye")) {
    //         icon.removeClass("fa-eye").addClass("fa-eye-slash");
    //     } else {
    //         icon.removeClass("fa-eye-slash").addClass("fa-eye");
    //     }
    // });
</script>
@endsection
