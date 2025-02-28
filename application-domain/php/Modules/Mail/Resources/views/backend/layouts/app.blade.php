@extends('mail::backend.layouts.base')

@section('htmlBody')
    <div class="container-fluid">
        <div class="row">

            <div class="sidebar col-lg-2 min-vh-100 d-none d-xl-block" style="background-color: #281029">

                <div class="mt-4">
                    <div class="text-center logo">
                        <a href="{{ route('backend.dashboard') }}" title="laravel mail ">
                            <img  src="{{ asset('logo.svg') }}" alt="Laravel Mail" class="logo-img">
                        </a>
                    </div>
                </div>

                <div class="mt-5">
                    @include('mail::backend.layouts.partials.sidebar')
                </div>
            </div>

            @include('mail::backend.layouts.main')
        </div>
    </div>
@endsection
