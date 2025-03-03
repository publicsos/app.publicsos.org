<?php

namespace Modules\Domain\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;


class BuildingsController extends BackendBaseController
{
    use Authorizable;


    private array $fields = [
        'id',

        'title',
        'type',
        'longitude',
        'latitude',
        'remote_id',
        'address',
        'risk',
        'apartments',
        'age_group',
        'height',
        'postcode'
    ];

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Buildings';

        // module name
        $this->module_name = 'buildings';

        // directory path of the module
        $this->module_path = 'domain::backend';

        // module icon
        $this->module_icon = 'fa-regular fa-building';

        // module model name, path
        $this->module_model = "Modules\Domain\Models\Buildings\Building";

    }


   public function index_data()
   {
       $module_title = $this->module_title;
       $module_name = $this->module_name;
       $module_path = $this->module_path;
       $module_icon = $this->module_icon;
       $module_model = $this->module_model;
       $module_name_singular = Str::singular($module_name);

       $module_action = 'List';

       $page_heading = label_case($module_title);
       $title = $page_heading.' '.label_case($module_action);

       //title,type,longitude,latitude,remote_id,address,risk,apartments,postcode
       $$module_name = $module_model::select("*");



       return Datatables::of($$module_name)
           ->addColumn('action', function ($data) {
               $module_name = $this->module_name;

               return view('backend.includes.action_column', compact('module_name', 'data'));
           })

           ->rawColumns(['title', 'action'])
           ->orderColumns(['id'], '-:column $1')
           ->make(true);
   }


}
