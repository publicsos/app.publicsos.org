@extends("backend.layouts.app")

@section('content')
    <!-- Session Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @component('mail::backend.layouts.partials.card')
        @slot('cardHeader', __('Test Email Service') . ' : ' . $emailService->name)

        @slot('cardBody')
            <form action="{{ route('backend.email-services.test.store', $emailService->id) }}"
                  method="POST"
                  class="form-horizontal">
                @csrf

                <x-text-field
                    name="to"
                    :label="__('To Email')"
                    placeholder="Email To"
                    required="required"
                    value="{{ old('to') }}"
                    class="@error('to') is-invalid @enderror" />

                <x-text-field
                    name="from"
                    :label="__('From Email')"
                    value="{{ old('from', 'info@publicsos.org') }}"
                    placeholder="Email From"
                    required="required"
                    class="@error('from') is-invalid @enderror" />

                <x-text-field
                    name="subject"
                    :label="__('Subject')"
                    placeholder="Email Subject"
                    required="required"
                    value="{{ old('subject') }}"
                    class="@error('subject') is-invalid @enderror" />

                <x-textarea-field
                    name="body"
                    :label="__('Email Body')"
                    required="required"
                    rows="5"
                    class="@error('body') is-invalid @enderror">
                    {{ old('body', "This is a test for the email service {$emailService->name}") }}
                </x-textarea-field>

                <x-submit-button :label="__('Test')" class="mt-3" />
            </form>
        @endSlot
    @endcomponent
@endsection