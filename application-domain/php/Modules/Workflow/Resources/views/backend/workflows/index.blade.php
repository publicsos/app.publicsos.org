@extends("backend.layouts.app")

@section('title', __('Workflows'))



@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('backend.workflows.create') }}" class="mb-2 btn btn-secondary">{{__('Create workflow')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-dark">
                    <tr>
                        <th>{{ __('Name')}}</th>
                        <th>{{ __('Tasks')}}</th>
                        <th>{{ __('Created at')}}</th>
                        <th></th>
                    </tr>
                    @forelse($workflows as $workflow)
                        <tr>
                            <td>{{ $workflow->name }}</td>
                            <td>{{ $workflow->tasks->count() }}</td>
                            <td>{{ $workflow->created_at->format('d.m.Y') }}</td>
                            <td>
                                <a href="{{ route('backend.workflows.show', ['workflow' => $workflow]) }}"><i class="fas fa-eye"></i></a> -
                                <a href="{{ route('backend.workflows.edit', ['workflow' => $workflow]) }}"><i class="fas fa-edit"></i></a> -
                                <a href="{{ route('backend.workflows.delete', ['workflow' => $workflow]) }}"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('No workflows available.') }}</td>
                        </tr>
                    @endforelse
                </table>
                {{ $workflows->links() }}
            </div>
        </div>
    </div>
@endsection
