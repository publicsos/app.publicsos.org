<div style="margin-bottom: 2vh" class="mb-10 form-group row form-group-name">
    <label for="id-field-name" class="text-white control-label col-sm-2">{{ __('Nume') }}</label>
    <div class="col-sm-6">
        <input id="id-field-name" class="form-control" name="name" type="text" value="{{ old('name', $template->name ?? '') }}">
    </div>
</div>

@push('after-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/theme/monokai.min.css">
    <style>
        .CodeMirror {
            height: 600px;
        }

        .template-preview {
            height: 600px;
        }
    </style>
@endpush

<div class="form-group row form-group-content template-content">
    <label for="id-field-content" class="text-white control-label col-sm-2">{{ __('Content') }}</label>
    <div class="col-sm-10">
        <textarea id="id-field-content" class="form-control" name="content" cols="50"
                  rows="20">{{ old('content', $template->content ?? null) }}</textarea>
    </div>
</div>

<div class="form-group row template-preview d-none">
    <div class="offset-sm-2 col-sm-10">
        <div class="border border-light h-100">
            <iframe width="100%" height="100%" scrolling="yes" frameborder="0"
                    srcdoc="{!! old('content', $template->content ?? null)  !!} "></iframe>
        </div>
    </div>
</div>

@push('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/xml/xml.min.js"></script>

    <script>
        $(document).ready(function () {
            var codeMirror = CodeMirror.fromTextArea(document.getElementById('id-field-content'), {
                lineNumbers: true,
                lineWrapping: true,
                mode: 'xml',
                theme: 'monokai',
            });

            $('.btn-preview').click(function (e) {
                e.preventDefault();

                var elContent = $('.template-preview');
                var elPreview = $('.template-content');
                var elButton = $('.btn-preview');

                if (elContent.hasClass('d-none')) {
                    $('.template-preview iframe').attr('srcdoc', codeMirror.getValue());
                    elContent.removeClass('d-none');
                    elPreview.addClass('d-none');
                    elButton.text('Show Design');
                } else {
                    elContent.addClass('d-none');
                    elPreview.removeClass('d-none');
                    elButton.text('Show Preview');
                }
            });
        });
    </script>
@endpush


<div class="form-group row" style="margin-top:2vh">
    <div class="text-right col-12">
        <a href="#" class="text-white btn btn-md btn-secondary btn-preview">{{ __('Cum arata Design') }}</a>
        <button class="btn btn-primary btn-md" type="submit">{{ __('Salveaza') }}</button>
    </div>
</div>
