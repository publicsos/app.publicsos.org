<?php

namespace Modules\Mail\Facades;

use Illuminate\Support\Facades\Facade;

class Helper extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mail.helper';
    }
}
