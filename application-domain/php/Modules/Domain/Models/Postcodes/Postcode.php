<?php

namespace Modules\Domain\Models\Postcodes;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Postcode extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'iso_code',
        'country',
        'county',
        'place',
        'place_multicode',
        'district',
        'street_suffix',
        'street',
        'street_number',
        'postal_code',
    ];


    protected $table = 'postal_codes';

}
