<?php

namespace Modules\Document\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentEntity extends Model
{

    protected $table = 'document_entities';


    protected $fillable = [
        'document_id',
        'type',
        'value'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

}
