<?php

namespace Modules\Domain\Models\Buildings;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'address',
        'type',
        'geometry_type',
        'longitude',
        'latitude',
        'properties_id',
        'properties_title',
        'properties_description',
        'properties_face',
        'properties_icon_scaledSize_width',
        'properties_icon_scaledSize_height',
        'properties_icon_origin_x',
        'properties_icon_origin_y',
        'properties_icon_anchor_x',
        'properties_icon_anchor_y',
        'distance_miles',
        'date',
        'contribuitor',
        'comment'
    ];

    protected $table = 'buildings';


}
