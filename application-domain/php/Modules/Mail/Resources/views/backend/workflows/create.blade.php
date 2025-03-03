@extends("backend.layouts.app")

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('Create workflow') }}</h1>
            </div>
        </div>

        <div class="row table-dark">
            <div class="p-4 col-md-12">
               <form action="{{ route('backend.workflows.store') }}" method="POST">
                   @csrf
                   <div class="col-md-12">
                   <div class="form-group">
                       <label for="name">{{  __('Workflow Name') }}</label>
                       <input type="text" class="form-control" id="name" name="name" aria-describedby="Name" placeholder="{{  __('Workflow Name') }}">
                   </div>
                   </div>
                   <div class="mt-2 text-right col-md-12">
                        <a href="{{ route('backend.workflows.index') }}" style="margin-right:1vw" class="mr-2 btn btn-warning float-start">{{ __('Cancel')}}</a>
                        <button type="submit" class="ml-4 btn btn-success float-start">{{ __('Save')}}</button>
                   </div>
               </form>
            </div>
        </div>
    </div>
@endsection
