@extends("backend.layouts.app")

@section('title', __('Template-uri'))


@section('content')


    @component('mail::backend.layouts.partials.actions')
    @slot('left')
    <form method="GET" action="{{ route('backend.templates.index') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cauta template-uri..." />
        <button type="submit" class="btn btn-primary btn-md btn-flat"> <i class="mr-1 fa fa-list"></i> Cauta</button>
    </form>

        @endslot
        @slot('right')
            <a class="btn btn-primary btn-md btn-flat" href="{{ route('backend.templates.create') }}">
                <i class="mr-1 fa fa-plus"></i> {{ __('Adauga Template') }}
            </a>

        @endslot
    @endcomponent

    @include('mail::backend.templates.partials.grid')

@endsection
