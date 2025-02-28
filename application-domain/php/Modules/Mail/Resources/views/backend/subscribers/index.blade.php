@extends("backend.layouts.app")

@section('title', __('Subscriber Management'))

@section('content')
<div class="container-fluid">
    <!-- Header Section with Stats and Actions -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h2 class="mb-3 mb-md-0">{{ __('Subscriber Management') }}
                    <span class="text-white badge bg-primary">{{ $subscribers->total() }}</span>
                </h2>
                <div class="flex-wrap gap-2 d-flex">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="importExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-exchange-alt me-1"></i> {{ __('Import/Export') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="importExportDropdown">
                            <li>
                                <a href="{{ route('backend.subscribers.import') }}" class="dropdown-item">
                                    <i class="fa fa-upload text-success me-2"></i> {{ __('Import Subscribers') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('backend.subscribers.export') }}" class="dropdown-item">
                                    <i class="fa fa-download text-primary me-2"></i> {{ __('Export Subscribers') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a class="btn btn-outline-info" href="{{ route('backend.tags.index') }}">
                        <i class="fa fa-tag me-1"></i> {{ __('Manage Tags') }}
                    </a>
                    <a class="btn btn-success" href="{{ route('backend.subscribers.create') }}">
                        <i class="fa fa-plus me-1"></i> {{ __('Add Subscriber') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="mb-4 shadow-sm card">
        <div class="card-header bg-dark">
            <h5 class="mb-0"><i class="fa fa-filter me-2"></i>{{ __('Search & Filter') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('backend.subscribers.index') }}" method="GET" class="row g-3">
                <div class="col-md-4 col-lg-3">
                    <label for="search" class="form-label">{{ __('Search') }}</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input id="search" class="form-control" name="name" type="text"
                            value="{{ request('name') }}" placeholder="{{ __('Email or name...') }}">
                    </div>
                </div>

                <div class="col-md-4 col-lg-3">
                    <label for="status" class="form-label">{{ __('Status') }}</label>
                    <select id="status" name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('All Statuses') }}</option>
                        <option value="subscribed" {{ request('status') == 'subscribed' ? 'selected' : '' }}>{{ __('Subscribed') }}</option>
                        <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>{{ __('Unsubscribed') }}</option>
                    </select>
                </div>

                @if(count($tags))
                <div class="col-md-4 col-lg-3">
                    <label for="tags" class="form-label">{{ __('Tags') }}</label>
                    <select id="tags"  class="form-select"
                        name="tags[]"  data-width="100%">
                        @foreach($tags as $tagId => $tagName)
                            <option value="{{ $tagId }}" @if(in_array($tagId, request()->get('tags') ?? [])) selected @endif>{{ $tagName }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="gap-2 col-md-12 d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search me-1"></i> {{ __('Search') }}
                    </button>

                    @if(request()->anyFilled(['name', 'status', 'tags']))
                    <a href="{{ route('backend.subscribers.index') }}" class="btn btn-light">
                        <i class="fa fa-times me-1"></i> {{ __('Clear Filters') }}
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Subscribers List Card -->
    <div class="shadow-sm card">
        <div class="text-white card-header bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-users me-2"></i>{{ __('Subscribers') }}</h5>
            <span class="badge bg-light text-primary">{{ $subscribers->total() }} {{ __('total') }}</span>
        </div>
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">{{ __('Avatar') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Tags') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th width="100">{{ __('Status') }}</th>
                            <th width="180">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscribers as $subscriber)
                            <tr>
                                <td class="text-center">
                                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($subscriber->email))) }}?s=40&d=identicon"
                                        alt="{{ $subscriber->full_name }}" class="rounded-circle" width="40" height="40">
                                </td>
                                <td>
                                    <a href="{{ route('backend.subscribers.show', $subscriber->id) }}" class="text-decoration-none fw-medium text-primary">
                                        {{ $subscriber->email }}
                                    </a>
                                </td>
                                <td>{{ $subscriber->full_name ?: '-' }}</td>
                                <td>
                                    @forelse($subscriber->tags as $tag)
                                        <span class="mb-1 text-white badge bg-secondary me-1">{{ $tag->name }}</span>
                                    @empty
                                        <span class="text-muted fst-italic">{{ __('No tags') }}</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $subscriber->created_at }}">
                                        {{ $subscriber->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    @if($subscriber->unsubscribed_at)
                                        <span class="text-white badge bg-danger">{{ __('Unsubscribed') }}</span>
                                    @else
                                        <span class="text-white badge bg-success">{{ __('Active') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="gap-1 d-flex">
                                        <a href="{{ route('backend.subscribers.show', $subscriber->id) }}"
                                            class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="{{ __('View Details') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a href="{{ route('backend.subscribers.edit', $subscriber->id) }}"
                                            class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        @if(empty($subscriber->meta['scan_id']))
                                            <a href="{{ route('backend.subscribers.enrich', $subscriber->id) }}"
                                                class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="{{ __('Enrich Data') }}">
                                                <i class="fa fa-magic"></i>
                                            </a>
                                        @endif

                                        <button type="button" class="btn btn-sm btn-outline-danger delete-subscriber"
                                                data-id="{{ $subscriber->id }}" data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $subscriber->id }}"
                                        action="{{ route('backend.subscribers.destroy', $subscriber->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center">
                                    <div class="empty-state">
                                        <div class="p-4 mb-3 empty-icon bg-light rounded-circle">
                                            <i class="fa fa-users fa-3x text-muted"></i>
                                        </div>
                                        <h5>{{ __('No subscribers found') }}</h5>
                                        <p class="text-muted">{{ __('No subscribers found matching your criteria') }}</p>
                                        @if(request()->anyFilled(['name', 'status', 'tags']))
                                            <a href="{{ route('backend.subscribers.index') }}" class="btn btn-outline-primary">
                                                <i class="fa fa-times me-1"></i> {{ __('Clear filters') }}
                                            </a>
                                        @else
                                            <a href="{{ route('backend.subscribers.import') }}" class="btn btn-outline-success">
                                                <i class="fa fa-upload me-1"></i> {{ __('Import subscribers') }}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($subscribers->hasPages())
            <div class="card-footer">
                {{ $subscribers->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="text-white modal-header bg-danger">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirm Deletion') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <i class="fa fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <p>{{ __('Are you sure you want to permanently delete this subscriber and all associated data?') }}</p>
                <p class="text-danger"><small>{{ __('This action cannot be undone.') }}</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fa fa-trash me-1"></i>{{ __('Delete Subscriber') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<style>
    /* Custom styling */
    .badge {
        font-weight: 500;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .empty-icon {
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    /* Ensure pagination styling is consistent */
    .pagination {
        justify-content: center;
        margin-bottom: 0;
    }

    /* Add gap between buttons */
    .gap-2 {
        gap: 0.5rem;
    }

    /* Improved table hover effect */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    /* Bootstrap 5 style adjustments */
    .fw-medium {
        font-weight: 500;
    }

    .fst-italic {
        font-style: italic;
    }

    .me-1 {
        margin-right: 0.25rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }
</style>
@endpush

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle delete subscriber confirmation
        let subscriberId = null;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));

        document.querySelectorAll('.delete-subscriber').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                subscriberId = this.getAttribute('data-id');
                deleteModal.show();
            });
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (subscriberId) {
                document.getElementById(`delete-form-${subscriberId}`).submit();
            }
            deleteModal.hide();
        });

        // Initialize selectpicker with explicit refresh
        $('.selectpicker').selectpicker({
            style: 'btn-outline-secondary',
            size: 5
        });

        // Ensure the selectpicker is properly refreshed
        setTimeout(function() {
            $('.selectpicker').selectpicker('refresh');
        }, 100);
    });
</script>
@endpush
