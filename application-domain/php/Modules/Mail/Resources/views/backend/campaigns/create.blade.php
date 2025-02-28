
@extends("backend.layouts.app")
@section('title', __('Creaza campanie'))


@section('content')


    @if( ! $emailServices)
        <div class="callout callout-danger">
            <h4>{{ __('You haven\'t added any email service!') }}</h4>
            <p>{{ __('Before you can create a campaign, you must first') }} <a
                    href="{{ route('backend.email_services.create') }}">{{ __('add an email service') }}</a>.
            </p>
        </div>
    @else
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card">
                    <div class="text-white card-header">
                        {{ __('Create Campaign') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('backend.campaigns.store') }}" method="POST" class="form-horizontal">
                            @csrf
                            @include('mail::backend.campaigns.partials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
	@endif
@stop
