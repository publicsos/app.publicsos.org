<?php

namespace Modules\Domain\Models\Phones;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneTac extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'tac',
        'model',
        'date',
        'contribuitor',
        'comment'
    ];

    protected $table = 'phone_tac';


}
