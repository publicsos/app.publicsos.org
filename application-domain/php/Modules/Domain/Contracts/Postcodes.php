<?php

namespace Modules\Domain\Contracts;

use App\Authorizable;
use App\Http\Controllers\Backend\BackendBaseController;

//todo rename this

interface Postcodes
{

    public function import();
}
