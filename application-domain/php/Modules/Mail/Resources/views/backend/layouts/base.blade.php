<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('mail::backend.layouts.partials.favicons')

    <title>
        @hasSection('title')
            @yield('title')
        @endif
        {{ config('app.name') }}
    </title>

    <link href="{{ asset('vendor/laravel-mail/css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/laravel-mail/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/laravel-mail/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Fav Icon !-->
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/svg+xml">

    @stack('css')

    <style>
        ul.pagination{
            background: transparent !important;
        }
    </style>

</head>
<body>

@yield('htmlBody')

<script src="{{ asset('vendor/laravel-mail/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-mail/js/popper.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-mail/js/bootstrap.min.js') }}"></script>

<script>
    $('.sidebar-toggle').click(function (e) {
        e.preventDefault();
        toggleElements();
    });

    function toggleElements() {
        $('.sidebar').toggleClass('d-none');
    }
</script>

@stack('js')
</body>
</html>
