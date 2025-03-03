@extends('backend.layouts.app')

@section('title')Import documents from official sources @endsection

@section('breadcrumbs')
<x-backend.breadcrumbs>
    <x-backend.breadcrumb-item route='{{route("backend.documents.index")}}'>
      Import Document
    </x-backend.breadcrumb-item>
    <x-backend.breadcrumb-item type="active">Import</x-backend.breadcrumb-item>
</x-backend.breadcrumbs>
@endsection

@section('content')

<form action="{{route("backend.documents.import-process")}}"  method="POST">
    @csrf

    <div class="mb-3">
        <label for="session_id" class="form-label">Session ID:</label>
        <input type="text" class="form-control" name="session_id" id="session_id">
    </div>

    <div class="mb-3">
        <label for="document_id" class="form-label">Document ID:</label>
        <input type="text" class="form-control" name="document_id" id="document_id">
    </div>



    <button type="submit" class="btn btn-primary">Import Document</button>
</form>

@endsection
