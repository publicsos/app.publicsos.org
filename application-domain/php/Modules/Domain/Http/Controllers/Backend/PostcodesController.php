<?php

namespace Modules\Domain\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Support\Str;

class PostcodesController extends BackendBaseController
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Postcodes';

        // module name
        $this->module_name = 'postcodes';

        // directory path of the module
        $this->module_path = 'domain::backend';

        // module icon
        $this->module_icon = 'fa-regular fa-sun';

        // module model name, path
        $this->module_model = "Modules\Domain\Models\Postcodes\Postcode";
    }

}
