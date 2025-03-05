@extends('backend.layouts.app')

@section('title', 'Import Documents from Official Sources')

@section('breadcrumbs')
    <x-backend.breadcrumbs>
        <x-backend.breadcrumb-item route="{{ route('backend.documents.index') }}">
            Documents
        </x-backend.breadcrumb-item>
        <x-backend.breadcrumb-item type="active">Import</x-backend.breadcrumb-item>
    </x-backend.breadcrumbs>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h2>Import Documents from Monitorul Official Sources</h2>



            <form action="{{ route('backend.documents.import-process') }}" method="POST">
                @csrf
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="session_id" class="form-label">The php Session ID:</label>
                        <input type="text" class="form-control" name="session_id" id="session_id">
                    </div>

                    <div class="col-md-6">
                        <label for="date" class="form-label">Date</label>

                        <input type="date" class="form-control" name="date" id="date">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (isset($documents) && $documents->count() > 0)
        <div class="mt-4 card">
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_documents[]" value="{{ $loop->index }}"
                                        class="form-check-input document-checkbox">
                                </td>
                                <td>{{ $document->title }}</td>
                                <td>{{ $document->status }}</td>
                                <td>
                                    <a href="{{ $document->source }}" target="_blank" class="text-primary">
                                        {{ $document->source }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button id="process-selected" class="mt-3 btn btn-success">Process Selected</button>
            </div>
        </div>

        @push('after-scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const selectAllCheckbox = document.getElementById('select-all');
                    const documentCheckboxes = document.querySelectorAll('.document-checkbox');
                    const processSelectedButton = document.getElementById('process-selected');

                    selectAllCheckbox.addEventListener('change', function() {
                        documentCheckboxes.forEach(checkbox => {
                            checkbox.checked = selectAllCheckbox.checked;
                        });
                    });

                    documentCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            selectAllCheckbox.checked =
                                Array.from(documentCheckboxes).every(cb => cb.checked);
                        });
                    });

                    processSelectedButton.addEventListener('click', function() {
                        const selectedIndices = [];
                        documentCheckboxes.forEach((checkbox, index) => {
                            if (checkbox.checked) {
                                selectedIndices.push(index);
                            }
                        });

                        if (selectedIndices.length > 0) {
                            const documents = @json($documents);
                            const selectedDocuments = selectedIndices.map(index => documents[index]);

                            fetch('{{ route('backend.documents.process-selected') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(selectedDocuments)
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Handle the response from the endpoint
                                console.log(data);
                                alert("Selected documents processed successfully!");
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert("Error processing selected documents.");
                            });
                        } else {
                            alert('Please select documents to process.');
                        }
                    });
                });
            </script>
        @endpush
    @endif
@endsection
