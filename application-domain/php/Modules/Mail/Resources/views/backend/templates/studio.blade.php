@extends("backend.layouts.app")

@section('title', __("Templates"))

@section('heading')
    Templates
@stop
@section('content')
<script src="https://unpkg.com/@grapesjs/studio-sdk@latest/dist/index.umd.js"></script>
<link rel="stylesheet" href="https://unpkg.com/@grapesjs/studio-sdk@latest/dist/style.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <p class="text-2xl font-bold text-bg-dark">
                        The following tags are available in your template:
                    </p>
                    <p class="text-2xl font-bold text-bg-dark">
                        'email',
                        'first_name',
                        'last_name',
                        'unsubscribe_url',
                        'webview_url'
                    </p>
                    <form action="{{ route('backend.templates.update', $template->id) }}" method="POST" class="form-horizontal">
                        <div class="mt-3 mb-3 form-group row">
                            <div class="col-12">
                                <input type="text" class="form-control" id="title" name="name" value="{{ $template->name }}" placeholder="Template Name">
                            </div>
                        </div>
                        @csrf
                        @method('PUT')
                        <!-- Editor -->
                        <input type="hidden" id="template-content" name="content">

                        <div id="gjs" style="height:100vh; width:100%;">
                            {!! $template->content !!}
                        </div>


                        <div class="mt-3 form-group row">
                            <div class="col-12">
                                <button class="btn btn-primary btn-md" type="submit">{{ __('Save Template') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        import createStudioEditor from '@grapesjs/studio-sdk';
import '@grapesjs/studio-sdk/style';

// ...
createStudioEditor({
  // ...
  project: {
    type: 'web',
    // The default project to use for new projects
    default: {
      pages: [
        { name: 'Home', component: '<h1>Home page</h1>' },
        { name: 'About', component: '<h1>About page</h1>' },
        { name: 'Contact', component: '<h1>Contact page</h1>' },
      ]
    },
  }
})
    </script>
@stop
