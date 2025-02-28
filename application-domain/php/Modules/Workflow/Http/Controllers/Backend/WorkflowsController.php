<?php

namespace Modules\Workflow\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Backend\BackendBaseController;

class WorkflowsController extends BackendBaseController
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Workflows';

        // module name
        $this->module_name = 'workflows';

        // directory path of the module
        $this->module_path = 'workflow::backend';

        // module icon
        $this->module_icon = 'fa-regular fa-sun';

        // module model name, path
        $this->module_model = "Modules\Workflow\Models\Workflow";
    }

}
