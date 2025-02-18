@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('breadcrumbs')
    <x-backend.breadcrumbs>
        <x-backend.breadcrumb-item type="active"
            icon='{{ $module_icon }}'>{{ __($module_title) }}</x-backend.breadcrumb-item>
    </x-backend.breadcrumbs>
@endsection

@section('content')
    <div class="mb-10 card" style="margin-bottom: 2vh">
        <div class="card-body">
            <div class="row">
                <div class="mb-3 col-12 col-sm-4">
                    <div class="form-group">
                        <?php
                        $field_name = 'imei';
                        $field_lable = label_case($field_name);
                        $field_placeholder = $field_lable;
                        $required = 'required';
                        ?>
                        {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! field_required($required) !!}
                        {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'maxlength' => '255']) }}
                    </div>
                    <p>
                        This checks for an IMEI number against multiple free websites. please don't abuse.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-12 col-sm-4">
                    <button class="btn btn-primary btn-block" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <x-backend.section-header>
                <i class="{{ $module_icon }}"></i> {{ __($module_title) }} <small
                    class="text-muted">{{ __($module_action) }}</small>

                <x-slot name="subtitle">
                    @lang(':module_name Management Dashboard', ['module_name' => Str::title($module_name)])
                </x-slot>
                <x-slot name="toolbar">
                    @can('add_' . $module_name)
                        <x-buttons.create route='{{ route("backend.$module_name.create") }}'
                            title="{{ __('Create') }} {{ ucwords(Str::singular($module_name)) }}" />
                    @endcan

                    @can('restore_' . $module_name)
                        <div class="btn-group">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-coreui-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href='{{ route("backend.$module_name.trashed") }}'>
                                        <i class="fas fa-eye-slash"></i> @lang('View trash')
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endcan
                </x-slot>
            </x-backend.section-header>

            <div class="mt-4 row">
                <div class="col">
                    <table id="datatable" class="table table-bordered table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>@lang('domain::text.tac')</th>
                                <th>@lang('domain::text.model')</th>
                                <th>@lang('domain::text.date')</th>
                                <th>@lang('domain::text.contributor')</th>
                                <th>@lang('domain::text.comment')</th>
                                <th class="text-end">@lang('domain::text.action')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-7">
                    <div class="float-left">
                        <!-- Additional footer content for left side -->
                    </div>
                </div>
                <div class="col-5">
                    <div class="float-end">
                        <!-- Additional footer content for right side -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-styles')
    <!-- DataTables Core and Extensions -->
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    <!-- DataTables Core and Extensions -->
    <script type="module" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

    <script type="module">
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route("backend.$module_name.index_data") }}',
                columns: [{
                        data: 'tac',
                        name: 'tac'
                    },
                    {
                        data: 'model',
                        name: 'model'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        render: function(data) {
                            return moment(data).format('DD-MM-YYYY');
                        }
                    },
                    {
                        data: 'contributor',
                        name: 'contributor'
                    },
                    {
                        data: 'comment',
                        name: 'comment',
                        render: function(data) {
                            return data ? data : '<em class="text-muted">No comment</em>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-end'
                    }
                ],
                order: [
                    [2, 'desc']
                ], // Sort by date by default
                pageLength: 25,
                language: {
                    processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });
        });
    </script>
@endpush
