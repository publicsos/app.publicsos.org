<ul class="mb-4 nav nav-pills">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('backend.messages.index') ? 'active'  : '' }}"
           href="{{ route('backend.messages.index') }}">{{ __('Sent') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('backend.messages.draft') ? 'active'  : '' }}"
           href="{{ route('backend.messages.draft') }}">{{ __('Draft') }}</a>
    </li>
</ul>
