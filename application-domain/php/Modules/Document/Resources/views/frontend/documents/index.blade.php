@extends('frontend.layouts.app')

@section('title') {{ __($module_title) }} @endsection

@section('content')

<section class="py-20 text-gray-600 bg-gray-100">
    <div class="container flex flex-col justify-center items-center px-5 mx-auto">
        <div class="w-full text-center lg:w-2/3">
            <h1 class="mb-4 text-3xl font-medium text-gray-800 sm:text-4xl">
                {{ __($module_title) }}
            </h1>
            <p class="mb-8 leading-relaxed">
                The list of {{ __($module_name) }}.
            </p>

            @include('frontend.includes.messages')
        </div>
    </div>
</section>

<section class="p-6 text-gray-600 bg-white sm:p-20">
    <div class="grid grid-cols-2 gap-6 sm:grid-cols-3">
        @foreach ($$module_name as $$module_name_singular)
        @php
        $details_url = route("frontend.$module_name.show",[encode_id($$module_name_singular->id), $$module_name_singular->title]);
        @endphp

        <x-frontend.card :url="$details_url" :name="$$module_name_singular->title">
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                {{Str::limit($$module_name_singular->content, 100)}}
            </p>
        </x-frontend.card>

        @endforeach
    </div>
    <div class="mt-3 d-flex justify-content-center w-100">
        {{$$module_name->links()}}
    </div>
</section>

@endsection