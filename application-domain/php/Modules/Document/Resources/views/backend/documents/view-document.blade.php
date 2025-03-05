@extends('backend.layouts.app')

@section('title') View Document {{ $document->title}} @endsection

@section('breadcrumbs')
<x-backend.breadcrumbs>
    <x-backend.breadcrumb-item route='{{route("backend.documents.index")}}' icon='fa-solid fa-home'>
       Documents
    </x-backend.breadcrumb-item>
    <x-backend.breadcrumb-item type="active">
        View
    </x-backend.breadcrumb-item>
</x-backend.breadcrumbs>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            @php
            $fileLocation = str_replace("/var/www/php/storage/app/public/", "/storage/", $document->source);
            @endphp
            <iframe src="{{ $fileLocation }}" width="100%" height="800"></iframe>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Document Details</h3>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Document
                        <p class="card-text">
                            {{ $document->title }}
                        </p>
                    </h5>
                    <h5 class="card-title">Issue Date
                        <p class="card-text">
                            {{ $document->date }}
                        </p>
                    </h5>

                    <h5 class="card-title">Status
                        <p class="card-text">
                            {{ $document->status }}
                        </p>
                    </h5>
                </div>
                <div class="card-footer text-end">
                    Last update: {{ $document->updated_at }}
                </div>
            </div>
        </div>
    </div>

    @if($document->entities && $document->entities->count() > 0)
    <div class="mt-4 container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-3 text-center">Entities</h2>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($document->entities as $entity)
                                <tr>
                                    <td>{{ $entity->type }}</td>
                                    <td>{{ $entity->value }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection