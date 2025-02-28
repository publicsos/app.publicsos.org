@extends("backend.layouts.app")
@section('title', __("Edit Tag"))

@section('heading')
    {{ __('Tags') }}
@stop

@section('content')

    @component('mail::backend.layouts.partials.card')
        @slot('cardHeader', __('Edit Tag'))

        @slot('cardBody')
            <form action="{{ route('backend.tags.update', $tag->id) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')

                @include('mail::backend.tags.partials.form')

                <x-submit-button :label="__('Save')" />
            </form>
        @endSlot
    @endcomponent

@stop
