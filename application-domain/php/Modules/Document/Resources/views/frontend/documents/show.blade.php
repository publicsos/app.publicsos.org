@extends('frontend.layouts.app')

@section('title') {{$$module_name_singular->name}} - {{ __($module_title) }} @endsection

@section('content')

<section class="py-10 text-gray-600 bg-gray-100 sm:py-20">
    <div class="container flex flex-col justify-center items-center px-5 mx-auto">
        <div class="w-full text-center lg:w-2/3">
            <p class="mb-8 leading-relaxed">
                <a href="{{route('frontend.'.$module_name.'.index')}}" class="px-3 py-1 mr-2 text-sm font-semibold text-gray-800 bg-gray-200 rounded outline outline-1 outline-gray-800 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                    {{ __($module_title) }}
                </a>
            </p>
            <h1 class="mb-4 text-3xl font-medium text-gray-800 sm:text-4xl">
                {{$$module_name_singular->title}}
            </h1>
            <p class="mb-8 leading-relaxed">
                {{$$module_name_singular->date }}
            </p>

            @include('frontend.includes.messages')
        </div>
    </div>
</section>

<section class="p-6 text-gray-600 bg-white sm:p-20">
    <div class="container mx-auto">
        <div>
            {{$$module_name_singular->content }}
        </div>
    </div>
</section>

@endsection
