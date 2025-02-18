<?php

namespace Modules\Domain\Models\Phones;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
class PhoneModels extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'name',
        'gsmarena',
        'phonearena',
        'phonedb',
    ];

    protected $table = 'phone_models';



}
