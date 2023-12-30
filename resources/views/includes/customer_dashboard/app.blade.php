<?php 
    use App\Models\WebSetting;
    $web_settings = WebSetting::first();
?>
<!DOCTYPE html>
<html lang="english">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{ auth()->user()->full_name }} </title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon/favicon.ico')}}" />

    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap1.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/themefy_icon/themify-icons.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/niceselect/css/nice-select.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/owl_carousel/css/owl.carousel.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/gijgo/gijgo.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/font_awesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/tagsinput/tagsinput.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/datepicker/date-picker.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/vectormap-home/vectormap-2.0.2.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/scroll/scrollable.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/responsive.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/buttons.dataTables.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/text_editor/summernote-bs4.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/vendors/morris/morris.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('backend/vendors/material_icon/material-icons.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/css/metisMenu.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/css/style1.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/colors/default.css') }}" id="colorSkinCSS">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-loading-overlay@1.1.0/dist/js-loading-overlay.min.js"></script>
    <script type="text/javascript" src="backend/js/overlay.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style type="text/css">
        .centered {
            position: realtive;
            text-align: center;
            top: 2px;
            left: 5px;
            margin: 0 auto;
            transform: translateY(-50%);
        }

        .parent {
            background: #CCCCCC;
            height: 650px;
            width: 100%;
            border-radius: 10px;
            position: relative;
        }

        .child {
            background: #FFFF00;
            width: 130px;
            height: 74px;
            top: 1%;
            right: 7%;
        }

        .child2 {
            background: #FFFF00;
            width: 130px;
            height: 74px;
            top: 1%;
            left: 4%;
        }

        @media (min-width: 400px) {
            .trans{
                width: 46%;
                margin: 10px 20px !important;
            }
        }
    </style>
</head>

