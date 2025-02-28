@extends("backend.layouts.app")
@section('title', __("Templates"))

@section('heading')
    Templates
@stop
<link href="https://unpkg.com/grapesjs-component-code-editor/dist/grapesjs-component-code-editor.min.css" rel="stylesheet">
<link rel="stylesheet" href="//unpkg.com/grapesjs/dist/css/grapes.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@section('content')
    <script src="//unpkg.com/grapesjs"></script>
    <script src="//unpkg.com/grapesjs-blocks-basic"></script>
    <link href="https://unpkg.com/grapesjs-component-code-editor/dist/grapesjs-component-code-editor.min.css" rel="stylesheet">
    <script src="https://unpkg.com/grapesjs-component-code-editor"></script>
    <script src="https://unpkg.com/grapesjs-parser-postcss"></script>
    <script src="https://unpkg.com/grapesjs-templates"></script>
    <script src="https://unpkg.com/grapesjs-plugin-ckeditor"></script>
    <script src="https://unpkg.com/grapesjs-templates"></script>
    <script src="https://unpkg.com/grapesjs-plugin-toolbox"></script>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <p class="text-2xl font-bold text-bg-dark">
                        Următoarele etichete sunt disponibile în template-ul dumneavoastră:
                    </p>
                    <p class="text-2xl font-bold text-bg-dark">
                        'email',
                        'first_name',
                        'last_name',
                        'unsubscribe_url',
                        'webview_url'
                    </p>
                    <form id="template-form" action="{{ route('backend.templates.update', $template->id) }}" method="POST" class="form-horizontal">
                        <div class="mt-3 mb-3 form-group row">
                            <div class="col-12">
                                <input type="text" class="form-control" id="title" name="name" value="{{ $template->name }}" placeholder="Nume template">
                            </div>
                        </div>
                        @csrf
                        @method('PUT')

                        <!-- Hidden input for editor content -->
                        <input type="hidden" name="content" id="editor-content">

                        <!-- Editor -->
                        <div id="gjs" style="height:100vh; width:100%;">
                            {!! $template->content !!}
                        </div>

                        <div class="mt-3 form-group row">
                            <div class="col-12">
                                <button class="btn btn-primary btn-md" type="submit">{{ __('Salveaza template') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    const editor = grapesjs.init({
        height: "100vh",
        container: "#gjs",
        showOffsets: true,
        fromElement: true,
        noticeOnUnload: false,
        storageManager: false,
        selectorManager: {
            componentFirst: true
        },
        plugins: ["grapesjs-component-code-editor",'grapesjs-plugin-toolbox', 'grapesjs-templates', "grapesjs-parser-postcss", "grapesjs-plugin-ckeditor"],
        pluginsOpts: {
            "grapesjs-component-code-editor": {},
            "grapesjs-plugin-ckeditor": {
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern",
                ]
            },
            'grapesjs-templates': {
                templates: 'http://localhost:3000/templates',
                projects: 'http://localhost:3000/projects',
            },
        }
    });

    const pn = editor.Panels;
    const panelViews = pn.addPanel({
        id: "views"
    });
    panelViews.get("buttons").add([
        {
            attributes: {
                title: "Open Code"
            },
            className: "fa fa-file-code-o",
            command: "open-code",
            togglable: false,
            id: "open-code"
        }
    ]);

    // Handle form submission
    document.getElementById('template-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Get HTML content from editor
        const htmlContent = editor.getHtml();
        const cssContent = editor.getCss();

        // Combine HTML and CSS
        const fullContent = `
            <style>
                ${cssContent}
            </style>
            ${htmlContent}
        `;

        // Set the content to hidden input
        document.getElementById('editor-content').value = fullContent;

        // Submit the form
        this.submit();
    });
    </script>

@stop
