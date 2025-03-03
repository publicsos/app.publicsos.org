<div id="settings-overlay" class="settings-overlay">
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="margin-bottom: 20px;">
                <div class="settings-headline">
                    <h1> {{ __('Logs') }}</h1>
                </div>
            </div>

            <div class="col-md-12">
                <div class="settings-body">
                    <table class="table table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Message</th>
                            <th>Tasks Processed</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>ReRun</th>
                        </tr>
                        @foreach($workflowLogs as $workflowLog)
                            <tr id="log-{{ $workflowLog->id }}">
                                <td>{{ $workflowLog->id }}</td>
                                <td>{{ $workflowLog->name }}</td>
                                <td>{{ $workflowLog->status }}</td>
                                <td>{{ $workflowLog->message }}</td>
                                <td>{{ $workflowLog->taskLogs()->count() }} / {{ $workflowLog->workflow->tasks()->count() }}</td>
                                <td>{{ $workflowLog->start }}</td>
                                <td>{{ $workflowLog->end }}</td>
                                <td><button class="btn btn-success" onclick="reRun({{ $workflowLog->id }});">{{ __('ReRun') }}</button></td>
                            </tr>
                        @endforeach
                    </table>

                    <!-- Add a placeholder for the result -->
                    <div id="result-placeholder" style="margin-top: 20px;"></div>
                </div>
            </div>

            <div class="settings-footer">
                <button class="btn btn-warning" onclick="closeSettings();">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    function reRun(id) {
        $.ajax({
            type: "POST",
            url: "{{ route('backend.workflows.reRunJSHelper') }}/" + id,
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function (data) {
                // Update the HTML with the result from the server
                var result = `<div class="alert alert-success">ReRun successful for log ID: ${id}. Message: ${data.message}</div>`;
                $("#result-placeholder").html(result);
            },
            error: function (xhr) {
                // Handle any error that occurs
                var errorResult = `<div class="alert alert-danger">ReRun failed for log ID: ${id}. Error: ${xhr.responseText}</div>`;
                $("#result-placeholder").html(errorResult);
            }
        });
    }
</script>
