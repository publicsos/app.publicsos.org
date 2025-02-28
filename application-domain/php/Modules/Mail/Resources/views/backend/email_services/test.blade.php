@extends("backend.layouts.app")


@section('content')

    @component('mail::backend.layouts.partials.card')
        @slot('cardHeader', __('Test Email Service') . ' : ' . $emailService->name)

        @slot('cardBody')
            <form action="{{ route('backend.email-services.test.store', $emailService->id) }}" method="POST" class="form-horizontal">
                @csrf

                <x-text-field name="to" :label="__('To Email')" placeholder="Email To" required="required" />

                <x-text-field name="from" :label="__('From Email')" value="alert@furaciuni.ro" placeholder="Email From" required="required" />

                <x-text-field name="subject" :label="__('Subject')" placeholder="Email Subject" required="required" />

                <x-textarea-field name="body" :label="__('Email Body')" required="required" rows="5">This is a test for the email service {{ $emailService->name }}</x-textarea-field>

                <x-submit-button :label="__('Test')" />
            </form>
        @endSlot
    @endcomponent

@stop


