
@extends("backend.layouts.app")
@section('title', __('Campaigns'))



@section('content')



    @component('mail::backend.layouts.partials.actions')
        @slot('left')
            @include('mail::backend.campaigns.partials.nav')
        @endslot
        @slot('right')
            <a class="btn btn-success btn-md btn-flat" href="{{ route('backend.campaigns.create') }}">
                <i class="mr-1 fa fa-plus"></i> {{ __('Create campaign') }}
            </a>
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-table table-responsive">
            <table class="table table-dark">
                <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    @if (request()->routeIs('backend.campaigns.sent'))
                        <th>{{ __('Sent') }}</th>
                        <th>{{ __('Opened') }}</th>
                        <th>{{ __('Clicked') }}</th>
                    @endif
                    <th>{{ __('Created') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th style="text-align: right">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            @if ($campaign->draft)
                                <a href="{{ route('backend.campaigns.edit', $campaign->id) }}">{{ $campaign->name }}</a>
                            @elseif($campaign->sent)
                                <a href="{{ route('backend.campaigns.reports.index', $campaign->id) }}">{{ $campaign->name }}</a>
                            @else
                                <a href="{{ route('backend.campaigns.status', $campaign->id) }}">{{ $campaign->name }}</a>
                            @endif
                        </td>
                        @if (request()->routeIs('backend.campaigns.sent'))
                            <td>{{ $campaignStats[$campaign->id]['counts']['sent'] }}</td>
                            <td>{{ number_format($campaignStats[$campaign->id]['ratios']['open'] * 100, 1) . '%' }}</td>
                            <td>
                                {{ number_format($campaignStats[$campaign->id]['ratios']['click'] * 100, 1) . '%' }}
                            </td>
                        @endif
                        <td><span title="{{ $campaign->created_at }}">{{ $campaign->created_at->diffForHumans() }}</span></td>
                        <td>
                            @include('mail::backend.campaigns.partials.status')
                        </td>
                        <td colspan="100%">
                            <div style="display: flex; flex-wrap: wrap; gap: 5px;float:right"> @if ($campaign->draft)
                                    <a href="{{ route('backend.campaigns.edit', $campaign->id) }}" class="btn btn-sm btn-primary">
                                        {{ __('Edit') }}
                                    </a>
                                @else
                                    <a href="{{ route('backend.campaigns.reports.index', $campaign->id) }}" class="btn btn-sm btn-info">
                                        {{ __('View Report') }}
                                    </a>
                                @endif

                                <a href="{{ route('backend.campaigns.duplicate', $campaign->id) }}" class="btn btn-sm btn-secondary">
                                    {{ __('Duplicate') }}
                                </a>

                                @if($campaign->canBeCancelled())
                                    <a href="{{ route('backend.campaigns.confirm-cancel', $campaign->id) }}" class="btn btn-sm btn-warning">
                                        {{ __('Cancel') }}
                                    </a>
                                @endif

                                @if ($campaign->draft)
                                    <a href="{{ route('backend.campaigns.destroy.confirm', $campaign->id) }}" class="btn btn-sm btn-danger">
                                        {{ __('Delete') }}
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <p class="empty-table-text">
                                @if (request()->routeIs('backend.campaigns.index'))
                                    {{ __('You do not have any draft campaigns.') }}
                                @else
                                    {{ __('You do not have any sent campaigns.') }}
                                @endif
                            </p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('mail::backend.layouts.partials.pagination', ['records' => $campaigns])

@endsection
