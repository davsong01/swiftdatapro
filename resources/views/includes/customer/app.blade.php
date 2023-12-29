<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

@include('partials.customer.head')
<body>
    <!-- Content -->
    @yield('content')
    @include('partials.customer.footer')
    @yield('extra-js')
</body>
</html>