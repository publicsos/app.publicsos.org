<div>
    <div class="title-box">
        {!! $icon !!}
        <span class="title" style="font-size: 14px; font-weight: bold;">
            {{ $elementName }}
        </span>
    </div>
    <div class="box">
        {{ $element->data_fields['description']['value'] ?? '' }}
    </div>
    <div class="footer-box" style="text-align: right; padding: 5px;">
        <i style="margin-left:5px" class="fas fa-clock delay-75-button" onclick="loadDelays('{{ $type }}', {{ isset($element) ? $element->id : 0 }}, this);"></i>
        <i class="fas fa-tasks settings-button" onclick="loadContitions('{{ $type }}', {{ isset($element) ? $element->id : 0 }}, this);"></i>
        <i class="fas fa-cog settings-button" onclick="loadSettings('{{ $type }}', {{ isset($element) ? $element->id : 0 }}, this);"></i>
    </div>
</div>
