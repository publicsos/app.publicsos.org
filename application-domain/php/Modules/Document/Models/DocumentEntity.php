<?php

namespace Modules\Document\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentEntity extends BaseModel
{

    protected $table = 'document_entities';


    public function document()
    {
        return $this->belongsTo(Document::class);
    }

}
