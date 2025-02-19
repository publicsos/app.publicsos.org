<?php

namespace Modules\Domain\Models\Phones;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneBrands extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gsmarena',
        'phonearena',
        'phonedb',
    ];

    protected $table = 'phone_brands';


}
