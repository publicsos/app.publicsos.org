@extends("backend.layouts.app")

@section('title', __('New Subscriber'))

@section('heading')
    {{ __('Subscribers') }}
@stop

@section('content')

    @component('mail::backend.layouts.partials.card')
        @slot('cardHeader', __('Create Subscriber'))

        @slot('cardBody')
            <form action="{{ route('backend.subscribers.store') }}" class="form-horizontal" method="POST">
                @csrf
                @include('mail::backend.subscribers.partials.form')

                <x-submit-button :label="__('Save')" />
            </form>
        @endSlot
    @endcomponent

@stop
