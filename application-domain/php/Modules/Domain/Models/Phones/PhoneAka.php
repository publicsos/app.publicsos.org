<?php

namespace Modules\Domain\Models\Phones;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneAka extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'model',
        'name'
    ];

    protected $table = 'aka';


}
