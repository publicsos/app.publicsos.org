@extends("backend.layouts.app")
@section('title', $message->subject ?? 'View Message')

@section('heading')
    {{ $message->subject ?? 'No Subject' }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Folders Sidebar --}}
        <div class="col-md-2">
            <div class="card">

                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach ($folders as $folder)
                            <li class="mb-2">
                                <a href="{{ route('backend.mailbox.folder', ['folder' => $folder['path']]) }}"
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

        {{-- Message Content --}}
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Message Details</h6>
                        <a href="{{ route('backend.mailbox.folder', ['folder' => request()->route('folder')]) }}"
                           class="btn btn-outline-secondary btn-sm">
                            Back to Inbox
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($message)
                        <div class="mb-4 message-header">
                            <div class="mb-2 row">
                                <div class="col-md-2"><strong>From:</strong></div>
                                <div class="col-md-10">{{ $message->from }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-md-2"><strong>To:</strong></div>
                                <div class="col-md-10">{{ $message->to }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-md-2"><strong>Date:</strong></div>
                                <div class="col-md-10">{{ \Carbon\Carbon::parse($message->date)->format('M d, Y H:i') }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-md-2"><strong>Subject:</strong></div>
                                <div class="col-md-10">{{ $message->subject }}</div>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-4 message-body">
                            {!! $message->body !!}
                        </div>

                        @if(!empty($message->attachments))
                            <hr>

                            <div class="message-attachments">
                                <h6 class="mb-3">Attachments</h6>
                                <div class="row">
                                    @foreach($message->attachments as $attachment)
                                        <div class="mb-3 col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p class="mb-2"><strong>{{ $attachment['name'] }}</strong></p>
                                                    @if($attachment['is_image'])
                                                        <img src="data:image;base64,{{ base64_encode(file_get_contents($attachment['path'])) }}"
                                                             class="mb-2 img-fluid"
                                                             alt="{{ $attachment['name'] }}">
                                                    @endif
                                                    <a href="#" class="btn btn-primary btn-sm">Download</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            Message not found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
