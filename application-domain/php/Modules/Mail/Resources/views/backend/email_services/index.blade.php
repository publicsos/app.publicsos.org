@extends("backend.layouts.app")

@section('title', __('Servicii și conexiuni'))

@section('content')
    <div class="container-fluid">
        <div class="mb-4 row">
            <div class="col-md-6">
                <a class="btn btn-primary" href="{{ route('backend.email-services.create') }}">
                    <i class="mr-2 fa fa-plus"></i> {{ __('Add new service') }}
                </a>
            </div>
            <div class="text-right col-md-6">
                <!-- Right-side actions can be added here -->
            </div>
        </div>

        <div class="shadow-sm card">
            <div class="card-header bg-dark">
                <h5 class="mb-0 card-title">{{ __('Communication Services') }}</h5>
            </div>
            <div class="p-0 card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Service') }}</th>
                                <th style="text-align: right" class="text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($emailServices as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ $service->type->name }}</td>
                                    <td colspan="100%" class="text-right" style="text-align: right">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('backend.email-services.test.create', $service->id) }}"
                                               class="btn btn-sm btn-outline-info" title="{{ __('Test') }}">
                                                <i class="mr-1 fa fa-check-circle"></i> {{ __('Test') }}
                                            </a>
                                            <a href="{{ route('backend.email-services.edit', $service->id) }}"
                                               class="btn btn-sm btn-outline-primary" title="{{ __('Edit') }}">
                                                <i class="mr-1 fa fa-edit"></i> {{ __('Editează') }}
                                            </a>
                                            <form action="{{ route('backend.email-services.destroy', $service->id) }}"
                                                  method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('{{ __('Are you sure you want to trash this email service') }}')">
                                                    <i class="mr-1 fa fa-trash"></i> {{ __('Trash') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center">
                                        <div class="empty-state">
                                            <i class="mb-3 fa fa-envelope-open-text fa-3x text-muted"></i>
                                            <p class="text-muted">{{ __("No connections found") }}</p>
                                            <a href="{{ route('backend.email-services.create') }}" class="btn btn-outline-primary">
                                                <i class="mr-1 fa fa-plus"></i> {{ __('Add a new connection') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
<script>
    // Add confirmation for delete actions
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('{{ __("Ești sigur că vrei să ștergi acest serviciu?") }}')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
