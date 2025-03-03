<div class="row">
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Title', 'title')->class('form-label') }}
            {{ html()->text('title')->placeholder('Title')->class('form-control') }}

        </div>
    </div>
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Type', 'type')->class('form-label') }}
            {{ html()->select('type', collect(\Modules\Domain\Enums\BuildingType::cases())->map(fn ($case) => $case->value)->toArray(), old('type'))->class('form-control') }}

        </div>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Longitude', 'longitude')->class('form-label') }}
            {{ html()->text('longitude')->placeholder('Longitude')->class('form-control') }}
        </div>
    </div>
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Latitude', 'latitude')->class('form-label') }}
            {{ html()->text('latitude')->placeholder('Latitude')->class('form-control') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Remote Id', 'remote_id')->class('form-label') }}
            {{ html()->text('remote_id')->placeholder('Remote Id')->class('form-control') }}
        </div>
    </div>
     <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Address', 'address')->class('form-label') }}
            {{ html()->text('address')->placeholder('Address')->class('form-control') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Risk', 'risk')->class('form-label') }}
            {{ html()->select('risk', collect(\Modules\Domain\Enums\BuildingRiskType::cases())->map(fn ($case) => $case->value)->toArray(), old('risk'))->class('form-control') }}

        </div>
    </div>
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Apartments', 'apartments')->class('form-label') }}
            {{ html()->text('apartments')->placeholder('Apartments')->class('form-control') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Age Group', 'age_group')->class('form-label') }}
            {{ html()->select('age_group', collect(\Modules\Domain\Enums\BuildingAgeGroup::cases())->map(fn ($case) => $case->value)->toArray(), old('age_group'))->class('form-control') }}
        </div>
    </div>
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Height', 'height')->class('form-label') }}
            {{ html()->text('height')->placeholder('Height')->class('form-control') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-12 col-sm-6">
        <div class="form-group">
            {{ html()->label('Postcode', 'postcode')->class('form-label') }}
            {{ html()->text('postcode')->placeholder('Postcode')->class('form-control') }}
        </div>
    </div>
</div>
