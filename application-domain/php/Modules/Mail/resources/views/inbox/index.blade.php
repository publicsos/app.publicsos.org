@extends('laravel-mail::layouts.app')
@section('title', __('Inbox'))

@section('heading')
    {{ __('Inbox') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Folders Sidebar --}}
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-white">{{ __('Folders') }}</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach ($folders as $folder)
                            <li class="mb-2">
                                <a href="{{ route('laravel-mail.mailbox.folder', ['folder' => $folder['path']]) }}"
                                   class="text-decoration-none">
                                    {{ $folder['name'] }}
                                    @if($folder['unseen'] > 0)
                                        <span class="badge bg-primary">{{ $folder['unseen'] }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Messages List --}}
        <div class="col-md-10">
            <div class="card">

                <div class="card-body p-0">
                    @if(!empty($messages))
                    <div class="card">
                        <div class="card-table table-responsive">
                            <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $msg)
                                    <tr>
                                        <td>{{ $msg['from'] }}</td>
                                        <td>
                                            <a href="{{ route('laravel-mail.mailbox.message', ['folder' => request()->route('folder'), 'messageId' => $msg['uid']]) }}"
                                               class="text-decoration-none">
                                                {{ $msg['subject'] }}
                                            </a>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($msg['date'])->format('M d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div></div>
                    @else
                        <div class="text-center p-4">
                            <p>No messages in this folder</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
