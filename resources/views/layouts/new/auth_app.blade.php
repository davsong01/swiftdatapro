<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> {{ $title ?? settings()->seo_title }} </title>
    <meta name="Description" content="{{ settings()->seo_description }}">
    <meta name="Author" content="{{config('app.name')}}">
	<meta name="keywords" content="">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset(settings()->favicon)}}" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="{{ asset('new/assets/js/authentication-main.js')}}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('new/assets/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" >

    <!-- Style Css -->
    <link href="{{ asset('new/assets/css/styles.min.css')}}" rel="stylesheet" >

    <!-- Icons Css -->
    <link href="{{ asset('new/assets/css/icons.min.css')}}" rel="stylesheet" >
</head>

<body>
    <div class="container-lg">
        <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
            <div class="col-xxl-7 col-xl-7 col-lg-7 col-md-6 col-sm-8 col-12">
                <div class="card custom-card">
                    <div class="card-body p-5">
                    <a href="/">
                        <img src="{{ asset(settings()->logo)}}" alt="logo" class="desktop-logo text-center" style="margin: auto;width: 90px;height:auto;margin-bottom: 15px;">
                    </a>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Custom-Switcher JS -->
    <script src="{{ asset('new/assets/js/custom-switcher.min.js')}}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('new/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Show Password JS -->
    <script src="{{ asset('new/assets/js/show-password.js')}}"></script>

</body>

</html>