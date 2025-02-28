@extends("backend.layouts.app")
@section('heading')
    {{ __('Email Services') }}
@stop

@section('content')

    @component('mail::backend.layouts.partials.card')

        @slot('cardHeader', __('Editeaza Serviciu mail'))

        @slot('cardBody')
            <form action="{{ route('backend.email-services.update', $emailService->id) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')

                <x-text-field name="name" :label="__('Name')" :value="$emailService->name" />

                @include('mail::backend.email_services.options.' . strtolower($emailServiceType->name), ['settings' => $emailService->settings])

                <x-submit-button :label="__('Update')" />
            </form>
        @endSlot
    @endcomponent

@stop
