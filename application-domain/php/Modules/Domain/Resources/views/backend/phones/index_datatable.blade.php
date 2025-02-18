@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('breadcrumbs')
<x-backend.breadcrumbs>
    <x-backend.breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ __($module_title) }}</x-backend.breadcrumb-item>
</x-backend.breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">

        <x-backend.section-header>
            <i class="{{ $module_icon }}"></i> {{ __($module_title) }} <small class="text-muted">{{ __($module_action) }}</small>

            <x-slot name="subtitle">
                @lang(":module_name Management Dashboard", ['module_name'=>Str::title($module_name)])
            </x-slot>
            <x-slot name="toolbar">
                @can('add_'.$module_name)
                <x-buttons.create route='{{ route("backend.$module_name.create") }}' title="{{__('Create')}} {{ ucwords(Str::singular($module_name)) }}" />
                @endcan

                @can('restore_'.$module_name)
                <div class="btn-group">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href='{{ route("backend.$module_name.trashed") }}'>
                                <i class="fas fa-eye-slash"></i> @lang("View trash")
                            </a>
                        </li>
                        <!-- <li>
                            <hr class="dropdown-divider">
                        </li> -->
                    </ul>
                </div>
                @endcan
            </x-slot>
        </x-backend.section-header>



        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="mb-4 card">
                    <div class="card-body">
                        <div class="fs-4 fw-semibold">
                            32,158 modele
                        </div>
                        <div>
                            Telefoane
                        </div>
                        <div class="my-2 progress progress-thin">
                            <div
                                class="progress-bar bg-success"
                                role="progressbar"
                                style="width: 85%"
                                aria-valuenow="85"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <small class="text-medium-emphasis">
                            (TBA)
                        </small>
                    </div>
                </div>
            </div>
            <!-- /.col-->
            <div class="col-sm-6 col-lg-3">
                <div class="mb-4 card">
                    <div class="card-body">
                        <div class="fs-4 fw-semibold">
                           1000 brand-uri
                        </div>
                        <div>Brand-uri</div>
                        <div class="my-2 progress progress-thin">
                            <div
                                class="progress-bar bg-info"
                                role="progressbar"
                                style="width: 25%"
                                aria-valuenow="25"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <small class="text-medium-emphasis">
                            (TBA)
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route("backend.imei.index") }}">
                    <div class="mb-4 card">
                        <div class="card-body">
                            <div class="fs-4 fw-semibold">
                            22,577 ime-uri
                            </div>
                            <div>Coduri</div>
                            <div class="my-2 progress progress-thin">
                                <div
                                    class="progress-bar bg-info"
                                    role="progressbar"
                                    style="width: 25%"
                                    aria-valuenow="25"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                ></div>
                            </div>
                            <small class="text-medium-emphasis">
                                (TBA)
                            </small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="mb-4 card">
                    <div class="card-body">
                        <div class="fs-4 fw-semibold">
                           20 telefoane
                        </div>
                        <div>
                            modificate in ultima ora
                        </div>
                        <div class="my-2 progress progress-thin">
                            <div
                                class="progress-bar bg-info"
                                role="progressbar"
                                style="width: 25%"
                                aria-valuenow="25"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <small class="text-medium-emphasis">
                            (TBA)
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row-->


        <div class="mt-4 row">
            <div class="col">
                <table id="datatable" class="table table-bordered table-hover table-responsive-sm">
                    <thead>
                        <tr>

                            <th>
                                @lang("domain::text.name")
                            </th>
                            <th>
                                @lang("domain::text.gsmarena")
                            </th>
                            <th class="text-end">
                                @lang("domain::text.action")
                            </th>
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

                </div>
            </div>
            <div class="col-5">
                <div class="float-end">

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push ('after-styles')
<!-- DataTables Core and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push ('after-scripts')
<!-- DataTables Core and Extensions -->
<script type="module" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

<script type="module">
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("backend.$module_name.index_data") }}',
        columns: [
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'gsmarena',
                name: 'gsmarena'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
</script>
@endpush