<body class="crm_body_bg">
    <nav class="sidebar">
        <div class="logo">
            <h5 class="">Available Balance. <br /> <span style="color: #940798;font-weight: bold;font-size: 20px;">
                    ₦{{ number_format(auth()->user()->wallets,2) }}</span></h5>
            <h6 class="">Level: {{ auth()->user()->level }} <span
                    style="color: #940798;font-weight: bold;font-size: 18px;"></span></h6>
            <div class="sidebar_close_icon d-lg-none">
                <i class="ti-close"></i>
            </div>
        </div>
        <ul id="sidebar_menu">
            <li>
                <a href="{{url('/').'/dashboard' }}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fa-solid fa-home"></i>
                    </div>
                    <div class="nav_title">
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>


            <li class="">
                <a class="has-arrow" href="#" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div class="nav_title">
                        <span>Deposit Money </span>
                    </div>
                </a>
                <ul>
                    <li><a href="{{url('/').'/automated_pay' }}">Automated Transfer</a></li>
                    <li><a href="{{url('/').'/deposit_payment' }}">Deposit/Transfer</a></li>
                </ul>
            </li>


            <li class="">
                <a class="has-arrow" href="#" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fa-solid fa-shopping-cart"></i>
                    </div>
                    <div class="nav_title">
                        <span>Transactions</span>
                    </div>
                </a>
                <ul>
                    <li><a href="{{url('/').'/transactions' }}">All Transactions</a></li>
                    <li><a href="{{url('/').'/transactions/success' }}">Success Transactions</a></li>
                    <li><a href="{{url('/').'/transactions/failed' }}">Failed Transactions</a></li>
                    <li><a href="{{url('/').'/wallet_summary' }}">Wallet Summary</a></li>
                    <li><a href="{{url('/').'/sales_analysis' }}">Sales Analysis</a></li>
                </ul>
            </li>

            <li class="">
                <a class="has-arrow" href="#" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="nav_title">
                        <span>My Referrals</span>
                    </div>
                </a>
                <ul>
                    <li><a href="{{url('/').'/ref_downlines' }}">My Downlines</a></li>
                    <li><a href="{{url('/').'/cashback_earn' }}">Cashback Earn.</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('buy', 'data')}}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fa-solid fa-signal"></i>
                    </div>
                    <div class="nav_title">
                        <span>Buy Data Bundle </span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('buy', 'airtime-vtu')}}" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fa-solid fa-mobile-button"></i>
                    </div>
                    <div class="nav_title">
                        <span>Buy Airtime VTU </span>
                    </div>
                </a>
            </li>

            <?php $baseurl = url('/'); ?>
            <li>
                <a href="<?php echo $baseurl; ?>/airtime_sns" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="nav_title">
                        <span>Buy Airtime SnS </span>
                    </div>
                </a>
            </li>

            <li>
                <a href="<?php echo $baseurl; ?>/exchange_airtime" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                    <div class="nav_title">
                        <span>Exchange Airtime </span>
                    </div>
                </a>
            </li>

            <li>
                <a href="<?php echo $baseurl; ?>/cable" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fas fa-tv"></i>
                    </div>
                    <div class="nav_title">
                        <span>TV Subscription </span>
                    </div>
                </a>
            </li>

            <li>
                <a href="<?php echo $baseurl; ?>/bills" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="nav_title">
                        <span>Buy Electricity Bills </span>
                    </div>
                </a>
            </li>

            <li>
                <a href="<?php echo $baseurl; ?>/epins" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fa fa-qrcode"></i>
                    </div>
                    <div class="nav_title">
                        <span>Buy Result E-pins </span>
                    </div>
                </a>
            </li>

            <li class="">
                <a class="has-arrow" href="#" aria-expanded="false">
                    <div class="nav_icon_small">
                        <i class="fas fa-gear"></i>
                    </div>
                    <div class="nav_title">
                        <span>Update Settings</span>
                    </div>
                </a>
                <ul>
                    <li><a href="<?php echo $baseurl; ?>/profile">My Profile</a></li>
                    <li><a href="<?php echo $baseurl; ?>/change_password">Change Password</a></li>
                    <li><a href="<?php echo $baseurl; ?>/generate_api">Generate API-KEY</a></li>
                    <li><a href="<?php echo $baseurl; ?>/kyc_register">KYC Registration</a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="nav_icon_small">
                        <i class="fa-solid fa-power-off"></i>
                    </div>
                    <div class="nav_title">
                        <span>Logout </span>
                    </div>
                </a>


                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>

        </ul>
    </nav>

    <section class="main_content dashboard_part large_header_bg">

        <div class="container-fluid g-0" style="background-color: #fff;">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="header_iner d-flex justify-content-between align-items-center">
                        <div class="sidebar_icon d-lg-none" style="cursor: pointer;">
                            <i class="ti-menu"></i>
                        </div>
                        <label class="form-label switch_toggle d-none d-lg-block" for="checkbox">
                            <input type="checkbox" id="checkbox">
                            <div class="slider round open_miniSide"></div>
                        </label>
                        <div class="header_right d-flex justify-content-between align-items-center">
                            <div class="header_notification_warp d-flex align-items-center">
                                <li>
                                </li>
                                <?php
                                
                                    if (auth()->user()->level == 1){
                                        $social_link = $web_settings->group_link;
                                    }else {   
                                        $social_link = $web_settings->group_link2;
                                    }
                                ?>
                                <li>
                                    <a class="" target="_blank" href="<?php echo $social_link; ?>"> <button class="btn btn-success"
                                            style="font-size:10px">Join Group <i class="fa fa-whatsapp"></i></button>
                                        <span>93</span>
                                    </a>
                                </li>

                                <li>
                                    <a class="bell_notification_clicker" href="#" onclick="PopUp()"> <img
                                            src="{{asset('backend/img/icon/bell.svg')}}" alt="">
                                        <span>1</span>
                                    </a>
                                </li>

                                <?php
                                    if (auth()->user()->rating ==0){
                                    echo '<li>
                                    <a class="CHATBOX_open" onclick="openRate()" href="#"> <i class="fa fa-star" style="color: orange; font-size: 24px;border-color: black;"></i><span><i class="fa fa-star"></i></span> </a>
                                    </li>';
                                    }
                                ?>
                            </div>
                            <div class="profile_info">
                                <img src="{{asset('backend/img/customer-image.png')}}" alt="#">
                                <div class="profile_info_iner">
                                    <div class="profile_author_name">
                                        <p>Welcome </p>
                                        <h5>{{ auth()->user()->full_name}}</h5>
                                    </div>
                                    <div class="profile_info_details">
                                        <a href="<?php echo $baseurl; ?>/profile">My Profile </a>
                                        <a href="<?php echo $baseurl; ?>/change_password">Settings</a>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="h6 modal-title">Announcements!</h2>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                            onclick="Closepopup()"></button>
                    </div>
                    <div class="modal-body"> {{ $web_settings->notification }}</div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-primary" data-dismiss="modal"
                            onclick="Closepopup()">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <br />
                    <br />

                    <center><img src="{{ asset('backend/img/success2.png')}}" height="100"></center>
                    <div class="modal-body" style="text-align: center;">

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" onclick="closeSuccessModal()">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <div id="errorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <br />
                    <br />

                    <center><img src="{{ asset('backend/img/error.png') }}" height="100"></center>
                    <div class="modal-body" style="text-align: center;">

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" onclick="closeErrorModal()">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <div id="rateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="h6 modal-title">Rate Us ! <i class="fa fa-smile" style="color: orange"></i><i
                                class="fa fa-smile" style="color: orange"></i></h2>
                        <button class="btn-close" aria-label="Close" onclick="closeRate()"></button>
                    </div>
                    <div class="modal-body">
                        <textarea id="rate_comment" class="form-control"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" id="submitRate">Submit</button>
                        <button class="btn btn-danger" data-dismiss="modal" onclick="closeRate()">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <div id="rateRes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="h6 modal-title">Rate Response ! <i class="fa fa-smile" style="color: red;"></i></h2>
                        <button class="btn-close" aria-label="Close" onclick="closeRateX()"></button>
                    </div>
                    <div class="modal-body"></div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" onclick="closeRateX()">Close</button>
                    </div>

                </div>
            </div>
        </div>


    <script>
        function Closepopup() {
            $("#myModal3").modal("hide");
        }

        function PopUp() {
            $("#myModal3").modal("show");
        }

        function openRate() {
            $("#rateModal").modal("show");
        }

        function closeRate() {
            $("#rateModal").modal("hide");
        }

        function closeRateX() {
            $("#rateRes").modal("hide");
        }

        function closeSuccessModal() {
            $("#successModal").modal("hide");
        }

        function closeErrorModal() {
            $("#errorModal").modal("hide");
        }
    </script>
  
    @if($web_settings->notification_mode == "ON")
    <script type="text/javascript">
        $(document).ready(function () {
            $("#myModal3").modal("show");
        })
    </script>
    @endif
    <div class="alert">
        @yield('header')
        <div class="col-xl-12">
        <div class="alert"
            style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;">

            <div id="response" style="text-align: left;">
                <span>Available Balance
                    <span style="float: right;"></span>
                </span>
                <br />

                <span style="color: #940798;font-weight: bold;font-size: 25px;">₦
                    <?php echo number_format(auth()->user()->wallets, 2); ?></span>

            </div>

        </div>
    </div>
        @yield('body')
    </div>
    @yield('scripts')
{{-- Footer --}}
<div class="footer_part">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer_iner text-center">
                    <h4>Download our mobile Apps</h4>
        <p>
                    <!-- <a target="_blank" href="https://play.google.com/store/apps/details?id=swift.data.ng"> -->
                    <a target="_blank" href="https://drive.google.com/drive/folders/107QE4pPKM4F2ah3c-uUM4nnxjM4O4EeC">
                        
                        <img src="../homepage/assets/img/play.png" style="width:120px; margin-right:10px">
                    </a>
                    <a href="#">
                        <img src="../homepage/assets/img/ios.png" style="width:120px">
                    </a>
                    </p>
                    <p><?php echo date("Y"); ?> © {{ config('app.name') }} </p>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<script type="text/javascript">
    function FundWallet() {
        window.location.href = "automated_pay";
    }
