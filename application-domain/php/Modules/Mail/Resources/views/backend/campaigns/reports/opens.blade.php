@extends("backend.layouts.app")

@section('title', $campaign->name)

@section('heading', $campaign->name)

@section('content')

    @include('mail::backend.campaigns.reports.partials.nav')

    <div class="mb-4 row">
        <div class="mb-3 col-md-4 col-sm-6 mb-md-0">
            <div class="flex-row widget align-items-center align-items-stretch">
                <div class="py-4 col-8 rounded-right">
                    <div class="m-0 h4">{{ $campaign->unique_open_count }}</div>
                    <div class="text-uppercase">{{ __('Unique Opens') }}</div>
                </div>
                <div class="col-4 d-flex align-items-center justify-content-center rounded-left">
                    <em class="far fa-envelope-open fa-2x color-gray-400"></em>
                </div>
            </div>
        </div>

        <div class="mb-3 col-md-4 col-sm-6 mb-md-0">
            <div class="flex-row widget align-items-center align-items-stretch">
                <div class="py-4 col-8 rounded-right">
                    <div class="m-0 h4">{{ $campaign->total_open_count }}</div>
                    <div class="text-uppercase">{{ __('Total Opens') }}</div>
                </div>
                <div class="col-4 d-flex align-items-center justify-content-center rounded-left">
                    <em class="fas fa-envelope-open fa-2x color-gray-400"></em>
                </div>
            </div>
        </div>

        <div class="mb-3 col-md-4 col-sm-6 mb-md-0">
            <div class="flex-row widget align-items-center align-items-stretch">
                <div class="py-4 col-8 rounded-right">
                    <div class="m-0 h4">{{ $averageTimeToOpen }}</div>
                    <div class="text-uppercase">{{ __('Avg. Time To Open') }}</div>
                </div>
                <div class="col-4 d-flex align-items-center justify-content-center rounded-left">
                    <em class="far fa-clock fa-2x color-gray-400"></em>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-table table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                        <tr>
                            <th>{{ __('Subscriber') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Opened') }}</th>
                            <th>{{ __('Open Count') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td>
                                    <a href="{{ route('backend.subscribers.show', $message->subscriber_id) }}">{{ $message->recipient_email }}</a>
                                </td>
                                <td>{{ $message->subject }}</td>
                                <td>{{ ($message->opened_at) }}</td>
                                <td>{{ $message->open_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="empty-table-text">{{ __('There are no messages') }}</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


    @include('mail::backend.layouts.partials.pagination', ['records' => $messages])

@endsection
