@extends("backend.layouts.app")

@section('title', __('Etichete'))


@section('content')
    @component('mail::backend.layouts.partials.actions')
        @slot('left')
        <a class="btn btn-primary btn-md btn-flat" href="{{ route('backend.tags.create') }}">
            <i class="fa fa-plus"></i> {{ __('New Tag') }}
        </a>
        @endslot

    @endcomponent

    <div class="card">
        <div class="card-table">
            <table class="table table-dark">
                <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Subscribers') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($tags as $tag)
                    <tr>
                        <td>
                            <a href="{{ route('backend.tags.edit', $tag->id) }}">
                                {{ $tag->name }}
                            </a>
                        </td>
                        <td>{{ $tag->subscribers_count }}</td>
                        <td>
                            @include('mail::backend.tags.partials.actions')
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <p class="empty-table-text">{{ __('You have not created any tags.') }}</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('mail::backend.layouts.partials.pagination', ['records' => $tags])

@endsection
