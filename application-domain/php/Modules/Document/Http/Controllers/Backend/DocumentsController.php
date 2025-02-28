<?php

namespace Modules\Document\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Backend\BackendBaseController;

class DocumentsController extends BackendBaseController
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Documents';

        // module name
        $this->module_name = 'documents';

        // directory path of the module
        $this->module_path = 'document::backend';

        // module icon
        $this->module_icon = 'fa-regular fa-sun';

        // module model name, path
        $this->module_model = "Modules\Document\Models\Document";
    }

}
