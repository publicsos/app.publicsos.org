<?php

namespace Modules\Domain\Models\Buildings;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'title',
        'type',
        'longitude',
        'latitude',
        'remote_id',
        'address',
        'risk',
        'apartments',
        'age_group',
        'height',
        'postcode'
    ];

    protected $table = 'buildings';

    public function getTableColumns()
    {
       return $this->fillable;
    }

}
