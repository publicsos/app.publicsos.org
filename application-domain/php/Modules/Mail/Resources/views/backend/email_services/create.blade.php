@extends("backend.layouts.app")

@section('content')
    @component('mail::backend.layouts.partials.card')
        @slot('cardHeader', __('Creaza un nou serviciu'))
        @slot('cardBody')
            <form action="{{ route('backend.email-services.store') }}" method="POST" class="form-horizontal">
                @csrf
                <x-text-field name="name" :label="__('Numele Serviciului')" />
                <x-select-field name="type_id" :label="__('Tipul Serviciului')" :options="$emailServiceTypes" />
                <div id="services-fields"></div>
                <x-submit-button :label="__('Save')" />
            </form>
        @endSlot
    @endcomponent
@stop

@push('after-scripts')
    <script>
        $(document).ready(function () {
            console.log('Document ready, initializing email service form...');
            const baseUrl = '{{ route('backend.email-services.ajax', ':id') }}';

            function createFields(serviceTypeId) {
                console.log('Creating fields for service type ID:', serviceTypeId);

                if (!serviceTypeId) {
                    console.warn('No service type ID provided, skipping field creation');
                    return;
                }

                let url = baseUrl.replace(':id', serviceTypeId);
                console.log('Fetching fields from URL:', url);

                $.get(url)
                    .done(function (result) {
                        console.log('Successfully received field data:', result);
                        $('#services-fields').html(result.view);
                        console.log('Fields inserted into DOM');
                    })
                    .fail(function (xhr, status, error) {
                        console.error('Error fetching service fields:', error);
                        console.error('Status:', status);
                        console.error('Response:', xhr.responseText);
                        $('#services-fields').html('<div class="alert alert-danger">Error loading service fields. Please try again.</div>');
                    });
            }

            // Initialize fields on page load
            const $typeSelect = $('select[name="type_id"]');
            console.log('Type select element:', $typeSelect.length ? 'Found' : 'Not found');

            let initialTypeId = $typeSelect.val();
            console.log('Initial type ID:', initialTypeId);

            // Check if we have a valid type ID to start with
            if (initialTypeId) {
                createFields(initialTypeId);
            } else {
                console.warn('No initial type ID selected, waiting for user selection');
            }

            // Handle change event with better selector targeting
            $(document).on('change', 'select[name="type_id"]', function() {
                console.log('Type changed to:', this.value);
                createFields(this.value);
            });
        });
    </script>
@endpush
