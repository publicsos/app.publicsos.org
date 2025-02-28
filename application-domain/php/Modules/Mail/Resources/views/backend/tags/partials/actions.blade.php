<form action="{{ route('backend.tags.destroy', $tag->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <a href="{{ route('backend.tags.edit', $tag->id) }}"
       class="btn btn-sm btn-light">{{ __('Edit') }}</a>
    <button type="submit" class="btn btn-sm btn-light">{{ __('Delete') }}</button>
</form>
