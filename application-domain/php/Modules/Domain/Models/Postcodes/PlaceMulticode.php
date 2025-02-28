<?php

namespace Modules\Domain\Models\Postcodes;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlaceMulticode extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'county',
        'place',
    ];




}
