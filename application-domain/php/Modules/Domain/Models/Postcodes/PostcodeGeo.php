<?php

namespace Modules\Domain\Models\Postcodes;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostcodeGeo extends Model
{
    use HasFactory;

    //postcodes_geolocations
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


    protected $table = 'postcodes_geolocations';

}
