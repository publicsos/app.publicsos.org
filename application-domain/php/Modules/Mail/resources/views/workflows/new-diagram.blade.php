@extends('laravel-mail::layouts.app')
@section('title', __('Workflows'))

@section('heading')
    {{ __('Create Workflow') }}
@endsection

@section('content')
    @vite('packages/mail/resources/assets/vue/main.ts')

    <div id="app" style="width:100%;height:100%;"></div>
@endsection
