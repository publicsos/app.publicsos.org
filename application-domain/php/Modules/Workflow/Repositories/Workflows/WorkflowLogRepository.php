<?php

namespace Modules\Workflow\Repositories\Workflows;

use Modules\Mail\Loggers\WorkflowLog;
use Modules\Mail\Repositories\BaseEloquentRepository;

class WorkflowLogRepository extends BaseEloquentRepository
{
    protected $modelName = WorkflowLog::class;

}
