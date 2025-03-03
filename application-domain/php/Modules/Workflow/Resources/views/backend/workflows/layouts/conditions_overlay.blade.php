

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-extendext@1.0.0/jquery-extendext.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder/dist/js/query-builder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-QueryBuilder/3.0.0/js/query-builder.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-extendext@1.0.0/jquery-extendext.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder@3.0.0/dist/js/query-builder.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder@3.0.0/dist/css/query-builder.default.min.css" rel="stylesheet">

<div id="conditions-overlay" class="settings-overlay">
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="margin-bottom: 20px;">
                <div class="settings-headline">
                    <h3>{!! $element::$icon !!} {{__('Workflows Conditions') }}</h3>
                </div>
            </div>
            <div class="col-md-12">
                <div class="settings-body">
                    <div id="builder"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="settings-footer text-right">
                    <button class="btn btn-default"
                            onclick="closeConditions();">{{__('Cancel') }}</button>
                    <button class="btn btn-success"
                            onclick="saveConditions({{ $element->id }}, '{{ $element->family }}');">{{__('Save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#builder').queryBuilder({
            operators: ['equal', 'not_equal'],
            allow_groups: false,
            @if(!empty($element->conditions))
            rules: {!! $element->conditions !!},
            @endif
            filters: [
                    @foreach($allFilters as $filterGroup => $filters)
                    @foreach($filters as $name => $values)
                {
                    id: '{{$filterGroup}}-{{$values}}',
                    field: '{{$name}}',
                    optgroup: '{{$filterGroup}}',
                    type: 'string',
                },
                @endforeach
                @endforeach
            ]
        });
    });

</script>
