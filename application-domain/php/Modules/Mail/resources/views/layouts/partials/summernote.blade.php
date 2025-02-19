@push('css')
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/theme/blackboard.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/theme/monokai.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
@endpush




<!-- codemirror -->


@push('js')
<!-- add summernote -->

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/mode/xml/xml.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-ko-KR.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.js"></script>

    <script>
$(function () {
    $('#id-field-content').summernote({
        minHeight: 200,
        prettifyHtml: true,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['codeview']],
            ['ai-rewrite', ['aiRewrite']],
        ],

        buttons: {
            aiRewrite: function(context) {
                var ui = $.summernote.ui;

                var button = ui.button({
                    contents: '<i class="fa fa-star"/> Rewrite Content',
                    class: 'btn btn-block btn-warning',
                    tooltip: 'It usually takes a few seconds to rewrite content.',
                    click: function () {
                        var content = $('#id-field-content').summernote('code');
                        var $btn = $(this); // Get button reference

                        // Remove any existing error message
                        $('#rewrite-error-message').remove();

                        // Show loading state <i class="fa-solid fa-hourglass-start"></i>
                        $btn.prop('disabled', true);
                        $btn.find('i').removeClass('fa-star').addClass('fa-hourglass-start');

                        // Make AJAX request
                        $.ajax({
                            url: '{{ route('laravel-mail.campaigns.rewrite') }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Include CSRF token
                            },
                            data: {
                                content: content,
                            },
                            success: function(response) {
                                $('#id-field-content').summernote('code', response.content);
                            },
                            error: function(xhr, status, error) {
                                // Show error message in UI
                                $('#id-field-content').after(`
                                    <div id="rewrite-error-message" class="alert alert-danger mt-2">
                                        <strong>Error:</strong> Failed to rewrite content. Please try again.
                                    </div>
                                `);

                                // Auto-hide the error after 5 seconds
                                setTimeout(function () {
                                    $('#rewrite-error-message').fadeOut(500, function () {
                                        $(this).remove();
                                    });
                                }, 5000);
                            },
                            complete: function() {
                                // Reset button state
                                $btn.prop('disabled', false);
                                $btn.find('i').removeClass('fa-hourglass-start').addClass('fa-star');
                            }
                        });
                    }
                });

                return button.render();
            }
        },

        onCreateLink: function (originalLink) {
            if (originalLink.includes('unsubscribe_url')) {
                return '@{{unsubscribe_url}}';
            }

            if (originalLink.includes('webview_url')) {
                return '@{{webview_url}}';
            }

            return /^([A-Za-z][A-Za-z0-9+-.]*\:|#|\/)/.test(originalLink)
                ? originalLink : 'https://' + originalLink;
        }
    });
});

    </script>

@endpush
