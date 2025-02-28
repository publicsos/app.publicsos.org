<ul class="mb-3 nav nav-pills">
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->named('backend.campaigns.reports.index') ? 'active'  : '' }}"
           href="{{ route('backend.campaigns.reports.index', $campaign->id) }}">{{ __('Overview') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->named('backend.campaigns.reports.recipients') ? 'active'  : '' }}"
           href="{{ route('backend.campaigns.reports.recipients', $campaign->id) }}">{{ __('Recipients') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->named('backend.campaigns.reports.opens') ? 'active'  : '' }}"
           href="{{ route('backend.campaigns.reports.opens', $campaign->id) }}">{{ __('Opens') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->named('backend.campaigns.reports.clicks') ? 'active'  : '' }}"
           href="{{ route('backend.campaigns.reports.clicks', $campaign->id) }}">{{ __('Clicks') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->named('backend.campaigns.reports.bounces') ? 'active'  : '' }}"
           href="{{ route('backend.campaigns.reports.bounces', $campaign->id) }}">{{ __('Bounces') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->route()->named('backend.campaigns.reports.unsubscribes') ? 'active'  : '' }}"
           href="{{ route('backend.campaigns.reports.unsubscribes', $campaign->id) }}">{{ __('Unsubscribes') }}</a>
    </li>
</ul>
