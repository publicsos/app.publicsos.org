@extends("backend.layouts.app")

@section('title', __('New Tag'))

@section('heading')
    {{ __('Tags') }}
@stop

@section('content')

    @component('mail::backend.layouts.partials.card')

        @slot('cardHeader', __('Create Tag'))

        @slot('cardBody')
            <form action="{{ route('backend.tags.store') }}" method="POST" class="form-horizontal">
                @csrf

                @include('mail::backend.tags.partials.form')

                <div class="form-group">
                    <label class="text-white">{{ __('Select Subscribers') }}</label>

                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>{{ __('First Name') }}</th>
                                <th>{{ __('Last Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{  __('Status')  }}</th>
                                <th>{{ __('Created At') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $subscriber)
                            <tr class="clickable-row" data-id="{{ $subscriber->id }}">
                                <td>
                                    <input
                                        class="subscriber-checkbox"
                                        type="checkbox"
                                        name="subscribers[]"
                                        value="{{ $subscriber->id }}"
                                        id="subscriber-{{ $subscriber->id }}">
                                </td>
                                <td>{{ $subscriber->first_name }}</td>
                                <td>{{ $subscriber->last_name }}</td>
                                <td>{{ $subscriber->email }}</td>
                                <td>
                                    @php
                                        $status = 'inactive'; // Default status
                                        $badgeClass = 'bg-danger'; // Default badge color
                                        $meta = $subscriber->meta ?? []; // Handle null or non-existent meta

                                        // Check if 'valid' exists and is true
                                        if (isset($meta['valid']) && $meta['valid'] === true) {
                                            $status = 'active';
                                            $badgeClass = 'bg-success';
                                        }

                                        // Optionally, check for 'scan_id' in meta, if it exists
                                        if (isset($meta['scan_id'])) {
                                            $status .= ' (Scan ID: ' . $meta['scan_id'] . ')';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>{{ $subscriber->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <x-submit-button :label="__('Save')" />
            </form>
        @endSlot
    @endcomponent

@stop

@push('after-styles')
<style>
   .clickable-row {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    // When a row is clicked, toggle the checkbox selection
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function() {
            const checkbox = this.querySelector('.subscriber-checkbox');
            checkbox.checked = !checkbox.checked;
        });
    });

    // Select/Deselect all checkboxes when the 'select-all' checkbox is clicked
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.subscriber-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endpush
