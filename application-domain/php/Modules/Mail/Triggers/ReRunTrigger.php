<?php
declare(strict_types=1);
namespace Modules\Mail\Triggers;

use Modules\Mail\Loggers\WorkflowLog;

class ReRunTrigger
{
    public static function startWorkflow(WorkflowLog $log)
    {

        info('Re-Running Workflow '.$log->workflow->name);
        $log->triggerable->start($log->elementable);
    }
}
