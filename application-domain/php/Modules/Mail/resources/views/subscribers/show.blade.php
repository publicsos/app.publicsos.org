@extends('laravel-mail::layouts.app')

@section('title', __("Subscriber Details") . " - {$subscriber->full_name}")

@section('heading')
    {{ __('Subscriber Information') }}
@stop

@section('content')
    <div class="container-fluid">
        {{-- Action Bar --}}
        @component('laravel-mail::layouts.partials.actions')
            @slot('right')
                <div class="d-flex gap-2">
                    <a class="btn btn-primary" href="{{ route('laravel-mail.subscribers.edit', $subscriber->id) }}">
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
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">{{ __('Subscriber Details') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark mb-0">
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
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-danger">{{ __('Unsubscribed') }}</span>
                                                    <span class="text-white">
                                                        {{ \LaravelCompany\Mail\Models\UnsubscribeEventType::findById($subscriber->unsubscribe_event_id) }}
                                                        ({{ $subscriber->unsubscribed_at->format('M d, Y') }})
                                                    </span>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                                    <span class="text-white">{{ __('Since') }} {{ $subscriber->created_at->format('M d, Y') }}</span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Tags') }}</th>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
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
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">{{ __('Intelligence') }}</h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="text-center p-4">
                            <i class="fas fa-chart-line fa-3x text-white mb-3"></i>
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
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ __('Message History') }}</h5>
                <span class="badge bg-light text-primary">{{ $subscriber->messages->count() }} {{ __('Messages') }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-dark align-middle mb-0">
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
                                            <a href="{{ route('laravel-mail.campaigns.reports.index', $message->source_id) }}"
                                               class="text-decoration-none">
                                                <i class="fas fa-envelope text-muted me-2"></i>
                                                {{ $message->source->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">{{ __('Direct Message') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @include('laravel-mail::messages.partials.status-row')
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
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-blue mb-3"></i>
                                        <p class="text-blue mb-0">{{ __('No messages found for this subscriber') }}</p>
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
                    <form action="{{ route('laravel-mail.subscribers.unsubscribe', $subscriber->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">{{ __('Confirm Unsubscribe') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
