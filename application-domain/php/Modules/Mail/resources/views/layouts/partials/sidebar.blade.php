<div class="sidebar-inner">

    <ul class="mt-4 nav flex-column">
        <li class="nav-item {{ request()->routeIs('laravel-mail.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laravel-mail.dashboard') }}">
                <i class="mr-2 fa-fw fas fa-home"></i><span>{{ __('Dashboard') }}</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('*campaigns*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laravel-mail.campaigns.index') }}">
                <i class="mr-2 fa-fw fas fa-envelope"></i><span>{{ __('Campaigns') }}</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('*templates*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laravel-mail.templates.index') }}">
                <i class="mr-2 fa-fw fas fa-file-alt"></i><span>{{ __('Templates') }}</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('*subscribers*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laravel-mail.subscribers.index') }}">
                <i class="mr-2 fa-fw fas fa-user"></i><span>{{ __('Subscribers') }}</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('*messages*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laravel-mail.messages.index') }}">
                <i class="mr-2 fa-fw fas fa-paper-plane"></i><span>{{ __('Messages') }}</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('*email-services*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laravel-mail.email_services.index') }}">
                <i class="mr-2 fa-fw fas fa-envelope"></i><span>{{ __('Services') }}</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('*workflows*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laravel-mail.workflows.index') }}">
                <i class="mr-2 fa-fw fas fa-robot"></i><span>{{ __('Workflows') }}</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('*inbox*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laravel-mail.mailbox.folder', ['folder' => 'INBOX']) }}">
                <i class="mr-2 fa-fw fas fa-envelope"></i><span>{{ __('Inbox') }}</span>
            </a>
        </li>
        {!! \LaravelCompany\Mail\Facades\LaravelMail::sidebarHtmlContent() !!}

    </ul>
</div>
