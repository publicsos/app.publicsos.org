<?php

namespace Modules\Domain\Models;

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


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Domain\database\factories\DomainFactory::new();
    }


}
