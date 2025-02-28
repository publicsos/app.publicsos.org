<div style="margin-bottom: 2vh" {{ $attributes->merge(['class' => 'form-group py-10 mb-10 pb-10 row form-group-' . $name . ' ' . $wrapperClass  . ' '. $errorClass($name)]) }}>
    <x-label :name="$name">{{ $label }}</x-label>
    <div class="col-sm-12">
        {{ $slot }}
    </div>
</div>
