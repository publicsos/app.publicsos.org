<?php

namespace Modules\Workflow\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workflow extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'workflows';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Workflow\database\factories\WorkflowFactory::new();
    }
}
