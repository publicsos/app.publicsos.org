@extends("backend.layouts.app")


@section('title', __('Confirma Campania'))



@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-4 card">
                <div class="card-header card-header-accent">
                    <div class="text-white card-header-inner">
                        {{ __('Continut') }}
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal">
                        <div class="row">
                            <label class="text-white col-sm-2 col-form-label">{{ __('De la') }}:</label>
                            <div class="col-sm-10">
                                <b class="text-white">
                                    <span class="text-white form-control-plaintext">{{ $campaign->from_name . ' <' . $campaign->from_email . '>' }}</span>
                                </b>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="text-white col-sm-2 col-form-label">{{ __('Subiect') }}:</label>
                            <div class="col-sm-10">
                                <b>
                                    <span class="text-white form-control-plaintext">{{ $campaign->subject }}</span>
                                </b>
                            </div>
                        </div>

                        <div style="border: 1px solid #ddd; height: 600px">
                            <iframe id="js-template-iframe" srcdoc="{{ $campaign->merged_content }}" class="embed-responsive-item" frameborder="0" style="height: 100%; width: 100%"></iframe>
                        </div>

                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-4">

            <form action="{{ route('backend.campaigns.test', $campaign->id) }}" method="POST">
                @csrf

                <div class="mb-4 card">
                    <div class="text-white card-header">
                        {{ __('Test Email') }}
                    </div>
                    <div class="card-body">

                        <div class="pb-2"><b class="text-white">{{ __('RECIPIENT') }}</b></div>
                        <div class="form-group row form-group-schedule">
                            <div class="col-sm-12">
                                <input name="recipient_email" id="test-email-recipient" type="email" class="form-control" placeholder="{{ __('Recipient email address') }}">
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="text-white btn btn-sm btn-primary">{{ __('Send Test Email') }}</button>
                        </div>
                    </div>
                </div>
            </form>

            <form action="{{ route('backend.campaigns.send', $campaign->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4 card">
                    <div class="text-white card-header">
                        {{ __('Sending options') }}
                    </div>
                    <div class="card-body">

                        <div class="pb-2"><b class="text-white">{{ __('Recipients') }}</b></div>
                        <div class="form-group row form-group-recipients">
                            <div class="col-sm-12">
                                <select id="id-field-recipients" class="form-control" name="recipients">
                                    <option value="send_to_all" {{ (old('recipients') ? old('recipients') == 'send_to_all' : $campaign->send_to_all) ? 'selected' : '' }}>
                                        {{ __('All subscribers') }} ({{ $subscriberCount }})
                                    </option>
                                    <option value="send_to_tags" {{ (old('recipients') ? old('recipients') == 'send_to_tags' : !$campaign->send_to_all) ? 'selected' : '' }}>
                                        {{ __('Select Tags') }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="tags-container {{ (old('recipients') ? old('recipients') == 'send_to_tags' : !$campaign->send_to_all) ? '' : 'hide' }}">
                            @forelse($tags as $tag)
                                <div class="checkbox">
                                    <label class="text-white">
                                        <input name="tags[]" type="checkbox" value="{{ $tag->id }}">
                                        {{ $tag->name }} ({{ $tag->activeSubscribers()->count() }} {{ __('subscribers') }})
                                    </label>
                                </div>
                            @empty
                                <small class="text-white">{{ __('There are no tags to select') }}</small>
                            @endforelse
                        </div>

                        <div class="pb-2"><b class="text-white">{{ __('Schedule') }}</b></div>
                        <div class="form-group row form-group-schedule">
                            <div class="col-sm-12">
                                <select id="id-field-schedule" class="form-control" name="schedule">
                                    <option value="now" {{ old('schedule') === 'now' || is_null($campaign->scheduled_at) ? 'selected' : '' }}>
                                        {{ __('Dispatch now') }}
                                    </option>
                                    <option value="scheduled" {{ old('schedule') === 'now' || $campaign->scheduled_at ? 'selected' : '' }}>
                                        {{ __('Dispatch at a specific time') }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <input id="input-field-scheduled_at" class="mb-3 form-control hide" name="scheduled_at" type="text" value="{{ $campaign->scheduled_at ?: now() }}">

                        <div class="pb-2"><b class="text-white">{{ __('Sending Behaviour') }}</b></div>
                        <div class="form-group row form-group-schedule">
                            <div class="col-sm-12">
                                <select id="id-field-behaviour" class="form-control" name="behaviour">
                                    <option value="draft">{{ __('Queue draft') }}</option>
                                    <option value="auto">{{ __('Send automatically') }}</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div>
                    <a href="{{ route('backend.campaigns.index') }}" class="btn btn-warning">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('Send campaign') }}</button>
                </div>

            </form>

        </div>


    </div>
</div>
@stop

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        var target = $('.tags-container');
        $('#id-field-recipients').change(function() {
            if (this.value == 'send_to_all') {
                target.addClass('hide');
            } else {
                target.removeClass('hide');
            }
        });

        var element = $('#input-field-scheduled_at');
        $('#id-field-schedule').change(function() {
            if (this.value == 'now') {
                element.addClass('hide');
            } else {
                element.removeClass('hide');
            }
        });

        $('#input-field-scheduled_at').flatpickr({
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
        });
    </script>
@endpush
