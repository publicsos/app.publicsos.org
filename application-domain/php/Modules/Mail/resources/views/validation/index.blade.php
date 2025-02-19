@extends('laravel-mail::layouts.app')

@section('title', __('Email Validation'))

@section('heading')
    {{ __('Email Validation') }}
@endsection

@section('content')

    Welcome to email  validation page.
    This will be a view where user can validate their subscribers lists using the local tool.
    The view will be rendered in Livewire.
@endsection
