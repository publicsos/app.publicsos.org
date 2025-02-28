@extends("backend.layouts.app")

@section('title', __("Subscriber Details") . " - {$subscriber->full_name}")

@section('heading')
    {{ __('Subscriber Information') }}
@stop

@section('content')
    <div class="container-fluid">
        {{-- Action Bar --}}
        @component('mail::backend.layouts.partials.actions')
            @slot('right')
                <div class="gap-2 d-flex">
                    <a class="btn btn-primary" href="{{ route('backend.subscribers.edit', $subscriber->id) }}">
                        <i class="fa fa-edit me-2"></i>{{ __('Edit Subscriber') }}
                    </a>
                    @if(!$subscriber->unsubscribed_at)
                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#unsubscribeModal">
                            <i class="fa fa-ban me-2"></i>{{ __('Unsubscribe') }}
                        </button>
                    @endif
                </div>
            @endslot
        @endcomponent

        {{-- Main Content --}}
        <div class="row g-4">
            {{-- Subscriber Details Card --}}
            <div class="col-12 col-lg-6">
                <div class="border-0 shadow-sm card h-100">
                    <div class="text-white card-header bg-primary">
                        <h5 class="mb-0 card-title">{{ __('Subscriber Details') }}</h5>
                    </div>
                    <div class="p-0 card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-dark">
                                <tbody>
                                    <tr>
                                        <th class="w-25">{{ __('Email') }}</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{ $subscriber->email }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <td>{{ $subscriber->first_name }} {{ $subscriber->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Status') }}</th>
                                        <td>
                                            @if($subscriber->unsubscribed_at)
                                                <div class="gap-2 d-flex align-items-center">
                                                    <span class="badge bg-danger">{{ __('Unsubscribed') }}</span>
                                                    <span class="text-white">
                                                        {{ \Modules\Mail\Models\UnsubscribeEventType::findById($subscriber->unsubscribe_event_id) }}
                                                        ({{ $subscriber->unsubscribed_at->format('M d, Y') }})
                                                    </span>
                                                </div>
                                            @else
                                                <div class="gap-2 d-flex align-items-center">
                                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                                    <span class="text-white">{{ __('Since') }} {{ $subscriber->created_at->format('M d, Y') }}</span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Tags') }}</th>
                                        <td>
                                            <div class="flex-wrap gap-2 d-flex">
                                                @forelse($subscriber->tags as $tag)
                                                    <span class="badge bg-secondary">{{ $tag->name }}</span>
                                                @empty
                                                    <span class="text-muted">{{ __('No tags assigned') }}</span>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Intelligence Card --}}
            <div class="col-12 col-lg-6">
                <div class="border-0 shadow-sm card h-100">
                    <div class="text-white card-header bg-primary">
                        <h5 class="mb-0 card-title">{{ __('Intelligence') }}</h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="p-4 text-center">
                            <i class="mb-3 text-white fas fa-chart-line fa-3x"></i>
                            <p class="mb-3 text-white">{{ __('Intelligence data is not available for this subscriber.') }}</p>
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fas fa-external-link-alt me-2"></i>{{ __('View Intelligence') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>



        </div>

        {{-- Messages History --}}
        <div class="mt-4 border-0 shadow-sm card">
            <div class="text-white card-header bg-primary d-flex justify-content-between align-items-center">
                <h5 class="mb-0 card-title">{{ __('Message History') }}</h5>
                <span class="badge bg-light text-primary">{{ $subscriber->messages->count() }} {{ __('Messages') }}</span>
            </div>
            <div class="p-0 card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-hover table-dark">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Source') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriber->messages as $message)
                                <tr>
                                    <td>{{ $message->sent_at?->format('M d, Y H:i') ?? $message->created_at->format('M d, Y H:i') }}</td>
                                    <td class="text-truncate" style="max-width: 300px;">{{ $message->subject }}</td>
                                    <td>
                                        @if($message->isCampaign())
                                            <a href="{{ route('backend.campaigns.reports.index', $message->source_id) }}"
                                               class="text-decoration-none">
                                                <i class="fas fa-envelope text-muted me-2"></i>
                                                {{ $message->source->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">{{ __('Direct Message') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @include('mail::backend.messages.partials.status-row')
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center">
                                        <i class="mb-3 fas fa-inbox fa-2x text-blue"></i>
                                        <p class="mb-0 text-blue">{{ __('No messages found for this subscriber') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Unsubscribe Modal --}}
    <div class="modal fade" id="unsubscribeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirm Unsubscribe') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to unsubscribe this subscriber?') }}</p>
                    <p class="text-muted">{{ __('This action can be reversed later.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <form action="{{ route('backend.subscribers.unsubscribe', $subscriber->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">{{ __('Confirm Unsubscribe') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
