<?php

namespace Modules\Blog\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'categories';

    /**
     * Caegories has Many posts.
     */
    public function posts()
    {
        return $this->hasMany('Modules\Blog\Models\Post');
    }


}
