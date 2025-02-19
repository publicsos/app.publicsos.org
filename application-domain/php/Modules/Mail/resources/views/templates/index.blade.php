@extends('laravel-mail::layouts.app')

@section('title', __('Email Templates'))

@section('heading')
    {{ __('Email Templates') }}
@endsection

@section('content')




    @component('laravel-mail::layouts.partials.actions')
    @slot('left')
    <form method="GET" action="{{ route('laravel-mail.templates.index') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search templates..." />
        <button type="submit" class="btn btn-primary btn-md btn-flat"> <i class="fa fa-list mr-1"></i> Search</button>
    </form>

        @endslot
        @slot('right')
            <a class="btn btn-primary btn-md btn-flat" href="{{ route('laravel-mail.templates.create') }}">
                <i class="fa fa-plus mr-1"></i> {{ __('New Template') }}
            </a>

        @endslot
    @endcomponent

    @include('laravel-mail::templates.partials.grid')

@endsection
