
@extends("backend.layouts.app")
@section('title', __('Delete Campaign'))

@section('heading')
    @lang('Delete Campaign') - {{ $campaign->name }}
@endsection

@section('content')

    @component('mail::backend.layouts.partials.actions')
        @slot('right')
            <a class="btn btn-primary btn-md btn-flat" href="{{ route('backend.campaigns.create') }}">
                <i class="mr-1 fa fa-plus"></i> {{ __('Create Campaign') }}
            </a>
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-header card-header-accent">
            <div class="text-white card-header-inner">
                {{ __('Confirm Delete') }}
            </div>
        </div>
        <div class="card-body">
            <p class="text-white">
                {!! __('Are you sure that you want to delete the <b>:name</b> campaign?', ['name' => $campaign->name]) !!}
            </p>
            <form action="{{ route('backend.campaigns.destroy', $campaign->id) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value="{{ $campaign->id }}">
                <a href="{{ route('backend.campaigns.index') }}" class="btn btn-md btn-light">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-md btn-danger">{{ __('DELETE') }}</button>
            </form>
        </div>
    </div>

@endsection
