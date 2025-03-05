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

    protected  $fillable = [

    ];

    public function entities()
    {
        return $this->hasMany(DocumentEntity::class);
    }
}
