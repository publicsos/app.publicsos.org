<div class="row masonry-grid">
    @if($templates->isEmpty())
        <div class="col-12">
            <div class="alert alert-warning text-center">
                No templates found for "<span class="highlight">{{ $searchTerm }}</span>"
            </div>
        </div>
    @else
        @foreach($templates as $template)
            <div class="col-lg-3 col-md-6 col-sm-12 mb-2 masonry-item">
                <div class="card">
                    <div class="card-header">
                        <div class="text-white">
                            {!! Str::replace($searchTerm, '<span class="highlight">'.$searchTerm.'</span>', e($template->name)) !!}
                        </div>

                        <!-- Buttons Wrapper (Aligned Left to Right) -->
                        <div class="d-flex justify-content-start gap-2 mt-2">

                            <a href="{{ route('laravel-mail.templates.edit', $template->id) }}" class="btn btn-block btn-primary">
                               <i class="fa fa-pencil"></i> {{ __('Edit') }}
                            </a>

                            @if (! $template->is_in_use)
                                <form action="{{ route('laravel-mail.templates.destroy', $template->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-block btn-warning">
                                         {{ __('Delete') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 25vh; overflow: hidden;">
                        <a href="{{ route('laravel-mail.templates.edit', $template->id) }}">
                            <div class="text-white">
                                <img
                                    src="{{ asset($template->thumbnail ?? 'logo.svg')  }}"
                                    class="img-fluid"
                                    loading="lazy"
                                    alt="{{ $template->name }}">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
<style>
    ..page-link{
        background-color: #4b1e4e
    }
</style>
<hr />

{{ $templates->links() }}

<style>
.main-wrapper {
    background: #4b1e4e !important;
}

.highlight {
    background-color: yellow;
    font-weight: bold;
}
</style>
