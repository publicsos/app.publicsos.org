
@extends("backend.layouts.app")
@section('title', __('Edit Campaign'))

@section('heading')
    {{ __('Edit Campaign') }}
@stop

@section('content')
@searchableDropdownStyles
@searchableDropdownScripts

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="text-white card-header">
                        {{ __('Edit Campaign') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('backend.campaigns.update', $campaign->id) }}" method="POST" class="form-horizontal">



                            @csrf
                            @method('PUT')
                            @include('mail::backend.campaigns.partials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
