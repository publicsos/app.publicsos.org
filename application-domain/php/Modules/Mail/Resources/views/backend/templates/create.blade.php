@extends("backend.layouts.app")

@section('title', __('Adauga un template nou'))



@section('content')

    <div class="card">
        <div class="text-white card-header">
            {{ __('Creeaza un template') }}
        </div>
        <div class="card-body">
            <form action="{{ route('backend.templates.store') }}" method="POST" class="form-horizontal">
                @csrf
                @include('mail::backend.templates.partials.form')
            </form>
        </div>
    </div>

@stop
