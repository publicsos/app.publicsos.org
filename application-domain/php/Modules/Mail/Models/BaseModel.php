<?php
declare(strict_types=1);
namespace Modules\Mail\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Mail\Triggers\WorkflowObservable;

class BaseModel extends Model
{

    use WorkflowObservable;


    /**
     * Store which fields are boolean in the model
     *
     * Note that any boolean fields not in the fillable
     * array will not be automatically set in the repo
     *
     * @var array
     */
    protected $booleanFields = [];

    /**
     * Return all boolean fields for the model
     *
     * @return array
     */
    public function getBooleanFields()
    {
        return $this->booleanFields;
    }



}
