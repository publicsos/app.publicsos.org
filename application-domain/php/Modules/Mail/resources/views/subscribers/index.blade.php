@extends('laravel-mail::layouts.app')

@section('title', __('Subscribers'))

@section('heading')
    {{ __('Subscribers') }}
@endsection

@section('content')

    @component('laravel-mail::layouts.partials.actions')

        @slot('left')
            <form action="{{ route('laravel-mail.subscribers.index') }}" method="GET" class="mb-3 form-inline mb-md-0">
                <input class="form-control form-control-sm" name="name" type="text" value="{{ request('name') }}"
                        style="margin-right:10px;"
                       placeholder="{{ __('Search...') }}">

                <div class="mr-2">
                    <select name="status" class="selectpicker form-control form-control-sm">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                        <option
                            value="subscribed" {{ request('status') == 'subscribed' ? 'selected' : '' }}>{{ __('Subscribed') }}</option>
                        <option
                            value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>{{ __('Unsubscribed') }}</option>
                    </select>
                </div>

                @if(count($tags))
                    <div class="mr-2">
                        <select  data-live-search="true" data-style="btn-light" class="selectpicker show-menu-arrow form-control form-control-sm" name="tags[]" data-width="auto">
                            @foreach($tags as $tagId => $tagName)
                                <option value="{{ $tagId }}" @if(in_array($tagId, request()->get('tags') ?? [])) selected @endif>{{ $tagName }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <button type="submit" class="btn btn-light btn-md">{{ __('Search') }}</button>

                @if(request()->anyFilled(['name', 'status']))
                    <a href="{{ route('laravel-mail.subscribers.index') }}"
                       class="btn btn-md btn-light">{{ __('Clear') }}</a>
                @endif
            </form>
        @endslot

        @slot('right')
            <div class="mr-2 btn-group">
                <button class="btn btn-md btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="fa fa-bars color-gray-400"></i>
                </button>
                <div class="dropdown-menu bg-dark-gray">
                    <a href="{{ route('laravel-mail.subscribers.import') }}" class="dropdown-item">
                        <i class="mr-2 fa fa-upload color-gray-400"></i> {{ __('Import Subscribers') }}
                    </a>
                    <a href="{{ route('laravel-mail.subscribers.export') }}" class="dropdown-item">
                        <i class="mr-2 fa fa-download color-gray-400"></i> {{ __('Export Subscribers') }}
                    </a>

                </div>
            </div>
            <a class="mr-2 btn btn-light btn-md" href="{{ route('laravel-mail.tags.index') }}">
                <i class="mr-1 fa fa-tag color-gray-400"></i> {{ __('Validate Subscribers') }}
            </a>
            <a class="mr-2 btn btn-light btn-md" href="{{ route('laravel-mail.tags.index') }}">
                <i class="mr-1 fa fa-tag color-gray-400"></i> {{ __('Tags') }}
            </a>
            <a class="btn btn-primary btn-md btn-flat" href="{{ route('laravel-mail.subscribers.create') }}">
                <i class="mr-1 fa fa-plus"></i> {{ __('New Subscriber') }}
            </a>
            <a class="btn btn-success btn-md btn-flat" href="{{ route('laravel-mail.subscribers.import-new') }}">
                <i class="mr-1 fa fa-file"></i> {{ __('Csv Import') }}
            </a>
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-table table-responsive">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>{{ __('Gravatar') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Tags') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $subscriber)
                        <tr>
                            <td>
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($subscriber->email))) }}?s=40&d=identicon" alt="Gravatar" class="rounded-circle">
                            </td>
                            <td>
                                <a href="{{ route('laravel-mail.subscribers.show', $subscriber->id) }}">
                                    {{ $subscriber->email }}
                                </a>
                            </td>
                            <td>{{ $subscriber->full_name }}</td>
                            <td>
                                @forelse($subscriber->tags as $tag)
                                    <span class="badge badge-dark">{{ $tag->name }}</span>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td>
                                <span title="{{ $subscriber->created_at }}">{{ $subscriber->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                @if($subscriber->unsubscribed_at)
                                    <span class="badge badge-danger">{{ __('Unsubscribed') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('Subscribed') }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('laravel-mail.subscribers.edit', $subscriber->id) }}"
                                    class="btn btn-xs btn-light">{{ __('Edit') }}</a>

                                @if(empty($subscriber->meta['scan_id']))
                                    <a href="{{ route('laravel-mail.subscribers.enrich', $subscriber->id) }}"
                                        class="btn btn-xs btn-success">{{ __('Enrich') }}</a>
                                @endif

                                <form action="{{ route('laravel-mail.subscribers.destroy', $subscriber->id) }}" class="pull-right" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-xs btn-danger delete-subscriber">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <p class="empty-table-text">{{ __('No Subscribers Found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('laravel-mail::layouts.partials.pagination', ['records' => $subscribers])

    <script>
        let subscribers = document.getElementsByClassName('delete-subscriber');

        Array.from(subscribers).forEach((element) => {
            element.addEventListener('click', (event) => {
                event.preventDefault();

                let confirmDelete = confirm('Are you sure you want to permanently delete this subscriber and all associated data?');

                if (confirmDelete) {
                    element.closest('form').submit();
                }
            });
        });
    </script>

@endsection

@push('css')
<style>
    .page-item.active .page-link {
  color: #fff !important;
  background: #281029 !important;
}
.page-item .page-link {
  color: #fff !important;
  background: #4b1e4e !important;

}
.dropdown-menu{

    background: #281029 !important;
}
.dropdown-menu .active{
    background: #281029 !important;
    padding-bottom:0px !important;
}
.dropdown-menu .show{
    background: #281029 !important;
    padding-bottom:0px !important;
}

.bs-searchbox{
    background: #281029!important;
    padding-bottom:0px !important;
}

</style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.12/dist/css/bootstrap-select.min.css">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.12/dist/js/bootstrap-select.min.js"></script>
@endpush
