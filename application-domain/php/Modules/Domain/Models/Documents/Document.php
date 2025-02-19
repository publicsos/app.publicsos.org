<?php

namespace Modules\Domain\Models\Documents;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Document extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'source',
        'date',
    ];

    protected $table = 'documents';




}