</script>
<script type="text/javascript">
    $("#submitRate").click(function () {
        $("#rateModal").modal("hide");
        var rate_comment = $("#rate_comment").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: 'customer/review',
            method: 'POST',
            dataType: "Json",
            data: {
                rate_comment: rate_comment
            },
            success: function (rate_response) {
                $("#rateRes").modal("show");
                $("#rateRes .modal-body").html(rate_response.message);
            }
        })
    })
</script>

<!--
<div id="back-top" style="display: none;">
<a title="Go to Top" href="#">
<i class="ti-angle-up"></i>
</a>
</div>-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
<script src="{{ asset('backend/js/jquery1-3.4.1.min.js') }}"></script>

<script src="{{ asset('backend/js/popper1.min.js') }}"></script>

<script src="{{ asset('backend/js/bootstrap1.min.js') }}"></script>

<script src="{{ asset('backend/js/metisMenu.js') }}"></script>

<script src="{{ asset('backend/vendors/count_up/jquery.waypoints.min.js') }}"></script>

<script src="{{ asset('backend/vendors/chartlist/Chart.min.js') }}"></script>

<script src="{{ asset('backend/vendors/count_up/jquery.counterup.min.js') }}"></script>

<script src="{{ asset('backend/vendors/niceselect/js/jquery.nice-select.min.js') }}"></script>

