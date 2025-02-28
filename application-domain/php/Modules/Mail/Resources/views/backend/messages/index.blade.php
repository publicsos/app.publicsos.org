@extends("backend.layouts.app")

@section('title', __('Messages'))
@section('heading', __('Messages'))

@section('content')
    @include('mail::backend.messages.partials.nav')

    <div class="mb-4 shadow-sm card">
        <div class="card-body">
            <form action="{{ route('backend.messages.index') }}" method="GET" class="flex-wrap d-flex align-items-end">
                <div class="mr-3 mb-2 form-group">
                    <input type="text" class="form-control" placeholder="{{ __('Search...') }}" name="search"
                           value="{{ request('search') }}">
                </div>

                @if(request()->route()->named('backend.messages.index'))
                    <div class="mr-3 mb-2 form-group">
                        <select name="status" class="form-control">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>{{ __('Sent') }}</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                            <option value="opened" {{ request('status') == 'opened' ? 'selected' : '' }}>{{ __('Opened') }}</option>
                            <option value="clicked" {{ request('status') == 'clicked' ? 'selected' : '' }}>{{ __('Clicked') }}</option>
                            <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>{{ __('Unsubscribed') }}</option>
                            <option value="bounced" {{ request('status') == 'bounced' ? 'selected' : '' }}>{{ __('Bounced') }}</option>
                        </select>
                    </div>
                @endif

                <div class="mb-2 d-flex">
                    <button type="submit" class="mr-2 btn btn-primary">
                        <i class="mr-1 fas fa-search"></i> {{ __('Search') }}
                    </button>

                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('backend.messages.index') }}" class="btn btn-outline-secondary">
                            <i class="mr-1 fas fa-times"></i> {{ __('Clear') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="shadow-sm card">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark">
            <h5 class="mb-0">{{ __('Message List') }}</h5>

            @if(request()->route()->named('backend.messages.draft'))
                <div class="d-flex">
                    <button class="mr-2 btn btn-sm btn-secondary" id="select-all">
                        <i class="mr-1 fas fa-check-square"></i> {{ __('Select All') }}
                    </button>
                    <form action="{{ route('backend.messages.send-selected') }}" method="post" id="send-selected-form">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="mr-1 fas fa-paper-plane"></i> {{ __('Send Selected') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Source') }}</th>
                            <th>{{ __('Recipient') }}</th>
                            <th>{{ __('Status') }}</th>
                            @if(request()->route()->named('backend.messages.draft'))
                                <th class="text-center">{{ __('Actions') }}</th>
                                <th class="text-center">{{ __('Select') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td class="align-middle">
                                    {{ $message->sent_at ?? $message->created_at }}
                                </td>
                                <td class="align-middle">
                                    <span class="text-truncate d-inline-block" style="max-width: 250px;">
                                        {{ $message->subject }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    @if($message->isCampaign())
                                        <span class="mr-1 badge badge-info">
                                            <i class="mr-1 fas fa-envelope"></i> {{ __('Campaign') }}
                                        </span>
                                        <a href="{{ route('backend.campaigns.reports.index', $message->source_id) }}">
                                            {{ $message->source->name }}
                                        </a>
                                    @elseif($message->isAutomation())
                                        <span class="mr-1 badge badge-primary">
                                            <i class="mr-1 fas fa-sync-alt"></i> {{ __('Automation') }}
                                        </span>
                                        <a href="{{ route('backend.automations.show', $message->source->automation_step->automation_id) }}">
                                            {{ $message->source->automation_step->automation->name }}
                                        </a>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('backend.subscribers.show', $message->subscriber_id) }}">
                                        {{ $message->recipient_email }}
                                    </a>
                                </td>
                                <td class="align-middle">
                                    @include('mail::backend.messages.partials.status-row')
                                </td>
                                @if(request()->route()->named('backend.messages.draft') && !$message->sent_at)
                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <a href="{{ route('backend.messages.show', $message->id) }}"
                                               class="btn btn-sm btn-info"
                                               title="{{ __('Preview') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <form action="{{ route('backend.messages.send') }}" method="post" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $message->id }}">
                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        title="{{ __('Send Now') }}">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('backend.messages.delete', $message->id) }}"
                                                  method="post"
                                                  class="d-inline delete-message">
                                                @csrf
                                                @method('delete')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="{{ __('Delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input message-select"
                                                   id="message-{{ $message->id }}"
                                                   name="messages[]"
                                                   value="{{ $message->id }}"
                                                   form="send-selected-form">
                                            <label class="custom-control-label" for="message-{{ $message->id }}"></label>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="py-4 text-center">
                                    <div class="empty-state">
                                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                                        <p class="text-muted">{{ __('Nu exista mesaje`') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($messages->count() > 0)
            <div class="card-footer">
                @include('mail::backend.layouts.partials.pagination', ['records' => $messages])
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            $(function () {
                // Select all messages
                $('#select-all').click(function () {
                    $('.message-select').prop('checked', true);
                });

                // Delete confirmation
                $('.delete-message').submit(function (event) {
                    event.preventDefault();

                    Swal.fire({
                        title: '{{ __("Are you sure?") }}',
                        text: '{{ __("You won\'t be able to revert this!") }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '{{ __("Yes, delete it!") }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });

                // Form validation for send selected
                $('#send-selected-form').submit(function(event) {
                    if (!$('.message-select:checked').length) {
                        event.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("No messages selected") }}',
                            text: '{{ __("Please select at least one message to send") }}'
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
