<!DOCTYPE html>
<html lang="en">

@include('partials.frontend.head')

<body>
    @include('partials.frontend.nav')
    <main id="main">
        @yield('content')
    </main>
    @include('partials.frontend.footer')
</body>

</html>