<script src="{{ asset('backend/vendors/owl_carousel/js/owl.carousel.min.js') }}"></script>

<script src="{{ asset('backend/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/vendors/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/vendors/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend/vendors/datatable/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('backend/vendors/datatable/js/jszip.min.js') }}"></script>
<script src="{{ asset('backend/vendors/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend/vendors/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('backend/vendors/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend/vendors/datatable/js/buttons.print.min.js') }}"></script>

<script src="{{ asset('backend/vendors/datepicker/datepicker.js') }}"></script>
<script src="{{ asset('backend/vendors/datepicker/datepicker.en.js') }}"></script>
<script src="{{ asset('backend/vendors/datepicker/datepicker.custom.js') }}"></script>
<script src="{{ asset('backend/js/chart.min.js') }}"></script>
<script src="{{ asset('backend/vendors/chartjs/roundedBar.min.js') }}"></script>

<script src="{{ asset('backend/vendors/progressbar/jquery.barfiller.js') }}"></script>

<script src="{{ asset('backend/vendors/tagsinput/tagsinput.js') }}"></script>

<script src="{{ asset('backend/vendors/text_editor/summernote-bs4.js') }}"></script>
<script src="{{ asset('backend/vendors/am_chart/amcharts.js') }}"></script>

<script src="{{ asset('backend/vendors/scroll/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('backend/vendors/scroll/scrollable-custom.js') }}"></script>

<script src="{{ asset('backend/vendors/vectormap-home/vectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('backend/vendors/vectormap-home/vectormap-world-mill-en.js') }}"></script>

<script src="{{ asset('backend/vendors/apex_chart/apex-chart2.js') }}"></script>
<script src="{{ asset('backend/vendors/apex_chart/apex_dashboard.js') }}"></script>
<script src="{{ asset('backend/vendors/echart/echarts.min.js') }}"></script>
<script src="{{ asset('backend/vendors/chart_am/core.js') }}"></script>
<script src="{{ asset('backend/vendors/chart_am/charts.js') }}"></script>
<script src="{{ asset('backend/vendors/chart_am/animated.js') }}"></script>
<script src="{{ asset('backend/vendors/chart_am/kelly.js') }}"></script>
<script src="{{ asset('backend/vendors/chart_am/chart-custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js">
</script>
<script src="{{ asset('backend/js/dashboard_init.js') }}"></script>
<script src="{{ asset('backend/js/custom.js') }}"></script>

<script>
    var url = 'https://wati-integration-service.clare.ai/ShopifyWidget/shopifyWidget.js?66890';
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = url;
            // "backgroundColor": "#940798",
    var options = {
        "enabled": true,
        "chatButtonSetting": {
            "backgroundColor": "#25D366",
            "ctaText": "",
            "borderRadius": "25",
            "marginLeft": "23",
            "marginBottom": "30",
            "marginRight": "30",
            "position": "right"
        },
        "brandSetting": {
            "brandName": "{{ config('app.name') }}",
            "brandSubTitle": "Typically replies within 60seconds",
            "brandImg": "backend/img/swiftlogo.png",
            "welcomeText": "Hi {{ config('app.name') }}! Can I speak with a representative?",
            "messageText": "Hi {{ config('app.name') }}! Can I speak with a representative?",
            "backgroundColor": "#25D366",
            "ctaText": "Start Chat",
            "borderRadius": "25",
            "autoShow": false,
            "phoneNumber": "{{ $web_settings->whatsapp_no }}"
        }
    };
    s.onload = function () {
        CreateWhatsappChatWidget(options);
    };
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
</script>
</body>

</html>

