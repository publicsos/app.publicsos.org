<x-laravel-mail.field-wrapper :name="$name" :label="$label">
    <select  data-live-search="true" name="{{ $name }}" {{ $attributes->merge(['id' => 'selectbox', 'class' => 'selectpicker  form-control ' . ($multiple ? 'selectpicker' : ''), 'multiple' => $multiple]) }}>
        @foreach($options as $key => $text)
            <option value="{{ $key }}" {{ $isSelected($key) ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>


</x-laravel-mail.field-wrapper>
<!-- Select2 CSS -->


@push('css')
<style>
    .page-item.active .page-link {
  color: #fff !important;
  background: #281029 !important;
}
.page-item .page-link {
  color: #fff !important;
  background: #4b1e4e !important;

}
.dropdown-menu{

    background: #281029 !important;
}
.dropdown-menu .active{
    background: #281029 !important;
    padding-bottom:0px !important;
}
.dropdown-menu .show{
    background: #281029 !important;
    padding-bottom:0px !important;
}

.bs-searchbox{
    background: #281029!important;
    padding-bottom:0px !important;
}

</style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.12/dist/css/bootstrap-select.min.css">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.12/dist/js/bootstrap-select.min.js"></script>
@endpush
