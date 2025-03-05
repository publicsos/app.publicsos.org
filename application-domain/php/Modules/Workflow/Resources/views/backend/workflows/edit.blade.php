@extends("backend.layouts.app")
@section('title', __('Workflows'))

@section('heading')
    {{ __('Edit Workflow') }} {{ $workflow->name }}
@endsection

@section('left')
    <a href="{{ route('backend.workflows.index') }}" class="btn btn-warning">{{ __('Cancel')}}</a>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
               <form action="{{ route('backend.workflows.update', ['workflow' => $workflow]) }}" method="POST">
                   @csrf4
                   <div class="col-md-12">
                       <div class="form-group">
                           <input type="text" class="form-control" id="name" name="name" value="{{ $workflow->name }}"
                                  aria-describedby="Name"
                                  placeholder="{{ $workflow->name }}">
                       </div>
                   </div>
                   <div class="text-right col-md-12">
                        <button type="submit" class="btn btn-success">{{ __('Save')}}</button>
                   </div>
               </form>
            </div>
        </div>
    </div>
@endsection
