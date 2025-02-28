@extends("backend.layouts.app")

@section('title', __("Edit Subscriber") . " : {$subscriber->full_name}")

@section('heading')
    {{ __('Subscribers') }}
@stop

@section('content')

    @component('mail::backend.layouts.partials.card')
        @slot('cardHeader', __('Edit Subscriber'))

        @slot('cardBody')
            <form action="{{ route('backend.subscribers.update', $subscriber->id) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')

                @include('mail::backend.subscribers.partials.form')

                <x-submit-button :label="__('Save')" />

            </form>
        @endSlot
    @endcomponent

@stop
