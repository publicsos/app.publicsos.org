
<?php

    $defaults = [
        'name' => 'Campign ' . now()->format('dH'),
        'subject' => 'Self host application',
        'from_name' => 'Stefan',
        'from_email' =>'marketing@laravelmail.com',
        'content' => "
                Sorry for the direct contact. <br />
                My name is Stefan and I'm a developer just like you.<br />
                <br />
                The reason why I'm contacting you is to introduce <a href='https://laravelmail.com'>Laravel Mail</a> to you. <br />
                It's a multi channel marketing platform that you can self host that is built with the Laravel Framework.<br />
                I'm searching for fellow developers to help me add more features to the platform.<br />
                If you are interested in joining me as a Co-Founder you can setup a meeting on this link.<br />
                <br  />
                Thanks for your time.<br  />
<br  /><br  />
                https://calendly.com/izdrail
<br  /><br  />

                Best regards, <br  />
                Stefan Bogdanel<br  />
                Founder<br  />
                <a href='https://izdrail.com'>Laravel Mail</a>"


    ];
?>


<div class="row">
    <div class="col-lg-12">
        <x-laravel-mail.text-field name="name" :label="__('Campaign Name')" :value="$campaign->name ?? old('name') ?? $defaults['name'] " />
    </div>
    <div class="col-lg-6">
        <x-laravel-mail.text-field name="subject" :label="__('Email Subject')" :value="$campaign->subject ?? old('subject') ?? $defaults['subject'] " />
    </div>
    <div class="col-lg-6">
        <x-laravel-mail.text-field name="from_name" :label="__('From Name')" :value="$campaign->from_name ?? old('from_name')?? $defaults['from_name'] " />
    </div>
    <div class="col-lg-6">
        <x-laravel-mail.text-field name="from_email" :label="__('From Email')" type="email" :value="$campaign->from_email ?? old('from_email') ?? $defaults['from_email'] " />
    </div>
    <div class="col-lg-6">
        <x-laravel-mail.select-field name="template_id" :label="__('Template')" :options="$templates" :value="$campaign->template_id ?? old('template_id')" />
    </div>
    <div class="col-lg-6">
        <x-laravel-mail.select-field name="email_service_id" :label="__('Email Service')" :options="$emailServices->pluck('formatted_name', 'id')" :value="$campaign->email_service_id ?? old('email_service_id')" />
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <x-laravel-mail.checkbox-field name="is_open_tracking" :label="__('Track Opens')" value="1" :checked="$campaign->is_open_tracking ?? true" />
    </div>
    <div class="col-lg-6">
        <x-laravel-mail.checkbox-field name="is_click_tracking" :label="__('Track Clicks')" value="1" :checked="$campaign->is_click_tracking ?? true" />
    </div>
</div>

<x-laravel-mail.textarea-field name="content" :label="__('Content')">{{ $campaign->content ?? old('content') ?? $defaults['content'] }}</x-laravel-mail.textarea-field>

<div class="form-group row">
    <div class="col-sm-4">
        <a href="{{ route('laravel-mail.campaigns.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
    </div>




    <div class="col-sm-4">
        <button type="submit" class="btn btn-primary">{{ __('Save and continue') }}</button>
    </div>
</div>

@include('laravel-mail::layouts.partials.summernote')

@push('js')





<!-- jQuery (required for Select2) -->

<!-- Select2 JS -->
<script src="https://raw.githubusercontent.com/BMSVieira/BVSelect-VanillaJS/refs/heads/master/js/bvselect.js"></script>
<script>
    $(document).ready(function () {
        document.addEventListener("DOMContentLoaded", function() {
      var demo1 = new BVSelect({
        selector: "#selectbox",
        width: "100%",
        searchbox: true,
        offset: true,
        placeholder: "Select Option",
        search_placeholder: "Search...",
        search_autofocus: true,
        breakpoint: 450
      });
});
});
</script>

@endpush
