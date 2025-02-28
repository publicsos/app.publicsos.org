@extends("backend.layouts.app")

@section('title', __('Sites Manager'))

@section('heading')
    {{ __('Websites Manager') }}
@endsection

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">


<div class="container-fluid" style="min-height: 100vh">

  <div id="fm"></div>

</div>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>

<style>

  :root {
    --bg--bs-table-bg: transparent !important;
    --bs-body-bg: #6e2c73;
  }


  .fm{
    background: none;
  }

  .fm-body {
    min-height: 85vh !important;
  }


</style>

@endsection