<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('partials.head')
<body>
    <div id='app'>
        <div class="wrapper">
            @yield('content')
        </div>
    </div>
</body>
@yield('scripts')
</html>