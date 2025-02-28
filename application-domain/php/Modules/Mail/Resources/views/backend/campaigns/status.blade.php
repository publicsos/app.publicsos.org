
@extends("backend.layouts.app")

@section('title', __('Status campanie'))



@section('content')


<div class="container-fluid">
<div class="card">
            <div class="card-header card-header-accent">
                <div class="text-white card-header-inner">
                    {{ __('Campania ta este ') }} <strong>{{ strtolower($campaign->status->name) }}</strong>
                </div>
            </div>
            <div class="text-white card-body">
                @if ($campaign->queued)
                    Your campaign is queued and will be sent out soon.
                @elseif ($campaign->cancelled)
                    Your campaign was cancelled.
                @else
                    <i class="fas fa-cog fa-spin"></i>
                    {{ $campaignStats[$campaign->id]['counts']['sent'] }} out of {{ $campaignStats[$campaign->id]['counts']['total'] }} messages sent.
                @endif
            </div>
        </div>
</div>

@stop
