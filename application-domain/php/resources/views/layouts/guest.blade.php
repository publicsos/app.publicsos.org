<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config("app.name", "Public sos - Emergency Management Online Platform") }}</title>

        <!-- Scripts -->
        @vite(["resources/css/app-frontend.css"])
        @vite(["resources/js/app-frontend.js"])
    </head>

    <body class="font-sans antialiased text-gray-900">
        <x-selected-theme />
        <div
            class="flex flex-col items-center pt-6 min-h-screen bg-gray-100 dark:bg-gray-900 sm:justify-center sm:pt-0"
        >
            <div style="min-height:5vh"  class="w-40 h-40 text-gray-500 fill-current">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="overflow-hidden px-6 py-4 mt-6 w-full bg-white shadow-md dark:bg-gray-800 sm:max-w-md sm:rounded-lg"
            >
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
