<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->currentLocale()) }}" dir="{{ language_direction() }}">
    <head>
        <meta charset="utf-8" />
        <link href="{{ asset("logo2.svg") }}" rel="apple-touch-icon" sizes="76x76" />

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>@yield("title") | {{ config("app.name") }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="{{ setting("meta_description") }}" />
        <meta name="keyword" content="{{ setting("meta_keyword") }}" />
        @include("frontend.includes.meta")

        <!-- Shortcut Icon -->
        <link href="{{ asset("logo2.svg") }}" rel="shortcut icon" />
        <link type="image/ico" href="{{ asset("img/favicon.png") }}" rel="icon" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @vite(["resources/css/app-frontend.css", "resources/js/app-frontend.js"])

        @livewireStyles

        @stack("after-styles")

        <x-google-analytics />
    </head>

    <body>
        <x-selected-theme />

        @include("frontend.includes.header")

        <main class="bg-white dark:bg-gray-800">
            @yield("content")
        </main>

        @include("frontend.includes.footer")

        <!-- Scripts -->
        @livewireScripts
        @stack("after-scripts")
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </body>
</html>
