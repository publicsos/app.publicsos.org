<?php
    $defaults = [
        'name' => 'Campanie ' . now()->format('d-M-Y'),
        'subject' => 'Campanie ' . now()->format('d-M-Y'),
        'from_name' => 'Stefan',
        'from_email' =>'info@furaciuni.ro',
        'content' => "Welcome to furaciuni.ro "
    ];
?>

<form method="POST" action="{{ route('backend.campaigns.store') }}">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-4 form-group">
                <label for="name" class="form-label">{{ __('Nume Campanie') }}</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ $campaign->name ?? old('name') ?? $defaults['name'] }}">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-4 form-group">
                <label for="subject" class="form-label">{{ __('Subiect Email') }}</label>
                <input type="text" class="form-control" id="subject" name="subject"
                    value="{{ $campaign->subject ?? old('subject') ?? $defaults['subject'] }}">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-4 form-group">
                <label for="from_name" class="form-label">{{ __('De la numele') }}</label>
                <input type="text" class="form-control" id="from_name" name="from_name"
                    value="{{ $campaign->from_name ?? old('from_name') ?? $defaults['from_name'] }}">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-4 form-group">
                <label for="from_email" class="form-label">{{ __('De la adresa email') }}</label>
                <input type="email" class="form-control" id="from_email" name="from_email"
                    value="{{ $campaign->from_email ?? old('from_email') ?? $defaults['from_email'] }}">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-4 form-group">
                <label for="template_id" class="form-label">{{ __('Template') }}</label>
                <select class="form-select" id="template_id" name="template_id">
                    @foreach($templates as $id => $name)
                        <option value="{{ $id }}" {{ (isset($campaign) && $campaign->template_id == $id) || old('template_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-4 form-group">
                <label for="email_service_id" class="form-label">{{ __('Serviciu Email') }}</label>
                <select class="form-select" id="email_service_id" name="email_service_id">
                    @foreach($emailServices->pluck('formatted_name', 'id') as $id => $name)
                        <option value="{{ $id }}" {{ (isset($campaign) && $campaign->email_service_id == $id) || old('email_service_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="mb-4 row">
        <div class="col-lg-6">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_open_tracking" name="is_open_tracking" value="1"
                    {{ (isset($campaign) && $campaign->is_open_tracking) || (!isset($campaign)) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_open_tracking">
                    {{ __('Urmareste Deschideri') }}
                </label>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_click_tracking" name="is_click_tracking" value="1"
                    {{ (isset($campaign) && $campaign->is_click_tracking) || (!isset($campaign)) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_click_tracking">
                    {{ __('Urmareste Clickuri') }}
                </label>
            </div>
        </div>
    </div>

    <div class="mb-4 form-group">
        <label for="content" class="form-label">{{ __('Continut') }}</label>
        <textarea class="form-control" id="content" name="content" rows="10">{{ $campaign->content ?? old('content') ?? $defaults['content'] }}</textarea>
    </div>

    <div class="form-group row">
        <div class="col-sm-4">
            <a href="{{ route('backend.campaigns.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
        </div>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-primary">{{ __('Salveaza si continua') }}</button>
        </div>
    </div>
</form>

@include('mail::backend.layouts.partials.summernote')

@push('scripts')
    <!-- jQuery (required for Summernote and BVSelect) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote.min.js" integrity="sha512-07bR+AJ2enmNU5RDrZkqMfVq06mQHgFxcmWN/hNSNY4E5SgYNOmTVqo/HCzrSxBhWU8mx3WB3ZJOixA9cRnCdA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- BVSelect JS -->
    <script src="https://raw.githubusercontent.com/BMSVieira/BVSelect-VanillaJS/refs/heads/master/js/bvselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs4.min.css" integrity="sha512-rDHV59PgRefDUbMm2lSjvf0ZhXZy3wgROFyao0JxZPGho3oOuWejq/ELx0FOZJpgaE5QovVtRN65Y3rrb7JhdQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        $(document).ready(function() {
            document.addEventListener("DOMContentLoaded", function() {
                var demo1 = new BVSelect({
                    selector: "#template_id, #email_service_id",
                    width: "100%",
                    searchbox: true,
                    offset: true,
                    placeholder: "Select Option",
                    search_placeholder: "Search...",
                    search_autofocus: true,
                    breakpoint: 450
                });
            });

            // Initialize Summernote for content textarea
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
                ]
            });
        });
    </script>
@endpush
