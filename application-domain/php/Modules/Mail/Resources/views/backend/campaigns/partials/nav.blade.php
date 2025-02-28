<ul class="mb-4 nav nav-pills">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('backend.campaigns.index') ? 'active'  : '' }}"
           href="{{ route('backend.campaigns.index') }}">{{ __('Draft') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('backend.campaigns.sent') ? 'active'  : '' }}"
           href="{{ route('backend.campaigns.sent') }}">{{ __('Sent') }}</a>
    </li>
</ul>
