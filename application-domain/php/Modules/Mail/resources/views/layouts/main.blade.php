<div class="main-wrapper col p-0 min-vh-100">

    <div class="modal modal-left fade sidebar" id="sidebar-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable bg-purple-100 mh-100" role="document">
            <div class="modal-content border-0 rounded-0 mh-100">
                <div class="modal-body bg-purple-100 p-0">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="logo text-center mt-4">
                        <a href="{{ route('laravel-mail.dashboard') }}">
                            
					</a>
                    </div>

                    @include('laravel-mail::layouts.partials.sidebar')
                </div>
            </div>
        </div>
    </div>

    @include('laravel-mail::layouts.partials.header')


    <div class="container-fluid">

        @if( ! in_array(request()->route()->getName(), [
            'login',
            'register',
            'password.reset',
        ]))
            @include('laravel-mail::layouts.partials.errors')
        @endif

        @include('laravel-mail::layouts.partials.success')
        @include('laravel-mail::layouts.partials.warning')
        @include('laravel-mail::layouts.partials.error')

        @yield('content')
    </div>

</div>
