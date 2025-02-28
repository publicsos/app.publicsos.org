@extends("backend.layouts.app")

@section('title', __('Verify SMTP Services'))

@section('heading')
    {{ __('SMTP Services') }}
@endsection


@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 same-height">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-white card-title">{{ __('Single SMTP Service') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('backend.email-services.postVerify') }}">
                            <div class="form-group">
                                <label for="formServer text-white" style="color: #ffff;">
                                    {{ __('Server') }}
                                </label>
                                <input name="server" type="text" class="form-control" id="formGroupExampleInput"
                                    placeholder="{{ __('Server') }}">
                            </div>
                            <div class="form-group">
                                <label for="formGroupPort text-white" style="color: #ffff;">
                                    {{ __('Port') }}
                                </label>
                                <input name="port" type="text" class="form-control" id="formGroupExampleInput"
                                    placeholder="{{ __('PORT') }}">
                            </div>

                            <div class="form-group">
                                <label for="formServer text-white" style="color: #ffff;">
                                    {{ __('Server') }}
                                </label>
                                <input name="username" type="text" class="form-control" id="formGroupExampleInput"
                                    placeholder="{{ __('Username') }}">
                            </div>
                            <div class="form-group" style="color: #ffff;">
                                <label for="text-white">
                                    {{ __('Password') }}
                                </label>
                                <input name="password" type="password" class="form-control" id="formGroupExampleInput"
                                    placeholder="{{ __('Password') }}">
                            </div>


                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 same-height">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-white card-title">{{ __('Multiple Email Services') }}</h5>
                    </div>
                    <div class="card-body">

                        <p class="text-white">
                            {{ __('You will have to upload a file to the server to verify the email service.') }}
                            {{ __('The file must be csv file in the format: ') }}
                        <blockquote class="text-white">
                            SMTP_SERVER | SMTP_PORT | SMTP_USERNAME | SMTP_PASSWORD
                        </blockquote>
                        </p>
                        <form action="{{ route('backend.email_services.postVerify') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mt-3 mb-3">
                                <label for="server" class="text-white form-label">Server:</label>
                                <input type="file" name="file" id="file"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5 row">
            <!-- Filter Inputs -->
            <div class="mb-3 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" id="filterServer" class="form-control" placeholder="Filter by Server">
                            </div>
                            <div class="col-md-2">
                                <input type="text" id="filterPort" class="form-control" placeholder="Filter by Port">
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="filterEmail" class="form-control" placeholder="Filter by Email">
                            </div>
                            <div class="col-md-2">
                                <select id="filterStatus" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="completed">Completed</option>
                                    <option value="failed">Failed</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button id="resetFilters" class="btn btn-secondary">Reset Filters</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Results Table -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-white">{{ __('SMTP Verification Results') }}</h4>
                        <p class="text-white">
                            SMTP job checking relies on Redis for real-time tracking, making them ephemeral. Only successful
                            results are persistently stored in the database.
                        </p>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark table-responsive table-striped" id="smtpTable">
                            <thead>
                                <tr>
                                    <th>Server</th>
                                    <th>Port</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jobs as $job)
                                    <tr>
                                        <td>{{ $job['server'] }}</td>
                                        <td>{{ $job['port'] }}</td>
                                        <td>{{ $job['email'] }}</td>
                                        <td>
                                            @if ($job['status'] === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif ($job['status'] === 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $job['error'] ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No jobs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Filter function
            function filterTable() {
                const server = $('#filterServer').val().toLowerCase();
                const port = $('#filterPort').val().toLowerCase();
                const email = $('#filterEmail').val().toLowerCase();
                const status = $('#filterStatus').val().toLowerCase();

                $('#smtpTable tbody tr').each(function() {
                    const rowServer = $(this).find('td:eq(0)').text().toLowerCase();
                    const rowPort = $(this).find('td:eq(1)').text().toLowerCase();
                    const rowEmail = $(this).find('td:eq(2)').text().toLowerCase();
                    const rowStatus = $(this).find('td:eq(3)').text().toLowerCase();

                    const serverMatch = rowServer.includes(server);
                    const portMatch = rowPort.includes(port);
                    const emailMatch = rowEmail.includes(email);
                    const statusMatch = status === "" || rowStatus.includes(status);

                    if (serverMatch && portMatch && emailMatch && statusMatch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            // Attach filter function to input events
            $('#filterServer, #filterPort, #filterEmail, #filterStatus').on('input change', filterTable);

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#filterServer').val('');
                $('#filterPort').val('');
                $('#filterEmail').val('');
                $('#filterStatus').val('');
                filterTable();
            });

        });

    </script>
@endpush
