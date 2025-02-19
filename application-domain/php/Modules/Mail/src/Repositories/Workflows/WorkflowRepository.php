<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Repositories\Workflows;


use LaravelCompany\Mail\Models\Workflow;
use LaravelCompany\Mail\Repositories\BaseEloquentRepository;

class WorkflowRepository extends BaseEloquentRepository
{
    protected $modelName = Workflow::class;

    //todo implement strict type 
    public function getWorkflows(int $workspaceId):mixed
    {
        return $this->getQueryBuilder()->where([
            'workspace_id' => $workspaceId
        ])->paginate();
    }
}
