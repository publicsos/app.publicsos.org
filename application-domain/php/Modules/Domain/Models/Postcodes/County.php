<?php

namespace Modules\Domain\Models\Postcodes;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class County extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'county',
    ];

    protected $table = 'counties';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Domain\database\factories\CountyFactory::new();
    }


}
