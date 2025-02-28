<?php
declare(strict_types=1);
namespace Modules\Mail\Repositories;

use Modules\Mail\Models\Template;

class TemplateTenantRepository extends BaseTenantRepository
{
    protected $modelName = Template::class;


}
