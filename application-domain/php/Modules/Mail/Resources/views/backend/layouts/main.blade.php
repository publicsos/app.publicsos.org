<div class="p-0 main-wrapper col min-vh-100">

    <div class="modal modal-left fade sidebar" id="sidebar-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="bg-purple-100 modal-dialog modal-dialog-scrollable mh-100" role="document">
            <div class="border-0 modal-content rounded-0 mh-100">
                <div class="p-0 bg-purple-100 modal-body">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="mt-4 text-center logo">
                        <a href="{{ route('backend.dashboard') }}">

					</a>
                    </div>

                    @include('mail::backend.layouts.partials.sidebar')
                </div>
            </div>
        </div>
    </div>

    @include('mail::backend.layouts.partials.header')


    <div class="container-fluid">

        @if( ! in_array(request()->route()->getName(), [
            'login',
            'register',
            'password.reset',
        ]))
            @include('mail::backend.layouts.partials.errors')
        @endif

        @include('mail::backend.layouts.partials.success')
        @include('mail::backend.layouts.partials.warning')
        @include('mail::backend.layouts.partials.error')

        @yield('content')
    </div>

</div>
