@extends('laravel-mail::layouts.base')

@section('htmlBody')
    <div class="container-fluid">
        <div class="row">

            <div class="sidebar col-lg-2 min-vh-100 d-none d-xl-block" style="background-color: #281029">

                <div class="mt-4">
                    <div class="text-center logo">
                        <a href="{{ route('laravel-mail.dashboard') }}" title="laravel mail ">
                            <img  src="{{ asset('logo2.svg') }}"  class="logo-img">
                        </a>
                    </div>
                </div>

                <div class="mt-5">
                    @include('laravel-mail::layouts.partials.sidebar')
                </div>
            </div>

            @include('laravel-mail::layouts.main')
        </div>
    </div>
@endsection
