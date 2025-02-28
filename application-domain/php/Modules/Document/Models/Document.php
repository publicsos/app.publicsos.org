<?php

namespace Modules\Document\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'documents';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Document\database\factories\DocumentFactory::new();
    }
}
