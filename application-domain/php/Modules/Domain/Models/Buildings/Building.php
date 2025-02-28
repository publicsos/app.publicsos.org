<?php

namespace Modules\Domain\Models\Buildings;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'address',
        'type',
        'geometry_type',
        'longitude',
        'latitude',
        'properties_id',
        'properties_title',
        'properties_description',
        'properties_face',
        'properties_icon_scaledSize_width',
        'properties_icon_scaledSize_height',
        'properties_icon_origin_x',
        'properties_icon_origin_y',
        'properties_icon_anchor_x',
        'properties_icon_anchor_y',
        'distance_miles',
        'date',
        'contribuitor',
        'comment'
    ];

    protected $table = 'buildings';



    public function getTableColumns()
    {
        $table_name = DB::getTablePrefix().$this->getTable();

        switch (config('database.default')) {
            case 'sqlite':
                $columns = DB::select("PRAGMA table_info({$table_name});");
                break;
            case 'mysql':
            case 'mariadb':
                $columns = DB::select('SHOW COLUMNS FROM '.$table_name);
                $columns = array_map(function ($column) {
                    return [
                        'name' => $column->Field,
                        'type' => $column->Type,
                        'notnull' => $column->Null,
                        'key' => $column->Key,
                        'default' => $column->Default,
                        'extra' => $column->Extra,
                    ];
                }, $columns);
                break;
            case 'pgsql':
                $columns = DB::select("SELECT column_name as `Field`, data_type as `Type` FROM information_schema.columns WHERE table_name = '{$table_name}';");
                break;

            default:
                // code...
                break;
        }

        return json_decode(json_encode($columns));
    }

}
