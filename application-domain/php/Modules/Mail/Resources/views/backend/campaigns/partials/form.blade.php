<?php
    $defaults = [
        'name' => 'Campaign ' . now()->format('d-M-Y'),
        'subject' => 'Campaign ' . now()->format('d-M-Y'),
        'from_name' => 'Stefan',
        'from_email' => 'info@publicsos.org',
        'content' => 'Info Communication Center',
        'template_id' => null, // Added for consistency
        'email_service_id' => null, // Added for consistency
    ];
?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('backend.campaigns.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Campaign Details -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Campaign name') }}</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ $campaign->name ?? old('name', $defaults['name']) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="subject" class="form-label">{{ __('Subject Email') }}</label>
                        <input type="text"
                               class="form-control @error('subject') is-invalid @enderror"
                               id="subject"
                               name="subject"
                               value="{{ $campaign->subject ?? old('subject', $defaults['subject']) }}"
                               required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="from_name" class="form-label">{{ __('From name') }}</label>
                        <input type="text"
                               class="form-control @error('from_name') is-invalid @enderror"
                               id="from_name"
                               name="from_name"
                               value="{{ $campaign->from_name ?? old('from_name', $defaults['from_name']) }}"
                               required>
                        @error('from_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="from_email" class="form-label">{{ __('From email') }}</label>
                        <input type="email"
                               class="form-control @error('from_email') is-invalid @enderror"
                               id="from_email"
                               name="from_email"
                               value="{{ $campaign->from_email ?? old('from_email', $defaults['from_email']) }}"
                               required>
                        @error('from_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="template_id" class="form-label">{{ __('Template') }}</label>
                        <select class="form-select @error('template_id') is-invalid @enderror"
                                id="template_id"
                                name="template_id">
                            <option value="">{{ __('None') }}</option>
                            @foreach($templates as $id => $name)
                                <option value="{{ $id }}"
                                        {{ ($campaign->template_id ?? old('template_id')) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('template_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="email_service_id" class="form-label">{{ __('Email Service') }}</label>
                        <select class="form-select @error('email_service_id') is-invalid @enderror"
                                id="email_service_id"
                                name="email_service_id">
                            <option value="">{{ __('None') }}</option>
                            @foreach($emailServices->pluck('formatted_name', 'id') as $id => $name)
                                <option value="{{ $id }}"
                                        {{ ($campaign->email_service_id ?? old('email_service_id')) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('email_service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tracking Options -->
            <div class="mt-3 row g-4">
                <div class="col-lg-6">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_open_tracking"
                               name="is_open_tracking"
                               value="1"
                               {{ ($campaign->is_open_tracking ?? !isset($campaign)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_open_tracking">
                            {{ __('Track opens') }}
                        </label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_click_tracking"
                               name="is_click_tracking"
                               value="1"
                               {{ ($campaign->is_click_tracking ?? !isset($campaign)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_click_tracking">
                            {{ __('Track Clicks') }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mt-4 form-group">
                <label for="content" class="form-label">{{ __('Content') }}</label>
                <textarea class="form-control @error('content') is-invalid @enderror"
                          id="content"
                          name="content"
                          rows="10">{{ $campaign->content ?? old('content', $defaults['content']) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="mt-4 row g-4">
                <div class="col-sm-6">
                    <a href="{{ route('backend.campaigns.index') }}"
                       class="btn btn-outline-secondary w-100">
                        {{ __('Cancel') }}
                    </a>
                </div>
                <div class="col-sm-6">
                    <button type="submit"
                            class="btn btn-primary w-100">
                        {{ __('Save and continue') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@include('mail::backend.layouts.partials.summernote')

@push('scripts')
    <!-- jQuery and Summernote -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote.min.js" integrity="sha512-07bR+AJ2enmNU5RDrZkqMfVq06mQHgFxcmWN/hNSNY4E5SgYNOmTVqo/HCzrSxBhWU8mx3WB3ZJOixA9cRnCdA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs4.min.css" integrity="sha512-rDHV59PgRefDUbMm2lSjvf0ZhXZy3wgROFyao0JxZPGho3oOuWejq/ELx0FOZJpgaE5QovVtRN65Y3rrb7JhdQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- BVSelect JS -->
    <script src="https://cdn.jsdelivr.net/npm/bvselect-vanillajs@latest/dist/bvselect.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize BVSelect for dropdowns
            new BVSelect({
                selector: "#template_id, #email_service_id",
                width: "100%",
                searchbox: true,
                offset: true,
                placeholder: "{{ __('None') }}",
                search_placeholder: "{{ __('Search...') }}",
                search_autofocus: true,
                breakpoint: 450
            });

            // Initialize Summernote
            $('#content').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                placeholder: "{{ __('Enter campaign content...') }}"
            });
        });
    </script>
@endpush