<div class="container-fluid">
    <div class="row">
        {{-- Folders Sidebar --}}
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success btn-sm">Send Mail</button>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach ($folders as $folder)
                            <li class="mb-2">
                                <a href="#" wire:click.prevent="selectFolder('{{ $folder['path'] }}')"
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Messages</h6>
                </div>
                <div class="card-body p-0">
                    @if(!empty($messages))
                        <div class="list-group">
                            @foreach($messages as $msg)
                                <a href="#" wire:click.prevent="selectMessage('{{ $msg['uid'] }}')"
                                   class="list-group-item {{ $selectedMessage && $selectedMessage['uid'] == $msg['uid'] ? 'active' : '' }}">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ $msg['from'] }}</h6>
                                        <small>{{ \Carbon\Carbon::parse($msg['date'])->format('M d') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $msg['subject'] }}</p>
                                    <small>{{ Str::limit(strip_tags($msg['body']), 100) }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center p-4">
                            <p>No messages in this folder</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Message Detail --}}
        <div class="col-md-6">
            @if($selectedMessage)
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $selectedMessage['subject'] ?? 'No Subject' }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>From:</strong> {{ $selectedMessage['from'] }}</p>
                        <p><strong>To:</strong> {{ $selectedMessage['to'] }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($selectedMessage['date'])->format('M d, Y H:i') }}</p>
                        <div class="email-content">
                            {!! $selectedMessage['body'] !!}
                        </div>

                        {{-- Attachments --}}
                        @if(!empty($selectedMessage['attachments']))
                            <h6>Attachments</h6>
                            <div class="row">
                                @foreach($selectedMessage['attachments'] as $attachment)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <p><strong>{{ $attachment['name'] }}</strong></p>
                                                @if($attachment['is_image'])
                                                    <img src="data:image;base64,{{ base64_encode(file_get_contents($attachment['path'])) }}" class="img-fluid">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <p>Select a message to read</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
