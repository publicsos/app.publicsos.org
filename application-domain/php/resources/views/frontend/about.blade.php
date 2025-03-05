@extends("frontend.layouts.app")

@section("title")
    {{ app_name() }} - Emergency Response Service Management System.
@endsection



@section("content")

    <section class="bg-white dark:bg-gray-800">
        <div class="px-4 py-24 mx-auto max-w-screen-xl text-center sm:px-12">
            <h1 class="mb-6 text-4xl font-extrabold tracking-tight leading-none text-gray-600 dark:text-white sm:text-6xl">
                Public SOS
            </h1>
            <h2 class="mb-10 text-lg font-normal text-gray-500 dark:text-white sm:px-16 sm:text-2xl xl:px-48">
                Emergency Response Service Management System
            </h2>
            @include("frontend.includes.messages")
        </div>
    </section>


    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endsection
